<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;

class AjaxController extends Controller
{
    function getProductDetails(Request $request) {

        try {
            $product = Product::with(['inventory','unit'])->where('id',$request->id)->get();

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
    
    function getTransactionByMerchant(Request $request) {
        try {
            $transaction = Transaction::with(['merchant'])
            ->where('payment_type','credit')
            ->where('is_complete',0)
            ->where('is_cancel',0)
            ->where('merchant_id',$request->id)->get();
            // ->where('reference_model','Merchant')
            // ->where('reference_id',$request->id)->get();
    
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
            'data' => $transaction
        ]);
        
    }
}
