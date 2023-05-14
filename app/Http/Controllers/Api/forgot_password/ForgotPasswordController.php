<?php

namespace App\Http\Controllers\Api\forgot_password;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\reset_password\ResetCodePassword;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendCodeResetPassword;
use Illuminate\Support\Facades\DB;
use Validator;


class ForgotPasswordController extends Controller
{
    /**
     * Send random code to email of user to reset password
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Contracts\Routing\ResponseFactory::json
     */
    public function forgotPassword(Request $request)
    {
        $query = DB::transaction(function () use ($request) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users',
            ]);

            $email = $request->input('email');

            if ($validator->fails()) {
                return response()->json(['msg' => 'Validation Error.', 'params' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE);
            }

            ResetCodePassword::where('email', $email)->delete();

            $dataReset = [
                'email' => $email,
                'code' => mt_rand(100000, 999999),
                'created_at' => now()
            ];

            $codeData = ResetCodePassword::create($dataReset);
            $data = new SendCodeResetPassword($codeData->code);

            Mail::to($email)->send($data);

            return response()->json(['msg' => 'Reseting password process, checkout yours emails']);
        });

        return $query;
    }
}
