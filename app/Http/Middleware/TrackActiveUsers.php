<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; 

class TrackActiveUsers
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check() && !$request->ajax()) {
            $userId = Auth::id();
            $filename = 'active_tickets/user_' . $userId . '.txt';

            if ($request->route('ticket_id')) {
                $ticketId = $request->route('ticket_id');
                Storage::put($filename, $ticketId);
            } else {
                if (Storage::exists($filename)) {
                    Storage::put($filename, '');
                    Log::info("User navigated away from ticket detail page, file content cleared. ".$request->path());
                }
            }
        }

        return $response;

        /*if (Auth::check()) {

            $userId = Auth::id();
            $ticketId = $request->route('ticket_id');

            $keyPattern = "active_user_{$userId}_*";

            // Retrieve all keys
            $allKeys = Cache::get('all_cache_keys', []);

            // Filter keys that match the pattern
            $matchingKeys = array_filter($allKeys, function($key) use ($keyPattern) {
                return fnmatch($keyPattern, $key);
            });

            // Remove all matching keys from the cache
            foreach ($matchingKeys as $key) {
                Cache::forget($key);
            }

            // Also remove these keys from the allKeys list
            $allKeys = array_diff($allKeys, $matchingKeys);
            Cache::put('all_cache_keys', $allKeys);

            
           // if($ticketId != '') {

                $newKey = "active_user_{$userId}_{$ticketId}";
                $allKeys[] = $newKey;
                Cache::put('all_cache_keys', $allKeys);
            //}
            // Optionally print the allKeys array for debugging
            $allKeys = Cache::get('all_cache_keys', []);
           // echo '<pre>';print_r($allKeys); die;
        }
        return $next($request); */
    }
}
