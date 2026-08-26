<script setup lang="ts">
    import AppButton from '@/Components/Ui/AppButton.vue'
    import AppModal from '@/Components/Ui/AppModal.vue'

    interface Props {
        open: boolean
        title?: string
        description?: string
        confirmText?: string
        cancelText?: string
        loading?: boolean
        danger?: boolean
    }

    withDefaults(
        defineProps<Props>(),
        {
            title: 'Are you sure?',
            description: 'This action cannot be undone.',
            confirmText: 'Confirm',
            cancelText: 'Cancel',
            loading: false,
            danger: false,
        }
    )

    const emit = defineEmits<{
        (e: 'update:open', value: boolean): void
        (e: 'confirm'): void
        (e: 'cancel'): void
    }>()

    const close = () => {
        emit('update:open', false)
        emit('cancel')
    }

    const confirm = () => {
        emit('confirm')
    }
</script>

<template>
    <AppModal :open="open" @update:open="emit('update:open', $event)">
        <div class="space-y-6">

            <div class="space-y-2">

                <h2 class="text-xl font-semibold
                           text-gray-900
                           dark:text-white">
                    {{ title }}
                </h2>

                <p class="text-sm
                           text-gray-500">
                    {{ description }}
                </p>

            </div>

            <div class="flex justify-end gap-3">
                <AppButton variant="secondary" @click="close">
                    {{ cancelText }}
                </AppButton>

                <AppButton 
                    :variant="danger? 'danger': 'primary'" 
                    :loading="loading" @click="confirm">
                    {{ confirmText }}
                </AppButton>
            </div>
        </div>
    </AppModal>
</template>