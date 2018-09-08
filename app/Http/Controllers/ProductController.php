<?php

namespace App\Http\Controllers;

use App\Merchant;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\MerchantCollection as MerchantCollectionResource;


class ProductController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/products",
     *      operationId="getAllProducts",
     *      tags={"Products"},
     *      summary="Get List Of All Products",
     *      description="Regresa el Listado de productos de la bd",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function list(Request $request) {
        try {
            $products = Product::all();
            Log::debug($products);
            return response()->json(["status" => true, "data" => $products]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(["status" => false, "error" => "Ocurrio un error interno, vuelva a internar o comuniquese a sistemas"], 500);
        }
    }


    /**
     * @OA\Get(
     *      path="/api/products/{id}",
     *      operationId="getAllProductsFromMerchant",
     *      tags={"Products"},
     *      summary="Get List Of All Products of an specified merchant",
     *      description="Regresa el listado de productos de un mercader especifico",
     *      @OA\Parameter(
     *          name="id",
     *          description="Merchant id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
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
    public function listByMerchant(Request $request,$id) {
        try {

            $products = Product::where('merchant_id',$id)->get();
            return response()->json(["status" => true, "data" => $products]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(["status" => false, "error" => "Ocurrio un error interno, vuelva a internar o comuniquese a sistemas"], 500);
        }
    }


    /**
     * @OA\Get(
     *      path="/api/product/{id}",
     *      operationId="getProduct",
     *      tags={"Products"},
     *      summary="Get one specified Product",
     *      description="El producto del id especificado",
     *      @OA\Parameter(
     *          name="id",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
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
    public function getProduct(Request $request,$id) {
        try {

            $products = Product::find($id)->get();
            return response()->json(["status" => true, "data" => $products]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(["status" => false, "error" => "Ocurrio un error interno, vuelva a internar o comuniquese a sistemas"], 500);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/product/add",
     *      operationId="Create New Product",
     *      tags={"Products"},
     *      summary="Create New Product",
     *      description="Crea un nuevo producto",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      description="Name",
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      description="price",
     *                      property="price",
     *                      type="double"
     *                  ),
     *                  @OA\Property(
     *                      description="Merchant Id",
     *                      property="merchant_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      description="Picture",
     *                      property="picture",
     *                      type="file"
     *                  )
     *              )
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
    public function add(Request $request) {
        try {
            $data = $request->all();
            extract($data);

            DB::beginTransaction();
            $product = new Product();
            $product->name = $name;
            $product->price = $price;
            $product->merchant_id = $merchant_id;

            //Upload Image

            $path = $request->file("picture")->store("products");
            $product->imagen = $path;

            $product->save();
            DB::commit();
            return response()->json(["status" => true, "message" => "Producto Agregado"]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(["status" => false, "error" => "Ocurrio un error interno, vuelva a internar o comuniquese a sistemas"], 500);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/product/{id}/update",
     *      operationId="updateProduct",
     *      tags={"Products"},
     *      summary="Update a Product",
     *      description="Actualiza informacion del  producto",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      description="Name",
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      description="price",
     *                      property="price",
     *                      type="double"
     *                  ),
     *                  @OA\Property(
     *                      description="Merchant Id",
     *                      property="merchant_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      description="Picture",
     *                      property="picture",
     *                      type="file"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
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
    public function update(Request $request,$id){
        try {
            $data = $request->all();
            extract($data);

            DB::beginTransaction();
            $product = Product::find($id);
            $product->name = $name;
            $product->price = $price;
            $product->merchant_id = $merchant_id;

            //Upload Image
            //Y si no manda imagen?
            if($request->file("picture")!=null){
                $path = $request->file("picture")->store("products");
                $product->imagen = $path;
            }

            $product->save();
            DB::commit();
            return response()->json(["status" => true, "message" => "Producto Corregido"]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(["status" => false, "error" => "Ocurrio un error interno, vuelva a internar o comuniquese a sistemas"], 500);
        }
    }


    /**
     * @OA\Delete(
     *      path="/api/product/{id}",
     *      operationId="deleteProduct",
     *      tags={"Products"},
     *      summary="Delete one specified Product",
     *      description="El producto del id especificado",
     *      @OA\Parameter(
     *          name="id",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
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
    public function delete(Request $request,$id){
        try {
            $data = $request->all();
            extract($data);

            DB::beginTransaction();
            $product = Product::find($id);
            $product->delete();
            DB::commit();
            return response()->json(["status" => true, "message" => "Producto Agregado"]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(["status" => false, "error" => "Ocurrio un error interno, vuelva a internar o comuniquese a sistemas"], 500);
        }
    }

}
