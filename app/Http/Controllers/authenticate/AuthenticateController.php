<?php

namespace App\Http\Controllers\authenticate;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
class AuthenticateController extends Controller
{
    
    /**
     * 
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmed' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return response()->json(['msg' => 'Validation Error.', 'errors' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE);       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        User::create($input);
        return response()->json(['msg' => 'User register successfully.']);
    }

    /**
     * 
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('administrando-t')->plainTextToken; 
            $success['name'] =  $user->name;
            return response()->json(['autenticado' => $success, 'msg' => 'User login successfully.']);
        } 
        else{ 
            return response()->json(['msg' => 'Unauthorised.'], Response::HTTP_UNAUTHORIZED);
        } 
    }
}
