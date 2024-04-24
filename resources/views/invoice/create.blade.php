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
                <form method="POST" action="{{ route('invoices.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="merchant" class="form-label">Merchant</label>
                                <select name="merchant" id="merchant" class="form-control">
                                    <option value="" selected>Select option</option>
                                    @foreach ($merchant as $key => $item)
                                        <option value="{{$item->id}}">{{ $item->merchant_name }}</option>
                                    @endforeach
                                </select>
                                @error('merchant')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="transaction" class="form-label">Transaction</label>
                                <select name="transaction" id="transaction" class="form-control" disabled>
                                    <option value="" selected>Select option</option>
                                </select>
                                @error('transaction')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="invoice_date" class="form-label">Invoice Date</label>
                                <input type="date" name="invoice_date" class="form-control" id="invoice_date" value="{{ old('invoice_date') }}">
                                @error('invoice_date')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="total_amount" class="form-label">Invoice Amount</label>
                                <input type="number" name="total_amount" class="form-control" id="total_amount" value="{{ old('total_amount') }}">
                                @error('total_amount')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="comment" class="form-label">Comment</label>
                                <textarea class="form-control" name="comment" id="comment" rows="5"></textarea>
                                @error('comment')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        
                    </div>
                    <button type="submit" class="btn btn-primary mt-4 mb-0">Create</button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-light mt-4 mb-0">Back</a>
                </form>
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