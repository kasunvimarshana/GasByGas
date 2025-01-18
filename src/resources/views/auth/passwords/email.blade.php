@extends('layouts.layout')

@section('content')
<!--begin::Row-->
<div class="row d-flex justify-content-center align-items-cente">
    {{-- ------------------------ --}}
    <div class="card card-outline card-secondary" style="width: 100%">
        <!-- begin::card content -->
        <div class="card-body">

          <h2>{{__('messages.reset_password')}}</h2>

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
                action="{!! route('password.email') !!}"
                autocomplete="off" >
            @method('POST')
            @csrf
            <div class="mb-3">
                <label>{{__('messages.email')}}</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required />
                @error('email')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">{{__('messages.send_password_reset_link')}}</button>
          </form>
        </div>
        <!-- end:card content -->
      </div>
    {{-- ------------------------ --}}
</div>
<!--end::Row-->
@endsection
