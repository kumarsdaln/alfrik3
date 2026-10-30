
<script setup lang="ts">
import { computed, ref } from 'vue'
import { router, Form } from '@inertiajs/vue3'

import AppInput from '@/components/form/AppInput.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import Heading from '@/components/Heading.vue'
import ProfileCell from '@/components/profile/ProfileCell.vue'
import Button from '@/components/ui/button/Button.vue'

import type {
    ContentParticipant,
    ParticipantRole,
    ParticipantableType,
    ParticipantUser,
    Pagination,
    Profile,
} from '@/types'

interface Props {
    content: {
        id: number
        type: ParticipantableType
        title: string
    }
    users: Pagination<Profile>
    participants: ContentParticipant[]
    roles: Pick<ParticipantRole, 'id' | 'name' | 'slug' | 'description'>[]
    filters?: {
        search?: string
    }
}

const props = defineProps<Props>()

const search = ref(props.filters?.search ?? '')

const selectedRoles = ref<Record<number, number>>({})

const contentUrl = computed(
    () =>
        `/admin/content/${props.content.type}/${props.content.id}/participants`,
)

const participantRoleOptions = computed(() =>
    props.roles.map((role) => ({
        label: role.name,
        value: role.id,
    })),
)

const getRole = (userId: number): number | undefined =>
    selectedRoles.value[userId] ?? props.roles[0]?.id

function setRole(
    userId: number,
    value: string | number | undefined,
) {
    if (value === undefined || value === '') {
        return
    }

    selectedRoles.value[userId] = Number(value)
}

function isSelected(userId: number): boolean {
    return props.participants.some(
        (participant) => participant.user?.id === userId,
    )
}

function searchUsers() {
    router.get(
        contentUrl.value,
        { search: search.value || undefined },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    )
}

function goToPage(url: string | null) {
    if (!url) {
        return
    }

    router.visit(url, {
        preserveState: true,
        preserveScroll: true,
    })
}

function removeParticipant(participant: ContentParticipant) {
    if (
        !confirm(
            `Remove ${participant.user.name} from "${props.content.title}"?`,
        )
    ) {
        return
    }

    router.delete(`${contentUrl.value}/${participant.id}`, {
        preserveScroll: true,
    })
}
</script>

