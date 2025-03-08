<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function check(Request $request)
    {
        return response()->json(['message' => 'Authorized'], 200);
    }
}