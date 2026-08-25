<script setup lang="ts">
import { Check } from '@lucide/vue'
import AppButton from '@/Components/Ui/AppButton.vue'

interface Props {
    processing?: boolean
    dirty?: boolean
    saveLabel?: string
    showReset?: boolean
}

withDefaults(defineProps<Props>(), {
    processing: false,
    dirty: false,
    saveLabel: 'Save',
    showReset: true,
})

defineEmits<{ reset: [] }>()
</script>

<template>
    <!--
        Fixed action bar. It spans the content region (right of the 256px sidebar
        on desktop) and matches ProfileLayout's content padding (px-4 lg:px-8),
        so Save lands at the form's right edge.
    -->
    <div
        class="fixed inset-x-0 bottom-0 z-30 border-t border-border-light bg-surface-light/95 backdrop-blur lg:left-[256px] dark:border-border-dark dark:bg-surface-dark/95"
    >
        <div class="flex w-full items-center justify-between gap-3 px-4 py-3 lg:px-8">
            <!-- Status -->
            <div class="flex items-center gap-2.5">
                <span v-if="dirty" class="relative flex h-2.5 w-2.5 shrink-0" aria-hidden="true">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-brand opacity-60" />
                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-brand" />
                </span>
                <span v-else
                    class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400"
                    aria-hidden="true">
                    <Check class="h-3 w-3" :stroke-width="3" />
                </span>

                <span class="hidden font-redhat text-sm text-content-lightMuted dark:text-content-darkMuted sm:block">
                    {{ dirty ? 'Unsaved changes' : 'All changes saved' }}
                </span>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2">
                <AppButton
                    v-if="showReset"
                    variant="cancel"
                    size="sm"
                    rounded="lg"
                    type="reset"
                    :disabled="!dirty || processing"
                    @click="$emit('reset')"
                >
                    Reset
                </AppButton>

                <AppButton
                    variant="primary"
                    size="sm"
                    rounded="lg"
                    type="submit"
                    :loading="processing"
                    :disabled="processing"
                >
                    {{ processing ? 'Saving…' : saveLabel }}
                </AppButton>
            </div>
        </div>
    </div>
</template>