<template>
    <div class="mt-4 space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <Heading
                title="Participants"
                :description="`Manage participants for ${content.title}`"
            />

            <div class="flex shrink-0 items-center gap-2 rounded-lg border px-4 py-2">
                <div>
                    <p class="text-xs text-muted-foreground">
                        Selected participants
                    </p>
                    <p class="text-xl font-semibold">
                        {{ participants.length }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid gap-6 lg:grid-cols-2">
            <!-- LEFT: ADD PARTICIPANTS -->
            <section class="min-w-0 rounded-lg border bg-card">
                <div class="border-b p-5 sm:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold">
                                Add Participants
                            </h2>

                            <p class="mt-1 text-sm text-muted-foreground">
                                Search users and assign them a role.
                            </p>
                        </div>

                        <div
                            class="flex h-8 min-w-8 items-center justify-center rounded-full bg-muted px-2 text-sm font-medium"
                        >
                            {{ users.meta.total }}
                        </div>
                    </div>

                    <!-- Search -->
                    <form
                        class="mt-5 flex flex-col gap-2 sm:flex-row"
                        @submit.prevent="searchUsers"
                    >
                        <AppInput
                            v-model="search"
                            placeholder="Search by name or email..."
                            class="flex-1"
                        />

                        <Button type="submit" class="w-full sm:w-auto">
                            Search
                        </Button>
                    </form>
                </div>

                <!-- User List -->
                <div class="divide-y">
                    <div
                        v-for="user in users.data"
                        :key="user.id"
                        class="p-4 sm:p-5"
                    >
                        <div
                            class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                        >
                            <!-- User -->
                            <div class="min-w-0 flex-1">
                                <ProfileCell
                                    :profile="user"
                                    show-email
                                    size="sm"
                                />
                            </div>

                            <!-- Already Added -->
                            <div
                                v-if="isSelected(user.id)"
                                class="flex shrink-0 items-center"
                            >
                                <span
                                    class="inline-flex items-center rounded-md bg-muted px-3 py-1.5 text-sm font-medium text-muted-foreground"
                                >
                                    Already Added
                                </span>
                            </div>

                            <!-- Add Form -->
                            <Form
                                v-else
                                :action="contentUrl"
                                method="post"
                                class="flex w-full shrink-0 flex-col gap-2 sm:w-auto sm:flex-row sm:items-center"
                            >
                                <AppSelect
                                    :name="`role_${user.id}`"
                                    :options="participantRoleOptions"
                                    :default-value="getRole(user.id)"
                                    class="w-full sm:w-36"
                                    @update:model-value="setRole(user.id, $event)"
                                />

                                <input
                                    type="hidden"
                                    name="user_id"
                                    :value="user.id"
                                />

                                <input
                                    type="hidden"
                                    name="participant_role_id"
                                    :value="getRole(user.id)"
                                />

                                <Button
                                    type="submit"
                                    class="w-full sm:w-auto"
                                    :disabled="!roles.length"
                                >
                                    Add
                                </Button>
                            </Form>
                        </div>
                    </div>

                    <!-- Empty users -->
                    <div
                        v-if="!users.data.length"
                        class="p-10 text-center"
                    >
                        <p class="text-sm font-medium">
                            No users found
                        </p>

                        <p class="mt-1 text-sm text-muted-foreground">
                            Try searching with a different name or email.
                        </p>
                    </div>

                    <!-- No active roles -->
                    <div
                        v-if="!roles.length"
                        class="border-t bg-muted/20 p-4 text-sm text-muted-foreground"
                    >
                        No active participant roles are available. Activate a
                        role before assigning participants.
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    v-if="users.meta.last_page > 1"
                    class="flex flex-col gap-3 border-t p-4 sm:flex-row sm:items-center sm:justify-between"
                >
                    <p class="text-sm text-muted-foreground">
                        Page {{ users.meta.current_page }}
                        of {{ users.meta.last_page }}
                    </p>

                    <div class="flex items-center gap-1 overflow-x-auto">
                        <Button
                            v-for="link in users.meta.links"
                            :key="link.label"
                            type="button"
                            size="sm"
                            :variant="link.active ? 'default' : 'outline'"
                            :disabled="!link.url"
                            @click="goToPage(link.url)"
                        >
                            <span v-html="link.label" />
                        </Button>
                    </div>
                </div>
            </section>

            <!-- RIGHT: SELECTED PARTICIPANTS -->
            <section class="min-w-0 rounded-lg border bg-card">
                <div class="border-b p-5 sm:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold">
                                Selected Participants
                            </h2>

                            <p class="mt-1 text-sm text-muted-foreground">
                                Users currently assigned to this content.
                            </p>
                        </div>

                        <div
                            class="flex h-8 min-w-8 items-center justify-center rounded-full bg-primary px-2 text-sm font-medium text-primary-foreground"
                        >
                            {{ participants.length }}
                        </div>
                    </div>
                </div>

                <!-- Selected list -->
                <div class="divide-y">
                    <div
                        v-for="participant in participants"
                        :key="participant.id"
                        class="p-4 sm:p-5"
                    >
                        <div
                            class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                        >
                            <!-- User -->
                            <div class="min-w-0 flex-1">
                                <ProfileCell
                                    :profile="participant.user"
                                    show-email
                                    size="sm"
                                />

                                <p
                                    v-if="participant.contribution"
                                    class="mt-2 line-clamp-2 text-sm text-muted-foreground"
                                >
                                    {{ participant.contribution }}
                                </p>
                            </div>

                            <!-- Role and actions -->
                            <div
                                class="flex w-full shrink-0 items-center gap-2 sm:w-auto"
                            >
                                <span
                                    class="flex-1 rounded-md bg-muted px-3 py-2 text-center text-sm font-medium sm:flex-none"
                                >
                                    {{ participant.role.name }}
                                </span>

                                <Button
                                    type="button"
                                    variant="destructive"
                                    size="sm"
                                    @click="removeParticipant(participant)"
                                >
                                    Remove
                                </Button>
                            </div>
                        </div>

                        <div
                            v-if="participant.is_primary"
                            class="mt-3"
                        >
                            <span
                                class="inline-flex items-center rounded-md border px-2 py-1 text-xs font-medium"
                            >
                                Primary participant
                            </span>
                        </div>
                    </div>

                    <!-- Empty selected participants -->
                    <div
                        v-if="!participants.length"
                        class="flex min-h-60 flex-col items-center justify-center p-10 text-center"
                    >
                        <p class="text-sm font-medium">
                            No participants selected
                        </p>

                        <p class="mt-1 max-w-sm text-sm text-muted-foreground">
                            Search for users on the left and click
                            <span class="font-medium">Add</span>
                            to assign them to this content.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>
