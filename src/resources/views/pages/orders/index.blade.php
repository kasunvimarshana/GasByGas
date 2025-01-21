@use('\App\Enums\OrderStatus')

@extends('layouts.layout')

@section('content')
<!--begin::Row-->
<div class="row">
    {{-- ------------------------ --}}
    @if ($orders->isNotEmpty())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{__('messages.id')}}</th>
                <th>{{__('messages.token')}}</th>
                <th>{{__('messages.amount')}}</th>
                <th>{{__('messages.expected_pickup_date')}}</th>
                <th>{{__('messages.status')}}</th>
                <th>{{__('messages.actions')}}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->token }}</td>
                    <td>{{ number_format($order->amount ?? 0, 2) }}</td>
                    <td>{{ $order->expected_pickup_date?->format('Y-m-d') }}</td>
                    <td>
                        <span class="badge bg-{{ $order->status?->color() }}">
                            {{ $order->status?->label() }}
                        </span>
                    </td>
                    <td>
                        <a href="{!! route('orders.show', $order) !!}" class="btn btn-info btn-sm">{{__('messages.view')}}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <x-pagination :paginator="$orders"/>
    @else
        <div class="alert alert-info" role="alert">
            {{ __('messages.no_data') }}
        </div>
    @endif
    {{-- ------------------------ --}}
</div>
<!--end::Row-->
@endsection
