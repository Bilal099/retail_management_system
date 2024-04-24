@extends('layouts.master')
@section('css')

@endsection
@section('content')						

<div class="row mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create Invoice</h4>
            </div>
            <div class="card-body">
                    
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="merchant" class="form-label">Merchant</label>
                                <input type="text"  class="form-control" id="" value="{{ $data->merchant->merchant_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="transaction" class="form-label">Transaction</label>
                                <input type="text"  class="form-control" id="" value="{{ $data->transaction==null? '':(@$data->transaction->transaction_date . ' - ' . @$data->transaction->payment_type . ' - ' . @$data->transaction->total_amount) }}" readonly>
                                
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="invoice_date" class="form-label">Invoice Date</label>
                                <input type="date" name="invoice_date" class="form-control" readonly value="{{ @$data->invoice_date }}">
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="total_amount" class="form-label">Invoice Amount</label>
                                <input type="number" name="total_amount" class="form-control" id="total_amount" readonly value="{{ @$data->total_amount }}">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="comment" class="form-label">Comment</label>
                                <textarea class="form-control" name="comment" id="comment" rows="5" readonly>{{@$data->comment}}</textarea>
                            </div>
                        </div>
                        
                    </div>

                    @if ($data->is_check == 0 && $data->remaining_amount==0)
                    <a href="{{ route('invoices.makerChecker',$data->id) }}" class="btn btn-primary mt-4 mb-0">Confirm</a>
                    @endif
                    <a href="{{ route('invoices.index') }}" class="btn btn-light mt-4 mb-0">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')

<script>
    $('#merchant').change(function (e) {
        e.preventDefault();

        let _id = $(this).val();

        $.ajax({
            type: "get",
            url: "{{route('ajax.getTransactionByMerchant')}}",
            data: {
                id: _id
            },
            success: function(response) {                
                let _data = response.data;
                
                $.each(_data, function(index, item) {
                    $('#transaction').append('<option value="' + item.id + '">' + item.transaction_date + ' - ' + item.payment_type + ' - ' + item.total_amount + '</option>');
                });
                $('#transaction').prop('disabled', false);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
                $('#transaction').prop('disabled', true);
            }
        });
    });
</script>
		
@endsection