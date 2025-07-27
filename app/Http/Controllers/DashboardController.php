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
        $calendarEvents = $googleService->getCalendarEvents();
        $tasks = $googleService->getTasks();
        $emails = $googleService->getEmails();

        return view('dashboard', [
            'calendarEvents' => $calendarEvents,
            'tasks' => $tasks,
            'emails' => $emails,
        ]);
    }
} 