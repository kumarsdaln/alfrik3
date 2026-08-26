<script setup lang="ts">
    import { ref } from 'vue'
    import { useForm } from '@inertiajs/vue3'
    import type { Sponsor } from '@/types/sponsor'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppButton from '@/components/ui/AppButton.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'

    const props = defineProps<{
        sponsor?: Sponsor | null
        tiers?: string[]
    }>()

    const isEdit = !!props.sponsor?.id

    const toOptions = (values?: string[]) =>
        (values ?? []).map((value) => ({ label: value.charAt(0).toUpperCase() + value.slice(1), value }))

    const logoPreview = ref<string | null>(props.sponsor?.logo_url ?? null)

    const form = useForm({
        name: props.sponsor?.name ?? '',
        tier: props.sponsor?.tier ?? (props.tiers?.[0] ?? 'partner'),
        website_url: props.sponsor?.website_url ?? '',
        description: props.sponsor?.description ?? '',
        sort_order: props.sponsor?.sort_order ?? 0,
        is_active: props.sponsor?.is_active ?? true,
        logo: null as File | null,
    })

    const onLogo = (e: Event) => {
        const file = (e.target as HTMLInputElement).files?.[0] ?? null
        form.logo = file
        logoPreview.value = file ? URL.createObjectURL(file) : (props.sponsor?.logo_url ?? null)
    }

    const submit = () => {
        // Cast the checkbox to 1/0 so it survives multipart (Laravel's `boolean`
        // rule rejects the "true"/"false" strings FormData would otherwise send).
        form.transform((data) => ({ ...data, is_active: data.is_active ? 1 : 0 }))

        const url = isEdit ? route('admin.sponsors.update', props.sponsor!.id) : route('admin.sponsors.store')
        form.post(url, { forceFormData: true })
    }
</script>

<template>
    <form class="mx-auto max-w-3xl space-y-6 p-4 sm:p-6" @submit.prevent="submit">
        <div>
            <AppHeading font="redhat" weight="bold" size="lg">{{ isEdit ? 'Edit sponsor' : 'Add sponsor' }}</AppHeading>
            <AppText color="muted" size="sm" class="mt-1">Sponsors and partners shown on the public sponsors page.</AppText>
        </div>

        <div class="space-y-5 rounded-2xl border border-border-light bg-surface-light p-6 shadow-sm dark:border-border-dark dark:bg-surface-dark">
            <AppInput v-model="form.name" name="name" label="Sponsor name" placeholder="Company or organisation name" required :error="form.errors.name" />

            <div class="grid gap-4 sm:grid-cols-2">
                <AppSelect v-model="form.tier" name="tier" label="Tier" :options="toOptions(tiers)" :error="form.errors.tier" />
                <AppInput v-model="form.sort_order" name="sort_order" type="number" label="Sort order" placeholder="0" :error="form.errors.sort_order" />
            </div>

            <AppInput v-model="form.website_url" name="website_url" type="url" label="Website URL" placeholder="https://…" :error="form.errors.website_url" />

            <AppTextarea v-model="form.description" name="description" label="Description" placeholder="Short description (optional)" :error="form.errors.description" />

            <!-- Logo -->
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Logo</label>
                <div class="flex items-center gap-4">
                    <div class="grid h-16 w-16 shrink-0 place-items-center overflow-hidden rounded-xl border border-border-light bg-gray-50 dark:border-border-dark dark:bg-gray-800">
                        <img v-if="logoPreview" :src="logoPreview" alt="" class="h-full w-full object-contain" />
                        <span v-else class="text-xl font-bold text-gray-300">{{ form.name?.charAt(0) || 'S' }}</span>
                    </div>
                    <input type="file" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-full file:border-0 file:bg-brand/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand hover:file:bg-brand/20 dark:text-gray-300" @change="onLogo" />
                </div>
                <p v-if="form.errors.logo" class="mt-1 text-sm text-red-600">{{ form.errors.logo }}</p>
                <p class="mt-1 text-xs text-gray-400">PNG, JPG or SVG up to 2&nbsp;MB. A transparent PNG looks best.</p>
            </div>

            <!-- Active -->
            <label class="flex cursor-pointer items-center gap-3">
                <input v-model="form.is_active" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-brand focus:ring-brand" />
                <span class="text-sm text-gray-700 dark:text-gray-300">Active — show on the public sponsors page</span>
            </label>
        </div>

        <div class="flex justify-end gap-3">
            <AppButton variant="cancel" :href="route('admin.sponsors.index')">Cancel</AppButton>
            <AppButton type="submit" variant="submit" :loading="form.processing" :disabled="form.processing">
                {{ isEdit ? 'Save changes' : 'Add sponsor' }}
            </AppButton>
        </div>
    </form>
</template>
