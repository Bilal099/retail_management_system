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
                    <h4 class="card-title">Show Transaction</h4>
                </div>
                <div class="card-body">
                    <div class="row row-sm">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="" class="form-label">Transaction Type</label>
                                <input type="text" class="form-control" value="{{ ucwords(@$data->transactionType->name) }}" readonly>
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
                    @if (!@$data->is_cancel)
                        <a href="{{ route('transaction.cancelTransaction',@$data->id) }}" class="btn btn-danger mt-4 mb-0">Cancel Transaction</a>
                    @endif
                    <a href="{{ URL::previous() }}" class="btn btn-light mt-4 mb-0">Back</a>

                </div>
            </div>
        </div>
    </div>


@endsection
@section('js')


@endsection
