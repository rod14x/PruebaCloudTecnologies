@props(['type' => 'success'])

@php
$styles = match($type) {
    'success' => 'bg-green-50 border-green-500 text-green-800',
    'error' => 'bg-red-50 border-red-500 text-red-800',
    'warning' => 'bg-yellow-50 border-yellow-500 text-yellow-800',
    'info' => 'bg-orange-50 border-brand-primary text-orange-800',
    default => 'bg-gray-50 border-gray-500 text-gray-800'
};

$icon = match($type) {
    'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>',
    'error' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
    'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
    'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
    default => ''
};
@endphp

<div 
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 5000)"
    @click="show = false"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    {{ $attributes->merge(['class' => "flex items-start p-4 rounded-lg border-l-4 shadow-lg cursor-pointer $styles"]) }}
>
    <div class="flex-shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    </div>
    <div class="ml-3 flex-1">
        <div class="text-sm font-medium">
            {{ $slot }}
        </div>
    </div>
    <button 
        @click="show = false"
        class="ml-4 flex-shrink-0 inline-flex text-gray-400 hover:text-gray-600 focus:outline-none"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>
