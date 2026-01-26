@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-slate-700 mb-2']) }}>
    {{ $value ?? $slot }}
</label>
