<?php

namespace App\Http\Controllers;

use App\Models\SwapRequest;
use Illuminate\Http\Request;
use App\Models\User;


class SwapRequestController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
        ]);

        // Create the swap request
        $swapRequest = SwapRequest::create($validatedData);

        // Send notifications to the receiver
        // You can implement your notification logic here, such as sending emails or push notifications to the receiver

        // Return a response indicating success
        return response()->json(['message' => 'Swap request sent successfully'], 200);
    }

    public function index($userId)
    {
        $user = User::findOrFail($userId);
        $swapRequests = $user->items()->with('swapRequests')->get();

        return response()->json($swapRequests, 200);
    }
}
