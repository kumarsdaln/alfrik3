<script setup lang="ts">
    import {
        Avatar,
        AvatarFallback,
        AvatarImage,
    } from '@/components/ui/avatar';

    import { getInitials } from '@/composables/useInitials';

    interface Props {
        image?: string | null;
        name?: string | null;
        size?: string;
        textSize?: string;
        rounded?: 'full' | 'xl' | 'lg' | 'md';
    }

    const props = withDefaults(
        defineProps<Props>(),
        {
            image: null,
            name: null,
            size: 'size-8',
            textSize: 'text-sm',
            rounded: 'full',
        },
    );

    const roundedClasses = {
        full: 'rounded-full',
        xl: 'rounded-xl',
        lg: 'rounded-lg',
        md: 'rounded-md',
    };
</script>

<template>
    <Avatar :class="[
        props.size,
        roundedClasses[props.rounded],
        'overflow-hidden',
    ]">
        <AvatarImage v-if="props.image" :src="props.image" :alt="props.name || 'User avatar'"
            class="h-full w-full object-cover" />

        <AvatarFallback :class="[
            roundedClasses[props.rounded],
            props.textSize,
            'bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white',
        ]">
            {{ getInitials(props.name ?? '') }}
        </AvatarFallback>
    </Avatar>
</template>