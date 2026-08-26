<script setup lang="ts">
    import { ref } from 'vue'
    import { Link, router } from '@inertiajs/vue3'

    import AppButton from '@/components/ui/AppButton.vue'
    import AppConfirmDialog from '@/components/ui/AppConfirmDialog.vue'
    import PlusIcon from '@/Icons/PlusIcon.vue'

    import { index as reportsIndex } from '@/routes/admin/reports'
    import { create as categoryCreate, edit as categoryEdit, destroy as categoryDestroy } from '@/routes/admin/reports/categories'
    import type { ReportCategory } from '@/types'

    defineProps<{ categories: ReportCategory[] }>()

    const toDelete = ref<ReportCategory | null>(null)
    const deleting = ref(false)
    function confirmDelete() {
        if (!toDelete.value) return
        deleting.value = true
        router.delete(categoryDestroy(toDelete.value.id).url, { preserveScroll: true, onFinish: () => { deleting.value = false; toDelete.value = null } })
    }
</script>

<template>
    <div class="max-w-4xl mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Report Categories</h1>
                <Link :href="reportsIndex().url" class="text-sm text-[#e0006c] hover:underline">← Back to reports</Link>
            </div>
            <AppButton variant="add" size="sm" :href="categoryCreate().url">
                <template #icon-left>
                    <PlusIcon class="w-3" />
                </template>
                New Category
            </AppButton>
        </div>

        <div class="rounded-xl border border-gray-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 text-left text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-5 py-3 font-medium">Name</th>
                        <th class="px-5 py-3 font-medium">Slug</th>
                        <th class="px-5 py-3 font-medium">Reports</th>
                        <th class="px-5 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                    <tr v-for="cat in categories" :key="cat.id">
                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ cat.name }}</td>
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ cat.slug }}</td>
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ cat.reports_count ?? 0 }}</td>
                        <td class="px-5 py-3">
                            <div class="flex justify-end gap-2">
                                <AppButton variant="edit" size="xs" :href="categoryEdit(cat.id).url">Edit</AppButton>
                                <AppButton variant="delete" size="xs" @click="toDelete = cat">Delete</AppButton>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!categories.length">
                        <td colspan="4" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">No categories
                            yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <AppConfirmDialog :open="!!toDelete" title="Delete category?"
        :description="`Reports in “${toDelete?.name}” will be moved to Uncategorized.`" danger confirm-text="Delete"
        :loading="deleting" @confirm="confirmDelete" @cancel="toDelete = null" />
</template>
