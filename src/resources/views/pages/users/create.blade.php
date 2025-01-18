@extends('layouts.layout')

@section('content')
<!--begin::Row-->
<div class="row d-flex justify-content-center align-items-cente">
    {{-- ------------------------ --}}
    <div class="card card-outline card-secondary" style="width: 100%">
        <!-- begin::card content -->
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
                action="{!! route('users.store') !!}"
                autocomplete="off"
                enctype="multipart/form-data" >
            @method('POST')
            @csrf
            <div class="mb-3">
                <label>{{__('messages.name')}}</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required />
                @error('name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label>{{__('messages.phone')}}</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required />
                @error('phone')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label>{{ __('messages.address') }}</label>
                <textarea name="address" class="form-control" rows="5" placeholder="{{ __('messages.address') }}">{{ old('address') }}</textarea>
                @error('address')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label>{{__('messages.email')}}</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required />
                @error('email')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label>{{__('messages.password')}}</label>
                <input type="password" name="password" class="form-control" value="{{ old('email') }}" required />
                @error('password')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label>{{__('messages.image')}}</label>
                <input type="file" name="image" class="form-control" />
                @error('image')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">{{__('messages.action_submit')}}</button>
          </form>
        </div>
        <!-- end:card content -->
      </div>
    {{-- ------------------------ --}}
</div>
<!--end::Row-->
@endsection
