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
        // Handle GET request for webhook verification
        if($request->isMethod('GET') && $request->has('hub_mode') && $request->hub_mode === 'subscribe'){
            return $this->verifyToken($request);
        }
        
        // Handle POST request for event notifications
        if($request->isMethod('POST')){
            return $this->handleEventNotification($request);
        }
        
        return response()->json(['message' => 'Invalid request'], 404);
    }

    private function verifyToken(Request $request)
    {
        try{
            if(!$request->has('hub_verify_token')){
                return response('Token is required', 400);
            }
            if($request->hub_verify_token !== env('META_TOKEN')){
                return response('Invalid token', 401);
            }
            $challenge = $request->input('hub_challenge');
            if(!$challenge){
                return response('Challenge is required', 400);
            }
            // Meta expects the challenge value as plain text, not JSON
            return response($challenge, 200);

        }   catch(\Exception $e){
            Log::error('Error verifying token: ' . $e->getMessage());
            return response('Internal server error', 500);
        }
    }

    private function handleEventNotification(Request $request)
    {
        try {
            // Validate the signature if you have META_APP_SECRET set
            if(env('META_APP_SECRET')){
                $signature = $request->header('X-Hub-Signature-256');
                if($signature && !$this->validateSignature($request->getContent(), $signature)){
                    Log::warning('Invalid webhook signature');
                    return response('Invalid signature', 401);
                }
            }

            $payload = $request->all();
            Log::info('Meta webhook event received', ['payload' => $payload]);

            // Process the webhook payload here
            // Example: handle leads, user updates, etc.

            // Always return 200 OK for event notifications
            return response('OK', 200);

        } catch(\Exception $e){
            Log::error('Error handling event notification: ' . $e->getMessage());
            return response('Internal server error', 500);
        }
    }

    private function validateSignature($payload, $signature)
    {
        $appSecret = env('META_APP_SECRET');
        if(!$appSecret){
            return false;
        }

        // Remove 'sha256=' prefix if present
        $signature = str_replace('sha256=', '', $signature);
        
        // Generate expected signature
        $expectedSignature = hash_hmac('sha256', $payload, $appSecret);
        
        // Use hash_equals for timing-safe comparison
        return hash_equals($expectedSignature, $signature);
    }
}
