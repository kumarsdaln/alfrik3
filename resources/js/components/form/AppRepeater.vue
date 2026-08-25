<script setup lang="ts">
import { ref } from 'vue'
import AppInput from '@/Components/Form/AppInput.vue'
import AppTextarea from '@/Components/Form/AppTextarea.vue'
import TextEditor from '@/Components/Editor/TextEditor.vue'
import X from '@/Icons/X.vue'

interface FieldDef {
    label: string
    key: string
    type?: 'text' | 'textarea' | 'editor'
}

interface Props {
    /** Base form key, e.g. "faqs" -> submits faqs[0][question], faqs[0][answer]. */
    name: string
    title?: string
    fields: FieldDef[]
    modelValue?: Record<string, string>[] | null
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Items',
    modelValue: null,
})

const items = ref<Record<string, string>[]>(
    props.modelValue ? JSON.parse(JSON.stringify(props.modelValue)) : [],
)

function addItem() {
    const row: Record<string, string> = {}
    props.fields.forEach((f) => (row[f.key] = ''))
    items.value.push(row)
}

function removeItem(i: number) {
    items.value.splice(i, 1)
}
</script>

<template>
    <div class="space-y-4">
        <h3 v-if="title" class="border-b border-zinc-200 pb-2 text-lg font-semibold text-zinc-900 dark:border-zinc-700 dark:text-zinc-100">
            {{ title }}
        </h3>

        <div
            v-for="(item, index) in items"
            :key="index"
            class="relative space-y-4 rounded-xl border border-zinc-300 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900"
        >
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-zinc-500 dark:text-zinc-400">#{{ index + 1 }}</span>
                <button
                    type="button"
                    class="text-red-500 transition hover:text-red-600"
                    @click="removeItem(index)"
                >
                    <X class="h-4 w-4" />
                </button>
            </div>

            <template v-for="field in fields" :key="field.key">
                <AppTextarea
                    v-if="field.type === 'textarea'"
                    :name="`${name}[${index}][${field.key}]`"
                    :label="field.label"
                    v-model="item[field.key]"
                />
                <div v-else-if="field.type === 'editor'">
                    <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ field.label }}</label>
                    <TextEditor v-model="item[field.key]" />
                    <input type="hidden" :name="`${name}[${index}][${field.key}]`" :value="item[field.key]" />
                </div>
                <AppInput
                    v-else
                    :name="`${name}[${index}][${field.key}]`"
                    :label="field.label"
                    v-model="item[field.key]"
                />
            </template>
        </div>

        <button
            type="button"
            class="rounded-xl border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
            @click="addItem"
        >
            + Add {{ title.replace(/s$/, '') || 'Item' }}
        </button>
    </div>
</template>
