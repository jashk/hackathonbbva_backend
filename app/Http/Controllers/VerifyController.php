<?php

namespace App\Http\Controllers;

use App\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\MerchantCollection as MerchantCollectionResource;
use \Exception;
use App\Http\Controllers\Controller;
use Authy\AuthyApi;
use Illuminate\Support\Facades\Validator;

class VerifyController extends Controller
{
    protected function verificationRequestValidator(array $data)
    {
        return Validator::make($data, [
            'country_code' => 'required|string|max:3',
            'phone_number' => 'required|string|max:10',
            'via' => 'required|string|max:4',
        ]);
    }

    protected function verificationCodeValidator(array $data)
    {
        return Validator::make($data, [
            'country_code' => 'required|string|max:3',
            'phone_number' => 'required|string|max:10',
            'token' => 'required|string|max:10'
        ]);
    }

    protected function startVerification(
        Request $request,
        AuthyApi $authyApi
    ) {
        $data = $request->all();
        $validator = $this->verificationRequestValidator($data);
        extract($data);
        if ($validator->passes()) {
            $response = $authyApi->phoneVerificationStart($phone_number, $country_code, $via);
            if ($response->ok()) {
                return response()->json($response->message(), 200);
            } else {
                return response()->json((array)$response->errors(), 400);
            }
        }
        return response()->json(['errors'=>$validator->errors()], 403);
    }

    protected function verifyCode(
        Request $request,
        AuthyApi $authyApi
    ) {
        $data = $request->all();
        $validator = $this->verificationCodeValidator($data);
        extract($data);
        if ($validator->passes()) {
            try {
                $result = $authyApi->phoneVerificationCheck($phone_number, $country_code, $token);
                return response()->json($result, 200);
            } catch (Exception $e) {
                $response=[];
                $response['exception'] = get_class($e);
                $response['message'] = $e->getMessage();
                $response['trace'] = $e->getTrace();
                return response()->json($response, 403);
            }
        }
        return response()->json(['errors'=>$validator->errors()], 403);
    }
}
