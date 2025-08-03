<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\GoogleApiService;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $googleService = new GoogleApiService($user);
        $pageToken = $request->get('pageToken');
        $tasksData = $googleService->getTasks($pageToken, 10);
        $appUrl = rtrim(config('app.url', '/'), '/');

        return view('tasks', [
            'tasks' => $tasksData['items'],
            'nextPageToken' => $tasksData['nextPageToken'],
            'prevPageToken' => $tasksData['prevPageToken'],
            'appUrl' => $appUrl,
        ]);
    }
} 