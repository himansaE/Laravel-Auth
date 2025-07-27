<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleServiceCalendar;
use Google\Service\Tasks as GoogleServiceTasks;
// use Google\Service\Gmail as GoogleServiceGmail; // Uncomment if Gmail API is enabled
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
        $this->client->setAccessToken([
            'access_token' => $user->google_token,
            'refresh_token' => $user->google_refresh_token,
        ]);
        $this->client->setScopes([
            GoogleServiceCalendar::CALENDAR,
            GoogleServiceTasks::TASKS,
            // GoogleServiceGmail::GMAIL_READONLY, // Uncomment if Gmail API is enabled
        ]);
    }

    public function getCalendarEvents()
    {
        try {
            $service = new GoogleServiceCalendar($this->client);
            $calendarId = 'primary';
            $events = $service->events->listEvents($calendarId, ['maxResults' => 10]);
            return $events->getItems();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getTasks()
    {
        try {
            $service = new GoogleServiceTasks($this->client);
            $tasklists = $service->tasklists->listTasklists(['maxResults' => 1]);
            if (count($tasklists->getItems()) > 0) {
                $tasklistId = $tasklists->getItems()[0]->getId();
                $tasks = $service->tasks->listTasks($tasklistId, ['maxResults' => 10]);
                return $tasks->getItems();
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getEmails()
    {
        // Uncomment and implement if Gmail API is enabled and scopes are set
        // try {
        //     $service = new GoogleServiceGmail($this->client);
        //     $messages = $service->users_messages->listUsersMessages('me', ['maxResults' => 10]);
        //     return $messages->getMessages();
        // } catch (\Exception $e) {
        //     return [];
        // }
        return [];
    }
} 