<script setup lang="ts">
import { Head, Form, Link } from '@inertiajs/vue3'

import AppFormControl from '@/components/form/AppFormControl.vue'
import AppInput from '@/components/form/AppInput.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import AppTextarea from '@/components/form/AppTextarea.vue'

import Heading from '@/components/Heading.vue'
import BackButton from '@/components/ui/BackButton.vue'
import Button from '@/components/ui/button/Button.vue'

import {
    index,
    store,
} from '@/routes/admin/report/sections/blocks'


interface Option {
    value: string
    label: string
}


interface Props {
    report: {
        id: number
        title: string
    }

    section: {
        id: number
        title: string
    }

    typeOptions: Option[]
}

defineProps<Props>()
</script>


<template>

    <Head :title="`Add Content Block - ${section.title}`" />

    <div class="space-y-6">

        <!-- ============================================================= -->
        <!-- Header -->
        <!-- ============================================================= -->

        <div class="flex items-center gap-3">
            <BackButton />

            <Heading title="Add Content Block" :description="`${report.title} / ${section.title}`
                " />
        </div>


        <!-- ============================================================= -->
        <!-- Form -->
        <!-- ============================================================= -->

        <Form v-slot="{ errors, processing }" v-bind="store.form({
            report: report.id,
            section: section.id,
        })" :options="{
                preserveScroll: true,
            }" :transform="(data) => ({
                ...data,
                position: Number(data.position ?? 0),
                content: data.content
                    ? JSON.parse(data.content as string)
                    : {},
            })" class="space-y-6">
            <div class="grid gap-6 lg:grid-cols-3">

                <!-- ===================================================== -->
                <!-- Main -->
                <!-- ===================================================== -->

                <div class="space-y-6 lg:col-span-2">

                    <div class="
                            rounded-lg
                            border
                            bg-background
                            p-6
                        ">
                        <div class="mb-6">
                            <h2 class="text-base font-semibold">
                                Block Information
                            </h2>

                            <p class="
                                    mt-1
                                    text-sm
                                    text-muted-foreground
                                ">
                                Define the content block and its data.
                            </p>
                        </div>


                        <div class="space-y-5">

                            <!-- Type -->

                            <AppFormControl label="Type" :error="errors.type" required>
                                <AppSelect name="type" :options="typeOptions" :default-value="typeOptions[0]?.value
                                    " placeholder="Select block type" />
                            </AppFormControl>


                            <!-- Title -->

                            <AppFormControl label="Title" :error="errors.title">
                                <AppInput name="title" placeholder="Enter block title" />
                            </AppFormControl>


                            <!-- Description -->

                            <AppFormControl label="Description" :error="errors.description">
                                <AppTextarea name="description" placeholder="Enter a short description..." rows="4" />
                            </AppFormControl>


                            <!-- Content -->

                            <AppFormControl label="Content" :error="errors.content"
                                description="Enter valid JSON content for this block." required>
                                <AppTextarea name="content" :default-value="'{}'" :placeholder="`{
    &quot;text&quot;: &quot;Write the content here...&quot;
}`" rows="18" class="font-mono text-sm" />
                            </AppFormControl>

                        </div>
                    </div>
                </div>


                <!-- ===================================================== -->
                <!-- Sidebar -->
                <!-- ===================================================== -->

                <div class="space-y-6">

                    <!-- Position -->

                    <div class="
                            rounded-lg
                            border
                            bg-background
                            p-6
                        ">
                        <div class="mb-6">
                            <h2 class="text-base font-semibold">
                                Position
                            </h2>

                            <p class="
                                    mt-1
                                    text-sm
                                    text-muted-foreground
                                ">
                                Lower numbers appear first.
                            </p>
                        </div>

                        <AppFormControl label="Position" :error="errors.position">
                            <AppInput name="position" type="number" min="0" default-value="0" />
                        </AppFormControl>
                    </div>


                    <!-- Selected Block -->

                    <div class="
                            rounded-lg
                            border
                            bg-background
                            p-6
                        ">
                        <h2 class="text-base font-semibold">
                            Content Block
                        </h2>

                        <p class="
                                mt-1
                                text-sm
                                text-muted-foreground
                            ">
                            Select the block type above and provide
                            its content.
                        </p>
                    </div>


                    <!-- Actions -->

                    <div class="
                            rounded-lg
                            border
                            bg-background
                            p-6
                        ">
                        <div class="flex justify-end gap-3">

                            <Button type="button" variant="outline" as-child>
                                <Link :href="index({
                                    report: report.id,
                                    section: section.id,
                                })
                                    ">
                                    Cancel
                                </Link>
                            </Button>

                            <Button type="submit" :disabled="processing">
                                {{
                                    processing
                                        ? 'Processing...'
                                        : 'Create Block'
                                }}
                            </Button>

                        </div>
                    </div>

                </div>
            </div>
        </Form>
    </div>
</template>