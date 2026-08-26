<script setup lang="ts">
import { ref, watch } from 'vue'
import AppFormField from '@/components/ui/AppFormField.vue'
import X from '@/Icons/X.vue'

interface Row {
    key: string
    value: string
}

interface Props {
    name: string
    label?: string
    error?: string
    /** Initial value: an object { key: value } or an array of { key, value }. */
    modelValue?: Record<string, string> | Row[] | null
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: null,
})

function toRows(value: Props['modelValue']): Row[] {
    if (!value) return []
    if (Array.isArray(value)) return value.map((r) => ({ key: r.key ?? '', value: r.value ?? '' }))
    return Object.entries(value).map(([key, value]) => ({ key, value: String(value ?? '') }))
}

const rows = ref<Row[]>(toRows(props.modelValue))

watch(
    () => props.modelValue,
    (v) => {
        if (!rows.value.length) rows.value = toRows(v)
    },
)

function addRow() {
    rows.value.push({ key: '', value: '' })
}

function removeRow(i: number) {
    rows.value.splice(i, 1)
}
</script>

<template>
    <AppFormField :label="label" :error="error">
        <div class="space-y-3">
            <div v-for="(row, index) in rows" :key="index" class="flex items-center gap-3">
                <input
                    v-model="row.key"
                    placeholder="Key"
                    class="w-1/3 rounded-xl border border-zinc-300 bg-white px-3 py-2.5 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-brand focus:ring-2 focus:ring-brand/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                />
                <input
                    v-model="row.value"
                    :name="row.key ? `${name}[${row.key}]` : undefined"
                    placeholder="Value"
                    class="w-2/3 rounded-xl border border-zinc-300 bg-white px-3 py-2.5 text-sm text-zinc-900 outline-none transition placeholder:text-zinc-400 focus:border-brand focus:ring-2 focus:ring-brand/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
                />
                <button
                    type="button"
                    class="shrink-0 text-red-500 transition hover:text-red-600"
                    @click="removeRow(index)"
                >
                    <X class="h-4 w-4" />
                </button>
            </div>

            <button
                type="button"
                class="rounded-xl border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                @click="addRow"
            >
                + Add Row
            </button>
        </div>
    </AppFormField>
</template>
