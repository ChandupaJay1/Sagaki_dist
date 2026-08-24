<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyLicense
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $domain = $request->getHost();
        $licenseServer = env('NERDTECH_LICENSE_SERVER');

        // Prevent empty configuration from blocking local dev, or handle as needed
        if (!$licenseServer) {
            Log::warning('Nerdtech license server configuration is missing.');
            // Return $next($request); // Uncomment if you want to bypass when not configured
        }

        $isValid = true; // Default to true so client isn't locked out on server error

        try {
            // Send the POST request to verify the license
            $response = Http::timeout(5)->post(rtrim($licenseServer, '/') . '/api/verify-license', [
                'domain' => $domain,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // If the API explicitly says it's invalid or expired, reject it.
                if (isset($data['valid']) && $data['valid'] === false) {
                    $isValid = false;
                }
                if (isset($data['status']) && in_array($data['status'], ['invalid', 'expired'])) {
                    $isValid = false;
                }

            } else {
                // If explicitly rejected via HTTP status codes (e.g., 401 Unauthorized or 403 Forbidden)
                if ($response->status() === 403 || $response->status() === 401) {
                    $isValid = false;
                } else {
                    // If the server returns a 500 error or similar, allow it to pass 
                    // so we don't lock out the client during temporary server issues.
                    Log::warning('Nerdtech License Server returned an error: ' . $response->status());
                }
            }

        } catch (\Exception $e) {
            // Server is completely unreachable (e.g., connection timeout)
            // We allow it to pass to prevent locking out the client
            Log::warning('Nerdtech License Server unreachable: ' . $e->getMessage());
            dd('API Connection Error: ' . $e->getMessage());
        }

        // Abort with 403 if the license is confirmed invalid
        if (!$isValid) {
            abort(403, 'Your software license has expired. Please contact Nerdtech-Labs.');
        }

        return $next($request);
    }
}
