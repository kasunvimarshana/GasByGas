@extends('layouts.layout')

@section('content')
<!--begin::Row-->
<div class="row">
    {{-- ------------------------ --}}
    @if ($products->isNotEmpty())
    <div class="container-fluid">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach ($products as $product)
            <div class="col">
                <div class="card h-100">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="object-fit: contain; width: 100%; height: 150px; padding: 5px;" />
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                    </div>
                    <ul class="list-group list-group-flush mt-auto">
                        <li class="list-group-item"><strong>{{ __('messages.sku') }}:</strong> {{ $product->sku }}</li>
                        <li class="list-group-item"><strong>{{ __('messages.price') }}:</strong> {{ $product->price }}</li>
                    </ul>
                    <div class="card-footer mt-auto">
                        <div class="d-flex justify-content-between">
                            <form action="{!! route('carts.create') !!}" method="POST" class="d-inline">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="product_id" value="{{ $product->id }}" />
                                <input type="hidden" name="quantity" value="1" />
                                <button type="submit" class="btn btn-warning btn-sm">{{__('messages.add')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <x-pagination :paginator="$products"/>
    @else
        <div class="alert alert-info" role="alert">
            {{ __('messages.no_data') }}
        </div>
    @endif
    {{-- ------------------------ --}}
</div>
<!--end::Row-->
@endsection
