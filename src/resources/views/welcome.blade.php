@extends('layouts.layout')

@section('content_header')
<!--begin::Row-->
<div class="row">
    <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
    <div class="col-sm-6">
        <x-breadcrumb />
    </div>
</div>
<!--end::Row-->
@endsection

@section('content')
<!--begin::Row-->
<div class="row">
    <img src="{!! asset(config('layout.logo_img')) !!}" class="img-fluid" />
</div>
<!--end::Row-->
@endsection


@section('footer')
<!--begin::To the end-->
<div class="float-end d-none d-sm-inline">Anything you want</div>
<!--end::To the end-->
<!--begin::Copyright-->
<strong>
    Copyright &copy; {{ date('Y') }}&nbsp;
    <a href="#" class="text-decoration-none">{{ config('layout.logo') }}</a>.
</strong>
All rights reserved.
<!--end::Copyright-->
@endsection
