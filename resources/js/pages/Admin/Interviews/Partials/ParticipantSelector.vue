<script setup lang="ts">
    import { ref, watch } from 'vue'

    import Avatar from '@/components/profile/Avatar.vue'
    import AppHeading from '@/Components/Ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'

    import {
        Check,
        Plus,
        X,
        Search,
        Users,
        UserPlus,
    } from '@lucide/vue'

    interface UserOption {
        value: number
        label: string
        avatar?: string | null
    }

    interface Participant {
        user_id: number
        label: string
        role: 'interviewer' | 'interviewee'
        avatar?: string | null
    }

    interface Props {
        modelValue?: Participant[]
        options?: UserOption[]
        loading?: boolean
        hasMore?: boolean
        error?: string
    }

    const props = withDefaults(
        defineProps<Props>(),
        {
            modelValue: () => [],
            options: () => [],
            loading: false,
            hasMore: false,
        },
    )

    const emit = defineEmits<{
        (e: 'update:modelValue', value: Participant[]): void
        (e: 'search', value: string): void
        (e: 'load-more'): void
    }>()

    const search = ref('')

    watch(search, (value) => {
        emit('search', value)
    })

    function isAdded(userId: number): boolean {
        return props.modelValue.some(
            participant => participant.user_id === userId,
        )
    }

    function addUser(user: UserOption): void {
        if (isAdded(user.value)) return

        emit('update:modelValue', [
            ...props.modelValue,
            {
                user_id: user.value,
                label: user.label,
                role: 'interviewee',
                avatar: user.avatar,
            },
        ])
    }

    function removeUser(userId: number): void {
        emit(
            'update:modelValue',
            props.modelValue.filter(
                participant => participant.user_id !== userId,
            ),
        )
    }

    function updateRole(
        userId: number,
        role: Participant['role'],
    ): void {
        emit(
            'update:modelValue',
            props.modelValue.map(participant =>
                participant.user_id === userId
                    ? {
                        ...participant,
                        role,
                    }
                    : participant,
            ),
        )
    }

    function handleRoleChange(
        userId: number,
        event: Event,
    ): void {
        const target = event.target as HTMLSelectElement

        updateRole(
            userId,
            target.value as Participant['role'],
        )
    }

    function handleScroll(event: Event): void {
        const element = event.target as HTMLElement

        const reachedBottom =
            element.scrollTop + element.clientHeight
            >= element.scrollHeight - 30

        if (
            reachedBottom &&
            props.hasMore &&
            !props.loading
        ) {
            emit('load-more')
        }
    }
</script>

