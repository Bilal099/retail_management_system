<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use App\Models\Supplier;
use Exception;

class SupplierController extends Controller
{
    public function index($page = 1,$limit = 10)
    {
        // $suppliers = Supplier::paginate($request->get('per_page', 10));
        $suppliers = Supplier::paginate($limit, ['*'], 'page', $page);
        dd($suppliers);
        return response()->json([
            'status' => true,
            'msg' => 'Data is Successfully fetch!',
            'data' => $suppliers
        ],200);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'supplier_name' => 'required|string|max:255',
                'contact_name'  => 'required|string|max:11',
                'contact_phone' => 'required|string|max:255',
                'contact_email' => 'nullable|email|max:50',
                'address'       => 'nullable|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'msg' => $validator->errors(),
                ],400);
            }

            Supplier::create($validator->validated());
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
            $data = Supplier::find($id);

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
            $supplier = Supplier::find($id);
            if (!$supplier) {
                return response()->json(['error' => 'Supplier not found'], 404);
            }
            
            $validator = Validator::make($request->all(), [
                'supplier_name' => 'required|string|max:255',
                'contact_name'  => 'required|string|max:11',
                'contact_phone' => 'required|string|max:255',
                'contact_email' => 'nullable|email|max:50',
                'address'       => 'nullable|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'msg' => $validator->errors(),
                ],400);
            }

            $supplier->update($request->all());
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
            $supplier = Supplier::find($id);
            if (!$supplier) {
                return response()->json(['error' => 'Supplier not found'], 404);
            }
            $supplier->delete();
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
