<?php
/**
 * Created by Anurag Sinha.
 * Start Date: 09-04-2024
 * Time: 17:30
 */

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Models\Contact;
use App\Models\ContactFund;
use App\Models\EmailAddress;
use App\Models\AuthMeeting;
use App\Models\CalendarTask;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use App\Helpers\GnUtils;

// use Google\Client as GoogleClient;
// use Google\Service\Calendar;



class GoogleMeetController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle()
    {

        return Socialite::driver('google')
        ->scopes(['https://www.googleapis.com/auth/calendar.events',
                  'https://www.googleapis.com/auth/meetings.space.created'
        ])
        ->with([
            'access_type' => 'offline', // Request offline access for refresh token
            'prompt' => 'consent' 
        ])
        ->redirect();
    }


    public function handleGoogleCallback(Request $request)
    {
        // Retrieve the access code from the callback request
        $accessCode = $request->get('code');

        // Extract Google client credentials from the configuration
        $clientId = Config::get('services.google.client_id');
        $clientSecret = Config::get('services.google.client_secret');
        $redirectUri = Config::get('services.google.redirect');

        // Prepare data for the token exchange request
        $data = [
            'code' => $accessCode,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code',
        ];

        // Create a Guzzle HTTP client
        $httpClient = new Client();

        try {
            // Send a POST request to Google's token endpoint to exchange the authorization code for an access token
            $response = $httpClient->post('https://oauth2.googleapis.com/token', [
                'form_params' => $data,
            ]);

            // Check if the request was successful
            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody(), true);

                $contactId = Contact::sessionContactId();
                $accessToken = $responseData['access_token'];
                $expiresIn = $responseData['expires_in'];
                $tokenType = $responseData['token_type'];
                $refresh_token =  $responseData['refresh_token'];
                // $idToken = $responseData['id_token'];
                // $tokenParts = explode('.', $idToken);
                // $tokenPayload = json_decode(base64_decode($tokenParts[1]), true);


                // Check if a record with the same user id exists
                $existingRecord = AuthMeeting::where('contact_id', $contactId)->first();

                if ($existingRecord) {
                    // If record exists, update the access token
                    $existingRecord->access_token = $accessToken;
                    $existingRecord->access_token_expires_at = now()->addSeconds($expiresIn);
                    $existingRecord->save();
                } else {
                   // If record doesn't exist, create a new record
                   AuthMeeting::create([
                      'contact_id' => $contactId,
                      'platform' => 'google',
                      'access_token' => $accessToken,
                      'access_token_expires_at' => now()->addSeconds($expiresIn),
                      'refresh_token' => $refresh_token,
                      'refresh_token_expires_at' => null,
                   ]);

		   $prettyCalendarEvents = $this->fetchCalendarEvents($accessToken); //Have to check response
		   $taskOnGoogleCalendar = $this->fetchTaskOnCalendar();
		   //$prettyCalendarEvents = json_encode($calendarEvents, JSON_PRETTY_PRINT);
                  // dd($prettyCalendarEvents);
                   // Redirect to the view page with events data
                   return view('agency.agency-advisor.meetings.view-calendar')->with(['success' => 'You are now authorized', 'prettyCalendarEvents' => $prettyCalendarEvents,'taskOnGoogleCalendar' => $taskOnGoogleCalendar]);
                
                }

             } else {
 
                 return response()->json(['error' => 'Failed to exchange authorization code for access token'], $response->getStatusCode());
             }
         } catch (\Exception $e) {
            //  return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
            return redirect('m/agency/googlemeet/authenticate')->with('error', 'Failed to authorize.');
         }
    }

    public function Authenticate(){

        GnUtils::addBreadcrumb('Calendar');
        $contactId = Contact::sessionContactId();
	$contactExists = AuthMeeting::where('contact_id', $contactId)->exists();
	$accessToken ='';
        // dd($contactExists);

        if ($contactExists) {

            $authDetail = AuthMeeting::where('contact_id', $contactId)->first();

            if ($authDetail->access_token_expires_at->isPast()) {
                // Access token is expired, refresh it
                $this->generateAccessTokenWithRefreshToken();
    
                // Fetch the updated access token from the database
                $accessToken = AuthMeeting::where('contact_id', $contactId)->value('access_token');

	    }else{
             $accessToken = AuthMeeting::where('contact_id', $contactId)->value('access_token');

             }		    
	    $prettyCalendarEvents = $this->fetchCalendarEvents($accessToken);
        $taskOnGoogleCalendar = $this->fetchTaskOnCalendar();
	    // $prettyCalendarEvents = json_encode($calendarEvents, JSON_PRETTY_PRINT);
	   // $prettyCalendarEvents = json_encode($calendarEvents, JSON_PRETTY_PRINT);
//	    dd($prettyCalendarEvents);
            return view('agency.agency-advisor.meetings.view-calendar')->with([
                'prettyCalendarEvents' => $prettyCalendarEvents, 
                'taskOnGoogleCalendar' => $taskOnGoogleCalendar
            ]);

        } else {

            // Contact does not exist in AuthMeeting, show authentication view
            return view('agency.agency-advisor.meetings.authenticate');
        }

    }

    // public function viewMeeting(){
    //     return view('agency.agency-advisor.meetings.view-meeting');
    // }

    public function viewCalendar(){
        return view('agency.agency-advisor.meetings.view-calendar');
    }

    // public function viewGoogleCalendar(){
    //     return view('agency.agency-advisor.meetings.view-googlecal');
    // }

    public function generateAccessTokenWithRefreshToken()
    {
        $contactId = Contact::sessionContactId();

        // Retrieve the record with the refresh token
        $authDetail = AuthMeeting::where('contact_id', $contactId)->first();

        if ($authDetail) {
            // Prepare data for the token exchange request
            $data = [
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'refresh_token' => $authDetail->refresh_token,
                'grant_type' => 'refresh_token',
            ];

            $httpClient = new Client();

            try {

                // Send a POST request to Google's token endpoint to exchange the refresh token for a new access token
                $response = $httpClient->post('https://oauth2.googleapis.com/token', [
                  'form_params' => $data,
                ]);
  
                if ($response->getStatusCode() === 200) {
                    // Decode the JSON response
                    $responseBody = $response->getBody()->getContents();
                    $responseData = json_decode($responseBody, true);
  
                    if ($responseData !== null) {
                        // Update the existing record with the new access token
                        $authDetail->access_token = $responseData['access_token'];
                        $authDetail->access_token_expires_at = now()->addSeconds($responseData['expires_in']);
                        $authDetail->save();

                        return $responseData['access_token'];
                        // echo "Access Token generated";
                    } else {
                        echo "Failed to decode JSON response";
                    }
                } else {
                    echo "Access Token not generated: " . $response->getStatusCode();
                }
            } catch (\Exception $e) {
               // Handle exceptions if an error occurs
                //    echo "Error: " . $e->getMessage();
                return redirect('m/agency/googlemeet/authenticate')->with('error', 'Error occured while generating access code based on refresh token.');
            
            }
  
        } else {
            // Handle the case where no record with the refresh token is found
            return null;
        }
    }

    private function fetchCalendarEvents($accessToken) //have to check this function
    {
        // Create a Guzzle HTTP client
        $httpClient = new Client();

        try {
            // Send a GET request to Google Calendar API to fetch events
            $response = $httpClient->get('https://www.googleapis.com/calendar/v3/calendars/primary/events', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ],
            ]);

            // Check if the request was successful
            if ($response->getStatusCode() === 200) {
		    $calendarEvents = json_decode($response->getBody(), true);

                return $calendarEvents;
            } else {
                // Handle error response from the API
                // return null;
                return ['error' => 'Failed to fetch events: ' . $response->body()];
            }
        } catch (\Exception $e) {
            // Handle exception
            // return null;
            return redirect('m/agency/googlemeet/authenticate')->with('error', 'Error occured while fetching events.');
        }
    }

    public function deleteEvent(Request $request) {
	    //    $data = $request->all();
        $eventId = $request->input('event_id');

        $contactId = Contact::sessionContactId();
	    $accessToken = AuthMeeting::where('contact_id', $contactId)->value('access_token');
        // $eventId = $request->input('eventId');
        $endPoint = 'https://www.googleapis.com/calendar/v3/calendars/primary/events/' . $eventId;

        $httpClient = new Client();
        
        try {
            // Send a GET request to Google Calendar API to fetch events
            $response = $httpClient->delete($endPoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ],
            ]);

            // Check if the request was successful
            if ($response->getStatusCode() === 204) {
                return redirect('m/agency/googlemeet/authenticate')->with('success', 'Deleted successfully');
                // $deleteEvent = json_decode($response->getBody(), true);
                // $prettyCalendarEvents = $this->fetchCalendarEvents($accessToken); //Have to check response
                // return view('agency.agency-advisor.meetings.view-calendar')->with(['success' => 'Event is succesfully deleted', 'prettyCalendarEvents' => $prettyCalendarEvents]);
                // return $deleteEvent;
            } else {
                // Handle error response from the API
                // return null;
                // return ['error' => 'Failed to delete events: ' . $response->body()];
            }
        } catch (\Exception $e) {
            // Handle exception
            // return null;
            return redirect('m/agency/googlemeet/authenticate')->with('error', 'Error occured while deleting the event.');
        }
    }

    public function updateEvent(Request $request)
    {
        // dd('Anurag');
        // dd($request->all());
        $contactId = Contact::sessionContactId();
        $accessToken = AuthMeeting::where('contact_id', $contactId)->value('access_token');
        $eventId = $request->input('event_id_input');
        $title = $request->input('title');
        // $startDate = $request->input('start_date'). ':00';
        // $endDate = $request->input('end_date'). ':00';
        $startDate = date('Y-m-d\TH:i:s', strtotime($request->input('start_date')));
        $endDate = date('Y-m-d\TH:i:s', strtotime($request->input('end_date')));
        $description = $request->input('description');
        $location = $request->input('location'); 
        // $timeZone ='Asia/Kolkata';
        $timeZone = $request->input('timezone');
        // dd($contactId, $accessToken, $eventId, $title, $startDate, $endDate, $description, $location);

        // Prepare data for the Google Calendar API request
        $requestData = [
            'summary' => $title,
            'description' => $description,
            'start' => ['dateTime' => $startDate,'timeZone' => $timeZone],
            'end' => ['dateTime' => $endDate,'timeZone' => $timeZone],
	    'location' => $location
    ];


        // If the assignees field is provided, split the input by commas and create an array of email addresses
        if ($request->filled('assignees')) {
            $assignees = explode(',', $request->input('assignees'));
            
            // Add each assignee to the attendees list
            foreach ($assignees as $assignee) {
                $requestData['attendees'][] = ['email' => trim($assignee)];
            }
        }

        //dd($requestData);
        $endPoint = 'https://www.googleapis.com/calendar/v3/calendars/primary/events/' . $eventId;
        // dd($endPoint);
        $httpClient = new Client();
        try {
            // Send a GET request to Google Calendar API to fetch events
            $response = $httpClient->put($endPoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ],
                'json' => $requestData
            ]);

            // Check if the request was successful
            if ($response->getStatusCode() === 200) {

                return redirect('m/agency/googlemeet/authenticate')->with('success', 'Updated successfully');
                
            } else {
                // Handle error response from the API
                // return null;
                return ['error' => 'Failed to update events: ' . $response->body()];
            }
        } catch (\Exception $e) {
		// Handle exception
		// return ['error' => 'An error occurred: ' . $e->getMessage()];
        return redirect('m/agency/googlemeet/authenticate')->with('error', 'Update cancel due to a technical issue.');
        }
     
    }

    public function createEventOnGoogleCalendar(Request $request)
    {
        // Retrieve the access token from the session or wherever you store it
        $accessToken = AuthMeeting::where('contact_id', Contact::sessionContactId())->value('access_token');
        
        $summary = $request->input('title');
        $description = $request->input('description');
      //  $startDateTime = $request->input('startDateTime');
	//  $endDateTime = $request->input('endDateTime');
	 $startDateTime = $request->input('startDateTime'). ':00';
        $endDateTime = $request->input('endDateTime'). ':00';
        // $startTime =  $request->input('startDate') . 'T' . $request->input('startTime') . ':00';
        // $endTime = $request->input('endDate') . 'T' . $request->input('endTime') . ':00';
        $timeZone = $request->input('timezone');
        $location = $request->input('location');
       
        // Event data
        $eventData = [
            'summary' => $summary,
            'description' => $description,
            'start' => [
                'dateTime' => $startDateTime,
                'timeZone' => $timeZone,
            ],
            'end' => [
                'dateTime' => $endDateTime,
                'timeZone' => $timeZone,
            ],
            'location' => $location,
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'email', 'minutes' => 30],
                ],
            ],
        ];

        // Check if the checkbox for creating Google Meet link is checked
      if ($request->has('createMeetLink')) {
            // Add conference data for Google Meet
            $eventData['conferenceData'] = [
                'createRequest' => [
                    'requestId' => uniqid(),
                ],
                'conferenceSolutionKey' => [
                    'type' => 'hangoutsMeet',
                ],
            ];
        }

        // If the assignees field is provided and not empty
        if ($request->filled('assignees')) {
            $assignees = array_filter(array_map('trim', explode(',', $request->input('assignees'))));
            
            // Add each assignee to the attendees list
            foreach ($assignees as $assignee) {
                $eventData['attendees'][] = ['email' => $assignee];
            }
        }
        // dd($eventData);

        $httpClient = new Client();
        
        try {
            // Send a POST request to the Google Calendar API endpoint
            $response = $httpClient->post('https://www.googleapis.com/calendar/v3/calendars/primary/events?conferenceDataVersion=1', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ],
                'json' => $eventData,
            ]);
    
            if ($response->getStatusCode() === 200) {

                $responseBody = $response->getBody()->getContents();
		$createdEvent = json_decode($responseBody, true);
		$prettyCalendarEvents = $this->fetchCalendarEvents($accessToken);
		$taskOnGoogleCalendar = $this->fetchTaskOnCalendar();
		return view('agency.agency-advisor.meetings.view-calendar')->with([
                'prettyCalendarEvents' => $prettyCalendarEvents,
                'taskOnGoogleCalendar' => $taskOnGoogleCalendar,
                'success_message' => 'Event Created Successfully'
                ]);
              // return view('agency.agency-advisor.meetings.view-calendar')->with('success_message', 'Event Created Sucessfully');
                //return $createdEvent; 
	    } else {
		    dd($response);
                return ['error' => 'Failed to create event: ' . $response->body()];
            }
        } catch (\Exception $e) {
		// Handle any errors
		// dd($e);
        return redirect('m/agency/googlemeet/authenticate')->with( 'error', 'Failed to create the event due to an error.');
            // return ['error' => 'An error occurred: ' . $e->getMessage()];
        }
    }

    public function getEmailSuggestions(Request $request)
    {
        $advisorId = Contact::sessionContactId();
        $term = $request->query('term');
        
        // Step 1: Get fund_id associated with Advisor
        $fundIds = ContactFund::where('contact_id', $advisorId)
        ->pluck('fund_id');
        

        // Step 2: Get contact IDs associated with these fund IDs
        $contactIds = ContactFund::whereIn('fund_id', $fundIds)
        ->pluck('contact_id');

        // Step 3: Retrieve email addresses associated with these contact IDs
        $emails = EmailAddress::whereIn('contact_id', $contactIds)
        ->where('email_address', 'LIKE', '%' . $term . '%')
        ->pluck('email_address');

        return response()->json($emails);
    }




    //Custom Task
    public function storeTaskOnCalendar (Request $request) {

        $contactId = Contact::sessionContactId();
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due' => 'required|date',
        ]);

        // $dueDate = Carbon::parse($request->due)->toDateTimeString();

        CalendarTask::create([
            'title' => $request->title,
            'description' => $request->description,
            'due' => $request->due,
            'contact_id' => $contactId, 
        ]);
        $accessToken = AuthMeeting::where('contact_id', Contact::sessionContactId())->value('access_token');
	$taskOnGoogleCalendar = $this->fetchTaskOnCalendar();
	$prettyCalendarEvents = $this->fetchCalendarEvents($accessToken);

        //return view('agency.agency-advisor.meetings.view-calendar')->with(['taskOnGoogleCalendar' => $taskOnGoogleCalendar, 'success' => 'Task created succesfully']);
      return view('agency.agency-advisor.meetings.view-calendar')->with(['taskOnGoogleCalendar' => $taskOnGoogleCalendar, 'prettyCalendarEvents' => $prettyCalendarEvents, 'success' => 'Task created succesfully']);
    }

    private function fetchTaskOnCalendar(){
        $taskOnCalendar = CalendarTask::all();
        return $taskOnCalendar;
    }

    public function deleteTaskFromCalendar (Request $request) {
        $taskId = $request->input('task_id');

        // Find the task by ID and delete it
        $task = CalendarTask::find($taskId);

        if ($task) {
            $task->delete();
            return redirect()->back()->with('success', 'Task deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Task not found.');
        }
    }

    public function updateTaskOnCalendar (Request $request) {
        // dd($request->all());
        $taskId = $request->input('task_id_input');
        // dd($taskId);
        $title = $request->input('taskTitle');
        $description = $request->input('taskDescription');
        $dueDate = $request->input('dueDate');

        // Find the task by ID
        $task = CalendarTask::findOrFail($taskId);

        // Update the task details
        $task->title = $title;
        $task->description = $description;
        
        // Parse and set the due date
        $task->due = \Carbon\Carbon::createFromFormat('m/d/Y, h:i A', $dueDate);

        // Save the updated task to the database
        $task->save();

	// Optionally, return a response
	return redirect('m/agency/googlemeet/authenticate')->with('success', 'Task updated successfully.');
        
    }
}



