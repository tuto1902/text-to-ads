@props(['value', 'required' => false ])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700 dark:text-gray-300']) }}>
    {{ $value ?? $slot }} <span class="text-sm text-red-600 dark:text-red-400 space-y-1">{{ $required ? '*' : '' }}</span>
</label>
