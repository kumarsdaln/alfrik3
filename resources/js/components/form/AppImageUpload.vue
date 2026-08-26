<script setup lang="ts">
import {
    ref,
    watch,
    onBeforeUnmount,
} from 'vue'

import AppFormField from '@/components/ui/AppFormField.vue'

interface Props {
    name?: string
    label?: string
    description?: string
    existingImage?: string | null
    accept?: string
    error?: string
    disabled?: boolean
    required?: boolean
    removeName?: string
}

const props = withDefaults(
    defineProps<Props>(),
    {
        existingImage: null,
        accept: 'image/*',
        disabled: false,
        required: false,
    },
)

const model = defineModel<File | null>({
    default: null,
})

const emit = defineEmits<{
    (e: 'remove-existing', value: boolean): void
}>()

const inputRef = ref<HTMLInputElement | null>(null)

const preview = ref<string | null>(
    props.existingImage,
)

const fileName = ref<string | null>(null)

const removeExisting = ref(false)

let previewUrl: string | null = null


/*
|--------------------------------------------------------------------------
| Existing Image Watcher
|--------------------------------------------------------------------------
*/

watch(
    () => props.existingImage,
    (value) => {
        if (!previewUrl && !removeExisting.value) {
            preview.value = value
        }
    },
)


/*
|--------------------------------------------------------------------------
| Model Watcher
|--------------------------------------------------------------------------
*/

watch(
    model,
    (file) => {
        if (!file && !props.existingImage) {
            preview.value = null
            fileName.value = null
        }
    },
)


/*
|--------------------------------------------------------------------------
| Open File Picker
|--------------------------------------------------------------------------
*/

function openPicker(): void {
    if (props.disabled) {
        return
    }

    inputRef.value?.click()
}


/*
|--------------------------------------------------------------------------
| File Selected
|--------------------------------------------------------------------------
*/

function onChange(event: Event): void {
    const target = event.target as HTMLInputElement

    const file = target.files?.[0] ?? null

    if (!file) {
        return
    }

    revokePreviewUrl()

    previewUrl = URL.createObjectURL(file)

    preview.value = previewUrl

    fileName.value = file.name

    model.value = file

    removeExisting.value = false

    emit('remove-existing', false)
}


/*
|--------------------------------------------------------------------------
| Remove Image
|--------------------------------------------------------------------------
*/

function remove(): void {
    revokePreviewUrl()

    preview.value = null

    fileName.value = null

    model.value = null

    removeExisting.value = true

    emit('remove-existing', true)

    if (inputRef.value) {
        inputRef.value.value = ''
    }
}


/*
|--------------------------------------------------------------------------
| Preview Cleanup
|--------------------------------------------------------------------------
*/

function revokePreviewUrl(): void {
    if (!previewUrl) {
        return
    }

    URL.revokeObjectURL(previewUrl)

    previewUrl = null
}


onBeforeUnmount(() => {
    revokePreviewUrl()
})
</script>

<template>
    <AppFormField
        :label="label"
        :description="!preview ? description : undefined"
        :error="error"
        :required="required"
    >
        <div
            class="
                relative
                cursor-pointer
                rounded-xl
                border
                border-dashed
                bg-white
                p-4
                transition-all
                duration-200

                dark:bg-zinc-900
            "
            :class="[
                error
                    ? [
                        'border-red-500',
                        'ring-2',
                        'ring-red-500/10',
                    ]
                    : [
                        'border-zinc-300',
                        'hover:border-brand',
                        'hover:bg-zinc-50',

                        'dark:border-zinc-700',
                        'dark:hover:border-brand',
                        'dark:hover:bg-zinc-800/50',
                    ],

                disabled && [
                    'cursor-not-allowed',
                    'opacity-60',
                ],
            ]"
            @click="openPicker"
        >
            <!-- Preview -->
            <div
                v-if="preview"
                class="flex items-center gap-4"
            >
                <img
                    :src="preview"
                    :alt="fileName || 'Image preview'"
                    class="
                        h-16
                        w-16
                        shrink-0
                        rounded-lg
                        border
                        border-zinc-200
                        object-cover

                        dark:border-zinc-700
                    "
                />

                <div class="min-w-0 flex-1">
                    <p
                        class="
                            truncate
                            text-sm
                            font-medium
                            text-zinc-900

                            dark:text-zinc-100
                        "
                    >
                        {{ fileName || 'Current Image' }}
                    </p>

                    <p
                        class="
                            mt-1
                            text-xs
                            text-zinc-500
                            dark:text-zinc-400
                        "
                    >
                        Click to replace
                    </p>
                </div>

                <button
                    type="button"
                    :disabled="disabled"
                    class="
                        shrink-0
                        rounded-lg
                        px-3
                        py-2
                        text-sm
                        font-medium
                        text-red-600
                        transition-colors

                        hover:bg-red-50
                        hover:text-red-700

                        disabled:pointer-events-none

                        dark:text-red-400
                        dark:hover:bg-red-500/10
                    "
                    @click.stop="remove"
                >
                    Remove
                </button>
            </div>


            <!-- Empty State -->
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
                    Upload Image
                </p>

                <p
                    class="
                        mt-1
                        text-xs
                        text-zinc-500

                        dark:text-zinc-400
                    "
                >
                    Click to browse
                </p>
            </div>


            <!-- Native File Input -->
            <input
                ref="inputRef"
                :name="name"
                type="file"
                :accept="accept"
                :disabled="disabled"
                :required="required && !existingImage"
                class="sr-only"
                @change="onChange"
            />


            <!-- Existing Image Removal State -->
            <input
                v-if="removeName"
                type="hidden"
                :name="removeName"
                :value="removeExisting ? '1' : '0'"
            />
        </div>
    </AppFormField>
</template>