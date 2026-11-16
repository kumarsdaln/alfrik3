<script setup lang="ts">
    import { ContentParticipant } from '@/types';
    import { computed } from 'vue'
    import Avatar from '../profile/Avatar.vue';

    const props = withDefaults(defineProps<{
        participants?: ContentParticipant[]
        maxVisible?: number
    }>(), {
        participants: () => [],
        maxVisible: 3,
    })

    const visibleParticipants = computed(() =>
        props.participants.slice(0, props.maxVisible)
    )

    const remainingCount = computed(() =>
        Math.max(0, props.participants.length - props.maxVisible)
    )

    function getName(participant: ContentParticipant): string {
        return participant.user?.name
            || 'Unknown participant'
    }

    function getAvatar(participant: ContentParticipant): string | null {
        return participant.user?.avatar
            || null
    }

    function getRole(participant: ContentParticipant): string {
        return participant.role?.name || 'Participant'
    }

    function getTooltip(participant: ContentParticipant): string {
        return `${getName(participant)} · ${getRole(participant)}`
    }
</script>

<template>
    <div v-if="participants.length" class="flex min-w-0 items-center gap-3">
        <!-- Avatar Stack -->
        <div class="flex shrink-0 items-center -space-x-2">
            <div v-for="participant in visibleParticipants" :key="participant.id"
                class="relative size-9 overflow-hidden rounded-full border-2 border-background bg-muted"
                :title="getTooltip(participant)">
                <Avatar :image="getAvatar(participant)!" :name="getName(participant)" size="md" />
            </div>

            <!-- Remaining Participants -->
            <div v-if="remainingCount"
                class="relative flex size-9 items-center justify-center rounded-full border-2 border-background bg-muted text-xs font-semibold text-muted-foreground"
                :title="`${remainingCount} more participants`">
                +{{ remainingCount }}
            </div>
        </div>

        <!-- Participant Information -->
        <div class="min-w-0">
            <p class="truncate text-sm font-medium text-foreground">
                {{ participants.length }}
                {{ participants.length === 1 ? 'participant' : 'participants' }}
            </p>

            <p class="truncate text-xs text-muted-foreground">
                {{visibleParticipants
                    .map(participant => getRole(participant))
                    .join(', ')
                }}<template v-if="remainingCount"> +{{ remainingCount }}</template>
            </p>
        </div>
    </div>

    <!-- Empty State -->
    <span v-else class="text-sm text-muted-foreground">
        —
    </span>
</template>