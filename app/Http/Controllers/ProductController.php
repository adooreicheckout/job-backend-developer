<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Foreach_;

class ProductController extends Controller
{

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products',
            'description' => 'required',
            'price' => 'required',
            'category' => 'required',
            'image_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 403);
        }

        $product = Product::create([
            'description' => $request->description,
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'image_url' => $request->image_url,
        ]);

        return response()->json(['result' => $product, 'status' => 200], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 403);
        }

        $product = Product::find($request->id);

        if (!isset($product)) {
            return response()->json(['error_code' => 10007, 'error_msg' => 'Product not found'], 404);
        }

        $product->update($request->all());

        $product->save();

        return response()->json(['result' => $product, 'status' => 200], 200);

    }

    public function delete($id)
    {
        $product = Product::find($id);

        if (!isset($product)) {
            return response()->json(['error_code' => 10007, 'error_msg' => 'Product not found'], 404);
        }


        $product->delete();
        return response()->json(['success' => 200], 200);
    }

    public function getById($id)
    {
        $product = Product::find($id);

        if (!isset($product)) {
            return response()->json(['error_code' => 10007, 'error_msg' => 'Product not found'], 404);
        }

        return response()->json(['result' => $product], 200);
    }

    public function getProduct(Request $request)
    {
        $params = $request->all();

        $records = Product::filters($params)->pagination($params)->get();

        $recordsTotal = Product::count();

        $recordsFiltered = Product::filters($params)->get()->count();

        $response = [
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
        ];


        // $response['data'] = $records;

        
        return response()->json(['result' => $records], 200);
        
    }

    /*    public function importProduct($oid)
    {

        if ($oid == 'all') {
            $url = "https://fakestoreapi.com/products";
        } else {
            $url = "https://fakestoreapi.com/products/" . $oid;
        }

        try {
            $curlHandle = curl_init();

            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($curlHandle, CURLOPT_URL, $url);

            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);

            $curlData = curl_exec($curlHandle);

            curl_close($curlHandle);
            $resultData = json_decode($curlData);

            if ($oid == 'all') {
                foreach ($resultData as $data) {
                    Product::create([
                        'description' => $data->description,
                        'name' => $data->title,
                        'price' => $data->price,
                        'category' => $data->category,
                        'image_url' => $data->image,
                    ]);
                }
            } else {
                Product::create([
                    'description' => $resultData->description,
                    'name' => $resultData->title,
                    'price' => $resultData->price,
                    'category' => $resultData->category,
                    'image_url' => $resultData->image,
                ]);
            }

            return response()->json(['Import performed successfully' => 200], 200);
        } catch (\Throwable $th) {
            return response()->json(['error_code' => 10007, 'error_msg' => 'Error getting response from fakestoreapi.'], 404);
        }
    } */
}
