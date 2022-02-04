<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Auth;

class FirebaseToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $idTokenString = $request->header('refreshedToken');

        $serviceAccount = [
            "type" => "service_account",
            "project_id" => config('services.firebase.project_id'),
            "private_key_id" => config('services.firebase.private_key_id'),
            "private_key" => config('services.firebase.private_key'),
            "client_email" => config('services.firebase.client_email'),
            "client_id" => config('services.firebase.client_id'),
            "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
            "token_uri" => "https://oauth2.googleapis.com/token",
            "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
            "client_x509_cert_url" => config('services.firebase.client_x509_cert_url')
        ];

        $factory = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri(config('app.FIREBASE_URL'));

        $auth = $factory->createAuth();

        try { // Try to verify the Firebase credential token with Google
    
            $verifiedIdToken = $auth->verifyIdToken($idTokenString);
        
        } catch (\InvalidArgumentException $e) { // If the token has the wrong format
        
            return response()->json([
                'message' => 'Unauthorized - Can\'t parse the token: ' . $e->getMessage()
            ], 401);        
        
        } catch (InvalidToken $e) { // If the token is invalid (expired ...)
        
            return response()->json([
                'message' => 'Unauthorized - Token is invalide: ' . $e->getMessage()
            ], 401);
        
        }

        //echo "<pre>";print_r($verifiedIdToken);die;
        return $next($request);
    }
}
