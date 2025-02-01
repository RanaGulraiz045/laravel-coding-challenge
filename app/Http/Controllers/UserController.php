<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function getRandomUsers(Request $request){
        $gender = $request->query('gender', ''); // Default to all genders if none is specified
        $page = $request->query('page', 1); // Default page number
        $results = $request->query('results', 10); // Default number of results per page

        $response = Http::get('https://randomuser.me/api/', [
            'gender' => $gender,
            'page' => $page,
            'results' => $results
        ]);

        $users = $response->json()['results'];
        $info = $response->json()['info']; // This contains pagination info

        return view('users.index', [
            'users' => $users,
            'info' => $info
        ]);
    }

    public function showProfile($seed)
    {
        $response = Http::get('https://randomuser.me/api/', [
            'username' => $seed
        ]);
        // return $response->json();

        $user = $response->json()['results'][0];

        return view('users.profile', compact('user'));
    }
}
