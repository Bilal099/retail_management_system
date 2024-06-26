<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use App\Models\Merchant;
use App\Models\Invoice;
use Exception;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // $perPage = $request->input('per_page', 10); // Number of items per page, defaulting to 10
        // $page = $request->input('page', 1); // Current page number, defaulting to 1

        // $data = Merchant::paginate($perPage, ['*'], 'page', $page);
        $data = Merchant::all();

        return view('merchant.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('merchant.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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
                return redirect()->back()->withError($validator->errors())->withInput();
            }
            Merchant::create($request->all());
            return redirect()->route('merchant.index');
        } catch (Exception $e) {
            return redirect()->back()->withError($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Merchant $merchant)
    {
        $merchantTransactions = $merchant->transactions->where('is_check',1);

        $totalAmount = $merchantTransactions->sum('total_amount');
        $merchantInvoice = Invoice::where('reference_id',$merchant->id)
        ->where('reference_model','Merchant' )
        ->where('is_check', 1)
        ->get();

        $merchantInvoiceRemaining = Invoice::where('reference_id',$merchant->id)
        ->where('reference_model','Merchant' )
        ->where('is_check', 0)
        ->where('remaining_amount', '>',0)
        ->get();
        $totalInvoice = $merchantInvoice->sum('total_amount') + $merchantInvoiceRemaining->sum('remaining_amount');

        $totalRemaining = $totalAmount - $totalInvoice;

        if ($merchantInvoiceRemaining->isNotEmpty()) {
            $merchantInvoice = $merchantInvoice->merge($merchantInvoiceRemaining);
            
        }

        return view('merchant.show')->with([
            'merchantTransactions'=>$merchantTransactions,
            'totalInvoice'  => $totalInvoice,
            'totalRemaining' => $totalRemaining,
            'totalAmount'  => $totalAmount,
            'merchantInvoice' => $merchantInvoice
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Merchant::find($id);
        return view('merchant.edit')->with('data',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
                return redirect()->back()->withError($validator->errors())->withInput();
            }

            $merchant->update($request->all());
            return redirect()->route('merchant.index');
        } catch (Exception $e) {
            return redirect()->back()->withError($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

//    public function merchantInvoice(Request $request, string $id) ()
//    {
//
//    }
}
