<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import AppFormField from '@/components/ui/AppFormField.vue'

interface Props {
    modelValue?: File | null
    name?: string
    label?: string
    description?: string
    existingFileName?: string | null
    existingFileUrl?: string | null
    preview?: string | null
    existingImage?: string | null
    accept?: string
    error?: string
    disabled?: boolean
}

const props = withDefaults(
    defineProps<Props>(),
    {
        modelValue: null,
        existingFileName: null,
        existingFileUrl: null,
        preview: null,
        existingImage: null,
        accept: '*/*',
        disabled: false,
    }
)

const emit = defineEmits<{
    (e: 'update:modelValue', value: File | null): void
    (e: 'remove-existing', value: boolean): void
}>()

const inputRef = ref<HTMLInputElement | null>(null)
const currentFileUrl = computed(() =>
    props.existingFileUrl ??
    props.preview ??
    props.existingImage
)

const fileName = ref<string | null>(
    props.modelValue?.name ??
    props.existingFileName ??
    currentFileUrl.value?.split('/').pop() ??
    null
)

watch(
    () => props.modelValue,
    value => {
        fileName.value =
            value?.name ??
            props.existingFileName ??
            currentFileUrl.value?.split('/').pop() ??
            null
    }
)

watch(
    () => [
        props.existingFileName,
        currentFileUrl.value,
    ],
    ([name, url]) => {
        if (!props.modelValue) {
            fileName.value =
                name ??
                url?.split('/').pop() ??
                null
        }
    }
)

function openPicker(): void {
    if (!props.disabled) {
        inputRef.value?.click()
    }
}

function onChange(event: Event): void {
    const target = event.target as HTMLInputElement
    const file = target.files?.[0] ?? null

    if (!file) {
        return
    }

    fileName.value = file.name

    emit('update:modelValue', file)
    emit('remove-existing', false)
}

function remove(): void {
    fileName.value = null

    emit('update:modelValue', null)
    emit('remove-existing', true)

    if (inputRef.value) {
        inputRef.value.value = ''
    }
}
</script>

<template>
    <AppFormField
        :label="label"
        :description="description"
        :error="error"
    >
        <div
            @click="openPicker"
            class="
                relative
                rounded-xl
                border
                border-dashed
                p-4
                transition
                cursor-pointer
                bg-white
                dark:bg-zinc-900
            "
            :class="[
                error
                    ? 'border-red-500'
                    : 'border-zinc-300 dark:border-zinc-700',

                disabled &&
                    'opacity-60 cursor-not-allowed'
            ]"
        >
            <div
                v-if="fileName"
                class="flex items-center gap-4"
            >
                <div
                    class="
                        flex
                        h-12
                        w-12
                        items-center
                        justify-center
                        rounded-lg
                        border
                        border-zinc-200
                        bg-zinc-50
                        text-xs
                        font-semibold
                        text-zinc-500
                        dark:border-zinc-700
                        dark:bg-zinc-800
                        dark:text-zinc-300
                    "
                >
                    FILE
                </div>

                <div class="min-w-0 flex-1">
                    <a
                        v-if="currentFileUrl && !modelValue"
                        :href="currentFileUrl"
                        target="_blank"
                        class="
                            block
                            truncate
                            text-sm
                            font-medium
                            text-zinc-900
                            hover:text-brand
                            dark:text-zinc-100
                        "
                        @click.stop
                    >
                        {{ fileName }}
                    </a>

                    <p
                        v-else
                        class="
                            truncate
                            text-sm
                            font-medium
                            text-zinc-900
                            dark:text-zinc-100
                        "
                    >
                        {{ fileName }}
                    </p>

                    <p class="text-xs text-zinc-500">
                        Click to replace
                    </p>
                </div>

                <button
                    type="button"
                    class="text-sm text-red-500 hover:text-red-600"
                    @click.stop="remove"
                >
                    Remove
                </button>
            </div>

            <div
                v-else
                class="py-6 text-center"
            >
                <p
                    class="
                        text-sm
                        font-medium
                        text-zinc-700
                        dark:text-zinc-300
                    "
                >
                    Upload File
                </p>

                <p class="mt-1 text-xs text-zinc-500">
                    Click to browse
                </p>
            </div>

            <input
                ref="inputRef"
                :name="name"
                type="file"
                :accept="accept"
                class="hidden"
                :disabled="disabled"
                @change="onChange"
            >
        </div>
    </AppFormField>
</template>
