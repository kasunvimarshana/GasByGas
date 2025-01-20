@extends('layouts.layout')

@section('content')
<!--begin::Row-->
<div class="row">
    {{-- ------------------------ --}}
    @if ($stocks->isNotEmpty())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{__('messages.id')}}</th>
                <th>{{__('messages.product')}}</th>
                <th>{{__('messages.quantity')}}</th>
                <th>{{__('messages.reorder_level')}}</th>
                <th>{{__('messages.actions')}}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($stocks as $stock)
                <tr>
                    <td>{{ $stock->id }}</td>
                    <td>{{ optional($stock->product)->name }}</td>
                    <td>{{ $stock->quantity }}</td>
                    <td>{{ $stock->reorder_level }}</td>
                    <td>
                        <a href="{!! route('stocks.show', $stock) !!}" class="btn btn-info btn-sm">{{__('messages.view')}}</a>
                        <a href="{!! route('stocks.edit', $stock) !!}" class="btn btn-warning btn-sm">{{__('messages.edit')}}</a>
                        <form action="{!! route('stocks.destroy', $stock) !!}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-danger btn-sm">{{__('messages.delete')}}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <x-pagination :paginator="$stocks"/>
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
