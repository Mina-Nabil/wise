<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\SetUserRequest;
use App\Models\Users\Notification;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MetaController extends Controller
{

    public function index(Request $request)
    {
        if($request->has('hub_mode') && $request->hub_mode === 'subscribe'){
            return $this->verifyToken($request);
        }
        return response()->json(['message' => 'Invalid request'], 404);
    }

    private function verifyToken(Request $request)
    {
        try{
            if(!$request->has('hub_verify_token')){
                return response()->json(['message' => 'Token is required'], 400);
            }
            if($request->hub_verify_token !== env('META_TOKEN')){
                return response()->json(['message' => 'Invalid token'], 401);
            }
            $challenge = $request->input('hub_challenge');
            if(!$challenge){
                return response()->json(['message' => 'Challenge is required'], 400);
            }
            return response()->json(['message' => 'Challenge verified', 'challenge' => $challenge]);

        }   catch(\Exception $e){
            Log::error('Error verifying token: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
