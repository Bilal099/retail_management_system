<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::with('unit')->get();
        return view('product.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_name' => 'required|string|max:255',
                'unit' => 'required|string|max:11',
                'price' => 'nullable|integer',
                'description' => 'nullable|string',
            ]);
            // dd($request->all());
    
            if ($validator->fails()) {
                dd($validator->errors());
                return redirect()->back()->withError($validator->errors())->withInput();
            }
            Product::create([
                'name'              => $request->product_name,
                'unit_id'           => $request->unit,
                'price'             => $request->price,
                'description'       => $request->description
            ]);
            return redirect()->route('product.index');
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withError($e->getMessage())->withInput();
        }  
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // $data = Product::with('unit')->get();
        return view('product.edit')->with('data',$product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {

            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'product_name' => 'required|string|max:255',
                'unit' => 'required|string|max:11',
                'price' => 'nullable|integer|max:255',
                'description' => 'nullable|string',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withError($validator->errors())->withInput();
            }
            $product->update([
                'name'              => $request->product_name,
                'unit_id'           => $request->unit,
                'price'             => $request->price,
                'description'       => $request->description
            ]);
        } catch (Exception $e) {
            return redirect()->back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('product.index');
    }
}
