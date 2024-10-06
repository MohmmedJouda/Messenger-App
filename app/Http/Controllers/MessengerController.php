<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessengerController extends Controller
{
    public function index($id = null)
    {
        $user = Auth::user();
        // return $user;
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $friends = User::where('id', '<>', $user->id)
            ->orderBy('name')
            ->paginate();
        // return $friends;
        // $friendsJson = json_encode($friends);
        // return $friendsJson;
        return view('messenger', compact('friends'));
        // return $data;
        // return response()->json($friends);

    }


    public function getUser()
    {
        return response()->json(Auth::user());
    }
}
