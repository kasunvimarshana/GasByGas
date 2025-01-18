@props(['type' => 'info', 'message' => '', 'dismissible' => false, 'icon' => null])

@php
    $types = [
        'success' => 'bg-green-100 text-green-800 border-green-300',
        'error' => 'bg-red-100 text-red-800 border-red-300',
        'warning' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
        'info' => 'bg-blue-100 text-blue-800 border-blue-300',
    ];

    $defaultIcons = [
        'success' => '✅',
        'error' => '❌',
        'warning' => '⚠️',
        'info' => 'ℹ️',
    ];

    $alertClass = $types[$type] ?? $types['info'];
    $alertIcon = $icon ?? ($defaultIcons[$type] ?? '');
@endphp

<div class="alert {{ $alertClass }} alert-{{ $type }} {{ $dismissible ? 'alert-dismissible fade show' : '' }}" role="alert">
    @if ($icon)
        <i class="bi bi-{{ $icon }}">{{ $alertIcon }}</i>
    @endif
    {{ $message }}
    @if ($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
