<script setup>
    import {
        ref,
        computed,
        onMounted,
        onUnmounted
    } from 'vue'

    const props = defineProps({
        modelValue: {
            type: [String, Number],
            required: true,
        },

        options: {
            type: Array,
            default: () => [],
        },

        disabled: {
            type: Boolean,
            default: false,
        },

        minWidth: {
            type: String,
            default: '140px',
        }
    })

    const emit = defineEmits([
        'update:modelValue',
        'change'
    ])

    const open = ref(false)
    const dropdown = ref(null)

    const selected = computed(() => {

        return props.options.find(
            item =>
                item.value == props.modelValue
        )

    })

    /*
    |--------------------------------------------------------------------------
    | COLOR HELPERS
    |--------------------------------------------------------------------------
    */

    const hexToRgb = (hex) => {

        if (!hex) return null

        hex = hex.replace('#', '')

        if (hex.length === 3) {

            hex = hex
                .split('')
                .map(x => x + x)
                .join('')

        }

        const num = parseInt(hex, 16)

        return {
            r: (num >> 16) & 255,
            g: (num >> 8) & 255,
            b: num & 255,
        }

    }

    const colorStyles = computed(() => {

        if (!selected.value?.color) {

            return {}

        }

        const rgb = hexToRgb(
            selected.value.color
        )

        if (!rgb) {

            return {}

        }

        return {
            color: selected.value.color,

            borderColor:
                `rgba(${rgb.r},${rgb.g},${rgb.b},0.25)`,

            backgroundColor:
                `rgba(${rgb.r},${rgb.g},${rgb.b},0.12)`,
        }

    })

    /*
    |--------------------------------------------------------------------------
    | SELECT
    |--------------------------------------------------------------------------
    */

    const selectOption = (option) => {

        emit(
            'update:modelValue',
            option.value
        )

        emit(
            'change',
            option
        )

        open.value = false

    }

    /*
    |--------------------------------------------------------------------------
    | OUTSIDE CLICK
    |--------------------------------------------------------------------------
    */

    const handleOutsideClick = (
        event
    ) => {

        if (
            dropdown.value &&
            !dropdown.value.contains(
                event.target
            )
        ) {

            open.value = false

        }

    }

    onMounted(() => {

        document.addEventListener(
            'click',
            handleOutsideClick
        )

    })

    onUnmounted(() => {

        document.removeEventListener(
            'click',
            handleOutsideClick
        )

    })

    const dropdownPosition = ref('bottom')
    const toggleDropdown = () => {
        if (!dropdown.value) return

        const rect = dropdown.value.getBoundingClientRect()

        const spaceBelow = window.innerHeight - rect.bottom
        const spaceAbove = rect.top

        // Approximate dropdown height
        const dropdownHeight = 320

        if (
            spaceBelow < dropdownHeight &&
            spaceAbove > spaceBelow
        ) {
            dropdownPosition.value = 'top'
        } else {
            dropdownPosition.value = 'bottom'
        }

        open.value = !open.value
    }
</script>

<template>

    <div ref="dropdown" class="relative">

        <!-- BUTTON -->
        <button type="button" :disabled="disabled" @click="toggleDropdown" :style="[
            colorStyles,
            {
                minWidth
            }
        ]" class="
                px-4 py-2.5
                rounded-xl

                border

                font-medium
                text-sm

                transition-all
                duration-200

                flex items-center
                justify-between
                gap-3

                hover:scale-[1.02]

                disabled:opacity-50
                disabled:cursor-not-allowed ">

            <div class="flex items-center gap-2 ">
                <span>
                    {{ selected?.label }}
                </span>
            </div>

            <!-- ARROW -->
            <svg class="
                    w-4 h-4
                    transition-all
                    duration-200
                " :class="open
                    ? 'rotate-180'
                    : ''
                    " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>

        </button>

        <!-- DROPDOWN -->
        <Transition enter-active-class="transition-all duration-200"
            enter-from-class="opacity-0 scale-95 -translate-y-1" enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition-all duration-150" leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 -translate-y-1">

            <div v-if="open" :class="[
                'absolute left-0 min-w-full max-h-80 overflow-y-auto rounded-2xl border border-gray-200 bg-white shadow-2xl z-50',
                dropdownPosition === 'bottom'
                    ? 'top-full mt-2'
                    : 'bottom-full mb-2'
            ]">

                <button v-for="option in options" :key="option.value" @click="
                    selectOption(
                        option
                    )
                    " class="
                        w-full

                        px-4 py-3

                        flex items-center
                        gap-3

                        text-left
                        text-sm

                        transition-all

                        hover:bg-gray-50
                        dark:hover:bg-white/[0.05]
                    ">

                    <div class="
                            w-2.5 h-2.5
                            rounded-full
                        " :style="{
                            backgroundColor:
                                option.color
                        }" />

                    <span>
                        {{ option.label }}
                    </span>

                    <svg v-if="
                        option.value ==
                        modelValue
                    " class="
                            ml-auto
                            w-4 h-4
                        " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>

                </button>

            </div>

        </Transition>

    </div>

</template>