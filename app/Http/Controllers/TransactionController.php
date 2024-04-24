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
use App\Models\TransactionType;
use App\Models\Invoice;
use Exception;
use DB;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Transaction::with(['merchant'])->where(['is_cancel'=>0])->where(function ($query) {
            $query->where('transaction_type_id', 2)
                ->orWhere('transaction_type_id', 3);
        })->orderBy('id','desc')->get();
        return view('transaction.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $product = Product::with(['unit'])->get();
        $merchant = Merchant::all();
        $transaction_type = TransactionType::where(function ($query) {
            $query->where('name', 'Sale')
                ->orWhere('name', 'Purchase');
        })->get();
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
                //'transaction_type'      => 'required|in:sale,purchase',
                'transaction_type'      => 'required',
                'merchant'              => 'required_if:transaction_type,2',
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
                //'transaction_type'  =>  $request->transaction_type,
                'transaction_type_id'  =>  $request->transaction_type,
                'transaction_date'  =>  $request->transaction_date,
                'merchant_id'       =>  $request->merchant,
                'total_amount'      =>  $request->total_amount,
                'payment_type'      =>  $request->payment_type,
                'comment'           =>  $request->comment,
            ]);

            foreach ($request->product as $key => $value) {
                $transactionDetail = TransactionDetail::create([
                    'transaction_id'    =>  $transaction->id,
                    'product_id'        =>  $value,
                    'quantity'          =>  $request->quantity[$key],
                    'unit_price'        =>  $request->unit_price[$key],
                    'additional_price'  =>  $request->additional_price[$key],
                ]);
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
        $product = Product::with(['unit'])->get();
        $merchant = Merchant::all();
//        $transaction_type = array('sale','purchase');
        $payment_type = array('cash','credit');

//        $transaction_type = TransactionType::where(function ($query) {
//            $query->where('name', 'Sale')
//                ->orWhere('name', 'Purchase');
//        })->get();

        $data = $transaction;
        $details = $transaction->transactionDetails;
        // dd($data);
        return view('transaction.show')->with([
            'data'=>$data,
            'details'=> $details,
            'product'=>$product,
            'merchant'=>$merchant,
//            'transaction_type' => $transaction_type,
            'payment_type' => $payment_type
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
//        $temp = Invoice::with(['merchant:reference_model'])->where('transaction_id',$transaction->id)->get();

//        $temp = Invoice::with(['merchant'])->where('transaction_id',$transaction->id)->where('reference_model', 'LIKE', '%Merchant%' )->get();

//        $temp = Merchant::where('id',$transaction->merchant_id)->get();
//
//        dd($temp);
//        dd($temp[0]['reference_model']);

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

    function makerChecker($transactionID){
    // function makerChecker(Request $request){
        try {
            DB::beginTransaction();
            // $transaction = Transaction::find($request->id);
            $transaction = Transaction::find($transactionID);

            $transactionType = TransactionType::find($transaction->transaction_type_id)->name;

            foreach ($transaction->transactionDetails as $key => $transactionDetail) {
                $product = Product::find($transactionDetail->product_id);
                // dd($product);
                if ($transactionType == 'Purchase') {
                    $inventory = Inventory::where(['product_id'=>$product->id])->first();
                    if (is_null($inventory)) {
                        $inventory = Inventory::create([
                            'product_id' => $transactionDetail->product_id,
                            'quantity_in_stock' => 1,
                            'last_restock_date' => $transaction->transaction_date,
                            'quantity' => $transactionDetail->quantity
                        ]);
                        $product->price = $transactionDetail->unit_price;

                        $product->save();
                    }
                    else {
                        $quantity = $inventory->quantity;

                        $inventory->quantity = $quantity + $transactionDetail->quantity;
                        $inventory->quantity_in_stock = 1;
                        $inventory->last_restock_date = $transaction->transaction_date;
                        $inventory->save();

                        /**
                         * set price in product object
                        */

                        if ($quantity == 0) {
                            $product->price = $transactionDetail->unit_price;
                        }
                        else {
                            $calculationForTransaction = $transactionDetail->unit_price * $transactionDetail->quantity;
                            $calculationForProduct = $product->price * $quantity;
                            $calculateAvg = ($calculationForTransaction + $calculationForProduct) / ($transactionDetail->quantity + $quantity);

                            $product->price = $calculateAvg;
                        }
                        $product->save();
                    }
                }
                elseif ($transactionType == 'Sale'){


                    $inventory = Inventory::where(['product_id'=>$transactionDetail->product_id,'quantity_in_stock'=>1])->first();

                    if (is_null($inventory)) {
                        // $product = Product::find($value);
                        return redirect()->back()->with('msg', $product->name.' not in stock');
                    }
                    else {
                        $quantity = $inventory->quantity - $transactionDetail->quantity;
                        if ($quantity < 0) {
                            return redirect()->back()->with('msg', $product->name.' is not available in '.$transactionDetail->quantity.' quantity.');
                        }
                        else{
                            $inventory->quantity_in_stock = ($quantity==0)? 0:1;
                            $inventory->quantity = $quantity;
                            $inventory->save();
                        }
                    }

                    if ($transaction->payment_type == 'cash') {
                        $invoice = Invoice::create([
                            'reference_id'        => $transaction->merchant_id,
                            //'reference_model'     => 'App\\Models\\Merchant',
                            'reference_model'     => 'Merchant',
                            'total_amount'        => $transaction->total_amount,
                            'transaction_id'      => $transaction->id,
                            'invoice_date'        => $transaction->transaction_date,
                            'is_check'            => 1
                        ]);
                        $transaction->is_complete = true;
                    }
                }
            }
            $transaction->is_check = true;
            $transaction->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            // return redirect()->back()->withError($e->getMessage())->withInput();
            return redirect()->back()->with('msg', $e->getMessage());
        }
        return redirect()->route('transactions.index');
    }

    function cancelTransaction($id) {
        try {
            $transaction = Transaction::find($id);
            $transaction->is_cancel = true;
            $transaction->save();
        } catch (Exception $e) {
            return redirect()->back()->with('msg', $e->getMessage());
        }
        return redirect()->route('transactions.index');
    }

    public function transactionExpenseList()
    {
        $data = Transaction::where(function ($query) {
            $query->where('is_cancel', 0)->where('transaction_type_id','>', 4);
        })->orderBy('id','desc')->get();

        return view('transaction.expense.index')->with('data',$data);
    }

    public function transactionExpenseCreate()
    {
        $product = Product::with(['unit'])->get();
        $merchant = Merchant::all();


        $transaction_type = TransactionType::where(function ($query) {
            $query->where('parent_id', 4);
        })->get();
        $payment_type = array('cash','credit');

        return view('transaction.expense.create')
            ->with([
                'product'=>$product,
                'merchant'=>$merchant,
                'transaction_type' => $transaction_type,
                'payment_type' => $payment_type
            ]);
    }

    public function transactionExpenseStore(Request $request) {

        try {
            $validator = Validator::make($request->all(),[
                'transaction_type'      => 'required',
                'payment_type'          => 'required',
                'transaction_date'      => 'required',
                'total_amount'          => 'required',
                'comment'               => 'nullable',
            ],
            [
                'transaction_type.required'     => 'This field is required',
                'payment_type.required'         => 'This field is required',
                'transaction_date.required'     => 'This field is required',
                'total_amount.required'         => 'This field is required'

            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            DB::beginTransaction();

            $transaction = Transaction::create([
                'transaction_type_id'  =>  $request->transaction_type,
                'transaction_date'  =>  $request->transaction_date,
                'total_amount'      =>  $request->total_amount,
                'payment_type'      =>  $request->payment_type,
                'comment'           =>  $request->comment,
            ]);


            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('transaction.expenseList');
    }

    public function transactionExpenseShow($id)
    {
        $payment_type = array('cash','credit');
        $data = Transaction::find($id);
//        $transaction_type = TransactionType::where(function ($query) {
//            $query->where('parent_id', 4);
//        })->get();
        return view('transaction.expense.show')->with([
            'data'=>$data,
//            'transaction_type' => $transaction_type,
            'payment_type' => $payment_type
        ]);
    }
}
