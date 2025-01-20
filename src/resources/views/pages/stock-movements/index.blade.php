@use('\App\Enums\StockMovementType')

@extends('layouts.layout')

@section('content')
<!--begin::Row-->
<div class="row">
    {{-- ------------------------ --}}
    @php
    $activeTab = old('type', $stockMovement?->type?->value) ?? request('type', StockMovementType::IN->value);
    $formAction = ($stockMovement) ? route('stock-movements.update', $stockMovement?->id ?? 0) : route('stock-movements.store', $stock?->id ?? 0);
    @endphp

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        @foreach (StockMovementType::cases() as $type)
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link @if($type->value === $activeTab) active @endif"
                    id="pills-{{ strtolower($type->value) }}-tab"
                    data-bs-toggle="pill"
                    data-bs-target="#pills-{{ strtolower($type->value) }}"
                    type="button"
                    role="tab"
                    aria-controls="pills-{{ strtolower($type->value) }}"
                    aria-selected="{{ ($type->value === $activeTab) ? 'true' : 'false' }}">
                    {{ $type->label() }}
                </button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content" id="pills-tabContent">
        @foreach (StockMovementType::cases() as $type)
            <div
                class="tab-pane fade @if($type->value === $activeTab) show active @endif"
                id="pills-{{ strtolower($type->value) }}"
                role="tabpanel"
                aria-labelledby="pills-{{ strtolower($type->value) }}-tab">

                {{-- start::Form --}}
                <form method="POST"
                        action="{!! $formAction !!}"
                        autocomplete="off"
                        enctype="multipart/form-data" >
                    @method('POST')
                    @csrf
                    <input type="hidden" name="type" value="{{ $type->value }}" />
                    <div class="mb-3">
                        <label>{{__('messages.quantity')}}</label>
                        <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $stockMovement?->quantity) }}" required />
                        @error('quantity')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>{{__('messages.reference')}}</label>
                        <input type="text" name="reference" class="form-control" value="{{ old('reference', $stockMovement?->reference) }}" required />
                        @error('reference')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('messages.action_submit')}}</button>
                </form>
                {{-- end::Form --}}
            </div>
        @endforeach
    </div>
    {{-- ------------------------ --}}
</div>
<!--end::Row-->

<!--begin::Row-->
<div class="row mt-3">
    {{-- ------------------------ --}}
    @if ($stockMovements->isNotEmpty())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{__('messages.id')}}</th>
                <th>{{__('messages.type')}}</th>
                <th>{{__('messages.quantity')}}</th>
                <th>{{__('messages.reference')}}</th>
                <th>{{__('messages.actions')}}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($stockMovements as $k => $v)
                <tr>
                    <td>{{ $v->id }}</td>
                    <td>{{ $v->type->label() }}</td>
                    <td>{{ $v->quantity }}</td>
                    <td>{{ $v->reference }}</td>
                    <td>
                        <a href="{!! route('stock-movements.show', $v) !!}" class="btn btn-info btn-sm">{{__('messages.view')}}</a>
                        <a href="{!! route('stock-movements.index', ['stock' => $stock->id, 'stockMovement' => $v->id]) !!}" class="btn btn-warning btn-sm">{{__('messages.edit')}}</a>
                        <form action="{!! route('stock-movements.destroy', $v) !!}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-danger btn-sm">{{__('messages.delete')}}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <x-pagination :paginator="$stockMovements"/>
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
