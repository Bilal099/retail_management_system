@extends('layouts.master')
@section('css')

@endsection
@section('page-header')
    @if (Session::has('msg'))
        <div class="alert alert-success mt-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{Session::get('msg')}}</div>
    @endif
@endsection
@section('content')						
{{-- <form method="POST" action="{{ route('transactions.store') }}"> --}}

<div class="row mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Show Transaction</h4>
            </div>
            <div class="card-body">
                    @csrf
                    <div class="row row-sm">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Transaction Type</label>
                                <input type="text" class="form-control" value="{{ ucwords(@$data->transaction_type) }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Payment Type</label>
                                <input type="text" class="form-control" value="{{ ucwords(@$data->payment_type) }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Transaction Date</label>
                                <input type="date" name="transaction_date" class="form-control" value="{{ @$data->transaction_date }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Merchant</label>
                                <input type="text" class="form-control" value="{{ ucwords(@$data->merchant->merchant_name) }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Total Amount</label>
                                <input type="number" name="total_amount" class="form-control" value="{{ @$data->total_amount }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="" class="form-label">Comment</label>
                                <textarea name="comment" class="form-control mb-4" rows="3" readonly>{{ @$data->comment }}</textarea>
                            </div>
                        </div>
                    </div>
                    {{-- <button type="submit" class="btn btn-primary mt-4 mb-0">Create</button> --}}
                    {{-- <a href="{{ route('transactions.index') }}" class="btn btn-light mt-4 mb-0">Back</a> --}}
                    <a href="{{ URL::previous() }}" class="btn btn-light mt-4 mb-0">Back</a>
                
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                    <h3 class="card-title">Detail Table</h3>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                    {{-- <h4 class="caption-subject bold uppercase"> <a class="btn btn-success" style="float: right;" id="add_detail">Add More Detail</a></h4> --}}
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered card-table table-vcenter text-nowrap">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Unit</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Additional Price</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach ($details as $key => $value)
                                <tr>
                                    <td><input type="text"  class="form-control" value="{{ $value->product->name}}" readonly></td>
                                    <td><input type="text"  class="form-control" value="{{ $value->product->unit->name}}" readonly></td>
                                    <td><input type="text"  class="form-control" value="{{ $value->quantity}}" readonly></td>
                                    <td><input type="text"  class="form-control" value="{{ $value->unit_price}}" readonly></td>
                                    <td><input type="text"  class="form-control" value="{{ $value->additional_price}}" readonly></td>                                  
                                </tr>
                                @endforeach
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
{{-- </form> --}}
<noscript type="text/html" id="data_row">
    <tr>
        <td>
            <select name="product[]" class="form-control custom-select select2 product-class">
                <option value="" data-unit="">Select Product</option>
                @foreach ($product as $item)
                    <option value="{{$item->id}}" data-unit="{{$item->unit->name}}">{{ $item->name }}</option>
                @endforeach
            </select>
        </td>
        {{-- <td class="product-unit"> </td> --}}
        <td class="product-unit"> <input type="text" name="unit[]" class="form-control" readonly> </td>
        <td> <input type="number" name="quantity[]" class="form-control"> </td>
        <td> <input type="number" name="unit_price[]" class="form-control"> </td>
        <td> <input type="number" name="additional_price[]" class="form-control"> </td>
        <td> <a class="btn btn-danger deleteRow"> <i class="fa fa-trash"></i> </a> </td>
    </tr>
</noscript>
@endsection
@section('js')
    {{-- <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/select2.js')}}"></script> --}}
		
    <script>
        $("#add_detail").click(function(){  
            $('tbody').append($("#data_row").html());
        });

        $("table").on('change','.product-class', function () {
            let _this = $(this);
            let unit = _this.find('option:selected').data('unit');
            _this.closest('tr').find('.product-unit').find('input').val(unit);                
        });

        $("table").on("click", ".deleteRow", function() {
            $(this).closest("tr").remove();
        });

        $('#transaction_type').change(function() {
            let _this = $(this);
            let _val = _this.find('option:selected').val();
            if (_val == 'sale') {
                $('#merchant').prop('disabled', false);
            } else  {
                $('#merchant').prop('disabled', true);
            }

        });
    </script>
@endsection