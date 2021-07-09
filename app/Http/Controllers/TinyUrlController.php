<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTinyUrlRequest;
use App\Http\Requests\RedirectTinyUrlRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TinyUrlController
{
    /**
     * Get the tinyUrl from longUrl
     */
    public function getTinyUrl(Request $request): JsonResponse
    {
        try {
            $getTinyUrlRequest = new GetTinyUrlRequest();
            $getTinyUrlRequest->populate($request->all());
            // validate request
            $validationResult = $getTinyUrlRequest->validate();
            if(filled($validationResult)) {
                throw new Exception($validationResult);
            }
            $response = $getTinyUrlRequest->process();
        }
        catch(Exception $e){
            $response = "Exception occured while calculating tinyUrl. Exception message - " . $e->getMessage();
        }
        return response()->json($response);
    }

    /**
     * Redirects the tinyUrl to matching longUrl, otherwise returns error message
     */ 
    public function redirectTinyUrl(Request $request)
    {
        try {
            $redirectTinyUrlRequest = new RedirectTinyUrlRequest();
            $redirectTinyUrlRequest->populate($request->all());
            // validate request
            $validationResult = $redirectTinyUrlRequest->validate();
            if(filled($validationResult)) {
                throw new Exception($validationResult);
            }
            $response = $redirectTinyUrlRequest->process();
            if(filled($response)) {
                // redirect when response is found
                return redirect($response);
            }
            else {
                return $response = "Unable to find matching URL to perform redirection.";
            }
        }
        catch(Exception $e){
            $response = "Exception occured while redirecting to the mapped url. Exception message - " . $e->getMessage();
        }
        return response()->json($response);
    }
}
