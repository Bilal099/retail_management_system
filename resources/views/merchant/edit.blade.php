@extends('layouts.master')
@section('css')

@endsection
@section('content')						

<div class="row mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Update Merchant</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('merchant.update',$data->id) }}">
                    @method('PUT')
                    @csrf
                    <div class="">
                        <div class="form-group">
                            <label for="exampleInputName" class="form-label">Name</label>
                            <input type="text" name="merchant_name" class="form-control" id="exampleInputName" placeholder="Enter Name" value="{{$data->merchant_name}}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPhone" class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" id="exampleInputPhone" placeholder="Enter Phone" value="{{$data->phone}}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputAddress" class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" id="exampleInputAddress" placeholder="Enter Address" value="{{$data->address}}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputDetails" class="form-label">Details</label>
                            <input type="text" name="details" class="form-control" id="exampleInputDetails" placeholder="Enter Details" value="{{$data->details}}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4 mb-0">Update</button>
                    <a href="{{ route('merchant.index') }}" class="btn btn-light mt-4 mb-0">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
		
@endsection