<script setup lang="ts">
    import Avatar from '@/components/profile/Avatar.vue'
    import AppHeading from '@/Components/Ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'

    type ParticipantRole =
        | 'interviewer'
        | 'interviewee'

    interface ParticipantUser {
        id: number
        name: string
        email?: string
        avatar?: string | null
    }

    interface Participant {
        id: number
        role: ParticipantRole
        user: ParticipantUser
    }

    interface Props {
        name?: string
        label?: string
        participants?: Participant[]
        error?: string
        required?: boolean
        disabled?: boolean
    }

    const model = defineModel < string | number | null > ({
        default: null,
    })

    withDefaults(
        defineProps < Props > (),
        {
            name: 'asked_by',
            label: 'Asked By',
            participants: () => [],
            required: false,
            disabled: false,
        },
    )
</script>

<template>
    <fieldset class="space-y-3" :disabled="disabled">
        <!-- Label -->

        <legend class="mb-3">
            <AppText tag="span" size="sm" weight="semibold" color="default">
                {{ label }}
                <span v-if="required" class="text-red-500">
                    *
                </span>
            </AppText>
        </legend>


        <!-- Participants -->

        <div v-if="participants.length" 
            class="
                grid grid-cols-1 gap-3
                sm:grid-cols-2
                lg:grid-cols-3">
            <label v-for="participant in participants" :key="participant.id" 
                class="
                    group relative flex cursor-pointer
                    items-center gap-3
                    rounded-xl border
                    bg-white p-3
                    transition-all duration-200
                    hover:border-zinc-300
                    hover:bg-zinc-50

                    dark:bg-zinc-900
                    dark:hover:bg-zinc-800/70" 
                :class="[
                    model === participant.user.id
                        ? [
                            'border-brand',
                            'dark:border-brand',
                            'dark:ring-brand/20',
                        ]
                        : [
                            'border-zinc-200',
                            'dark:border-zinc-700',
                        ],

                    disabled && [
                        'cursor-not-allowed',
                        'opacity-60',
                    ],
                ]">

                <!-- Native Radio -->
                <input v-model="model" type="radio" :name="name" :value="participant.user.id" :required="required"
                    :disabled="disabled" class="sr-only">


                <!-- Avatar -->
                <Avatar :name="participant.user.name" :image="participant.user.avatar" size="h-10 w-10" />


                <!-- User Information -->
                <div class="min-w-0 flex-1">
                    <AppHeading tag="h4" font="redhat" size="sm" weight="semibold" leading="tight" class="truncate">
                        {{ participant.user.name }}
                    </AppHeading>
                    <AppText tag="p" size="xs" color="muted" class="mt-1 capitalize">
                        {{ participant.role }}
                    </AppText>
                </div>


                <!-- Selection Indicator -->
                <div class="
                        flex h-5 w-5 shrink-0
                        items-center justify-center
                        rounded-full border
                        transition-all duration-200
                    " :class="model === participant.user.id
                            ? 'border-brand bg-brand'
                            : 'border-zinc-300 dark:border-zinc-600'
                        ">
                    <span v-if="model === participant.user.id" class="
                            h-2 w-2
                            rounded-full
                            bg-white
                        " />
                </div>
            </label>
        </div>


        <!-- Empty State -->
        <div v-else class="
                rounded-xl border border-dashed
                border-zinc-300

                px-4 py-8
                text-center

                dark:border-zinc-700
            ">
            <AppText size="sm" color="muted">
                No participants available.
            </AppText>
        </div>


        <!-- Validation Error -->
        <AppText v-if="error" tag="p" size="xs" color="danger">
            {{ error }}
        </AppText>
    </fieldset>
</template>