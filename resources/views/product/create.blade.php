@extends('layouts.master')
@section('css')

@endsection
@section('page-header')
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
@endsection
@section('content')						

<div class="row mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create Product</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('product.store') }}">
                    @csrf
                    <div class="">
                        <div class="form-group">
                            <label for="exampleInputName" class="form-label">Name</label>
                            <input type="text" name="product_name" class="form-control" id="exampleInputName" placeholder="Enter Name" value="{{ old('product_name') }}" >
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPhone" class="form-label">Unit</label>
                            <select name="unit" class="form-control custom-select select2" @readonly(true)>
                                <option value="1" selected>KG</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputAdditionalCost" class="form-label">Additional Cost</label>
                            <input type="text" name="price" class="form-control" id="exampleInputAdditionalCost" placeholder="Enter Additional Cost" value="{{ old('price') }}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputDescription" class="form-label">Description</label>
                            <input type="text" name="description" class="form-control" id="exampleInputDescription" placeholder="Enter Description" value="{{ old('description') }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4 mb-0">Create</button>
                    <a href="{{ route('product.index') }}" class="btn btn-light mt-4 mb-0">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
		
@endsection