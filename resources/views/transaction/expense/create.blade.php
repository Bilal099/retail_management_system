@extends('layouts.master')
@section('css')

@endsection
@section('page-header')
    @if (Session::has('msg'))
        <div class="alert alert-success mt-2" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{Session::get('msg')}}</div>
    @endif
@endsection
@section('content')

        <div class="row mt-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Create Expense Transaction</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('transaction.expenseStore') }}">
                        @csrf
                        <div class="row row-sm">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="" class="form-label">Transaction Type</label>
                                    <select id="transaction_type" name="transaction_type" class="form-control custom-select select2">
                                        <option value="" disabled selected>Select Option</option>
                                        @foreach ($transaction_type as $key => $item)
                                            <option value="{{$item->id}}" {{old('transaction_type')==$item? 'selected':''}} >{{ucwords($item->name)}}</option>
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
                        <a href="{{ route('transaction.expenseList') }}" class="btn btn-light mt-4 mb-0">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>


@endsection
@section('js')
    {{-- <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/select2.js')}}"></script> --}}

    <script>





    </script>
@endsection
