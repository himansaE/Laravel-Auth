<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\GoogleApiService;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $googleService = new GoogleApiService($user);
        $pageToken = $request->get('pageToken');
        $calendarData = $googleService->getCalendarEvents($pageToken, 10);
        $appUrl = rtrim(config('app.url', '/'), '/');

        return view('calendar', [
            'calendarEvents' => $calendarData['items'],
            'nextPageToken' => $calendarData['nextPageToken'],
            'prevPageToken' => $calendarData['prevPageToken'],
            'appUrl' => $appUrl,
        ]);
    }
} 