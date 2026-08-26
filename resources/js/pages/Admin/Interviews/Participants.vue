<script setup lang="ts">
import {
    onBeforeUnmount,
    onMounted,
    ref,
} from 'vue'

import { Form } from '@inertiajs/vue3'
import type { FormDataConvertible } from '@inertiajs/core'

import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'

import AppButton from '@/components/ui/AppButton.vue'
import ParticipantSelector from './Partials/ParticipantSelector.vue'

import {
    save as saveParticipants,
} from '@/actions/App/Http/Controllers/Admin/Interview/ParticipantController'

import {
    index as usersIndex,
} from '@/actions/App/Http/Controllers/Admin/User/UserController'


/*
|--------------------------------------------------------------------------
| Types
|--------------------------------------------------------------------------
*/

type ParticipantRole =
    | 'interviewer'
    | 'interviewee'


interface InterviewUser {
    name: string
    email: string
    avatar?: string | null
}


interface InterviewParticipant {
    user_id: number
    role: ParticipantRole
    user?: InterviewUser | null
}


interface Interview {
    id: number
    participants?: InterviewParticipant[]
}


interface UserOption {
    value: number
    label: string
    name: string
    email: string
    avatar?: string | null
}


interface SelectedParticipant {
    user_id: number
    label: string
    role: ParticipantRole
    avatar?: string | null
}


interface UserApiItem {
    id: number
    name: string
    email: string
    avatar?: string | null
}


interface UserApiResponse {
    data: UserApiItem[]
    current_page: number
    next_page_url: string | null
}


/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/

interface Props {
    interview: Interview
}

const props = defineProps<Props>()


/*
|--------------------------------------------------------------------------
| Participants
|--------------------------------------------------------------------------
*/

const participants = ref<SelectedParticipant[]>(
    props.interview.participants?.map(
        (participant): SelectedParticipant => ({
            user_id: participant.user_id,

            label:
                participant.user?.name ??
                `User #${participant.user_id}`,

            role: participant.role,

            avatar:
                participant.user?.avatar ?? null,
        }),
    ) ?? [],
)


/*
|--------------------------------------------------------------------------
| User Search State
|--------------------------------------------------------------------------
*/

const users = ref<UserOption[]>([])

const page = ref(1)

const search = ref('')

const loading = ref(false)

const hasMore = ref(true)


/*
|--------------------------------------------------------------------------
| Request Control
|--------------------------------------------------------------------------
*/

let searchTimeout: ReturnType<typeof setTimeout> | null = null

let requestController: AbortController | null = null

let requestId = 0


/*
|--------------------------------------------------------------------------
| Fetch Users
|--------------------------------------------------------------------------
*/

