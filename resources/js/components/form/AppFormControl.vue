<script setup lang="ts">
    import { useId } from 'vue'

    import { Label } from '@/components/ui/label'
    import InputError from '@/components/InputError.vue'

    interface Props {
        label?: string
        error?: string
        required?: boolean
        id?: string
    }

    const props = withDefaults(
        defineProps<Props>(),
        {
            required: false,
        },
    )

    const generatedId = useId()

    const inputId = props.id ?? generatedId
</script>

<template>
    <div class="grid gap-2">

        <Label v-if="label" :for="inputId">
            {{ label }}

            <span v-if="required" class="text-destructive">
                *
            </span>
        </Label>

        <slot />

        <InputError v-if="error" :message="error" />

    </div>
</template>