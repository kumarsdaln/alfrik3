<script setup lang="ts">
import AppFormField from '@/components/ui/AppFormField.vue'

interface Props {
    name?: string
    label?: string
    description?: string
    error?: string
    disabled?: boolean
    trueValue?: string | number
    falseValue?: string | number
}

const props = withDefaults(
    defineProps<Props>(),
    {
        disabled: false,
        trueValue: 1,
        falseValue: 0,
    },
)

const model = defineModel<boolean>({
    default: false,
})

function toggle(): void {
    if (props.disabled) return

    model.value = !model.value
}
</script>

<template>
    <AppFormField :error="error">
        <!--
            Submitted by Inertia Form.

            true  -> 1
            false -> 0
        -->
        <input
            v-if="name"
            type="hidden"
            :name="name"
            :value="model ? trueValue : falseValue"
        />

        <div
            class="
                flex items-start justify-between gap-4
                rounded-xl border p-4
                transition-all duration-200

                bg-white
                dark:bg-zinc-900

                border-zinc-200
                dark:border-zinc-800
            "
            :class="[
                error && [
                    'border-red-500',
                    'ring-2 ring-red-500/10',
                ],

                !disabled && [
                    'hover:border-zinc-300',
                    'dark:hover:border-zinc-700',
                ],

                disabled && [
                    'cursor-not-allowed',
                    'opacity-60',
                ],
            ]"
        >
            <!-- Content -->
            <div class="min-w-0 flex-1">
                <div
                    v-if="label"
                    class="
                        text-sm font-medium
                        text-zinc-900
                        dark:text-zinc-100
                    "
                >
                    {{ label }}
                </div>

                <p
                    v-if="description"
                    class="
                        mt-1 max-w-xl
                        text-xs leading-relaxed
                        text-zinc-500
                        dark:text-zinc-400
                    "
                >
                    {{ description }}
                </p>
            </div>

            <!-- Switch -->
            <button
                type="button"
                role="switch"
                :aria-checked="model"
                :aria-label="label"
                :disabled="disabled"
                class="
                    relative inline-flex
                    h-6 w-11 shrink-0
                    items-center rounded-full

                    outline-none

                    transition-all
                    duration-200
                    ease-out

                    focus-visible:ring-2
                    focus-visible:ring-brand/30
                    focus-visible:ring-offset-2

                    dark:focus-visible:ring-offset-zinc-900

                    disabled:cursor-not-allowed
                "
                :class="
                    model
                        ? 'bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.25)]'
                        : 'bg-zinc-300 dark:bg-zinc-700'
                "
                @click="toggle"
            >
                <span
                    aria-hidden="true"
                    class="
                        pointer-events-none
                        absolute
                        h-5 w-5
                        rounded-full
                        bg-white
                        shadow-sm
                        ring-1 ring-black/5

                        transition-transform
                        duration-200
                        ease-out
                    "
                    :class="
                        model
                            ? 'translate-x-5'
                            : 'translate-x-0.5'
                    "
                />
            </button>
        </div>
    </AppFormField>
</template>