async function fetchUsers(
    searchValue = search.value,
    reset = false,
): Promise<void> {
    /*
    |--------------------------------------------------------------------------
    | Prevent duplicate pagination requests
    |--------------------------------------------------------------------------
    */

    if (loading.value && !reset) {
        return
    }

    if (!hasMore.value && !reset) {
        return
    }


    /*
    |--------------------------------------------------------------------------
    | Reset search
    |--------------------------------------------------------------------------
    */

    if (reset) {
        requestController?.abort()

        users.value = []

        page.value = 1

        hasMore.value = true
    }


    /*
    |--------------------------------------------------------------------------
    | Request ID
    |--------------------------------------------------------------------------
    */

    const currentRequestId = ++requestId

    const controller = new AbortController()

    requestController = controller

    loading.value = true


    try {
        /*
        |--------------------------------------------------------------------------
        | Wayfinder URL
        |--------------------------------------------------------------------------
        */

        const action = usersIndex({
            query: {
                search: searchValue || undefined,
                page: page.value,
            },
        })


        /*
        |--------------------------------------------------------------------------
        | Request JSON
        |--------------------------------------------------------------------------
        */

        const response = await fetch(
            action.url,
            {
                method: 'GET',

                signal: controller.signal,

                headers: {
                    Accept: 'application/json',
                },
            },
        )


        /*
        |--------------------------------------------------------------------------
        | HTTP Error
        |--------------------------------------------------------------------------
        */

        if (!response.ok) {
            throw new Error(
                `Failed to fetch users: ${response.status}`,
            )
        }


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        const result =
            await response.json() as UserApiResponse


        /*
        |--------------------------------------------------------------------------
        | Ignore stale response
        |--------------------------------------------------------------------------
        */

        if (currentRequestId !== requestId) {
            return
        }


        /*
        |--------------------------------------------------------------------------
        | Convert users
        |--------------------------------------------------------------------------
        */

        const incomingUsers: UserOption[] =
            result.data.map(
                (user): UserOption => ({
                    value: user.id,
                    label: user.name,
                    name: user.name,
                    email: user.email,
                    avatar: user.avatar ?? null,
                }),
            )


        /*
        |--------------------------------------------------------------------------
        | Remove duplicates
        |--------------------------------------------------------------------------
        */

        const existingIds = new Set(
            users.value.map(
                user => user.value,
            ),
        )

        const uniqueUsers =
            incomingUsers.filter(
                user =>
                    !existingIds.has(user.value),
            )


        users.value.push(...uniqueUsers)


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        hasMore.value =
            result.next_page_url !== null

        if (hasMore.value) {
            page.value++
        }

    } catch (error) {
        /*
        |--------------------------------------------------------------------------
        | Ignore aborted requests
        |--------------------------------------------------------------------------
        */

        if (
            error instanceof DOMException &&
            error.name === 'AbortError'
        ) {
            return
        }

        console.error(
            'Failed to fetch users:',
            error,
        )
    } finally {
        if (currentRequestId === requestId) {
            loading.value = false

            if (requestController === controller) {
                requestController = null
            }
        }
    }
}


/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

function handleSearch(
    value: string,
): void {
    search.value = value

    if (searchTimeout !== null) {
        clearTimeout(searchTimeout)
    }

    searchTimeout = setTimeout(() => {
        fetchUsers(
            value,
            true,
        )

        searchTimeout = null
    }, 400)
}


/*
|--------------------------------------------------------------------------
| Load More
|--------------------------------------------------------------------------
*/

function loadMore(): void {
    if (
        loading.value ||
        !hasMore.value
    ) {
        return
    }

    fetchUsers()
}


/*
|--------------------------------------------------------------------------
| Form Transform
|--------------------------------------------------------------------------
*/

function transformData(
    data: Record<string, FormDataConvertible>,
): Record<string, FormDataConvertible> {
    return {
        ...data,

        participants: participants.value.map(
            participant => ({
                user_id: participant.user_id,
                role: participant.role,
            }),
        ),
    }
}


/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/

function goBack(): void {
    window.history.back()
}


/*
|--------------------------------------------------------------------------
| Lifecycle
|--------------------------------------------------------------------------
*/

onMounted(() => {
    fetchUsers()
})


onBeforeUnmount(() => {
    requestId++

    requestController?.abort()

    requestController = null

    if (searchTimeout !== null) {
        clearTimeout(searchTimeout)

        searchTimeout = null
    }
})
</script>


<template>
    <Form
        :action="saveParticipants(props.interview.id)"
        :transform="transformData"
    >
        <template #default="{ errors, processing }">
            <AppFormLayout
                title="Manage Interview Participants"
                description="Add participants and assign their roles in this interview."
            >
                <ParticipantSelector
                    v-model="participants"
                    :options="users"
                    :loading="loading"
                    :has-more="hasMore"
                    :error="errors.participants"
                    @search="handleSearch"
                    @load-more="loadMore"
                />

                <template #footer>
                    <div
                        class="
                            flex flex-col-reverse gap-3
                            sm:flex-row
                            sm:items-center
                            sm:justify-end
                        "
                    >
                        <AppButton
                            variant="cancel"
                            type="button"
                            :disabled="processing"
                            @click="goBack"
                        >
                            Cancel
                        </AppButton>

                        <AppButton
                            variant="submit"
                            type="submit"
                            :loading="processing"
                            :disabled="processing"
                        >
                            Save Participants
                        </AppButton>
                    </div>
                </template>
            </AppFormLayout>
        </template>
    </Form>
</template>