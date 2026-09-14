<script setup lang="ts">
import { computed, ref, watch } from 'vue'

type Value = string | number | boolean

interface Props {
    modelValue?: boolean
    defaultValue?: boolean

    onValue?: Value
    offValue?: Value

    disabled?: boolean
    name?: string
    id?: string

    onLabel?: string
    offLabel?: string
}

const props = withDefaults(defineProps<Props>(), {
    defaultValue: false,
    onValue: true,
    offValue: false,
    disabled: false,
    onLabel: 'On',
    offLabel: 'Off',
})

const emit = defineEmits<{
    'update:modelValue': [value: boolean]
}>()

const checked = ref(
    props.modelValue ?? props.defaultValue
)

watch(
    () => props.modelValue,
    value => {
        if (value !== undefined) {
            checked.value = value
        }
    },
)

const submittedValue = computed(() => {
    return checked.value
        ? props.onValue
        : props.offValue
})

function toggle() {
    if (props.disabled) {
        return
    }

    checked.value = !checked.value

    emit('update:modelValue', checked.value)
}
</script>

<template>
    <div class="inline-flex items-center gap-2">
        <button
            :id="id"
            type="button"
            role="switch"
            :aria-checked="checked"
            :disabled="disabled"
            class="
                relative inline-flex h-6 w-11 shrink-0
                items-center rounded-full
                border transition-colors duration-200
                focus:outline-none
            "
            :class="checked
                ? 'border-primary bg-primary'
                : 'border-border-light bg-muted'
            "
            @click="toggle"
        >
            <span
                class="
                    block size-4 rounded-full bg-white
                    shadow transition-transform duration-200
                "
                :class="checked
                    ? 'translate-x-5'
                    : 'translate-x-0.5'
                "
            />
        </button>

        <span class="text-sm">
            {{ checked ? onLabel : offLabel }}
        </span>

        <input
            v-if="name"
            type="hidden"
            :name="name"
            :value="submittedValue"
        >
    </div>
</template>