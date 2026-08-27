<script setup lang="ts">
import {
    ref,
    computed,
    onMounted,
    onBeforeUnmount,
} from 'vue'

import { X } from '@lucide/vue'
import DownAngle from '@/Icons/DownAngle.vue'
import type { FormValue } from '@/types/forms'

type OptionRecord = Record<string, unknown>

interface Props {
    modelValue?: FormValue[]
    options?: OptionRecord[]
    label?: string
    placeholder?: string
    error?: string
    disabled?: boolean
    optionLabel?: string
    optionValue?: string
    loading?: boolean
}

const props = withDefaults(
    defineProps<Props>(),
    {
        modelValue: () => [],
        options: () => [],
        placeholder: 'Search...',
        disabled: false,
        optionLabel: 'name',
        optionValue: 'id',
        loading: false,
    }
)

const emit = defineEmits<{
    (e: 'update:modelValue', value: FormValue[]): void
}>()

const wrapperRef = ref<HTMLElement | null>(null)

const search = ref('')

const open = ref(false)

const filteredOptions = computed(() => {

    return props.options.filter(option => {

        const label =
            String(
                option[
                    props.optionLabel
                ] ?? ''
            )

        const value =
            option[
                props.optionValue
            ]

        return (
            label
                .toLowerCase()
                .includes(
                    search.value.toLowerCase()
                ) &&
            !props.modelValue.includes(
                value
            )
        )
    })
})

const selectedOptions = computed(() => {

    return props.options.filter(
        option =>
            props.modelValue.includes(
                option[
                    props.optionValue
                ]
            )
    )
})

const optionValue = (option: OptionRecord): FormValue =>
    option[props.optionValue] as FormValue

function add(option: OptionRecord) {

    emit(
        'update:modelValue',
        [
            optionValue(option),
            ...props.modelValue,
        ]
    )

    search.value = ''

    open.value = false
}

function remove(value: FormValue) {

    emit(
        'update:modelValue',
        props.modelValue.filter(
            item => item !== value
        )
    )
}

function clickOutside(event: MouseEvent) {

    if (
        wrapperRef.value &&
        !wrapperRef.value.contains(
            event.target
        )
    ) {
        open.value = false
    }
}

onMounted(() => {

    document.addEventListener(
        'click',
        clickOutside
    )
})

onBeforeUnmount(() => {

    document.removeEventListener(
        'click',
        clickOutside
    )
})
</script>

<template>
    <div
        ref="wrapperRef"
        class="space-y-1.5"
    >
        <!-- Label -->

        <label
            v-if="label"
            class="
                block
                text-sm
                font-medium
                text-zinc-700
                dark:text-zinc-300
            "
        >
            {{ label }}
        </label>

        <!-- Search (matches AppInputControl) -->

        <div
            class="
                flex
                w-full
                min-w-0
                items-center
                gap-2
                rounded-xl
                border
                px-3
                py-2.5
                text-sm
                bg-white
                text-zinc-900
                dark:bg-zinc-900
                dark:text-zinc-100
                transition-[border-color,box-shadow,background-color]
                duration-200
            "
            :class="[
                error
                    ? 'border-red-500 focus-within:border-red-500 focus-within:ring-2 focus-within:ring-red-500/20'
                    : 'border-zinc-300 dark:border-zinc-700 hover:border-zinc-400 dark:hover:border-zinc-600 focus-within:border-brand focus-within:ring-2 focus-within:ring-brand/20',

                disabled &&
                    'cursor-not-allowed bg-zinc-100 opacity-60 dark:bg-zinc-800',
            ]"
        >
            <input
                v-model="search"
                :placeholder="placeholder"
                :disabled="disabled"
                @focus="open = true"
                class="
                    flex-1
                    appearance-none
                    border-0
                    bg-transparent
                    p-0
                    text-sm
                    text-zinc-900
                    dark:text-zinc-100
                    placeholder:text-zinc-400
                    dark:placeholder:text-zinc-500
                    outline-none
                    ring-0
                    focus:border-0
                    focus:outline-none
                    focus:ring-0
                "
            >

            <button
                type="button"
                @click="open = !open"
                class="shrink-0"
            >
                <DownAngle
                    class="
                        h-4
                        w-4
                        text-zinc-400
                        transition-transform
                    "
                    :class="{
                        'rotate-180': open
                    }"
                />
            </button>
        </div>

        <!-- Dropdown -->

        <div
            v-if="open"
            class="
                overflow-hidden
                rounded-xl
                border
                border-zinc-200
                bg-white
                shadow-lg
                dark:border-zinc-700
                dark:bg-zinc-900
            "
        >
            <div
                v-if="loading"
                class="
                    px-4
                    py-3
                    text-sm
                    text-zinc-500
                "
            >
                Loading...
            </div>

            <div
                v-else-if="
                    !filteredOptions.length
                "
                class="
                    px-4
                    py-3
                    text-sm
                    text-zinc-500
                "
            >
                No results found
            </div>

            <div
                v-else
                class="
                    max-h-60
                    overflow-y-auto
                "
            >
                <button
                    v-for="
                        option
                            in filteredOptions
                    "
                    :key="
                        option[
                            optionValue
                        ]
                    "
                    type="button"
                    @click="
                        add(option)
                    "
                    class="
                        block
                        w-full
                        px-4
                        py-2.5
                        text-left
                        text-sm
                        text-zinc-700
                        dark:text-zinc-200
                        hover:bg-zinc-100
                        dark:hover:bg-zinc-800
                    "
                >
                    {{
                        option[
                            optionLabel
                        ]
                    }}
                </button>
            </div>
        </div>

        <!-- Pills -->

        <div
            v-if="selectedOptions.length"
            class="
                flex
                flex-wrap
                gap-2
            "
        >
            <span
                v-for="
                    option
                        in selectedOptions
                "
                :key="
                    option[
                        optionValue
                    ]
                "
                class="
                    inline-flex
                    items-center
                    gap-1
                    rounded-full
                    px-3
                    py-1
                    text-xs
                    font-medium
                    bg-brand/10
                    text-brand
                "
            >
                {{
                    option[
                        optionLabel
                    ]
                }}

                <button
                    type="button"
                    @click="
                        remove(
                            option[
                                optionValue
                            ]
                        )
                    "
                >
                    <X
                        class="
                            h-3
                            w-3
                        "
                    />
                </button>
            </span>
        </div>

        <!-- Error -->

        <p
            v-if="error"
            class="
                text-xs
                font-medium
                text-red-500
            "
        >
            {{ error }}
        </p>
    </div>
</template>
