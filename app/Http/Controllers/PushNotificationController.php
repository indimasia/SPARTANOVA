<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushNotificationController extends Controller
{
    public function subscribe(Request $request)
    {
        $user = Auth::user();
        
        $user->updatePushSubscription($request->input('endpoint'), $request->input('keys'), $request->input('contentEncoding'));

        return response()->json(['success' => true]);
    }
}
