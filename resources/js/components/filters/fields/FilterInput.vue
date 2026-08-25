<script setup>
    import FilterWrapper from '../shared/FilterWrapper.vue'

    const props = defineProps({
        modelValue: {
            type: [
                String,
                Number
            ],
            default: '',
        },

        field: {
            type: Object,
            default: () => ({})
        },

        type: {
            type: String,
            default: 'text',
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
    | INPUT
    |--------------------------------------------------------------------------
    */

    const handleInput = (event) => {

        emit(
            'update:modelValue',
            event.target.value
        )

    }

    const handleChange = (event) => {

        emit(
            'change',
            event.target.value
        )

    }
</script>

<template>

    <FilterWrapper :label="field.label" :error="field.error" :required="field.required" :full-width="field.fullWidth">

        <div class="relative">

            <!-- ICON -->
            <div v-if="field.icon" class="absolute left-4 top-1/2
                       -translate-y-1/2
                       pointer-events-none">

                <component :is="field.icon" class="w-4 h-4
                           text-gray-400" />

            </div>

            <!-- INPUT -->
            <input :type="type" :value="modelValue" :placeholder="field.placeholder ||
                'Enter value'
                " :disabled="disabled" @input="handleInput" @change="handleChange" class="w-full h-11 rounded-2xl

                       border border-black/5
                       dark:border-white/10

                       bg-white
                       dark:bg-white/[0.03]

                       text-sm
                       text-gray-900
                       dark:text-white

                       placeholder:text-gray-400
                       dark:placeholder:text-gray-500

                       px-4

                       transition-all duration-200

                       focus:outline-none
                       focus:ring-4
                       focus:ring-brand/10
                       focus:border-brand

                       disabled:opacity-50
                       disabled:cursor-not-allowed" :class="[
                        field.icon
                            ? 'pl-11'
                            : ''
                    ]" />

        </div>

    </FilterWrapper>

</template>