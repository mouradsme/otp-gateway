<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\OTP;
use Carbon\Carbon;
use App\Services\InfobipService;
class SiineOTPController extends Controller
{
    public function generate(Request $request)
    {     
        
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'regex:/^(05|06|07)[0-9]{8}$/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'VALIDATION_ERROR',
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $existingOtpCount = OTP::where('phone', $request->phone)
                                ->where('created_at', '>=', Carbon::now()->subHour())
                                ->count();

        if ($existingOtpCount >= 5) {
            return response()->json(['message' => 'RATE_LIMIT_EXCEEDED'], 429);
        }

        OTP::where('phone', $request->phone)->delete();

        $otpCode = rand(1000, 9999);

        $otp = OTP::create([
            'phone' => $request->phone,
            'otp' => $otpCode,
            'expiry_datetime' => Carbon::now()->addMinutes(2),
        ]);

        $this->sendOtpSms($request->phone, $otpCode);

        return response()->json(['message' => 'OTP_SENT']);
    }

    private function sendOtpSms($phoneNumber, $otpCode)
    {
        $phoneNumber = preg_replace('/^0/', '00213', $phoneNumber);

        $result = InfobipService::send($phoneNumber, env('SENDER_ID'), $otpCode);

    }

    public function verify(Request $request)
    {       
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'string', 'regex:/^(05|06|07)[0-9]{8}$/'],
            'otp' => 'required|string|size:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'VALIDATION_ERROR',
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }



        $otpRecord = OTP::where('phone', $request->phone)
                        ->orderBy('created_at', 'desc')
                        ->first();

        if (!$otpRecord) {
            return response()->json(['message' => 'INVALID_INPUT'], 400);
        }

        if ($otpRecord->isValid($request->otp)) {
            $otpRecord->delete();

            return response()->json(['message' => 'OTP_VERIFIED']);
        }

        return response()->json(['message' => 'INVALID_OR_EXPIRED'], 400);
    }
}