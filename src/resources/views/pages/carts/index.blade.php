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
                <th>{{__('messages.actions')}}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($carts as $cart)
                <tr>
                    <td>{{ $cart->id }}</td>
                    <td>{{ optional($cart->product)->name }}</td>
                    <td>{{ $cart->quantity }}</td>
                    <td>
                        <a href="{!! route('carts.show', $cart) !!}" class="btn btn-info btn-sm">{{__('messages.view')}}</a>
                        <a href="{!! route('carts.edit', $cart) !!}" class="btn btn-warning btn-sm">{{__('messages.edit')}}</a>
                        <form action="{!! route('carts.destroy', $cart) !!}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-danger btn-sm">{{__('messages.delete')}}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <x-pagination :paginator="$carts"/>
    @else
        <div class="alert alert-info" role="alert">
            {{ __('messages.no_data') }}
        </div>
    @endif
    {{-- ------------------------ --}}
</div>
<!--end::Row-->
@endsection

@push('js')
    <script>
        function confirmDelete() {
            return confirm('{{ __('messages.confirm_delete') }}');
        }
    </script>
@endpush
