<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;

class QRController extends Controller
{
    const OPERATION_TYPE = '0001';
    const DEBIT_CARD = 'TD';
    const CREDIT_CARD = 'TC';
    const CLABE = 'CL';
    const BANK_BBVA = '00012';
    const COUNTRY = 'MX';
    const CURRENCY = 'MXN';

    const ACCOUNT_TYPES = ['DEBIT_CARD', 'CREDIT_CARD', 'CLABE'];


    public function generate(Request $request) {

        $trans = $request->validate([
            'alias' => ['string','min:3', 'max:100', 'regex:/(^([a-zA-Z])?$)/u'],
            'account' => 'required|string|min:10|max:20',
            'account_type' => 'required',
            'reference' => 'string|min:6:max:7',
            'account_holder_name' => 'required|string|min:3|max:20',
            'amount' => 'string|max:100',
        ]);

        if(!in_array($trans['account_type'],ACCOUNT_TYPES)){
           trow  \Illuminate\Validation\ValidationException::withMessages([
               'account_type' => ['El tipo de cuenta no es soportado'],
            ]);
        }

        if(!empty($trans['amount'])){
            $trans['amount'] = number_format((float)$trans['amount'], 2, '.', '');

            if($trans['amount'] <= 0 ){
                trow  \Illuminate\Validation\ValidationException::withMessages([
                   'amount' => ['El monto a pagar no puede ser igual o menos a cero'],
                ]);
            }
        }

        $layout = [
            "ot" => OPERATION_TYPE,
            "dOp" => [
                ["alias" => $trans['alias']],
                ["cl" => $trans['account']],
                ['type' => self::$trans['account_type']],
                ['refn' => $trans['reference']],
                ['refa' => $trans['account_holder_name']],
                ['amount' => $trans['amount']],
                ['bank' => BANK_BBVA],
                ['country' => COUNTRY],
                ['currency' => CURRENCY]
            ]
        ];

        $tras_string = json_encode($layout);

        $qrCode = new QrCode($tras_string);
        $response->header(
            'Content-type',
            $qrCode->getContentType()
        );

        // We return our image here.
        return $qrCode->writeString();
    }

}
