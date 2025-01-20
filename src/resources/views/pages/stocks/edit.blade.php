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
                action="{!! route('stocks.update', $stock->id) !!}"
                autocomplete="off"
                enctype="multipart/form-data" >
            @method('POST')
            @csrf
            <div class="mb-3">
                <label>{{ __('messages.product') }}</label>
                <select name="product_id" class="form-control select2">
                    <option value="">{{ __('messages.select_product') }}</option>
                    @foreach($products as $k => $v)
                    <option value="{{$v->id}}" {{ old('product_id', $stock->product_id) == $v->id ? 'selected' : '' }}>{{$v->name}}</option>
                    @endforeach
                </select>
                @error('product_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label>{{__('messages.reorder_level')}}</label>
                <input type="number" name="reorder_level" class="form-control" value="{{ old('reorder_level', $stock->reorder_level) }}" required />
                @error('reorder_level')
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