<template>
    <div class="w-full max-w-6xl">

        <p v-if="error" class="mb-3 text-sm text-red-500">
            {{ error }}
        </p>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

            <!-- Available Users -->
            <section class="
                    overflow-hidden rounded-2xl border
                    border-zinc-200 bg-white shadow-sm
                    dark:border-zinc-800 dark:bg-zinc-900
                ">
                <div class="
                        border-b border-zinc-200 p-4
                        dark:border-zinc-800
                    ">
                    <div class="mb-4 flex items-center gap-3">

                        <div class="
                                flex h-10 w-10 items-center justify-center
                                rounded-xl bg-brand/10 text-brand
                            ">
                            <Users class="h-5 w-5" />
                        </div>

                        <div>
                            <AppHeading tag="h3" font="redhat" size="sm" weight="semibold" leading="tight"
                                tracking="normal">
                                Available Users
                            </AppHeading>
                            <AppText tag="p" font="redhat" size="xs" color="muted" leading="normal" class="mt-1">
                                Search and add participants
                            </AppText>
                        </div>

                    </div>

                    <!-- Search -->
                    <div class="relative">

                        <Search class="
                                pointer-events-none absolute
                                left-3 top-1/2 h-4 w-4
                                -translate-y-1/2
                                text-zinc-400
                            " />

                        <input v-model="search" type="search" placeholder="Search name or email..." class="
                                w-full rounded-xl border
                                border-zinc-200 bg-zinc-50
                                py-2.5 pl-10 pr-3
                                text-sm text-zinc-900
                                outline-none

                                placeholder:text-zinc-400

                                focus:border-brand
                                focus:ring-2
                                focus:ring-brand/20

                                dark:border-zinc-700
                                dark:bg-zinc-800
                                dark:text-zinc-100
                            " />

                    </div>
                </div>


                <!-- Users -->
                <div class="h-[360px] overflow-y-auto p-2" @scroll.passive="handleScroll">
                    <button v-for="user in options" :key="user.value" type="button" :disabled="isAdded(user.value)"
                        class="
                            group flex w-full items-center gap-3
                            rounded-xl p-2.5 text-left
                            transition-colors

                            hover:bg-zinc-50
                            dark:hover:bg-zinc-800
                        " :class="{
                            'cursor-default opacity-60':
                                isAdded(user.value),
                        }" @click="addUser(user)">
                        <Avatar :name="user.label" :image="user.avatar" size="h-9 w-9" />

                        <div class="min-w-0 flex-1">
                            <AppText tag="p" font="redhat" size="sm" weight="medium" color="default" leading="tight"
                                truncate>
                                {{ user.label }}
                            </AppText>
                            <AppText tag="p" font="redhat" size="xs" color="muted" leading="normal" class="mt-1">
                                User #{{ user.value }}
                            </AppText>
                        </div>


                        <!-- Added -->
                        <span v-if="isAdded(user.value)" class="
                                flex h-8 w-8 items-center justify-center
                                rounded-full
                                bg-emerald-500/10
                                text-emerald-500
                            ">
                            <Check class="h-4 w-4" :stroke-width="2.5" />
                        </span>


                        <!-- Add -->
                        <span v-else class="
                                flex h-8 w-8 items-center justify-center
                                rounded-full bg-brand/10 text-brand
                                transition-transform
                                group-hover:scale-105
                            ">
                            <Plus class="h-4 w-4" />
                        </span>
                    </button>


                    <!-- Loading -->
                    <div v-if="loading" class="
                            flex items-center justify-center
                            gap-2 py-5 text-sm text-zinc-500
                        ">
                        <span class="
                                h-4 w-4 animate-spin rounded-full
                                border-2 border-zinc-300
                                border-t-brand
                            " />

                        Loading users...
                    </div>


                    <!-- Empty -->
                    <div v-else-if="!options.length" class="
                            flex h-48 items-center justify-center
                            px-6 text-center
                        ">
                        <div>
                            <Search class="
                                    mx-auto h-7 w-7
                                    text-zinc-300
                                    dark:text-zinc-600
                                " />

                            <AppHeading tag="h4" font="redhat" size="sm" weight="medium" leading="tight" class="mt-3">
                                No users found
                            </AppHeading>

                            <AppText size="xs" color="muted" class="mt-1">
                                Try another search term.
                            </AppText>
                        </div>
                    </div>
                </div>
            </section>


            <!-- Selected Participants -->
            <section class="
                    overflow-hidden rounded-2xl border
                    border-zinc-200 bg-white shadow-sm
                    dark:border-zinc-800 dark:bg-zinc-900
                ">
                <div class="
                        flex items-center justify-between
                        border-b border-zinc-200 p-4
                        dark:border-zinc-800
                    ">
                    <div class="flex items-center gap-3">

                        <div class="
                                flex h-10 w-10 items-center justify-center
                                rounded-xl
                                bg-emerald-500/10
                                text-emerald-500
                            ">
                            <UserPlus class="h-5 w-5" />
                        </div>

                        <div class="min-w-0">
                            <AppHeading tag="h3" font="redhat" size="sm" weight="semibold" leading="tight"
                                tracking="normal">
                                Selected Participants
                            </AppHeading>

                            <AppText size="xs" color="muted" class="mt-1">
                                Assign participant roles
                            </AppText>
                        </div>

                    </div>

                    <span class="
                            rounded-full bg-brand/10
                            px-2.5 py-1
                            text-xs font-semibold text-brand
                        ">
                        {{ modelValue.length }}
                    </span>
                </div>


                <!-- Selected List -->
                <div v-if="modelValue.length" class="
                        h-[430px] space-y-2
                        overflow-y-auto p-3
                    ">
                    <div v-for="participant in modelValue" :key="participant.user_id" class="
                            flex items-center gap-3
                            rounded-xl border
                            border-zinc-200 bg-zinc-50 p-3

                            dark:border-zinc-700
                            dark:bg-zinc-800
                        ">
                        <Avatar :name="participant.label" :image="participant.avatar" size="h-9 w-9" />

                        <div class="min-w-0 flex-1">
                            <AppText tag="p" font="redhat" size="sm" weight="medium" color="default" leading="tight"
                                truncate>
                                {{ participant.label }}
                            </AppText>

                            <AppText tag="p" font="redhat" size="xs" color="muted" class="mt-1">
                                User #{{ participant.user_id }}
                            </AppText>
                        </div>


                        <!-- Role -->
                        <select :value="participant.role" class="
                                rounded-lg border
                                border-zinc-200 bg-white
                                px-2 py-1.5 text-xs
                                text-zinc-800 outline-none

                                focus:border-brand
                                focus:ring-2
                                focus:ring-brand/20

                                dark:border-zinc-700
                                dark:bg-zinc-900
                                dark:text-zinc-100
                            " @change="
                                handleRoleChange(
                                    participant.user_id,
                                    $event,
                                )
                                ">
                            <option value="interviewer">
                                Interviewer
                            </option>

                            <option value="interviewee">
                                Interviewee
                            </option>
                        </select>


                        <!-- Remove -->
                        <button type="button" title="Remove participant" class="
                                flex h-8 w-8 shrink-0
                                items-center justify-center
                                rounded-full

                                bg-red-500/10
                                text-red-500

                                transition-colors

                                hover:bg-red-500/20
                                hover:text-red-600
                            " @click="
                                removeUser(
                                    participant.user_id,
                                )
                                ">
                            <X class="h-4 w-4" />
                        </button>
                    </div>
                </div>


                <!-- Empty -->
                <div v-else class="
        flex h-[430px]
        items-center justify-center
        px-6 text-center
    ">
                    <div>
                        <div class="
                mx-auto
                flex h-14 w-14
                items-center justify-center
                rounded-2xl
                bg-zinc-100
                text-zinc-400

                dark:bg-zinc-800
                dark:text-zinc-500
            ">
                            <UserPlus class="h-6 w-6" />
                        </div>

                        <AppHeading tag="h4" font="redhat" size="sm" weight="medium" leading="tight" class="mt-4">
                            No participants selected
                        </AppHeading>

                        <AppText tag="p" font="redhat" size="xs" color="muted" leading="relaxed"
                            class="mx-auto mt-1 max-w-xs">
                            Choose users from the available list and assign their interview roles.
                        </AppText>
                    </div>
                </div>
            </section>

        </div>
    </div>
</template>