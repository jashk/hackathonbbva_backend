<?php

namespace App\Http\Controllers;

use App\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\MerchantCollection as MerchantCollectionResource;


class MerchantController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/merchants",
     *      operationId="getListMerchants",
     *      tags={"Merchants"},
     *      summary="Get List Of Merchants",
     *      description="Regresa el listado de merchants para  ser consultado por el ejecutivo",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/MerchantCollection")
     *          )
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function list(Request $request) {
        try {
            $merchants = new MerchantCollectionResource(Merchant::all());
            return response()->json(["status" => true, "data" => $merchants]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(["status" => false, "error" => "Ocurrio un error interno, vuelva a internar o comuniquese a sistemas"], 500);
        }
    }
    /**
     * @OA\Post(
     *      path="/api/merchants",
     *      operationId="postAddMerchant",
     *      tags={"Merchants"},
     *      summary="Add Merchant",
     *      description="Agrega un nuevo merchant",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      description="Name",
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      description="Lastname",
     *                      property="lastname",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      description="Phone",
     *                      property="phone",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      description="Business Name",
     *                      property="business_name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      description="RFC",
     *                      property="rfc",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      description="Business Address",
     *                      property="business_address",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      description="starts_at",
     *                      property="starts_at",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      description="Ends At",
     *                      property="ends_at",
     *                      type="string"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function add(Request $request) {
        //try {
            DB::beginTransaction();
            $merchant = new Merchant();
            $merchant->muid = uniqid();
            $merchant->firts_name = $request->name;
            $merchant->last_name = $request->lastname;
            $merchant->phone = $request->phone;
            $merchant->business_social_name = $request->business_name;
            $merchant->business_social_rfc = $request->rfc;
            $merchant->business_social_address = $request->business_address;
            // $merchant->business_social_address_lat = $request->lat;
            // $merchant->business_social_address_lng = $request->lng;
            $merchant->approved = 0;   //Correccion
            $merchant->status = 0;  //Correccion
            $merchant->business_start = $request->starts_at;
            $merchant->business_end = $request->ends_at;
            $merchant->save();
            DB::commit();
            return response()->json(["status" => true, "message" => "Solicitud registrada"]);
        //} catch (\Exception $e) {
        //    Log::debug($e->getMessage());
        //    return response()->json(["status" => false, "error" => "Ocurrio un error interno, vuelva a internar o comuniquese a sistemas"], 500);
        //}
    }
    /**
     * @OA\Post(
     *      path="/api/merchants/{id}/approvals",
     *      operationId="postMerchantApproval",
     *      tags={"Merchants"},
     *      summary="Approval / Reject Merchant",
     *      description="Acepta o rechaza un merchant",
     *      @OA\Parameter(
     *          name="id",
     *          description="Merchant id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      description="Approved",
     *                      property="approved",
     *                      type="integer"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function approvals(Request $request, $id) {
        try {
            $merchant = Merchant::find($id);
            $merchant->approved = $request->approved;
            $merchant->status = $request->approved ? 1 : 0;
            $merchant->save();
            DB::commit();
            return response()->json(["status" => true, "message" => $request->approved ? "Aprobado" : "Rechazado"]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(["status" => false, "error" => "Ocurrio un error interno, vuelva a internar o comuniquese a sistemas"], 500);
        }
    }
    /**
     * @OA\Post(
     *      path="/api/merchants/{id}/qr",
     *      operationId="getMerchantQR",
     *      tags={"Merchants"},
     *      summary="Get Merchant QR",
     *      description="Returns qr data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Merchant id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      description="Approved",
     *                      property="approved",
     *                      type="integer"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function getQR(Request $request, $id) {
        try {
            $merchant = Merchant::find($id);
            $merchant->approved = $request->approved;
            $merchant->status = $request->approved ? 1 : 0;
            $merchant->save();
            DB::commit();
            return response()->json(["status" => true, "message" => $request->approved ? "Aprobado" : "Rechazado"]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(["status" => false, "error" => "Ocurrio un error interno, vuelva a internar o comuniquese a sistemas"], 500);
        }
    }
}
