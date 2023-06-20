<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;



class ItemsController extends Controller
{
    public function update(Request $request, string $id, string $item_id)
    {
        $name = $request->input('name');
        $description = $request->input('description');
        $quantity = $request->input('quantity');
        $category_id = $request->input('category_id');

        // Required parameters check
        if (!$name || !$quantity || !$category_id) {
            return response()->json([
                'message' => 'Required parameters missing',
            ], 400);
        }

        $user = User::findOrFail($id);
        $item = $user->items()->find($item_id);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $item->update([
            'name' => $name,
            'description' => $description,
            'quantity' => $quantity,
            'category_id' => $category_id,
        ]);

        // Retrieve the existing items of the user
        $items = $user->items()->get()->toArray();

        $user->items = json_encode($items);
        $user->save();

        return response()->json([
            'message' => 'Item updated successfully',
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'category_id' => $item->category_id,
                'image' => $item->image,
            ],
        ]);
    }

    /**
     * Delete an item by ID.
     */
    public function destroy($id, $item_id)
    {
        $user = User::findOrFail($id);
        $item = $user->items()->find($item_id);
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        $item->delete();
        $user->items = $user->items()->get();
        $user->save();
        return response()->json(['message' => 'Item deleted successfully'], 200);
    }
    public function item_post(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'category_id' => 'required|integer|exists:categories,id',
            'images' => 'nullable|array|max:3', // Adjusted validation for the 'images' field
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjusted validation for the array of images
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = User::findOrFail($id);

        $itemData = [
            "name" => $request->input('name'),
            "description" => $request->input('description'),
            "quantity" => $request->input('quantity'),
            "category_id" => $request->input('category_id'),
        ];

        // Handle image upload
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $imagePaths = [];

            foreach ($images as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();

                // Store the uploaded image
                $imagePath = $image->storeAs('images', $imageName, 'public');

                $imagePaths[] = $imagePath;
            }

            $itemData['images'] = $imagePaths;
        }

        $item = $user->items()->create($itemData);

        // Retrieve the existing items of the user
        $items = $user->items()->get()->toArray();

        // Update the items field of the user with the updated array
        $user->items = json_encode($items);
        $user->save();

        return response()->json([
            'message' => 'New item added successfully',
            'item' => [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'category_id' => $item->category_id,
                'images' => $item->images, // Assuming 'images' is a field on the Item model
            ],
        ]);
    }




    public function getUserByItemId(string $itemId)
    {
        $user = User::whereHas('items', function ($query) use ($itemId) {
            $query->where('id', $itemId);
        })->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'user' => $user,
        ]);
    }

    public function getItemById(string $itemId)
    {
        $user = User::whereHas('items', function ($query) use ($itemId) {
            $query->where('id', $itemId);
        })->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $item = $user->items()->findOrFail($itemId);

        return response()->json([
            'item' => $item,
        ]);
    }
}
