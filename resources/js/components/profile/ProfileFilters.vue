<script setup lang="ts">
import { computed } from 'vue'

import AppSelect from '@/components/form/AppSelect.vue'
import AppMultiSelect from '@/components/form/AppMultiSelect.vue'

interface Option {
    value: string | number
    label: string
}

interface Props {
    countryId?: number
    positionId?: number
    languageIds: number[]
    industryIds: number[]

    countries: Option[]
    positions: Option[]
    languages: Option[]
    industries: Option[]
}

const props = withDefaults(
    defineProps<Props>(),
    {
        countryId: undefined,
        positionId: undefined,
        languageIds: () => [],
        industryIds: () => [],
    },
)

const emit = defineEmits<{
    'update:countryId': [value: number | undefined]
    'update:positionId': [value: number | undefined]
    'update:languageIds': [value: number[]]
    'update:industryIds': [value: number[]]
}>()

const countryId = computed({
    get: () => props.countryId,
    set: value => emit('update:countryId', value),
})

const positionId = computed({
    get: () => props.positionId,
    set: value => emit('update:positionId', value),
})

const languageIds = computed({
    get: () => props.languageIds,
    set: value => emit('update:languageIds', value),
})

const industryIds = computed({
    get: () => props.industryIds,
    set: value => emit('update:industryIds', value),
})
</script>

<template>
    <div class="space-y-6">

        <!-- Country -->

        <AppSelect
            v-model="countryId"
            name="country"
            label="Country"
            placeholder="All countries"
            :options="countries"
        />


        <!-- Position -->

        <AppSelect
            v-model="positionId"
            name="position"
            label="Position"
            placeholder="All positions"
            :options="positions"
        />


        <!-- Languages -->

        <AppMultiSelect
            v-model="languageIds"
            name="languages"
            label="Languages"
            placeholder="All languages"
            :options="languages"
        />


        <!-- Industries -->

        <AppMultiSelect
            v-model="industryIds"
            name="industries"
            label="Industries"
            placeholder="All industries"
            :options="industries"
        />

    </div>
</template>