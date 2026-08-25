<script setup lang="ts">
    import {
        ref,
        watch,
        onBeforeUnmount,
    } from 'vue'
    import type { FilePreviewItem } from '@/types/forms'

    interface Props {
        modelValue?: File[]
        label?: string
        description?: string
        error?: string
        accept?: string
        max?: number
        disabled?: boolean
    }

    const props = withDefaults(
        defineProps<Props>(),
        {
            modelValue: () => [],
            accept: 'image/*',
            disabled: false,
        }
    )

    const emit = defineEmits<{
        (e: 'update:modelValue', value: File[]): void
    }>()

    const inputRef = ref<HTMLInputElement | null>(null)

    const previews = ref<FilePreviewItem[]>([])

    function clearPreviews() {
        previews.value.forEach(item => {
            if (item.url?.startsWith('blob:')) {
                URL.revokeObjectURL(item.url)
            }
        })
    }

    function syncPreviews(files: File[]) {
        clearPreviews()

        previews.value = files.map(file => ({
            file,
            name: file.name,
            url: file.type?.startsWith('image/')
                ? URL.createObjectURL(file)
                : null,
        }))
    }

    watch(
        () => props.modelValue,
        value => {
            syncPreviews(value)
        },
        {
            immediate: true,
            deep: true,
        }
    )

    function openPicker() {
        if (!props.disabled) {
            inputRef.value?.click()
        }
    }

    function onChange(event: Event) {
        const target = event.target as HTMLInputElement

        const files = Array.from(
            target.files || []
        )

        if (!files.length) {
            return
        }

        let updated = [
            ...props.modelValue,
            ...files,
        ]

        if (props.max) {
            updated = updated.slice(
                0,
                props.max
            )
        }

        emit(
            'update:modelValue',
            updated
        )

        target.value = ''
    }

    function remove(index: number) {

        const updated = [
            ...props.modelValue
        ]

        updated.splice(
            index,
            1
        )

        emit(
            'update:modelValue',
            updated
        )
    }

    onBeforeUnmount(() => {
        clearPreviews()
    })
</script>

<template>
    <div class="space-y-1.5">
        <label v-if="label" class="
                block
                text-sm
                font-medium
                text-zinc-700
                dark:text-zinc-300
            ">
            {{ label }}
        </label>

        <div @click="openPicker" class="
                rounded-xl
                border
                border-dashed
                p-6
                text-center
                transition
                cursor-pointer
                bg-white
                dark:bg-zinc-900
            " :class="[
                error
                    ? 'border-red-500'
                    : 'border-zinc-300 dark:border-zinc-700',

                disabled &&
                'opacity-60 cursor-not-allowed'
            ]">
            <p class="
                    text-sm
                    font-medium
                    text-zinc-700
                    dark:text-zinc-300
                ">
                Upload Files
            </p>

            <p class="
                    mt-1
                    text-xs
                    text-zinc-500
                ">
                Click to browse
            </p>

            <p v-if="description" class="
                    mt-2
                    text-xs
                    text-zinc-400
                ">
                {{ description }}
            </p>

            <p v-if="max" class="
                    mt-2
                    text-xs
                    text-zinc-400
                ">
                Maximum {{ max }} files
            </p>
        </div>

        <div v-if="previews.length" class="
                grid
                grid-cols-2
                md:grid-cols-4
                gap-3
                mt-3
            ">
            <div v-for="(item, index) in previews" :key="index" class="
                    relative
                    rounded-xl
                    overflow-hidden
                    border
                    border-zinc-200
                    dark:border-zinc-700
                    bg-white
                    dark:bg-zinc-900
                ">
                <img v-if="item.url" :src="item.url" class="
                        h-28
                        w-full
                        object-cover
                    ">

                <div v-else class="
                        h-28
                        flex
                        items-center
                        justify-center
                        text-xs
                        text-zinc-500
                    ">
                    File
                </div>

                <div class="
                        p-2
                        text-xs
                        truncate
                    ">
                    {{ item.name }}
                </div>

                <button type="button" @click.stop="remove(index)" class="
                        absolute
                        top-2
                        right-2
                        h-6
                        w-6
                        rounded-full
                        bg-black/70
                        text-white
                        text-xs
                    ">
                    ×
                </button>
            </div>
        </div>

        <p v-if="error" class="
                text-xs
                font-medium
                text-red-500
            ">
            {{ error }}
        </p>

        <input ref="inputRef" type="file" multiple class="hidden" :accept="accept" :disabled="disabled"
            @change="onChange">
    </div>
</template>
