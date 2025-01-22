@extends('layouts.layout')

@section('content')
<!--begin::Row-->
<div class="row">
    {{-- ------------------------ --}}
    @if ($users->isNotEmpty())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{__('messages.id')}}</th>
                <th>{{__('messages.name')}}</th>
                <th>{{__('messages.email')}}</th>
                <th>{{__('messages.username')}}</th>
                <th>{{__('messages.actions')}}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->username }}</td>
                    <td>
                        <a href="{!! route('users.show', $user) !!}" class="btn btn-info btn-sm">{{__('messages.view')}}</a>
                        <a href="{!! route('users.edit', $user) !!}" class="btn btn-warning btn-sm">{{__('messages.edit')}}</a>
                        <form action="{!! route('users.destroy', $user) !!}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-danger btn-sm">{{__('messages.delete')}}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <x-pagination :paginator="$users"/>
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
