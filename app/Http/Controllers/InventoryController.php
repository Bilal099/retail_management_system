<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use App\Models\InventoryMapping;
use App\Models\Inventory;
use App\Models\Product;
use Exception;
use Carbon;
use DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Inventory::with(['product'])->get();
        return view('inventory.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $product = Product::all();
        return view('inventory.create')->with('product',$product);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_from'  => 'required',
                'quantity_from' => 'required',
                'price_from'    => 'required',
                'product_to'    => 'required',
                'quantity_to'   => 'required',
                'price_to'      => 'required',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            DB::beginTransaction();
            InventoryMapping::create([
                'product_from'  => $request->product_from,     
                'quantity_from' => $request->quantity_from,        
                'price_from'    => $request->price_from,       
                'product_to'    => $request->product_to,       
                'quantity_to'   => $request->quantity_to,      
                'price_to'      => $request->price_to,     
                'created_by'    => Auth::user()->id,
            ]);

            $productTo = Product::find($request->product_to);
            $productTo->price = $request->price_to;
            $productTo->save();

            $inventoryTo = Inventory::where(['product_id'=> $request->product_to])->first();
            if (is_null($inventoryTo)) {
                $inventoryTo = Inventory::create([
                    'product_id' => $request->product_to,
                    'quantity_in_stock' => 1,
                    'last_restock_date' => Carbon\Carbon::now()->format('Y-m-d'),
                    'quantity' => $request->quantity_to
                ]);
            }
            else {
                $inventoryTo->quantity = $inventoryTo->quantity + $request->quantity_to;
                $inventoryTo->quantity_in_stock = 1;
                $inventoryTo->last_restock_date = Carbon\Carbon::now()->format('Y-m-d');
                $inventoryTo->save();
            }

            $inventoryFrom = Inventory::where(['product_id'=> $request->product_from])->first();
            $quantityFrom = $inventoryFrom->quantity;
            $inventoryFrom->quantity = $quantityFrom - $request->quantity_to;
            $inventoryFrom->quantity_in_stock = (($quantityFrom - $request->quantity_to)==0)? 0:1;
            $inventoryFrom->last_restock_date = Carbon\Carbon::now()->format('Y-m-d');
            $inventoryFrom->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        } 
        return redirect()->route('inventories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        //
    }
}
