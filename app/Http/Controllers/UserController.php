<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest\SignInRequest;
use App\Http\Requests\UserRequest\SignUpRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Exception;

class UserController extends Controller
{
    public function signUp(SignUpRequest $request)
    {
        try{
            $user=User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>$request->password
            ]);

            if($user){
                $response=response()->created("User account created successfully",new UserResource($user));
            }else{
                $response=response()->failed("Failed to create the account");
            }
            return $response;
        }catch(Exception $e){
            return response()->json(["error"=>true,"error_message"=>$e->getMessage()]);
        }

    }
    public function signIn(SignInRequest $request)
    {
        try{
            $user=User::where(['email'=>$request->email,'password'=>$request->password])->first();
            if(!empty($user))
            {
                $user->update(['token'=>Str::random(60)]);
                $response=response()->success("Logged In Successfully",new UserResource($user));
            }
            else
            {
                $response=response()->failed("Email or password is Invalid");
            }
            return $response;
        }
        catch(Exception $e)
        {
            return response()->json(["error"=>true,"error_message"=>$e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        if($request->user->token=$request->header('token'))
        {
            $request->user->update(['token'=>null]);
            $response=response()->success("User Logged Out Successfully");
        }
        else{
           $response=response()->forbidden();   
        }
        return $response;
    }
}
