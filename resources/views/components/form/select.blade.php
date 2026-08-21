@props([
'name',
'label',
'options' => null,
'value' => null,
'placeholder' => null,
'width' => 'w-64',
])

@php
$selected = old($name, $value);
// dd($selected);
@endphp

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        {{ $label }}
    </label>

    <select id="{{ $name }}" name="{{ $name }}" {{ $attributes->merge([
        'class' => "{$width} rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
        focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" .
        ($errors->has($name) ? ' border-red-500 focus:border-red-500 focus:ring-red-500' : '')
        ]) }}
        >

        @if($placeholder)
        <option value="">{{ $placeholder }}</option>
        @endif

        @if(isset($options))

        @foreach($options as $key => $option)

        @php
        if (is_object($option)) {
        $optionValue = $option->id;
        $optionLabel = $option->name;
        } else {
        $optionValue = $key;
        $optionLabel = $option;
        }
        @endphp

        <option value="{{ $optionValue }}" @selected($selected==$optionValue)>
            {{ $optionLabel }}
        </option>

        @endforeach

        @else

        {{ $slot }}

        @endif

    </select>

    @error($name)
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">
        {{ $message }}
    </p>
    @enderror
</div>