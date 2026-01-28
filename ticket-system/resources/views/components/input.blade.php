@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block w-full px-4 py-3 border border-brand-neutral rounded-lg text-brand-secondary placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent transition duration-150 ease-in-out disabled:bg-gray-100 disabled:cursor-not-allowed']) !!}>
