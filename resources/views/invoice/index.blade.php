@extends('layouts.master')
@section('css')
	<!-- Data table css -->
	<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
	<link href="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
	<!-- Slect2 css -->
	<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
@endsection
@section('page-header')
						<!--Page header-->
						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">Merchant Invoice</h4>
								{{-- <ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Tables</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">Data Tables</a></li>
								</ol> --}}
							</div>
							<div class="page-rightheader">
								<div class="btn btn-list">
									<a href="{{ route('invoices.create') }}" class="btn btn-primary">Create</a>
								</div>
							</div>
						</div>
                        <!--End Page header-->
@endsection
@section('content')	
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Listing</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap" id="example1">
                        <thead>
                            <tr>
                                <th class="wd-15p border-bottom-0">S.No</th>
                                <th class="wd-15p border-bottom-0">Merchant Name</th>
                                {{-- <th class="wd-15p border-bottom-0">Invoice Type</th> --}}
                                <th class="wd-15p border-bottom-0">Invoice Date</th>
                                <th class="wd-15p border-bottom-0">Total Amount</th>
                                <th class="wd-20p border-bottom-0">Comment</th>
                                <th class="wd-15p border-bottom-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                                <tr class="{{@$item->is_check? 'alert-success':(($item->is_check == 0 && $item->remaining_amount > 0)? 'alert-info ':'' )}}">
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $item->merchant->merchant_name }}</td>
                                    {{-- <td>{{ $item->reference_model }}</td> --}}
                                    <td>{{ $item->invoice_date }}</td>
                                    <td>{{ $item->total_amount }}</td>
                                    <td>{{ $item->comment }}</td>
                                    <td>
                                            <a href="{{route('invoices.show',$item->id)}}" class="btn btn-sm btn-icon  btn-warning"  data-toggle="tooltip" data-placement="top" title="View Detail"><i class="fe fe-eye"></i></a>
                                        @if (!is_null($item->transaction_id))
                                            <a href="{{route('transactions.show',$item->transaction_id)}}" class="btn btn-sm btn-icon  btn-info" data-toggle="tooltip" data-placement="top" title="View Transaction"><i class="fe fe-list"></i></a>
                                        @endif
                                        @if ($item->is_check == 0 && $item->remaining_amount==0)
                                            <button type="button" class="btn btn-sm btn-icon  btn-danger"><i class="fe fe-trash"></i></button>
                                            {{-- <a href="{{ route('invoices.makerChecker',$item->id) }}" class="btn btn-primary mt-4 mb-0">Confirm</a> --}}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
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
	<script src="{{URL::asset('assets/js/datatables.js')}}"></script>

	<!-- INTERNAL Select2 js -->
	<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
@endsection