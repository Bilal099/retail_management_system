@extends('layouts.master')
@section('css')

@endsection
@section('page-header')
    @if (Session::has('msg'))
        <div class="alert alert-success mt-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{Session::get('msg')}}</div>
    @endif
@endsection
@section('content')						
<form method="POST" action="{{ route('transactions.store') }}">

<div class="row mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create Transaction</h4>
            </div>
            <div class="card-body">
                {{-- <form method="POST" action="{{ route('transactions.store') }}"> --}}
                    @csrf
                    <div class="row row-sm">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Transaction Type</label>
                                <select id="transaction_type" name="transaction_type" class="form-control custom-select select2">
                                    <option value="">Select Option</option>
                                    @foreach ($transaction_type as $item)
                                    <option value="{{$item}}" {{old('transaction_type')==$item? 'selected':''}} >{{ucwords($item)}}</option>
                                    @endforeach
                                </select>
                                @error('transaction_type')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Payment Type</label>
                                <select name="payment_type" class="form-control custom-select select2">
                                    @foreach ($payment_type as $item)
                                        <option value="{{$item}}" {{old('payment_type')==$item? 'selected':''}} >{{ucwords($item)}}</option>
                                    @endforeach
                                </select>
                                @error('payment_type')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Transaction Date</label>
                                <input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date') }}" >
                                @error('transaction_date')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Merchant</label>
                                <select id="merchant" name="merchant" class="form-control custom-select select2" {{ old('transaction_type')!=null? (old('transaction_type')=='sale'? '':'disabled'):'disabled' }}>
                                    <option value="">Select Merchant</option>
                                    @foreach ($merchant as $item)
                                            <option value="{{$item->id}}" {{ old('merchant')==$item->id? 'selected':'' }}>{{$item->merchant_name}}</option>                                        
                                    @endforeach
                                </select>
                                @error('merchant')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Total Amount</label>
                                <input type="number" id="total_amount" name="total_amount" class="form-control" value="{{ old('total_amount',0) }}" >
                                @error('total_amount')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="" class="form-label">Comment</label>
                                <textarea name="comment" class="form-control mb-4" rows="3">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4 mb-0">Create</button>
                    <a href="{{ route('transactions.index') }}" class="btn btn-light mt-4 mb-0">Back</a>
                
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                    <h3 class="card-title">Bordered Table</h3>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                    <h4 class="caption-subject bold uppercase"> <a class="btn btn-success" style="float: right;" id="add_detail">Add More Detail</a></h4>
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (old('product') != null)
                                @foreach (old('product') as $key => $oldValue)
                                <tr>
                                    <td>
                                        <select name="product[]" class="form-control custom-select select2 product-class">
                                            <option value="" data-unit="">Select Product</option>
                                            @foreach ($product as $item)
                                                <option value="{{$item->id}}" data-unit="{{$item->unit->name}}" {{ $oldValue==$item->id? 'selected':'' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('product.'.$key)
                                            <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                        @enderror
                                    </td>
                                    <td class="product-unit"> <input type="text" name="unit[]" value="{{old('unit.'.$key)}}" class="form-control" readonly> </td>
                                    <td> 
                                        <input type="number" name="quantity[]" class="form-control product_quantity" value="{{old('quantity.'.$key)}}">
                                        @error('quantity.'.$key)
                                            <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                        @enderror
                                    </td>
                                    <td> 
                                        <input type="number" name="unit_price[]" class="form-control product_unit_price" value="{{old('unit_price.'.$key)}}"> 
                                        @error('unit_price.'.$key)
                                            <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                        @enderror
                                    </td>
                                    <td> 
                                        <input type="number" name="additional_price[]" class="form-control product_additional_price" value="{{old('additional_price.'.$key)}}"> 
                                        @error('additional_price.'.$key)
                                            <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                        @enderror
                                    </td>
                                    <td><a class="btn btn-danger deleteRow"> <i class="fa fa-trash"></i> </a></td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>
                                        <select name="product[]" class="form-control custom-select select2 product-class">
                                            <option value="" data-unit="">Select Product</option>
                                            @foreach ($product as $item)
                                                <option value="{{$item->id}}" data-unit="{{$item->unit->name}}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="product-unit"> <input type="text" name="unit[]" class="form-control" readonly> </td>
                                    <td> <input type="number" name="quantity[]" class="form-control product_quantity"> </td>
                                    <td> <input type="number" name="unit_price[]" class="form-control product_unit_price"> </td>
                                    <td> <input type="number" name="additional_price[]" class="form-control product_additional_price"> </td>
                                    <td>{{--  <a class="btn btn-danger deleteRow"> <i class="fa fa-trash"></i> </a> --}} </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
</form>
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
        <td> <input type="number" name="quantity[]" class="form-control product_quantity"> </td>
        <td> <input type="number" name="unit_price[]" class="form-control product_unit_price"> </td>
        <td> <input type="number" name="additional_price[]" class="form-control product_additional_price"> </td>
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
            calculateTotalPrice();
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

        $("table").on('input' ,'.product_quantity, .product_unit_price, .product_additional_price', calculateTotalPrice);
        
        function calculateTotalPrice() {
            let _total = 0;
            $('tr').each(function () {
                    var $row = $(this);
                    var quantity = parseFloat($row.find('.product_quantity').val());
                    var unitPrice = parseFloat($row.find('.product_unit_price').val());
                    var additionalPrice = parseFloat($row.find('.product_additional_price').val());
                    var _totalPrice = 0;
                    if (!isNaN(quantity) && !isNaN(unitPrice) && !isNaN(additionalPrice)) {
                        // Calculate total price
                        _totalPrice = (quantity * unitPrice) + additionalPrice;
                        _total += parseFloat(_totalPrice);
                    } else {
                        // If any input value is not a valid number, set total price field to empty
                        _totalPrice = 0;
                    }
            });
            $('#total_amount').val(_total);
        }
    </script>
@endsection