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
            <!--begin::Brand Text-->
            <a
              href="#"
              class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover"
            >
              <h1 class="mb-0">{{ config('layout.logo') }}</h1>
            </a>
            <!--end::Brand Text-->

            <!--begin::Brand Image-->
            {{-- <img
                src="{!! asset(config('layout.logo_img')) !!}"
                alt="{{ config('layout.logo_img_alt') }}"
                style="width: 100px; height: auto;" /> --}}
            <!--end::Brand Image-->
          </div>

          <div class="card-body login-card-body">
            <p class="login-box-msg">{{__('messages.login_message')}}</p>

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

            <form method="POST" action="{!! route('login') !!}" autocomplete="off">
              @csrf

              <div class="input-group mb-1">
                <div class="form-floating">
                  <input name="email" type="email" class="form-control" value="{{ old('email') }}" placeholder="" />
                  <label>{{__('messages.email')}}</label>
                </div>
                <div class="input-group-text"><span class="bi bi-envelope"></span></div>
              </div>
              @error('email')
                <div class="text-danger">{{ $message }}</div>
              @enderror

              <div class="input-group mb-1">
                <div class="form-floating">
                  <input name="password" type="password" class="form-control" placeholder="" autocomplete="off" />
                  <label>{{__('messages.password')}}</label>
                </div>
                <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
              </div>
              @error('password')
                <div class="text-danger">{{ $message }}</div>
              @enderror

              <!--begin::Row-->
              <div class="row">
                <div class="col-8 d-inline-flex align-items-center">
                  <div class="form-check">
                    <input name="remember" id="remember" type="checkbox" class="form-check-input"  value="1" />
                    <label class="form-check-label" for="remember">{{__('messages.remember_me')}}</label>
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-4">
                  <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">{{__('messages.sign_in')}}</button>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!--end::Row-->
            </form>
            {{-- <div class="social-auth-links text-center mb-3 d-grid gap-2">
              <p>- OR -</p>
              <a href="#" class="btn btn-primary">
                <i class="bi bi-facebook me-2"></i> Sign in using Facebook
              </a>
              <a href="#" class="btn btn-danger">
                <i class="bi bi-google me-2"></i> Sign in using Google+
              </a>
            </div> --}}
            <!-- /.social-auth-links -->
            <p class="mb-1"><a href="{!! route('password.request') !!}">I forgot my password</a></p>
            {{-- <p class="mb-0">
              <a href="register.html" class="text-center"> Register a new membership </a>
            </p> --}}
          </div>
          <!-- end:card content -->
        </div>
      </div>
    <!--end::App Wrapper-->
@stop

{{-- @section('app_js')
@stop --}}
