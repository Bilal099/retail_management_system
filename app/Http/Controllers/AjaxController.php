<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class AjaxController extends Controller
{
    function getProductDetails(Request $request) {
        // dd($request->all());
        try {
            $product = Product::with(['inventory','unit'])->where('id',$request->id)->get();
            // dd($product);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage(),
                'data' => null
            ]);
        }
        return response()->json([
            'status' => true,
            'msg' => 'Data is Successfully fetch!',
            'data' => $product
        ]);
    }
}
