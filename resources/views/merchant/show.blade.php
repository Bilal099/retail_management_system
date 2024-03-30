@extends('layouts.master')
@section('css')

<!-- INTERNAL  Tabs css-->
<link href="{{URL::asset('assets/plugins/tabs/style.css')}}" rel="stylesheet" />
@endsection
@section('content')		

<!--Row-->
<div class="row  mt-2">
    <div class="col-md-12">
        <div class="card">
            <div class="row mr-0 ml-0">
                <div class="col-xl-4 col-lg-6 col-sm-6 pr-0 pl-0 border-right">
                    <div class="card-body text-center">
                        <p class="mb-1">Total Transaction in Cash</p>
                        <h2 class="mb-1 font-weight-bold">{{ number_format($totalCash) }}</h2>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-sm-6 pr-0 pl-0 border-right">
                    <div class="card-body text-center">
                        <p class="mb-1">Total Transaction in Credit</p>
                        <h2 class="mb-1 font-weight-bold">{{ number_format($totalCredit) }}</h2>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-sm-6 pr-0 pl-0 border-right">
                    <div class="card-body text-center">
                        <p class="mb-1">Total Paid</p>
                        <h2 class="mb-1 font-weight-bold">0</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End row-->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Merchant Details</h4>
            </div>
            <div class="card-body">
                <div class="tab_wrapper first_tab">
                    <ul class="tab_list">
                        <li class="">Cash</li>
                        <li>Credits</li>
                    </ul>
                    <div class="content_wrapper">
                        <div class="tab_content active">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap" id="example1">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">Transaction Type</th>
                                            <th class="wd-20p border-bottom-0">Payment Type</th>
                                            <th class="wd-15p border-bottom-0">Date</th>
                                            <th class="wd-15p border-bottom-0">Total Amount</th>
                                            <th class="wd-15p border-bottom-0">Merchant</th>
                                            <th class="wd-15p border-bottom-0">Comment</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($merchantTransactions['cash']))
                                            @foreach ($merchantTransactions['cash'] as $item)
                                                <tr>
                                                    <td>{{ @$item->transaction_type }}</td>
                                                    <td>{{ @$item->payment_type }}</td>
                                                    <td>{{ @$item->transaction_date }}</td>
                                                    <td>{{ @$item->total_amount }}</td>
                                                    <td>{{ @$item->merchant->merchant_name }}</td>
                                                    <td>{{ @$item->comment }}</td>
                                                    <td>
                                                        <a href="{{route('transactions.show',$item->id)}}" class="btn btn-sm btn-icon  btn-purple"><i class="fe fe-eye"></i></a>
                                                    
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab_content">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap" id="example1">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">Transaction Type</th>
                                            <th class="wd-20p border-bottom-0">Payment Type</th>
                                            <th class="wd-15p border-bottom-0">Date</th>
                                            <th class="wd-15p border-bottom-0">Total Amount</th>
                                            <th class="wd-15p border-bottom-0">Merchant</th>
                                            <th class="wd-15p border-bottom-0">Comment</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($merchantTransactions['credit']))    
                                            @foreach ($merchantTransactions['credit'] as $item)
                                                <tr>
                                                    <td>{{ @$item->transaction_type }}</td>
                                                    <td>{{ @$item->payment_type }}</td>
                                                    <td>{{ @$item->transaction_date }}</td>
                                                    <td>{{ @$item->total_amount }}</td>
                                                    <td>{{ @$item->merchant->merchant_name }}</td>
                                                    <td>{{ @$item->comment }}</td>
                                                    <td>
                                                        <a href="{{route('transactions.show',$item->id)}}" class="btn btn-sm btn-icon  btn-purple"><i class="fe fe-eye"></i></a>
                                                    
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
		<!--- INTERNAL Tabs js-->
		<script src="{{URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js')}}"></script>
		<script src="{{URL::asset('assets/js/tabs.js')}}"></script>
@endsection