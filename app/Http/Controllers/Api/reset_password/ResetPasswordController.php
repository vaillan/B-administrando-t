<?php

namespace App\Http\Controllers\Api\reset_password;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\reset_password\ResetCodePassword;
use App\Models\User;
use Validator;

class ResetPasswordController extends Controller
{
    /**
     * Change the password
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Contracts\Routing\ResponseFactory::json
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|exists:reset_code_passwords',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $code = $request->input('code');
        $password = $request->input('password');

        if ($validator->fails()) {
            return response()->json(['msg' => 'Validation Error.', 'params' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE);
        }

        $passwordReset = ResetCodePassword::firstWhere('code', $code);

        if ($passwordReset->isExpire()) {
            return response()->json(['msg' => 'Code is expire'], Response::HTTP_PRECONDITION_FAILED);
        }

        $user = User::firstWhere('email', $passwordReset->email);

        $user->update(['password' => bcrypt($password)]);

        $passwordReset->delete();

        return response()->json(['msg' => 'Password has been successfully reset']);
    }
}
