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
                action="{!! route('carts.update', $cart->id) !!}"
                autocomplete="off"
                enctype="multipart/form-data" >
            @method('POST')
            @csrf
            <div class="mb-3">
                <label>{{__('messages.quantity')}}</label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $cart->quantity) }}" required />
                @error('quantity')
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
