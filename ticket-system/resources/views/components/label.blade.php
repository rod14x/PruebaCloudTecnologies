@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-brand-secondary mb-2']) }}>
    {{ $value ?? $slot }}
</label>
