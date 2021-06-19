<?php

namespace App\Providers;

use Illuminate\Routing\ResponseFactory as Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->call([$this,'registerResponseMacros']);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('created', function ($message,$data) {
            $response=["error_status"=>false,"message"=>$message,'data'=>$data];
            return response()->json($response,HttpResponse::HTTP_CREATED);
        });

        Response::macro('success', function ($message,$data=null) {
            $response=["error_status"=>false,"message"=>$message];
            if(!empty($data)){
                $response=array_merge($response,['data'=>$data]);
            }
            return response()->json($response,HttpResponse::HTTP_OK);
        });

        Response::macro('failed', function ($message) {
            $response=["error_status"=>true,"message"=>$message];
            return response()->json($response,HttpResponse::HTTP_EXPECTATION_FAILED);
        });

        Response::macro('forbidden', function ($message="You are not authorized to perform this action.") {
            $response=["error_status"=>true,"message"=>$message];
            return response()->json($response,HttpResponse::HTTP_FORBIDDEN);
        });
    }
}
