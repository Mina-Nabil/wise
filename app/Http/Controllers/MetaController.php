<?php

namespace App\Http\Controllers;

use App\Models\Customers\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

            // Process leadgen webhook events
            if(isset($payload['object']) && $payload['object'] === 'page' && isset($payload['entry'])){
                foreach($payload['entry'] as $entry){
                    if(isset($entry['changes'])){
                        foreach($entry['changes'] as $change){
                            // Check if this is a leadgen event
                            if(isset($change['field']) && $change['field'] === 'leadgen'){
                                $leadgenId = $change['value']['leadgen_id'] ?? null;
                                $pageId = $change['value']['page_id'] ?? null;
                                
                                if($leadgenId){
                                    $this->processLeadgen($leadgenId, $pageId);
                                }
                            }
                        }
                    }
                }
            }

            // Always return 200 OK for event notifications
            return response('OK', 200);

        } catch(\Exception $e){
            Log::error('Error handling event notification: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response('Internal server error', 500);
        }
    }

    /**
     * Process a leadgen event by fetching lead details and creating a lead if from upcoming event
     */
    private function processLeadgen($leadgenId, $pageId = null)
    {
        try {
            // Fetch lead details from Meta Graph API
            $leadData = $this->fetchLeadData($leadgenId);
            
            if(!$leadData){
                Log::warning('Failed to fetch lead data from Meta API', ['leadgen_id' => $leadgenId]);
                return;
            }

            // Check if lead is associated with an event and if event is upcoming
            $eventId = $leadData['event_id'] ?? null;
            if($eventId){
                $isUpcoming = $this->isEventUpcoming($eventId);
                if(!$isUpcoming){
                    Log::info('Lead is from past event, skipping', [
                        'leadgen_id' => $leadgenId,
                        'event_id' => $eventId
                    ]);
                    return;
                }
            } else {
                // If no event_id, check if we should process all leads or only event-based leads
                // For now, we'll skip leads without events as per requirement
                Log::info('Lead is not associated with an event, skipping', ['leadgen_id' => $leadgenId]);
                return;
            }

            // Parse and create the lead
            $this->createLeadFromMetaData($leadData, $eventId);

        } catch(\Exception $e){
            Log::error('Error processing leadgen', [
                'leadgen_id' => $leadgenId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Fetch lead data from Meta Graph API
     */
    private function fetchLeadData($leadgenId)
    {
        $accessToken = env('META_ACCESS_TOKEN');
        if(!$accessToken){
            Log::error('META_ACCESS_TOKEN not configured');
            return null;
        }

        try {
            // Fetch lead details
            // Fields: id, created_time, ad_id, ad_name, adset_id, adset_name, campaign_id, campaign_name, form_id, field_data
            $response = Http::get("https://graph.facebook.com/v18.0/{$leadgenId}", [
                'access_token' => $accessToken,
                'fields' => 'id,created_time,ad_id,ad_name,adset_id,adset_name,campaign_id,campaign_name,form_id,field_data'
            ]);

            if(!$response->successful()){
                Log::error('Failed to fetch lead data from Meta API', [
                    'leadgen_id' => $leadgenId,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }

            $leadData = $response->json();
            
            // Fetch form details to get event_id if associated
            if(isset($leadData['form_id'])){
                $formResponse = Http::get("https://graph.facebook.com/v18.0/{$leadData['form_id']}", [
                    'access_token' => $accessToken,
                    'fields' => 'id,name,leads_count,event_id'
                ]);

                if($formResponse->successful()){
                    $formData = $formResponse->json();
                    if(isset($formData['event_id'])){
                        $leadData['event_id'] = $formData['event_id'];
                    }
                }
            }

            return $leadData;

        } catch(\Exception $e){
            Log::error('Exception fetching lead data from Meta API', [
                'leadgen_id' => $leadgenId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Check if an event is upcoming (not in the past)
     */
    private function isEventUpcoming($eventId)
    {
        $accessToken = env('META_ACCESS_TOKEN');
        if(!$accessToken){
            Log::error('META_ACCESS_TOKEN not configured');
            return false;
        }

        try {
            // Fetch event details
            $response = Http::get("https://graph.facebook.com/v18.0/{$eventId}", [
                'access_token' => $accessToken,
                'fields' => 'id,name,start_time,end_time'
            ]);

            if(!$response->successful()){
                Log::warning('Failed to fetch event data from Meta API', [
                    'event_id' => $eventId,
                    'status' => $response->status()
                ]);
                // If we can't fetch event, assume it's upcoming to be safe
                return true;
            }

            $eventData = $response->json();
            
            // Check if event has a start_time
            if(isset($eventData['start_time'])){
                $startTime = Carbon::parse($eventData['start_time']);
                $now = Carbon::now();
                
                // Event is upcoming if start_time is in the future
                return $startTime->isFuture();
            }

            // If no start_time, assume it's upcoming
            return true;

        } catch(\Exception $e){
            Log::error('Exception checking if event is upcoming', [
                'event_id' => $eventId,
                'error' => $e->getMessage()
            ]);
            // On error, assume it's upcoming to be safe
            return true;
        }
    }

    /**
     * Create a Customer lead from Meta lead data
     */
    private function createLeadFromMetaData($leadData, $eventId = null)
    {
        try {
            // Parse field_data to extract lead information
            $fieldData = $leadData['field_data'] ?? [];
            $parsedData = $this->parseFieldData($fieldData);

            // Extract name (could be full name or separate first/last)
            $fullName = $parsedData['full_name'] ?? $parsedData['first_name'] . ' ' . ($parsedData['last_name'] ?? '');
            $nameParts = $this->splitName($fullName);
            $firstName = $parsedData['first_name'] ?? $nameParts['first'];
            $lastName = $parsedData['last_name'] ?? $nameParts['last'];
            $middleName = $parsedData['middle_name'] ?? null;

            // Extract contact information
            $phone = $parsedData['phone_number'] ?? $parsedData['phone'] ?? null;
            $email = $parsedData['email'] ?? null;

            // Extract other fields
            $birthDate = isset($parsedData['date_of_birth']) ? Carbon::parse($parsedData['date_of_birth'])->format('Y-m-d') : null;
            $gender = $this->normalizeGender($parsedData['gender'] ?? null);

            // Find or create campaign based on Meta campaign name
            $campaignId = null;
            if(isset($leadData['campaign_name'])){
                $campaignId = $this->findOrCreateCampaign($leadData['campaign_name'], $eventId);
            }

            // Build note with Meta lead information
            $note = "Meta Lead ID: {$leadData['id']}\n";
            if(isset($leadData['ad_name'])){
                $note .= "Ad: {$leadData['ad_name']}\n";
            }
            if($eventId){
                $note .= "Event ID: {$eventId}\n";
            }
            if(isset($leadData['created_time'])){
                $note .= "Submitted: {$leadData['created_time']}\n";
            }
            $note .= "\nForm Data:\n" . json_encode($parsedData, JSON_PRETTY_PRINT);

            // Create the lead
            $lead = Customer::newLead(
                $firstName,
                $phone ?? 'N/A', // Phone is required
                $email,
                null, // second phone
                $middleName,
                null, // arabic_first_name
                null, // arabic_middle_name
                null, // arabic_last_name
                $birthDate,
                $gender,
                null, // marital_status
                null, // id_type
                null, // id_number
                null, // nationality_id
                null, // profession_id
                null, // salary_range
                null, // income_source
                null, // owner_id (will use default from Auth)
                null, // id_doc
                null, // driver_license_doc
                $note,
                $campaignId
            );

            if($lead){
                Log::info('Successfully created lead from Meta webhook', [
                    'lead_id' => $lead->id,
                    'meta_leadgen_id' => $leadData['id'],
                    'event_id' => $eventId
                ]);
            } else {
                Log::error('Failed to create lead from Meta webhook', [
                    'meta_leadgen_id' => $leadData['id']
                ]);
            }

        } catch(\Exception $e){
            Log::error('Exception creating lead from Meta data', [
                'lead_data' => $leadData,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Parse Meta field_data array into key-value pairs
     */
    private function parseFieldData($fieldData)
    {
        $parsed = [];
        
        foreach($fieldData as $field){
            $name = $field['name'] ?? null;
            $values = $field['values'] ?? [];
            
            if($name && !empty($values)){
                // Take the first value (usually there's only one)
                $parsed[$name] = is_array($values[0]) ? ($values[0]['value'] ?? $values[0]) : $values[0];
            }
        }
        
        return $parsed;
    }

    /**
     * Split a full name into first and last name
     */
    private function splitName($fullName)
    {
        $parts = explode(' ', trim($fullName), 2);
        return [
            'first' => $parts[0] ?? '',
            'last' => $parts[1] ?? ''
        ];
    }

    /**
     * Normalize gender value to match Customer model constants
     */
    private function normalizeGender($gender)
    {
        if(!$gender){
            return null;
        }

        $gender = strtolower(trim($gender));
        
        if(in_array($gender, ['male', 'm', 'man', 'men'])){
            return Customer::GENDER_MALE;
        }
        
        if(in_array($gender, ['female', 'f', 'woman', 'women'])){
            return Customer::GENDER_FEMALE;
        }
        
        return null;
    }

    /**
     * Find or create a Campaign based on Meta campaign name
     */
    private function findOrCreateCampaign($campaignName, $eventId = null)
    {
        try {
            $campaign = \App\Models\Marketing\Campaign::where('name', $campaignName)->first();
            
            if(!$campaign){
                // Create a new campaign
                $campaign = \App\Models\Marketing\Campaign::create([
                    'name' => $campaignName,
                    'description' => $eventId ? "Meta campaign for event: {$eventId}" : "Meta campaign: {$campaignName}",
                    'marketing_channels' => 'Meta/Facebook',
                ]);
                
                Log::info('Created new campaign from Meta webhook', [
                    'campaign_id' => $campaign->id,
                    'campaign_name' => $campaignName
                ]);
            }
            
            return $campaign->id;
            
        } catch(\Exception $e){
            Log::error('Error finding or creating campaign', [
                'campaign_name' => $campaignName,
                'error' => $e->getMessage()
            ]);
            return null;
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
