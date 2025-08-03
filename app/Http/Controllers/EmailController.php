<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\GoogleApiService;

class EmailController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $googleService = new GoogleApiService($user);
        $pageToken = $request->get('pageToken');
        $emailsData = $googleService->getEmails($pageToken, 10);
        $appUrl = rtrim(config('app.url', '/'), '/');

        return view('emails', [
            'emails' => $emailsData['items'],
            'nextPageToken' => $emailsData['nextPageToken'],
            'prevPageToken' => $emailsData['prevPageToken'],
            'appUrl' => $appUrl,
        ]);
    }
} 