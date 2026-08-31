<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';
    import {
        ArrowUpRight,
        FileText,
        Headphones,
        Play,
    } from '@lucide/vue';
    import { computed, ref } from 'vue';

    import { formatDate } from '@/utils/dateUtils';
    import Avatar from '@/components/profile/Avatar.vue';
import Badge from '../ui/badge/Badge.vue';

    type InterviewFormat = 'written' | 'video' | 'audio';

    interface Participant {
        id: number | string;
        user?: {
            name?: string;
            avatar?: string;
        };
    }

    interface Interview {
        id?: number | string;
        href: string;
        title: string;
        format: InterviewFormat;
        image?: string;
        created_at?: string;
        participants?: Participant[];
    }

    const props = defineProps<{
        interview: Interview;
    }>();

    const FALLBACK_IMAGE = '/frontend/images/placeholder.jpg';
    const MAX_VISIBLE_PARTICIPANTS = 3;

    const imageFailed = ref(false);

    const imageSrc = computed(() => {
        const src = String(props.interview.image || '').trim();

        if (!src || imageFailed.value) {
            return FALLBACK_IMAGE;
        }

        if (/^(https?:)?\/\//i.test(src)) {
            return src;
        }

        return src.startsWith('/') ? src : `/${src}`;
    });

    const formatConfig = computed(() => {
        switch (props.interview.format) {
            case 'video':
                return {
                    label: 'Video Interview',
                    action: 'Watch interview',
                    icon: Play,
                };

            case 'audio':
                return {
                    label: 'Audio Interview',
                    action: 'Listen to interview',
                    icon: Headphones,
                };

            default:
                return {
                    label: 'Written Interview',
                    action: 'Read interview',
                    icon: FileText,
                };
        }
    });

    const participants = computed(() => {
        return (props.interview.participants ?? []).filter(
            (participant) => participant.user?.name,
        );
    });

    const visibleParticipants = computed(() => {
        return participants.value.slice(0, MAX_VISIBLE_PARTICIPANTS);
    });

    const remainingParticipants = computed(() => {
        return Math.max(
            participants.value.length - MAX_VISIBLE_PARTICIPANTS,
            0,
        );
    });

    const participantNames = computed(() => {
        return participants.value
            .map((participant) => participant.user?.name)
            .filter(Boolean)
            .join(', ');
    });

    const handleImageError = () => {
        imageFailed.value = true;
    };
</script>

<template>
    <article class="group w-full">
        <Link :href="interview.href" :aria-label="interview.title" class="block">
            <!-- Image -->
            <div class="relative aspect-[4/3] w-full overflow-hidden bg-neutral-100">
                <img :src="imageSrc" :alt="interview.title" loading="lazy" decoding="async"
                    class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.025]"
                    @error="handleImageError" />

                <!-- Format -->
                <div class="absolute left-3 top-3">
                    <Badge variant="secondary">
                        <component :is="formatConfig.icon" :size="13" :stroke-width="1.8"
                        :class="interview.format === 'video' ? 'fill-current' : ''" />
                        {{ formatConfig.label }}
                    </Badge>
                </div>

                <!-- Video / Audio action -->
                <div v-if="interview.format !== 'written'"
                    class="absolute bottom-3 right-3 flex h-10 w-10 items-center justify-center bg-white text-neutral-950 transition-transform duration-200 group-hover:scale-105">
                    <Play v-if="interview.format === 'video'" :size="15" :stroke-width="1.8"
                        class="ml-0.5 fill-current" />

                    <Headphones v-else :size="16" :stroke-width="1.8" />
                </div>
            </div>

            <!-- Content -->
            <div class="pt-4">
                <!-- Participants -->
                <div v-if="participants.length" class="flex min-w-0 items-center gap-3">
                    <!-- Avatar stack -->
                    <div class="flex shrink-0 -space-x-2">
                        <Avatar v-for="participant in visibleParticipants" :key="participant.id"
                            :name="participant.user?.name" 
                            :image="participant.user?.avatar" 
                            size="size-7"
                            text-size="text-[10px]" 
                            rounded="full" 
                            class="ring-2 ring-white" />

                        <div v-if="remainingParticipants"
                            class="flex size-7 shrink-0 items-center justify-center rounded-full bg-neutral-100 text-[9px] font-medium text-neutral-600 ring-2 ring-white">
                            +{{ remainingParticipants }}
                        </div>
                    </div>

                    <!-- Names -->
                    <p class="min-w-0 truncate text-[11px] leading-5 text-neutral-500" :title="participantNames">
                        {{ participantNames }}
                    </p>
                </div>

                <!-- Title -->
                <h2
                    class="mt-3 max-w-[580px] text-[21px] font-medium leading-[1.2] tracking-[-0.025em] text-neutral-950 transition-colors duration-200 group-hover:text-neutral-600 sm:text-[23px]">
                    {{ interview.title }}
                </h2>

                <!-- Bottom metadata -->
                <div class="mt-4 flex items-center justify-between gap-4">
                    <span
                        class="inline-flex items-center gap-2 border-b border-neutral-300 pb-1 text-[10px] font-semibold uppercase tracking-[0.15em] text-neutral-700 transition-colors group-hover:border-neutral-900 group-hover:text-neutral-950">
                        {{ formatConfig.action }}

                        <ArrowUpRight :size="13" :stroke-width="1.8"
                            class="transition-transform duration-200 group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
                    </span>

                    <span v-if="interview.created_at"
                        class="shrink-0 text-[10px] uppercase tracking-[0.1em] text-neutral-400">
                        {{ formatDate(interview.created_at) }}
                    </span>
                </div>
            </div>
        </Link>
    </article>
</template>