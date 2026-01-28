@props(['type' => 'submit', 'variant' => 'primary'])

@php
$classes = match($variant) {
    'primary' => 'w-full py-3 px-4 bg-gradient-to-r from-brand-primary to-brand-primary-dark text-white font-semibold rounded-lg hover:from-brand-primary-light hover:to-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed',
    'secondary' => 'w-full py-3 px-4 bg-brand-neutral text-brand-secondary font-semibold rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-secondary focus:ring-offset-2 transition duration-150 ease-in-out',
    'link' => 'text-sm font-medium text-brand-primary hover:text-brand-primary-dark transition duration-150 ease-in-out',
    default => 'w-full py-3 px-4 bg-brand-primary text-white font-semibold rounded-lg hover:bg-brand-primary-dark transition duration-150 ease-in-out'
};
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
