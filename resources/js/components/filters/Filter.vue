<script setup lang="ts">
import { reactive } from 'vue'
import FilterInput from '@/Components/FilterTypes/FilterInput.vue'
import FilterSelect from '@/Components/FilterTypes/FilterSelect.vue'

interface FilterFieldDef {
    name: string
    type?: 'text' | 'select'
    label?: string
    placeholder?: string
    options?: { value: string | number; label: string }[]
}

interface Props {
    fields: FilterFieldDef[]
}

const props = defineProps<Props>()

const emit = defineEmits<{ change: [values: Record<string, unknown>] }>()

const values = reactive<Record<string, string | number>>(
    Object.fromEntries(props.fields.map((f) => [f.name, ''])),
)

function onChange() {
    emit('change', { ...values })
}
</script>

<template>
    <div class="flex flex-wrap items-end gap-3">
        <template v-for="field in fields" :key="field.name">
            <FilterSelect
                v-if="field.type === 'select'"
                v-model="values[field.name]"
                :field="{ label: field.label, placeholder: field.placeholder, options: field.options ?? [] }"
                @change="onChange"
            />
            <FilterInput
                v-else
                v-model="values[field.name]"
                :field="{ label: field.label, placeholder: field.placeholder, type: field.type }"
                @change="onChange"
            />
        </template>
    </div>
</template>
