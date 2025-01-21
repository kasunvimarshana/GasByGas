@use('\App\Enums\PaymentMethod')

@extends('layouts.layout')

@section('content')
<!--begin::Row-->
<div class="row">
    {{-- ------------------------ --}}
    @if ($carts->isNotEmpty())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{__('messages.id')}}</th>
                <th>{{__('messages.product')}}</th>
                <th>{{__('messages.quantity')}}</th>
                <th>{{__('messages.amount')}}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($carts as $cart)
                <tr>
                    <td>{{ $cart->id }}</td>
                    <td>{{ optional($cart->product)->name }}</td>
                    <td>{{ $cart->quantity }}</td>
                    <td>{{ number_format((optional($cart->product)->price ?? 0) * $cart->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total Amount -->
    <div class="text-right">
        <h5>{{ __('messages.amount') }}:
            {{ number_format($carts->sum(fn($cart) => (optional($cart->product)->price ?? 0) * $cart->quantity), 2) }}
        </h5>
    </div>

    {{-- <x-pagination :paginator="$carts"/> --}}
    @else
        <div class="alert alert-info" role="alert">
            {{ __('messages.no_data') }}
        </div>
    @endif
    {{-- ------------------------ --}}
</div>
<!--end::Row-->

<!--begin::Row-->
<div class="row my-3">
    {{-- ======= --}}
    <form method="POST"
            action="#"
            autocomplete="off"
            enctype="multipart/form-data" >
        @method('POST')
        @csrf
        <!-- Shipping Information -->
        {{-- <div class="mb-3">
            <label>{{__('messages.shipping_address')}}</label>
            <input type="text" name="shipping_address" class="form-control" value="{{ old('shipping_address') }}" required />
            @error('shipping_address')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div> --}}

        <!-- Payment Method -->
        <div class="mb-3">
            <label>{{ __('messages.payment_method') }}</label>
            <select name="payment_method" class="form-control select2">
                <option value="">{{ __('messages.select_payment_method') }}</option>
                @foreach(PaymentMethod::cases() as $method)
                <option value="{{$method->value}}" {{ old('payment_method') == $method->value ? 'selected' : '' }}>{{$method->label()}}</option>
                @endforeach
            </select>
            @error('payment_method')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">{{__('messages.action_submit')}}</button>
    </form>
    {{-- ======= --}}
</div>
<!--end::Row-->

@endsection
