@props(['href' => null])

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 text-sm font-medium text-brand-secondary bg-white border border-brand-neutral rounded-lg hover:bg-gray-50 hover:text-brand-primary transition']) }}>
        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Volver
    </a>
@else
    <button type="button" onclick="history.back()" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 text-sm font-medium text-brand-secondary bg-white border border-brand-neutral rounded-lg hover:bg-gray-50 hover:text-brand-primary transition']) }}>
        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Volver
    </button>
@endif
