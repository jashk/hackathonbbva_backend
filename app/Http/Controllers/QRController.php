<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Illuminate\Validation\ValidationException;

class QRController extends Controller
{
    const OPERATION_TYPE = '0001';
    // const DEBIT_CARD = 'TD';
    // const CREDIT_CARD = 'TC';
    // const CLABE = 'CL';
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

<<<<<<< Updated upstream
        $trans = $request->all();
        // $request->validate([
        //     'alias' => 'string|max:100',
        //     'account' => 'required|string|max:20',
        //     'account_type' => 'required|string',
        //     'reference' => 'string',
        //     'account_holder_name' => 'required|string|max:20',
        //     'amount' => 'string|max:100',
        // ]);



        if(!in_array($trans['account_type'], array_keys(self::$ACCOUNT_TYPES))){
            return response()->json(["status" => false, "error" => "El tipo de cuenta no es soportado"], 400);
=======
        $trans = $request->validate([
            'alias' => ['string','min:3', 'max:100', 'regex:/(^([a-zA-Z])?$)/u'],
            'account' => 'required|string|min:10|max:20',
            'account_type' => 'required|string',
            'reference' => 'string|min:6:max:7',
            'account_holder_name' => 'required|string|min:3|max:20',
            'amount' => 'string|max:100',
        ]);

        if(!in_array($trans['account_type'],ACCOUNT_TYPES)){
           trow  \Illuminate\Validation\ValidationException::withMessages([
               'account_type' => ['El tipo de cuenta no es soportado'],
            ]);
>>>>>>> Stashed changes
        }

        if(!empty($trans['amount'])){
            $trans['amount'] = number_format((float)$trans['amount'], 2, '.', '');

            if($trans['amount'] <= 0 ){
                return response()->json(["status" => false, "error" => "El monto a pagar no puede ser igual o menos a cero"], 400);
            }
        }

        if(!empty($trans['reference']) && (strlen($trans['reference']) < 6  || strlen($trans['reference']) > 7)){
            return response()->json(["status" => false, "error" => "La referencia debe contener en 6 y 7 carácteres"], 400);
        }

        if(!empty($trans['account_holder_name']) && (strlen($trans['account_holder_name']) < 3  || strlen($trans['account_holder_name']) > 20)){
            return response()->json(["status" => false, "error" => "La nombre del beneficiario debe contener en 3 y 20 carácteres"], 400);
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

        return response($qrCode->writeString())
              ->header('Content-Type', $qrCode->getContentType());
    }

}
