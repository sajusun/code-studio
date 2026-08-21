@props([
'label' => 'Meta Data',
'name' => 'meta',
'data' => null,
])

@php
$value = old($name, data_get($data, $name, []));

if (is_string($value)) {
$value = json_decode($value, true) ?? [];
}

$value = is_array($value) ? $value : [];
@endphp

<div class="json-kv-component mb-4" data-name="{{ $name }}">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
        {{ $label }}
        <span class="ml-1 text-xs text-gray-400 font-normal">
            (JSON Key-Value Pairs)
        </span>
    </label>

    <div class="json-kv-wrapper space-y-2.5">

        @forelse($value as $key => $val)

        <div class="json-kv-row flex items-center gap-2">

            <input type="text" name="{{ $name }}_keys[]" value="{{ $key }}" placeholder="Key"
                class="json-key flex-1 px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">

            <input type="text" name="{{ $name }}_values[]" value="{{ $val }}" placeholder="Value"
                class="json-value flex-1 px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">

            <button type="button"
                class="remove-json-row flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/20 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/40">

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>

            </button>

        </div>

        @empty

        <div class="json-kv-row flex items-center gap-2">

            <input type="text" name="{{ $name }}_keys[]" placeholder="Key"
                class="json-key flex-1 px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">

            <input type="text" name="{{ $name }}_values[]" placeholder="Value"
                class="json-value flex-1 px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">

            <button type="button"
                class="remove-json-row flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/20 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/40">

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>

            </button>

        </div>

        @endforelse

    </div>

    <div class="mt-3 flex items-center gap-3">

        <button type="button"
            class="add-json-row inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/50">

            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>

            Add Row

        </button>

        <span class="text-xs text-gray-400">
            Saved into
            <code class="text-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 px-1 rounded">
                {{ $name }}
            </code>
        </span>

    </div>
</div>

@once
@push('scripts')

<script>
    document.querySelectorAll('.json-kv-component').forEach(component=>{

    const wrapper=component.querySelector('.json-kv-wrapper');
    const addBtn=component.querySelector('.add-json-row');
    const fieldName=component.dataset.name;

    const inputClass='flex-1 px-3 py-2 text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500';

    function bindRemove(btn){

        btn.addEventListener('click',function(){

            const row=this.closest('.json-kv-row');

            if(wrapper.querySelectorAll('.json-kv-row').length>1){

                row.remove();

            }else{

                row.querySelectorAll('input').forEach(i=>i.value='');

            }

        });

    }

    wrapper.querySelectorAll('.remove-json-row').forEach(bindRemove);

    addBtn.addEventListener('click',()=>{

        const row=document.createElement('div');

        row.className='json-kv-row flex items-center gap-2';

        row.innerHTML=`
            <input
                type="text"
                name="${fieldName}_keys[]"
                placeholder="Key"
                class="json-key ${inputClass}">

            <input
                type="text"
                name="${fieldName}_values[]"
                placeholder="Value"
                class="json-value ${inputClass}">

            <button
                type="button"
                class="remove-json-row flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100">

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>

            </button>
        `;

        bindRemove(row.querySelector('.remove-json-row'));

        wrapper.appendChild(row);

    });

    component.closest('form').addEventListener('submit',function(){

        const obj={};

        wrapper.querySelectorAll('.json-kv-row').forEach(row=>{

            const key=row.querySelector('.json-key').value.trim();

            const value=row.querySelector('.json-value').value.trim();

            if(key){

                obj[key]=value;

            }

        });

        let hidden=this.querySelector(`input[name="${fieldName}"]`);

        if(!hidden){

            hidden=document.createElement('input');

            hidden.type='hidden';

            hidden.name=fieldName;

            this.appendChild(hidden);

        }

        hidden.value=JSON.stringify(obj);

        wrapper.querySelectorAll('input').forEach(i=>i.disabled=true);

    });

});

</script>

@endpush
@endonce