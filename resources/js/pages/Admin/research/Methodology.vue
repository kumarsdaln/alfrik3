<script setup lang="ts">
    import {
        AppFormControl,
        AppInput,
        AppTextarea,
    } from '@/components/form'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import { Form } from '@inertiajs/vue3'

    interface Props {
        research: {
            id: number
            title: string
        }

        methodology: {
            id: number
            method: string
            description: string | null
            research_design: string | null
            data_collection_method: string | null
            sample_size: number | null
            population: string | null
            geography: string | null
            start_date: string | null
            end_date: string | null
            limitations: string | null
        } | null
    }

    const props = defineProps<Props>()
</script>

<template>
    <div class="flex gap-4 py-5">
        <BackButton />

        <Heading title="Research Methodology" :description="`Define the methodology used for ${research.title}.`" />
    </div>

    <Form v-slot="{ errors, processing }" :action="`/admin/research/${research.id}/methodology`" method="put"
        class="space-y-4">
        <AppFormControl label="Method" required :error="errors.method">
            <AppInput name="method" placeholder="e.g. Quantitative, Qualitative, Mixed Methods"
                :default-value="methodology?.method ?? ''" />
        </AppFormControl>

        <AppFormControl label="Description" :error="errors.description">
            <AppTextarea name="description" placeholder="Describe the research methodology..."
                :default-value="methodology?.description ?? ''" />
        </AppFormControl>

        <AppFormControl label="Research Design" :error="errors.research_design">
            <AppInput name="research_design" placeholder="e.g. Cross-sectional, Experimental, Case Study"
                :default-value="methodology?.research_design ?? ''" />
        </AppFormControl>

        <AppFormControl label="Data Collection Method" :error="errors.data_collection_method">
            <AppInput name="data_collection_method" placeholder="e.g. Survey, Interview, Observation"
                :default-value="methodology?.data_collection_method ?? ''" />
        </AppFormControl>

        <AppFormControl label="Sample Size" :error="errors.sample_size">
            <AppInput name="sample_size" type="number" min="0" placeholder="Enter sample size"
                :default-value="methodology?.sample_size ?? ''" />
        </AppFormControl>

        <AppFormControl label="Population" :error="errors.population">
            <AppTextarea name="population" placeholder="Describe the target population..."
                :default-value="methodology?.population ?? ''" />
        </AppFormControl>

        <AppFormControl label="Geography" :error="errors.geography">
            <AppTextarea name="geography" placeholder="Describe the geographical scope..."
                :default-value="methodology?.geography ?? ''" />
        </AppFormControl>

        <div class="grid gap-4 md:grid-cols-2">
            <AppFormControl label="Start Date" :error="errors.start_date">
                <AppInput name="start_date" type="date" :default-value="methodology?.start_date ?? ''" />
            </AppFormControl>

            <AppFormControl label="End Date" :error="errors.end_date">
                <AppInput name="end_date" type="date" :default-value="methodology?.end_date ?? ''" />
            </AppFormControl>
        </div>

        <AppFormControl label="Limitations" :error="errors.limitations">
            <AppTextarea name="limitations" placeholder="Describe research limitations..."
                :default-value="methodology?.limitations ?? ''" />
        </AppFormControl>

        <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
            <Button type="submit" :disabled="processing">
                {{ processing ? 'Processing...' : 'Save Methodology' }}
            </Button>
        </div>
    </Form>
</template>