<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserAvatarController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'avatar_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        // save image to public folder
        $imagePath = $request->file('avatar_img')->store('avatars', 'public');

        $user->update([
            'avatar_img' => $imagePath
        ]);

        // return response()->json([
        //     'message' => 'Avatar updated successfully',
        //     'user' => $user
        // ], 200);


    }
}
