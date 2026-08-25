<script setup lang="ts">
import { ref, watch, onBeforeUnmount } from 'vue'

import AppInput from './AppInput.vue'
import X from '@/Icons/X.vue'
import type {
    FormErrorBag,
    GalleryUploadItem,
} from '@/types/forms'

interface Props {
    modelValue?: GalleryUploadItem[]
    title?: string
    description?: string
    error?: string | FormErrorBag | null
    disabled?: boolean
}

const props = withDefaults(
    defineProps<Props>(),
    {
        modelValue: () => [],
        title: 'Gallery Images',
        description: 'Upload images with descriptions',
        error: null,
        disabled: false,
    }
)

const emit = defineEmits<{
    (e: 'update:modelValue', value: GalleryUploadItem[]): void
    (e: 'remove-existing', value: string | number): void
}>()

const inputRef = ref<HTMLInputElement | null>(null)

const items = ref<GalleryUploadItem[]>([])

/*
|--------------------------------------------------------------------------
| Sync From Parent
|--------------------------------------------------------------------------
*/

watch(
    () => props.modelValue,
    value => {
        items.value = [...value]
    },
    {
        immediate: true,
        deep: true,
    }
)

/*
|--------------------------------------------------------------------------
| Sync To Parent
|--------------------------------------------------------------------------
*/

watch(
    items,
    value => {
        emit(
            'update:modelValue',
            value
        )
    },
    {
        deep: true,
    }
)

/*
|--------------------------------------------------------------------------
| File Picker
|--------------------------------------------------------------------------
*/

function pickFiles() {
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

    const newItems = files.map(
        file => ({
            id: null,

            file,

            preview: URL.createObjectURL(
                file
            ),

            description: '',

            isNew: true,
        })
    )

    items.value = [
        ...items.value,
        ...newItems,
    ]

    target.value = ''
}

/*
|--------------------------------------------------------------------------
| Remove
|--------------------------------------------------------------------------
*/

function remove(index: number) {

    const item = items.value[index]

    if (
        item.preview &&
        item.preview.startsWith('blob:')
    ) {
        URL.revokeObjectURL(
            item.preview
        )
    }

    if (item.id !== null && item.id !== undefined) {
        emit(
            'remove-existing',
            item.id
        )
    }

    items.value.splice(
        index,
        1
    )
}

/*
|--------------------------------------------------------------------------
| Cleanup
|--------------------------------------------------------------------------
*/

onBeforeUnmount(() => {

    items.value.forEach(item => {

        if (
            item.preview &&
            item.preview.startsWith('blob:')
        ) {
            URL.revokeObjectURL(
                item.preview
            )
        }
    })
})

const errorBag = () =>
    typeof props.error === 'object' && props.error !== null
        ? props.error
        : {}
</script>

<template>
    <div class="space-y-3">

        <!-- Header -->

        <div>
            <h3
                class="
                    text-sm
                    font-medium
                    text-zinc-700
                    dark:text-zinc-300
                "
            >
                {{ title }}
            </h3>

            <p
                class="
                    mt-1
                    text-xs
                    text-zinc-500
                "
            >
                {{ description }}
            </p>
        </div>

        <!-- Upload Box -->

        <div
            @click="pickFiles"
            class="
                rounded-xl
                border
                border-dashed
                p-6
                text-center
                transition
                bg-white
                dark:bg-zinc-900
            "
            :class="[
                disabled
                    ? 'opacity-60 cursor-not-allowed'
                    : 'cursor-pointer hover:border-brand',

                error
                    ? 'border-red-500'
                    : 'border-zinc-300 dark:border-zinc-700'
            ]"
        >
            <p
                class="
                    text-sm
                    font-medium
                    text-zinc-700
                    dark:text-zinc-300
                "
            >
                Upload Images
            </p>

            <p
                class="
                    mt-1
                    text-xs
                    text-zinc-500
                "
            >
                Click to browse files
            </p>
        </div>

        <!-- Gallery -->

        <div
            v-if="items.length"
            class="
                grid
                grid-cols-1
                md:grid-cols-2
                lg:grid-cols-3
                gap-4
            "
        >
            <div
                v-for="(item, index) in items"
                :key="item.id || index"
                class="
                    relative
                    overflow-hidden
                    rounded-xl
                    border
                    border-zinc-200
                    bg-white
                    dark:bg-zinc-900
                    dark:border-zinc-700
                "
            >
                <!-- Image -->

                <img
                    :src="item.preview"
                    class="
                        h-48
                        w-full
                        object-cover
                    "
                >

                <!-- Remove -->

                <button
                    type="button"
                    @click.stop="remove(index)"
                    class="
                        absolute
                        top-2
                        right-2
                        flex
                        h-8
                        w-8
                        items-center
                        justify-center
                        rounded-full
                        bg-black/70
                        text-white
                        transition
                        hover:bg-black
                    "
                >
                    <X class="w-4 h-4" />
                </button>

                <!-- Description -->

                <div class="p-3">
                    <AppInput
                        v-model="item.description"
                        placeholder="Image description"
                    />

                    <p
                        v-if="
                            errorBag()[
                                `images_data.${index}.file`
                            ]
                        "
                        class="
                            mt-1
                            text-xs
                            text-red-500
                        "
                    >
                        {{
                            errorBag()[
                                `images_data.${index}.file`
                            ]
                        }}
                    </p>

                    <p
                        v-if="
                            errorBag()[
                                `images_data.${index}.description`
                            ]
                        "
                        class="
                            mt-1
                            text-xs
                            text-red-500
                        "
                    >
                        {{
                            errorBag()[
                                `images_data.${index}.description`
                            ]
                        }}
                    </p>
                </div>
            </div>
        </div>

        <!-- General Error -->

        <p
            v-if="
                typeof error === 'string'
            "
            class="
                text-xs
                font-medium
                text-red-500
            "
        >
            {{ error }}
        </p>

        <!-- Hidden Input -->

        <input
            ref="inputRef"
            type="file"
            multiple
            accept="image/*"
            class="hidden"
            :disabled="disabled"
            @change="onChange"
        >
    </div>
</template>
