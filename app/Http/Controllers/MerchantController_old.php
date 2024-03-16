<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use App\Models\Merchant;
use Exception;

class MerchantController extends Controller
{
    public function index($page = 1,$limit = 10)
    {
        // $suppliers = Supplier::paginate($request->get('per_page', 10));
        $object = Merchant::paginate($limit, ['*'], 'page', $page);
        return response()->json([
            'status' => true,
            'msg' => 'Data is Successfully fetch!',
            'data' => $object
        ],200);
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'merchant_name' => 'required|string|max:255',
                'phone' => 'required|string|max:11',
                'address' => 'required|string|max:255',
                'details' => 'nullable|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'msg' => $validator->errors(),
                ],400);
            }

            Merchant::create($validator->validated());
            return response()->json([
                'status' => true,
                'msg' => 'Data is Successfully Store!',
            ],201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage(),
            ],500);
        }
    }

    public function show($id)
    {
        try {
            $data = Merchant::find($id);

            return response()->json([
                'status' => true,
                'msg' => 'Data is Successfully Fetch!',
                'data'  => $data,
            ],201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage(),
            ],500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $merchant = Merchant::find($id);
            if (!$merchant) {
                return response()->json(['error' => 'Merchant not found'], 404);
            }
            
            $validator = Validator::make($request->all(), [
                'merchant_name' => 'required|string|max:255',
                'phone' => 'required|string|max:11',
                'address' => 'required|string|max:255',
                'details' => 'nullable|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'msg' => $validator->errors(),
                ],400);
            }

            $merchant->update($request->all());
            return response()->json([
                'status' => true,
                'msg' => 'Data is Successfully Update!',
            ],200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage(),
            ],500);
        }  
    }

    public function destroy($id)
    {
        try {
            $merchant = Merchant::find($id);
            if (!$merchant) {
                return response()->json(['error' => 'Merchant not found'], 404);
            }
            $merchant->delete();
            return response()->json([
                'status' => true,
                'msg' => 'Data is Successfully Deleted!',
            ],200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage(),
            ],500);
        }
    }
}
