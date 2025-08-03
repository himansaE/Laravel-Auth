<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\GoogleApiService;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $googleService = new GoogleApiService($user);
        
        // Get full counts from Google APIs
        $calendarEventsCount = $googleService->getCalendarEventsCount();
        $tasksCount = $googleService->getTasksCount();
        $emailsCount = $googleService->getEmailsCount();

        return view('dashboard', [
            'calendarEventsCount' => $calendarEventsCount,
            'tasksCount' => $tasksCount,
            'emailsCount' => $emailsCount,
        ]);
    }
} 