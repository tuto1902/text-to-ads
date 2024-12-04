@props(['disabled' => false])
@php
$name = $attributes->whereStartsWith('wire:model')->first();
@endphp
<select
    @disabled($disabled)
    {{ $attributes->class([
        'dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm',
        'border-red-600 dark:border-red-400 focus:border-red-600 dark:focus:border-red-400 focus:ring-red-600 dark:focus:ring-red-400' => $errors->has($name),
        'border-gray-300 dark:border-gray-700 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600' => $errors->missing($name)
    ]) }}
>
{{$slot}}
</select>
