<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleServiceCalendar;
use Google\Service\Tasks as GoogleServiceTasks;
use Google\Service\Gmail as GoogleServiceGmail;
use App\Models\User;

class GoogleApiService
{
    protected $client;
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->client = new GoogleClient();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect'));
        
        // Set access token
        if ($user->google_token) {
            $this->client->setAccessToken([
                'access_token' => $user->google_token,
                'refresh_token' => $user->google_refresh_token,
            ]);
            
            // Check if token is expired and refresh if needed
            if ($this->client->isAccessTokenExpired() && $user->google_refresh_token) {
                $this->refreshAccessToken();
            }
        }
        
        $this->client->setScopes([
            'https://www.googleapis.com/auth/calendar.readonly',
            'https://www.googleapis.com/auth/tasks.readonly',
            'https://www.googleapis.com/auth/gmail.readonly',
        ]);
    }
    
    private function refreshAccessToken()
    {
        try {
            $newToken = $this->client->fetchAccessTokenWithRefreshToken($this->user->google_refresh_token);
            
            if (isset($newToken['access_token'])) {
                // Update user's token in database
                $this->user->update([
                    'google_token' => $newToken['access_token'],
                ]);
                
                \Log::info('Access token refreshed for user: ' . $this->user->email);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to refresh access token for user ' . $this->user->email . ': ' . $e->getMessage());
        }
    }

    public function getCalendarEvents($pageToken = null, $maxResults = 10)
    {
        try {
            $service = new GoogleServiceCalendar($this->client);
            $calendarId = 'primary';
            
            $optParams = [
                'maxResults' => $maxResults,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => date('c', strtotime('-1 month')), // Get events from last month
                'timeMax' => date('c', strtotime('+6 months'))  // Get events up to 6 months ahead
            ];
            
            if ($pageToken) {
                $optParams['pageToken'] = $pageToken;
            }
            
            $events = $service->events->listEvents($calendarId, $optParams);
            
            // Sort by start time descending (newest first)
            $items = $events->getItems();
            usort($items, function($a, $b) {
                $aTime = $a->getStart() ? ($a->getStart()->dateTime ?: $a->getStart()->date) : '';
                $bTime = $b->getStart() ? ($b->getStart()->dateTime ?: $b->getStart()->date) : '';
                return strtotime($bTime) - strtotime($aTime);
            });
            
            \Log::info('Calendar events fetched successfully: ' . count($items) . ' events');
            
            return [
                'items' => $items,
                'nextPageToken' => $events->getNextPageToken(),
                'prevPageToken' => $pageToken
            ];
        } catch (\Exception $e) {
            \Log::error('Calendar API Error: ' . $e->getMessage());
            return [
                'items' => [],
                'nextPageToken' => null,
                'prevPageToken' => null
            ];
        }
    }

    public function getTasks($pageToken = null, $maxResults = 10)
    {
        try {
            $service = new GoogleServiceTasks($this->client);
            $tasklists = $service->tasklists->listTasklists(['maxResults' => 1]);
            
            if (count($tasklists->getItems()) > 0) {
                $tasklistId = $tasklists->getItems()[0]->getId();
                
                $optParams = [
                    'maxResults' => $maxResults,
                    'showCompleted' => true,
                    'showDeleted' => false
                ];
                
                if ($pageToken) {
                    $optParams['pageToken'] = $pageToken;
                }
                
                $tasks = $service->tasks->listTasks($tasklistId, $optParams);
                
                // Sort by updated time descending (newest first)
                $items = $tasks->getItems();
                usort($items, function($a, $b) {
                    $aTime = $a->getUpdated() ?: '1970-01-01T00:00:00Z';
                    $bTime = $b->getUpdated() ?: '1970-01-01T00:00:00Z';
                    return strtotime($bTime) - strtotime($aTime);
                });
                
                return [
                    'items' => $items,
                    'nextPageToken' => $tasks->getNextPageToken(),
                    'prevPageToken' => $pageToken
                ];
            }
            
            return [
                'items' => [],
                'nextPageToken' => null,
                'prevPageToken' => null
            ];
        } catch (\Exception $e) {
            \Log::error('Tasks API Error: ' . $e->getMessage());
            return [
                'items' => [],
                'nextPageToken' => null,
                'prevPageToken' => null
            ];
        }
    }

    public function getEmails($pageToken = null, $maxResults = 10)
    {
        try {
            $service = new GoogleServiceGmail($this->client);
            
            $optParams = [
                'maxResults' => $maxResults,
                'q' => 'in:inbox'
            ];
            
            if ($pageToken) {
                $optParams['pageToken'] = $pageToken;
            }
            
            $messages = $service->users_messages->listUsersMessages('me', $optParams);
            $messageList = $messages->getMessages() ?? [];
            
            $emails = [];
            foreach ($messageList as $message) {
                try {
                    $messageDetail = $service->users_messages->get('me', $message->getId());
                    $emails[] = $messageDetail;
                } catch (\Exception $e) {
                    \Log::warning('Failed to fetch email: ' . $message->getId() . ' - ' . $e->getMessage());
                    continue;
                }
            }
            
            // Gmail API returns messages in reverse chronological order by default (newest first)
            return [
                'items' => $emails,
                'nextPageToken' => $messages->getNextPageToken(),
                'prevPageToken' => $pageToken
            ];
        } catch (\Exception $e) {
            \Log::error('Gmail API Error: ' . $e->getMessage());
            return [
                'items' => [],
                'nextPageToken' => null,
                'prevPageToken' => null
            ];
        }
    }

    public function hasCalendarAccess()
    {
        try {
            $service = new GoogleServiceCalendar($this->client);
            $service->calendarList->listCalendarList(['maxResults' => 1]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getCalendarEventsSimple()
    {
        $data = $this->getCalendarEvents(null, 1);
        return $data['items'];
    }

    public function hasTasksAccess()
    {
        try {
            $service = new GoogleServiceTasks($this->client);
            $service->tasklists->listTasklists(['maxResults' => 1]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getTasksSimple()
    {
        $data = $this->getTasks(null, 1);
        return $data['items'];
    }

    public function hasGmailAccess()
    {
        try {
            $service = new GoogleServiceGmail($this->client);
            $service->users->getProfile('me');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getEmailsSimple()
    {
        $data = $this->getEmails(null, 1);
        return $data['items'];
    }

    public function getGmailProfile()
    {
        try {
            $service = new GoogleServiceGmail($this->client);
            return $service->users->getProfile('me');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getCalendarEventsCount()
    {
        try {
            $service = new GoogleServiceCalendar($this->client);
            $calendarId = 'primary';

            $optParams = [
                'maxResults' => 2500, // Google's max limit
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => date('c', strtotime('-1 year')), // Get events from last year
                'timeMax' => date('c', strtotime('+1 year'))  // Get events up to 1 year ahead
            ];

            $events = $service->events->listEvents($calendarId, $optParams);
            return count($events->getItems());
        } catch (\Exception $e) {
            \Log::error('Calendar Count API Error: ' . $e->getMessage());
            return 0;
        }
    }

    public function getTasksCount()
    {
        try {
            $service = new GoogleServiceTasks($this->client);
            $tasklists = $service->tasklists->listTasklists(['maxResults' => 10]);
            
            $totalTasks = 0;
            foreach ($tasklists->getItems() as $tasklist) {
                $tasklistId = $tasklist->getId();
                
                $optParams = [
                    'maxResults' => 2500, // Google's max limit
                    'showCompleted' => true,
                    'showDeleted' => false
                ];
                
                $tasks = $service->tasks->listTasks($tasklistId, $optParams);
                $totalTasks += count($tasks->getItems());
            }
            
            return $totalTasks;
        } catch (\Exception $e) {
            \Log::error('Tasks Count API Error: ' . $e->getMessage());
            return 0;
        }
    }

    public function getEmailsCount()
    {
        try {
            $service = new GoogleServiceGmail($this->client);
            $profile = $service->users->getProfile('me');
            return $profile->getMessagesTotal() ?? 0;
        } catch (\Exception $e) {
            \Log::error('Gmail Count API Error: ' . $e->getMessage());
            return 0;
        }
    }
} 