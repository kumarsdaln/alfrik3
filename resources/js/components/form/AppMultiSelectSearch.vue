<script setup lang="ts">
    import {
        ref,
        computed,
        watch,
        onMounted,
        onBeforeUnmount,
    } from 'vue'

    import X from '@/icons/X.vue'
    import DownAngle from '@/icons/DownAngle.vue'
    type OptionRecord = Record<string, unknown>

    interface Props {
        modelValue?: OptionRecord[]
        options?: OptionRecord[]
        label?: string
        placeholder?: string
        error?: string
        disabled?: boolean
        optionLabel?: string
        optionValue?: string
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
        }
    )

    const emit = defineEmits<{
        (e: 'update:modelValue', value: OptionRecord[]): void
    }>()

    const wrapper = ref<HTMLElement | null>(null)

    const search = ref('')

    const open = ref(false)

    const selected = ref<OptionRecord[]>([])

    watch(
        () => props.modelValue,
        value => {
            selected.value = [...value]
        },
        {
            immediate: true,
            deep: true,
        }
    )

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

            const alreadySelected =
                selected.value.some(
                    item =>
                        item[
                        props.optionValue
                        ] === value
                )

            return (
                label
                    .toLowerCase()
                    .includes(
                        search.value.toLowerCase()
                    ) &&
                !alreadySelected
            )
        })
    })

    function add(option: OptionRecord) {

        selected.value = [
            ...selected.value,
            option,
        ]

        emit(
            'update:modelValue',
            selected.value
        )

        search.value = ''

        open.value = true
    }

    function remove(value: unknown) {

        selected.value =
            selected.value.filter(
                item =>
                    item[
                    props.optionValue
                    ] !== value
            )

        emit(
            'update:modelValue',
            selected.value
        )
    }

    function clickOutside(event: MouseEvent) {

        if (
            wrapper.value &&
            !wrapper.value.contains(
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
    <div ref="wrapper" class="space-y-1.5">
        <!-- Label -->

        <label v-if="label" class="
                block
                text-sm
                font-medium
                text-zinc-700
                dark:text-zinc-300
            ">
            {{ label }}
        </label>

        <!-- Control -->

        <div class="
                rounded-xl
                border
                bg-white
                dark:bg-zinc-900
                transition-all
            " :class="[
                error
                    ? 'border-red-500'
                    : 'border-zinc-300 dark:border-zinc-700',

                disabled &&
                'opacity-60 cursor-not-allowed',

                'focus-within:border-brand focus-within:ring-2 focus-within:ring-brand/20'
            ]">
            <!-- Selected -->

            <div v-if="selected.length" class="
                    flex
                    flex-wrap
                    gap-2
                    p-3
                    pb-0
                ">
                <span v-for="item in selected" :key="item[
                    optionValue
                    ]
                    " class="
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
                    ">
                    {{
                        item[
                        optionLabel
                        ]
                    }}

                    <button type="button" @click.stop="
                        remove(
                            item[
                            optionValue
                            ]
                        )
                        ">
                        <X class="
                                h-3
                                w-3
                            " />
                    </button>
                </span>
            </div>

            <!-- Search -->

            <div class="
                    flex
                    items-center
                    gap-2
                    px-3
                    py-2
                ">
                <input v-model="search" :placeholder="placeholder
                    " :disabled="disabled" @focus="open = true" class="
                        flex-1
                        bg-transparent
                        text-sm
                        text-zinc-900
                        dark:text-zinc-100
                        placeholder:text-zinc-400
                        dark:placeholder:text-zinc-500
                        outline-none
                    ">

                <button type="button" :disabled="disabled" @click="
                    open = !open
                    ">
                    <DownAngle class="
                            h-4
                            w-4
                            text-zinc-400
                        " />
                </button>
            </div>
        </div>

        <!-- Dropdown -->

        <div v-if="open" class="
                overflow-hidden
                rounded-xl
                border
                border-zinc-200
                bg-white
                shadow-lg
                dark:border-zinc-700
                dark:bg-zinc-900
            ">
            <div v-if="
                !filteredOptions.length
            " class="
                    px-4
                    py-3
                    text-sm
                    text-zinc-500
                ">
                No results found
            </div>

            <div v-else class="
                    max-h-60
                    overflow-y-auto
                ">
                <button v-for="
option
                            in filteredOptions
                    " :key="option[
                        optionValue
                        ]
                        " type="button" @click="
                        add(option)
                        " class="
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
                    ">
                    {{
                        option[
                        optionLabel
                        ]
                    }}
                </button>
            </div>
        </div>

        <!-- Error -->

        <p v-if="error" class="
                text-xs
                font-medium
                text-red-500
            ">
            {{ error }}
        </p>
    </div>
</template>
