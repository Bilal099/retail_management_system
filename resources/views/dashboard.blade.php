@extends('layouts.master')
@section('page-header')
<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title mb-0">Hi! Welcome Back</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/' . $page='#')}}"><i class="fe fe-home mr-2 fs-14"></i>Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="{{url('/' . $page='#')}}">Sales Dashboard</a></li>
        </ol>
    </div>
</div>
<!--End Page header-->
@endsection
@section('content')						

<div class="row">
    
</div>
@endsection
@section('js')

<!--INTERNAL Peitychart js-->
<script src="{{URL::asset('assets/plugins/peitychart/jquery.peity.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/peitychart/peitychart.init.js')}}"></script>

<!--INTERNAL Apexchart js-->
<script src="{{URL::asset('assets/js/apexcharts.js')}}"></script>

<!--INTERNAL ECharts js-->
<script src="{{URL::asset('assets/plugins/echarts/echarts.js')}}"></script>

<!--INTERNAL Chart js -->
<script src="{{URL::asset('assets/plugins/chart/chart.bundle.js')}}"></script>
<script src="{{URL::asset('assets/plugins/chart/utils.js')}}"></script>

<!-- INTERNAL Select2 js -->
<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{URL::asset('assets/js/select2.js')}}"></script>

<!--INTERNAL Moment js-->
<script src="{{URL::asset('assets/plugins/moment/moment.js')}}"></script>

<!--INTERNAL Index js-->
<script src="{{URL::asset('assets/js/index1.js')}}"></script>

@endsection