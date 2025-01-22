@extends('layouts.master')

{{-- @section('app_css')
@stop --}}

@section('classes_body', '')

@section('body_data', '')

@section('body')
    <!--begin::App Wrapper-->
    <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center">
        <div class="card card-outline card-primary" style="width: 100%; max-width: 400px;">
          <!-- begin::card content -->
          <div class="card-header text-center">
            <h2>{{__('messages.reset_password')}}</h2>
          </div>

          <div class="card-body">
            {{-- @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
            @endif --}}

            <form method="POST"
                    action="{!! route('password.update') !!}"
                    autocomplete="off" >
                @method('POST')
                @csrf
                <input type="hidden" name="token" value="{{ $token }}" />
                <div class="mb-3">
                    <label>{{__('messages.email')}}</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required />
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label>{{__('messages.password')}}</label>
                    <input type="password" name="password" class="form-control" required />
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label>{{__('messages.confirm_password')}}</label>
                    <input type="password" name="password_confirmation" class="form-control" required />
                    @error('password_confirmation')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">{{__('messages.reset_password')}}</button>
            </form>
          </div>
          <!-- end:card content -->
        </div>
      </div>
    <!--end::App Wrapper-->
@stop

{{-- @section('app_js')
@stop --}}
