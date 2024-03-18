@extends('layouts.master')
@section('css')

@endsection
@section('content')						

<div class="row mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Map Inventory</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('inventories.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Product From</label>
                                <select id="product_from" data-type="from" name="product_from" class="form-control custom-select select2 select-product">
                                    <option value="">Select product</option>
                                    @foreach ($product as $item)
                                            <option value="{{$item->id}}" {{ old('product_from')==$item->id? 'selected':'' }}>{{$item->name}}</option>                                        
                                    @endforeach
                                </select>
                                @error('product_from')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Product To</label>
                                <select id="product_to" data-type="to" name="product_to" class="form-control custom-select select2 select-product">
                                    <option value="">Select product</option>
                                    @foreach ($product as $item)
                                            <option value="{{$item->id}}" {{ old('product_to')==$item->id? 'selected':'' }}>{{$item->name}}</option>                                        
                                    @endforeach
                                </select>
                                @error('product_to')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Quantity From</label>
                                <input type="number" name="quantity_from" class="form-control" id="quantity_from" placeholder="Enter Quantity" value="{{ old('quantity_from') }}" >
                                @error('quantity_from')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Quantity To</label>
                                <input type="number" name="quantity_to" class="form-control" id="quantity_to" placeholder="Enter Quantity" value="{{ old('quantity_to') }}" >
                                @error('quantity_to')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Price From</label>
                                <input type="number" name="price_from" class="form-control" id="price_from" placeholder="Enter Price" value="{{ old('price_from') }}" >
                                @error('price_from')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Price To</label>
                                <input type="number" name="price_to" class="form-control" id="price_to" placeholder="Enter Price" value="{{ old('price_to') }}" >
                                @error('price_to')
                                    <span class="text-sm text-danger" style="padding: 5px;font-size:12px">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4 mb-0">Submit</button>
                    <a href="{{ route('inventories.index') }}" class="btn btn-light mt-4 mb-0">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')

<script>
    $('.select-product').change(function (e) { 
        e.preventDefault();
        let _id = $(this).val();
        let _type = $(this).data('type');
        let _quantity = "#quantity_"+_type;
        let _price = "#price_"+_type;
        // console.log({_type});
        $.ajax({
            type: "get",
            url: "{{route('ajax.getProductDetails')}}",
            data: {
                id: _id
            },
            success: function (response) {
                // console.log(response);
                let data = response.data;
                // console.log({data});
                // console.log(data[0]['inventory']['quantity']);
                // variable ?? defaultValue
                // console.log(data[0]?.inventory.quantity);
                // $(_quantity).val(data[0]['inventory']['quantity'] ?? 0);
                // $(_price).val(data[0]['price'] ?? 0);
                // quantity_from
                // price_from
                if (data[0]?.inventory?.quantity !== undefined && data[0]?.inventory?.quantity !== null) {
                    $(_quantity).val(data[0]['inventory']['quantity']);
                    // Quantity exists, handle it here
                } else {
                    // Quantity does not exist, handle it here
                    $(_quantity).val(0);
                }
                $(_price).val(data[0]['price'] ?? 0);

            }
        });
    });
</script>
		
@endsection