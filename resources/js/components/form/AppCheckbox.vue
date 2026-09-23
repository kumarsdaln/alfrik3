<script setup lang="ts">
import { Check } from '@lucide/vue'
import { computed, useId } from 'vue'

interface Props {
    name?: string
    label?: string

    modelValue?: boolean

    defaultValue?: boolean

    trueValue?: string | number
    falseValue?: string | number

    disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: undefined,
    defaultValue: false,
    trueValue: '1',
    falseValue: '0',
    disabled: false,
})

const emit = defineEmits<{
    'update:modelValue': [value: boolean]
}>()

const id = useId()

const checked = computed({
    get() {
        return props.modelValue ?? props.defaultValue
    },

    set(value: boolean) {
        emit('update:modelValue', value)
    },
})
</script>

<template>
    <div>
        <!--
            Only needed when the checkbox is being submitted
            through an HTML/Inertia form.
        -->
        <input v-if="name" type="hidden" :name="name" :value="falseValue" />

        <label :for="id" class="
                inline-flex
                cursor-pointer
                items-center
                gap-3
                select-none
            " :class="{
                'cursor-not-allowed opacity-60': disabled,
            }">
            <input :id="id" v-model="checked" type="checkbox" :name="name" :value="trueValue" :disabled="disabled"
                class="peer sr-only" />

            <span class="
                    flex
                    size-5
                    shrink-0
                    items-center
                    justify-center
                    border
                    border-input
                    bg-background
                    text-transparent
                    transition-colors

                    peer-focus-visible:ring-2
                    peer-focus-visible:ring-ring
                    peer-focus-visible:ring-offset-2

                    peer-checked:border-primary
                    peer-checked:bg-primary
                    peer-checked:text-primary-foreground
                ">
                <Check class="size-3.5 stroke-[3]" />
            </span>

            <span v-if="label" class="text-sm font-medium leading-none">
                {{ label }}
            </span>
        </label>
    </div>
</template>