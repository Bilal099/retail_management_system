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
                <div class="col-xl-6 col-lg-6 col-sm-6 pr-0 pl-0 border-right">
                    <div class="card-body text-center">
                        <p class="mb-1">Total Remaining Amount</p>
                        <h2 class="mb-1 font-weight-bold">{{ number_format($totalRemaining) }}</h2>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-sm-6 pr-0 pl-0 border-right">
                    <div class="card-body text-center">
                        <p class="mb-1">Total Transaction Amount</p>
                        <h2 class="mb-1 font-weight-bold">{{ number_format($totalAmount) }}</h2>
                    </div>
                </div>
{{--                <div class="col-xl-4 col-lg-6 col-sm-6 pr-0 pl-0 border-right">--}}
{{--                    <div class="card-body text-center">--}}
{{--                        <p class="mb-1">Total Paid</p>--}}
{{--                        <h2 class="mb-1 font-weight-bold">0</h2>--}}
{{--                    </div>--}}
{{--                </div>--}}
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
                        <li class="">Transactions</li>
                        <li>Invoice</li>
                    </ul>
                    <div class="content_wrapper">
                        <div class="tab_content active">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap data-table" id="">
                                    <thead>
                                        <tr>
                                            <th class="wd-20p border-bottom-0">S.No</th>
                                            <th class="wd-20p border-bottom-0">Payment Type</th>
                                            <th class="wd-15p border-bottom-0">Date</th>
                                            <th class="wd-15p border-bottom-0">Total Amount</th>
                                            <th class="wd-15p border-bottom-0">Merchant</th>
                                            <th class="wd-15p border-bottom-0">Comment</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($merchantTransactions))
                                            @php
                                                $index = 0;
                                            @endphp
                                            @foreach ($merchantTransactions->sortByDesc('id') as $item)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
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
                                <table class="table table-bordered text-nowrap data-table" id="">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">S.No</th>
                                            <th class="wd-15p border-bottom-0">Total Amount</th>
                                            <th class="wd-15p border-bottom-0">Date</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($merchantInvoice))

                                            @php
                                                $index = 0;
                                            @endphp
                                            @foreach ($merchantInvoice->sortByDesc('id') as $key => $item)
                                                <tr class="{{@$item->is_check? 'alert-success':(($item->is_check == 0 && $item->remaining_amount > 0)? 'alert-info ':'' )}}">
                                                    <td>{{++$index}}</td>
                                                    <td>{{ @$item->total_amount }}</td>
                                                    <td>{{ @$item->invoice_date }}</td>
                                                    <td>
                                                        @if($item->transaction_id != null)
                                                        <a href="{{route('transactions.show',$item->transaction_id)}}" class="btn btn-sm btn-icon  btn-purple"><i class="fe fe-eye"></i></a>
                                                        @endif

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

	<!-- INTERNAL Data tables -->
	<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js')}}"></script>
	{{-- <script src="{{URL::asset('assets/js/datatables.js')}}"></script> --}}

	<!-- INTERNAL Select2 js -->
	<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>

    <script>
        $('.data-table').DataTable({
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_',
            }
        });
    </script>
@endsection