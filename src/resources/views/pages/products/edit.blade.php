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
                action="{!! route('products.update', $product->id) !!}"
                autocomplete="off"
                enctype="multipart/form-data" >
            @method('POST')
            @csrf
            <div class="mb-3">
                <label>{{__('messages.name')}}</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required />
                @error('name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label>{{__('messages.sku')}}</label>
                <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required />
                @error('sku')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label>{{__('messages.price')}}</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" required />
                @error('price')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label>{{ __('messages.color') }}</label>
                <input type="color" name="color" class="form-control form-control-color" value="{{ old('color', $product->color) }}" title="{{ __('messages.choose_color') }}" />
                @error('color')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label>{{ __('messages.description') }}</label>
                <textarea name="description" class="form-control" rows="5" placeholder="{{ __('messages.description') }}">{{ old('description', $product->description) }}</textarea>
                @error('description')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label>{{__('messages.image')}}</label>
                <input type="file" name="image" class="form-control" />
                @if($product->image)
                <div class="mt-2">
                    <img src="{!! asset($product->image) !!}" alt="Product Image" class="img-thumbnail" />
                </div>
                @endif
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
