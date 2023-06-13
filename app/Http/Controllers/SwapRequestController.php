<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SwapRequest;



class SwapRequestController extends Controller
{
    public function receivedSwapRequests($userId)
    {
        $user = User::findOrFail($userId);
        $receivedSwapRequests = $user->receivedSwapRequests;

        return response()->json($receivedSwapRequests, 200);
    }
}
