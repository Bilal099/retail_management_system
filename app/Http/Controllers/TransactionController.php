<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\Merchant;
use App\Models\Inventory;
use App\Models\InventoryDetail;
use Exception;
use DB;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Transaction::with(['merchant'])->get();
        return view('transaction.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $product = Product::with(['unit'])->get();
        $merchant = Merchant::all();
        $transaction_type = array('sale','purchase');
        $payment_type = array('cash','credit');

        return view('transaction.create')
        ->with([
            'product'=>$product, 
            'merchant'=>$merchant,
            'transaction_type' => $transaction_type,
            'payment_type' => $payment_type
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        try {
            $validator = Validator::make($request->all(),[
                'transaction_type'      => 'required|in:sale,purchase',
                'merchant'              => 'required_if:transaction_type,sale',
                'payment_type'          => 'required',
                'transaction_date'      => 'required',
                'total_amount'          => 'nullable',
                'comment'               => 'nullable',
                'product.*'             => 'required',
                'quantity.*'            => 'required',
                'unit_price.*'          => 'required',
            ],
            [
                'transaction_type.required'     => 'This field is required',
                'payment_type.required'         => 'This field is required',
                'transaction_date.required'     => 'This field is required',
                // 'total_amount.required'         => 'This field is required',
                'product.*.required'            => 'This field is required',
                'quantity.*.required'           => 'This field is required',
                'unit_price.*.required'         => 'This field is required',
            ]);
    
            if ($validator->fails()) {
                // dd($validator->errors());
                return redirect()->back()->withErrors($validator->errors())->withInput();
                // return redirect()->back()->withError($validator)->withInput();
            }
            DB::beginTransaction();

            $transaction = Transaction::create([
                'transaction_type'  =>  $request->transaction_type,
                'transaction_date'  =>  $request->transaction_date,
                'merchant_id'       =>  $request->merchant,
                'total_amount'      =>  $request->total_amount,
                'payment_type'      =>  $request->payment_type,
                'comment'           =>  $request->comment,
            ]);
            
            foreach ($request->product as $key => $value) {
                $transactionDetail = TransactionDetail::create([
                    'transaction_id'  =>  $transaction->id,
                    'product_id'  =>  $value,
                    'quantity'       =>  $request->quantity[$key],
                    'unit_price'      =>  $request->unit_price[$key],
                ]);

                if ($request->transaction_type == 'purchase') {
                    $inventory = Inventory::where(['product_id'=>$value])->first();
                    if (is_null($inventory)) {
                        $inventory = Inventory::create([
                            'product_id' => $value,
                            'quantity_in_stock' => 1,
                            'last_restock_date' => $request->transaction_date
                        ]);
                    } else {
                        $inventory->quantity_in_stock = 1;
                        $inventory->last_restock_date = $request->transaction_date;
                        $inventory->save();
                    }
    
                    $inventoryDetail = InventoryDetail::where(['inventory_id'=>$inventory->id,'unit_price'=>$request->unit_price[$key]])->first();
                    if (is_null($inventoryDetail)) {
                        $inventoryDetail = InventoryDetail::create([
                            'inventory_id' => $inventory->id,
                            'unit_price' => $request->unit_price[$key],
                            'remaining_quantity' => $request->quantity[$key]
                        ]);
                    }
                    else{
                        $inventoryDetail->remaining_quantity = ($inventoryDetail->remaining_quantity + $request->quantity[$key]);
                        $inventoryDetail->save();
                    }
                }
                else{
                    $inventory = Inventory::where(['product_id'=>$value,'quantity_in_stock'=>1])->first();
                    // dd(is_null($inventory));
                    if (is_null($inventory)) {
                        $product = Product::find($value);
                        return redirect()->back()->withInput($request->input())->withErrors($validator)->with('msg', $product->name.' not in stock');                        
                    }
                    else {
                        $inventoryDetail = $inventory->inventoryDetail
                        ->where('unit_price',$request->unit_price[$key])
                        ->where('remaining_quantity', '>=', $request->quantity[$key])
                        ->first();
                        if (is_null($inventoryDetail)) {
                            $product = Product::find($value);
                            $msg = $product->name.' with "'.$request->unit_price[$key].'" unit price is not avaiable.';
                            return redirect()->back()->withInput($request->input())->withErrors($validator)->with('msg', $msg);                        
                        }
                        else{
                            $remaining_quantity = $inventoryDetail->remaining_quantity - $request->quantity[$key];
                            $inventoryDetail->remaining_quantity = $remaining_quantity;
                            $inventoryDetail->save();

                            if ($remaining_quantity == 0) {
                                $in_stock_quantity = $inventory->inventoryDetail->sum('remaining_quantity');
                                if ($in_stock_quantity == 0) {
                                    $inventory->quantity_in_stock = 0;
                                    $inventory->save();
                                }
                            }
                        }
                    }
                }
                
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('transactions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        // dd($transaction->transactionDetails);
        $inventory = Inventory::where(['product_id'=>3])->first();
        // dd($inventory->inventoryDetail); 
        // $temp = $inventory->inventoryDetail
        // ->where('unit_price',30)
        // ->where('remaining_quantity', '>=', 174)
        // ->first(); 

        $temp = $inventory->inventoryDetail->sum('remaining_quantity');
        dd($temp);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
