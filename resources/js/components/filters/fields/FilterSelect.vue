<script setup>
    import FilterWrapper from '../shared/FilterWrapper.vue'

    const props = defineProps({
        modelValue: {
            type: [
                String,
                Number,
                Boolean
            ],
            default: '',
        },

        field: {
            type: Object,
            default: () => ({})
        },

        options: {
            type: Array,
            default: () => [],
        },

        disabled: {
            type: Boolean,
            default: false,
        }
    })

    const emit = defineEmits([
        'update:modelValue',
        'change'
    ])

    /*
    |--------------------------------------------------------------------------
    | CHANGE
    |--------------------------------------------------------------------------
    */

    const handleChange = (event) => {

        emit(
            'update:modelValue',
            event.target.value
        )

        emit(
            'change',
            event.target.value
        )

    }
</script>

<template>

    <FilterWrapper :label="field.label" :error="field.error" :required="field.required" :full-width="field.fullWidth">

        <div class="relative">

            <!-- SELECT -->
            <select :value="modelValue" :disabled="disabled" @change="handleChange" class="w-full h-11 rounded-2xl
                border border-black/5
                dark:border-white/10
    
                bg-white
                dark:bg-white/[0.03]
    
                text-sm
                text-gray-900
                dark:text-white
    
                px-4 pr-10
    
                appearance-none
                bg-none
    
                transition-all duration-200
    
                focus:outline-none
                focus:ring-4
                focus:ring-brand/10
                focus:border-brand
    
                disabled:opacity-50
                disabled:cursor-not-allowed">

                <!-- PLACEHOLDER -->
                <option value="">
                    {{
                        field.placeholder ||
                        'Select option'
                    }}
                </option>

                <!-- OPTIONS -->
                <option v-for="option in options" :key="option.value" :value="option.value">
                    {{ option.label }}
                </option>

            </select>

            <!-- ICON -->
            <div class="absolute right-4 top-1/2
                       -translate-y-1/2
                       pointer-events-none">

                <svg class="w-4 h-4
                           text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>

            </div>

        </div>

    </FilterWrapper>

</template>
<style scoped>
select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

/* Remove Edge arrow */
select::-ms-expand {
    display: none;
}

/* Light mode */
select,
option {
    background-color: white;
    color: #111827;
}

/* Dark mode */
.dark select,
.dark option {
    background-color: #111827;
    color: white;
}
</style>