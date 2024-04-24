<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;

use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Exception;
use DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Invoice::with(['merchant'])->where('reference_model','Merchant')->
        orderBy('id','desc')->get();

        return view('invoice.index')->with([
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $transaction = Transaction::where('is_complete',1)->get();
        $merchant = Merchant::all();
        return view('invoice.create')->with([
            'merchant' => $merchant
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
                'merchant'         => 'required',
                'transaction'      => 'nullable', 
                'invoice_date'     => 'required', 
                'total_amount'     => 'required',
                'comment'          => 'required',  
            ],
            [
                'merchant.required'       => 'This field is required',
                'transaction.required'    => 'This field is required',
                'invoice_date.required'   => 'This field is required',
                'total_amount.required'   => 'This field is required',
                'comment.required'        => 'This field is required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            DB::beginTransaction();

            $invoice = Invoice::create([
                'reference_id'        => @$request->merchant,
                'reference_model'     => 'Merchant',
                'total_amount'        => @$request->total_amount,
                'transaction_id'      => @$request->transaction,
                'invoice_date'        => @$request->invoice_date,
                'comment'             => @$request->comment
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('msg', $e->getMessage());
        }
        return redirect()->route('invoices.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return view('invoice.show')->with([
            'data' => $invoice
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }

    function makerChecker($invoiceID) {
        try {
            DB::beginTransaction();

            $invoice = Invoice::find($invoiceID);

            $total_remaining = (int)$invoice->total_amount;
            $invoice_amount = (int)$invoice->total_amount;

            $remaining_invoice = Invoice::where('reference_id',$invoice->reference_id)
            ->where('reference_model','Merchant')
            ->where('is_check', 0)
            ->where('remaining_amount', '>',0)
            ->get();

            if ($remaining_invoice->sum('remaining_amount') > 0) {
                $total_remaining = $total_remaining + $remaining_invoice->sum('remaining_amount');
                $invoice_amount = $total_remaining;
            }

            $transactions = Transaction::where('merchant_id',$invoice->merchant->id)
            ->where('is_check',1)
            ->where('is_complete',0)
            ->where('is_cancel', 0)
            ->where('payment_type', 'credit')
            ->get();

            $checkTransaction = $transactions->where('total_amount',$invoice_amount);

            

            if ($checkTransaction->isEmpty()) {

                foreach ($transactions as $key => $transaction) {

                    $invoice_amount = $invoice_amount - $transaction->total_amount ;
                    if ($invoice_amount < 0) {
                        // $invoice->remaining_amount = abs($invoice_amount);
                        $invoice->remaining_amount = $total_remaining;
                        if (count($remaining_invoice) > 0) {
                            foreach ($remaining_invoice as $key => $value) {
                                $temp = Invoice::find($value->id);
                                $temp->is_check = 1;
                                $temp->remaining_amount = 0;
                                $temp->save();
                            }
                        }
                        break;
                    }
                    elseif($invoice_amount >= 0){
                        $transaction->is_complete = 1;
                        $transaction->save();
                        $total_remaining = $invoice_amount; 

                        
                    }
                }

                $invoice->is_check = $total_remaining==0? true:false;
                $invoice->save();
               

            }
            else{
                $transactions = Transaction::find($checkTransaction[0]->id);
                $transactions->is_complete = 1;
                $transactions->save();

                $invoice->is_check = true;
                $invoice->save();

                if (count($remaining_invoice) > 0) {
                    foreach ($remaining_invoice as $key => $value) {
                        $temp = Invoice::find($value->id);
                        $temp->is_check = 1;
                        $temp->remaining_amount = 0;
                        $temp->save();
                    }
                }
            }
            // dd('stop');

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            // return redirect()->back()->withError($e->getMessage())->withInput();
            return redirect()->back()->with('msg', $e->getMessage());
        }
        return redirect()->route('invoices.index');
    }
}
