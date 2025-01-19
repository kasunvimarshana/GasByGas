@extends('layouts.layout')

@section('content')
<!--begin::Row-->
<div class="row">
    {{-- ------------------------ --}}
    @if ($products->isNotEmpty())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{__('messages.id')}}</th>
                <th>{{__('messages.name')}}</th>
                <th>{{__('messages.sku')}}</th>
                <th>{{__('messages.price')}}</th>
                <th>{{__('messages.actions')}}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->price }}</td>
                    <td>
                        <a href="{!! route('products.show', $product) !!}" class="btn btn-info btn-sm">{{__('messages.view')}}</a>
                        <a href="{!! route('products.edit', $product) !!}" class="btn btn-warning btn-sm">{{__('messages.edit')}}</a>
                        <form action="{!! route('products.destroy', $product) !!}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-danger btn-sm">{{__('messages.delete')}}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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

@push('js')
    <script>
        function confirmDelete() {
            return confirm('{{ __('messages.confirm_delete') }}');
        }
    </script>
@endpush
