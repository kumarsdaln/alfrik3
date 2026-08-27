<script setup lang="ts">
    interface Props {
        title: string
        description?: string
        align?: 'left' | 'center'
        divider?: boolean
    }

    withDefaults(
        defineProps < Props > (),
        {
            kicker: undefined,
            description: undefined,
            align: 'left',
            divider: false,
        },
    )
</script>
<template>

    <section :class="[
        'mb-16 md:mb-20',
        align === 'center'
            ? 'text-center'
            : 'text-left',
    ]">

        <!-- Top -->
        <div v-if="$slots.top" class="mb-4 flex flex-wrap gap-3" :class="align === 'center'
                ? 'justify-center'
                : ''
            ">
            <slot name="top" />
        </div>

        <!-- Title -->
        <h1 class="
                max-w-4xl
                text-3xl
                font-prata
                font-semibold
                text-content-light
                md:text-5xl
                lg:text-7xl
                dark:text-white
            " :class="align === 'center'
                    ? 'mx-auto'
                    : ''
                ">
            {{ title }}
        </h1>

        <!-- Description -->
        <p v-if="description" class="
                mt-5
                max-w-5xl
                font-lora
                text-2xl
                leading-7
                text-content-lightMuted
                dark:text-content-darkMuted
            " :class="align === 'center'
                    ? 'mx-auto'
                    : ''
                ">
            {{ description }}
        </p>

        <!-- Actions -->
        <div v-if="$slots.actions" class="mt-8 flex flex-wrap gap-3" :class="align === 'center'
                ? 'justify-center'
                : ''
            ">
            <slot name="actions" />
        </div>

        <!-- Divider -->
        <div v-if="divider" class="
                mt-10
                h-px
                w-full
                bg-gradient-to-r
                from-transparent
                via-black/10
                to-transparent
                dark:via-white/10
            " />

    </section>

</template>