@props(['type' => 'submit', 'variant' => 'primary'])

@php
$classes = match($variant) {
    'primary' => 'w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed',
    'secondary' => 'w-full py-3 px-4 bg-slate-200 text-slate-700 font-semibold rounded-lg hover:bg-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition duration-150 ease-in-out',
    'link' => 'text-sm font-medium text-blue-600 hover:text-blue-700 transition duration-150 ease-in-out',
    default => 'w-full py-3 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out'
};
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
