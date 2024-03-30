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
								{{-- <h4 class="page-title mb-0">Merchant</h4> --}}
								{{-- <ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Tables</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">Data Tables</a></li>
								</ol> --}}
							</div>
							<div class="page-rightheader">
								<div class="btn btn-list">
									<a href="{{ route('merchant.create') }}" class="btn btn-primary">Create</a>
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
                                <th class="wd-15p border-bottom-0">Name</th>
                                <th class="wd-15p border-bottom-0">Phone</th>
                                <th class="wd-20p border-bottom-0">Address</th>
                                <th class="wd-15p border-bottom-0">Details</th>
                                <th class="wd-15p border-bottom-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item['merchant_name'] }}</td>
                                    <td>{{ $item['phone'] }}</td>
                                    <td>{{ $item['address'] }}</td>
                                    <td>{{ $item['details'] }}</td>
                                    <td>
                                        <a href="{{route('merchant.show',$item['id'])}}" class="btn btn-sm btn-icon  btn-warning"><i class="fe fe-eye"></i></a>
                                        <a href="{{route('merchant.edit',$item['id'])}}" class="btn btn-sm btn-icon  btn-purple"><i class="fe fe-edit"></i></a>
                                        <button type="button" class="btn btn-sm btn-icon  btn-danger"><i class="fe fe-trash"></i></button>
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