<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SwapRequest;

class SwapRequestController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
            'my_item_id' => 'required|exists:items,id',
        ]);

        // Create the swap request
        $swapRequest = SwapRequest::create($validatedData);

        // Send notifications to the receiver
        // You can implement your notification logic here, such as sending emails or push notifications to the receiver

        // Return a response indicating success
        return response()->json(['message' => 'Swap request sent successfully'], 200);
    }

    public function receivedSwapRequests($userId)
    {
        $user = User::findOrFail($userId);
        $receivedSwapRequests = $user->receivedSwapRequests;

        return response()->json($receivedSwapRequests, 200);
    }
    public function destroy($swapRequestId)
    {
        $swapRequest = SwapRequest::findOrFail($swapRequestId);
        $swapRequest->delete();

        return response()->json(['message' => 'Swap request deleted successfully'], 200);
    }
}
