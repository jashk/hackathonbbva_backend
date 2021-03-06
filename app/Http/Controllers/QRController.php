<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class QRController extends Controller
{
    const OPERATION_TYPE = '0001';
    const BANK_BBVA = '00012';
    const COUNTRY = 'MX';
    const CURRENCY = 'MXN';

    static $ACCOUNT_TYPES = ['DEBIT_CARD' => 'TD', 'CREDIT_CARD' => 'TC', 'CLABE' => 'CL'];


    /**
     * @OA\Get(
     *      path="/api/code/generate",
     *      operationId="generateQR",
     *      tags={"QR"},
     *      summary="Get the QR code",
     *      description="Genera un QR",
     *      @OA\Parameter(
     *          name="alias",
     *          description="Alias",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="account",
     *          description="Acount",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="account_type",
     *          description="Account Type",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="reference",
     *          description="Reference",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="account_holder_name",
     *          description="Account Holder Name",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="amount",
     *          description="Amount",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function generate(Request $request) {

        $trans = $request->all();

        $validator = Validator::make($request->all(), [
            'alias' => '',
            'account' => 'required|string',
            'account_type' => 'required|string',
            'reference' => '',
            'account_holder_name' => 'required|string',
            'amount' => '',
            'url' => ''
        ]);

        if ($validator->fails()) {
            return response()->json([
                    "status" => false,
                    "error" => $validator->messages()->first()
                ], 400);

        }
        if(!empty($trans['alias']) && (strlen($trans['alias']) < 3  || strlen($trans['alias']) > 100 || preg_match('/(^([a-zA-Z ]+)?$)/u', $trans['alias']) == false)){
            return response()->json([
                "status" => false,
                "error" => "La nombre del beneficiario debe contener en 3 y 20 caracteres. No debe contener caracteres acentuados, especiales o ñ"
            ], 400);
        }


        if((strlen($trans['account']) < 10  || strlen($trans['account']) > 20 || preg_match('/^[a-zA-Z0-9]*$/', $trans['account']) == false)){
            return response()->json([
                "status" => false,
                "error" => "El número de cuenta consta de 18 dígitos para cuenta CLABE y 16 dígitos para tarjeta de crédito o débito"
            ], 400);
        }

        if(!in_array($trans['account_type'], array_keys(self::$ACCOUNT_TYPES))){
            return response()->json(["status" => false, "error" => "El tipo de cuenta no es soportado, tutilice DEBIT_CARD,  o CLABE"], 400);
        }

        if(!empty($trans['reference']) && (strlen($trans['reference']) < 6  || strlen($trans['reference']) > 7 || preg_match('/^[a-zA-Z0-9]*$/', $trans['reference']) == false)){
            return response()->json(["status" => false, "error" => "La referencia debe contener en 6 y 7 caracteres"], 400);
        }

        if(!empty($trans['amount'])){
            $trans['amount'] = number_format((float)$trans['amount'], 2, '.', '');
            if($trans['amount'] <= 0 || strlen($trans['amount']) > 100){
                return response()->json(["status" => false, "error" => "El monto a pagar no puede ser igual o menor a cero y no debe ser mayor de 100 caracteres"], 400);
            }
        }

        if(!empty($trans['account_holder_name']) && (strlen($trans['account_holder_name']) < 3  || strlen($trans['account_holder_name']) > 20 || preg_match('/(^([a-zA-Z ]+)?$)/u', $trans['account_holder_name']) == false)){
            return response()->json(["status" => false, "error" => "El nombre del beneficiario debe contener en 3 y 20 caracteres.Sin apellidos. No debe contener caracteres acentuados, especiales o ñ"], 400);
        }

        $layout = [
            "ot" => self::OPERATION_TYPE,
            "dOp" => [
                ["alias" => $trans['alias']],
                ["cl" => $trans['account']],
                ['type' => self::$ACCOUNT_TYPES[$trans['account_type']]],
                ['refn' => $trans['reference']],
                ['refa' => $trans['account_holder_name']],
                ['amount' => $trans['amount']],
                ['bank' => self::BANK_BBVA],
                ['country' => self::COUNTRY],
                ['currency' => self::CURRENCY]
            ]
        ];

        $tras_string = json_encode($layout);
        $qrCode = new QrCode($tras_string);

        if(isset($trans['url']) && $trans['url'] == 'true'){
            $path = Storage::disk('public_uploads')->path('/');
            $filename = str_random(10) . '-qrcode.png';
            $qrCode->writeFile($path . '/' . $filename);
            return Storage::disk('public_uploads')->url($filename);
        }else{
            return response($qrCode->writeString())
              ->header('Content-Type', $qrCode->getContentType());
        }
    }

}
