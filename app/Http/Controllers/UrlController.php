<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UrlController extends Controller
{

    function urlShortenerPager(){
        return view('pages.dashboard.url-shortener-page');
    }

    public function shortUrlAction(Request $request){
      try{
            $request->validate([
                'original_url' => 'required|url',
            ]);

            $userID = $request->header('id');

            $ip = $request->ip();

            // Checking anti-spam 
            $cacheKey = "spam_check_$ip";
            $spamCount = Cache::get($cacheKey, 0);

            if ($spamCount >= 3) {
                Cache::put($cacheKey, $spamCount + 1, 5);
            
                return back()->with('error', 'You are temporarily blocked due to spamming.. Try again later.');
            }

            // Save the shortened URL to the database
            $shortenedUrl = $this->generateShortenedUrl();
            $url = Url::create([
                'original_url' => $request->input('original_url'),
                'short_url' => $shortenedUrl,
                'user_id' => $userID,
            ]);

            // Increment spam count
            Cache::put($cacheKey, $spamCount + 1, 1);

            return response()->json([
                'status'=>'success',
                'message'=>'Request successfully done',
                'data'=>$url
            ], 201);
      }
      catch(Exception $e){
        return response()->json([
            'status'=>'fail',
            'message'=>'Something went wrong',
            'error'=>$e->getMessage()
        ]);
      }

    }


        private function generateShortenedUrl(){
            $shortKey = substr(md5(uniqid()), 0, 6);

        return url('/') . '/' . $shortKey;
    }


    public function shortURLListShow(Request $request){
        $userID = $request->header('id');

        $allurl = Url::where('user_id', '=', $userID)->get();

        return response()->json([
            'status' => 'success', 
            'data' => $allurl
        ]);
    }

    function shortUrlDeleteAction(Request $request){
        try{
            $userId = $request->header('id');
            $urlId = $request->input('id');

            Url::where('id', '=', $urlId)->where('user_id', '=', $userId)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Url has been deleted successfully'
            ]);

        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => 'Request fail to delete url'
            ]);
        }
    }


    function shortURLListShowById(Request $request){
        try{
            $userId = $request->header('id');
            $urlId = $request->input('id');

            $urlbyid = Url::where('id', '=', $urlId)->where('user_id', '=', $userId)->first();

            return response()->json([
                'status' => 'success',
                'data' => $urlbyid
            ]);

        }
        catch(Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => 'Request fail !'
            ]);
        }
    }



    
    
}


