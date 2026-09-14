<script setup lang="ts">
    import { Form, router } from '@inertiajs/vue3'
    import { ref } from 'vue'

    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'

    import {
        index as participantsIndex,
        store as participantsStore,
        destroy as participantDestroy,
    } from '@/routes/admin/interviews/participants'

    import { edit as interviewEdit } from '@/routes/admin/interviews'

    import type {
        InterviewParticipantRole,
        InterviewParticipantRoleOption,
        Pagination,
        Profile,
        User,
    } from '@/types'
import ProfileCell from '@/components/profile/ProfileCell.vue'

    interface Participant {
        id: number
        role: InterviewParticipantRoleOption
        user: Profile
    }

    const props = defineProps<{
        interview: {
            id: number
            title: string
        }

        users: Pagination<Profile>

        participants: Participant[]

        participantRoles: InterviewParticipantRoleOption[]
    }>()

    const search = ref('')

    const selectedRoles = ref<
        Record<number, InterviewParticipantRole>
    >({})

    const isSelected = (userId: number) => {
        return props.participants.some(
            participant => participant.user?.id === userId
        )
    }

    const getRole = (userId: number): InterviewParticipantRole => {
        return (
            selectedRoles.value[userId]
            ?? props.participantRoles[0]?.value
        )
    }

    const setRole = (
        userId: number,
        value: string | number | undefined
    ) => {
        if (!value) {
            return
        }

        selectedRoles.value[userId] =
            value as InterviewParticipantRole
    }

    const searchUsers = () => {
        router.get(
            participantsIndex(props.interview.id).url,
            {
                search: search.value || undefined,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        )
    }

    const goToPage = (url: string | null) => {
        if (!url) {
            return
        }

        router.visit(url, {
            preserveState: true,
            preserveScroll: true,
        })
    }

    const backToInterview = () => {
        router.visit(
            interviewEdit(props.interview.id).url
        )
    }
</script>

<template>
    <div class="space-y-6 mt-4">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <Heading title="Participants" :description="`Manage participants for ${interview.title}`" />

            <Button type="button" variant="outline" class="w-full sm:w-auto" @click="backToInterview">
                Back to Interview
            </Button>
        </div>

        <!-- Main Content -->
        <div class="grid gap-6 lg:grid-cols-2">
            <!-- ====================================================== -->
            <!-- LEFT: ADD PARTICIPANTS -->
            <!-- ====================================================== -->
            <section class="rounded-lg border bg-card">
                <!-- Header -->
                <div class="border-b p-5 sm:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold">
                                Add Participants
                            </h2>

                            <p class="mt-1 text-sm text-muted-foreground">
                                Search users and add them to this interview.
                            </p>
                        </div>

                        <div
                            class="flex h-8 min-w-8 items-center justify-center rounded-full bg-muted px-2 text-sm font-medium">
                            {{ users.meta.total }}
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="mt-5 flex flex-col gap-2 sm:flex-row">
                        <AppInput v-model="search" placeholder="Search by name or email..." class="flex-1"
                            @keyup.enter="searchUsers" />

                        <Button type="button" class="w-full sm:w-auto" @click="searchUsers">
                            Search
                        </Button>
                    </div>
                </div>

                <!-- User List -->
                <div class="divide-y">
                    <div v-for="user in users.data" :key="user.id" class="p-4 sm:p-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <!-- User -->
                            <div class="min-w-0 flex-1">
                                <ProfileCell :profile="user" show-email size="sm" />
                            </div>

                            <!-- Already Added -->
                            <div v-if="isSelected(user.id)" class="flex shrink-0 items-center">
                                <span
                                    class="inline-flex items-center rounded-md bg-muted px-3 py-1.5 text-sm font-medium text-muted-foreground">
                                    Already Added
                                </span>
                            </div>

                            <!-- Add Form -->
                            <Form v-else v-bind="participantsStore.form(interview.id)"
                                class="flex w-full shrink-0 flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
                                <AppSelect :name="`role_${user.id}`" :options="participantRoles"
                                    :default-value="getRole(user.id)" class="w-full sm:w-36"
                                    @update:model-value="setRole(user.id, $event)" />

                                <input type="hidden" name="user_id" :value="user.id" />

                                <input type="hidden" name="role" :value="getRole(user.id)" />

                                <Button type="submit" class="w-full sm:w-auto">
                                    Add
                                </Button>
                            </Form>
                        </div>
                    </div>

                    <!-- Empty -->
                    <div v-if="!users.data.length" class="p-10 text-center">
                        <p class="text-sm font-medium">
                            No users found
                        </p>

                        <p class="mt-1 text-sm text-muted-foreground">
                            Try searching with a different name or email.
                        </p>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="users.meta.last_page > 1"
                    class="flex flex-col gap-3 border-t p-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-muted-foreground">
                        Page {{ users.meta.current_page }}
                        of {{ users.meta.last_page }}
                    </p>

                    <div class="flex items-center gap-1 overflow-x-auto">
                        <Button v-for="link in users.meta.links" :key="link.label" type="button" size="sm" :variant="link.active
                                ? 'default'
                                : 'outline'
                            " :disabled="!link.url" @click="goToPage(link.url)">
                            <span v-html="link.label" />
                        </Button>
                    </div>
                </div>
            </section>

            <!-- ====================================================== -->
            <!-- RIGHT: SELECTED PARTICIPANTS -->
            <!-- ====================================================== -->
            <section class="rounded-lg border bg-card">
                <!-- Header -->
                <div class="border-b p-5 sm:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold">
                                Selected Participants
                            </h2>

                            <p class="mt-1 text-sm text-muted-foreground">
                                Users currently participating in this interview.
                            </p>
                        </div>

                        <div
                            class="flex h-8 min-w-8 items-center justify-center rounded-full bg-primary px-2 text-sm font-medium text-primary-foreground">
                            {{ participants.length }}
                        </div>
                    </div>
                </div>

                <!-- Participants -->
                <div class="divide-y">
                    <div v-for="participant in participants" :key="participant.id" class="p-4 sm:p-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <!-- User -->
                            <ProfileCell :profile="participant.user" show-email size="sm" />

                            <!-- Actions -->
                            <div class="flex w-full shrink-0 items-center gap-2 sm:w-auto">
                                <span
                                    class="flex-1 rounded-md bg-muted px-3 py-2 text-center text-sm font-medium sm:flex-none">
                                    {{ participant.role.label }}
                                </span>

                                <Form :action="participantDestroy(participant.id)" method="delete">
                                    <Button type="submit" variant="destructive" size="sm">
                                        Remove
                                    </Button>
                                </Form>
                            </div>
                        </div>
                    </div>

                    <!-- Empty -->
                    <div v-if="!participants.length"
                        class="flex min-h-60 flex-col items-center justify-center p-10 text-center">
                        <p class="text-sm font-medium">
                            No participants selected
                        </p>

                        <p class="mt-1 max-w-sm text-sm text-muted-foreground">
                            Search for users on the left and click
                            <span class="font-medium">Add</span>
                            to add them to this interview.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>