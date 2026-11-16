<script setup lang="ts">
    import { Quote as QuoteIcon } from '@lucide/vue'

    interface Props {
        text: string
        author?: string | null
        role?: string | null
        source?: string | null
    }

    defineProps<Props>()
</script>

<template>
    <figure class="my-8 max-w-4xl">
        <div class="flex items-start gap-4 sm:gap-6">
            <QuoteIcon class="mt-1 size-8 shrink-0 text-muted-foreground/40 sm:size-10" :stroke-width="1.5" />

            <div class="min-w-0 flex-1">
                <blockquote>
                    <p class="text-xl font-medium leading-relaxed tracking-tight text-foreground sm:text-2xl">
                        {{ text }}
                    </p>
                </blockquote>

                <figcaption v-if="author || role || source" class="mt-7 border-l-2 border-foreground/30 pl-4">
                    <p v-if="author" class="text-sm font-semibold text-foreground">
                        {{ author }}
                    </p>

                    <p v-if="role" class="mt-1 text-sm text-muted-foreground">
                        {{ role }}
                    </p>

                    <p v-if="source" class="mt-2 text-xs text-muted-foreground">
                        {{ source }}
                    </p>
                </figcaption>
            </div>
        </div>
    </figure>
</template>
