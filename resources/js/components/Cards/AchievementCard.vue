<script setup lang="ts">
  import { Eye } from '@lucide/vue'

  import Calendar from '@/Icons/Calendar.vue'

  import VerifiedBadge from '@/Components/Profile/VerifiedBadge.vue'
  import AppHeading from '@/Components/Ui/AppHeading.vue'
  import AppText from '@/Components/Ui/AppText.vue'

  import { formatDate } from '@/utils/dateUtils'


  interface Achievement {
    id?: number | string

    title?: string | null
    issuer?: string | null
    description?: string | null

    date?: string | null
    proof_file?: string | null

    is_verified?: boolean
  }


  interface Props {
    achievement: Achievement
  }


  const props = defineProps<Props>()


  const emit = defineEmits<{
    preview: [proofFile: string]
  }>()


  function openProof(): void {
    if (!props.achievement.proof_file) {
      return
    }

    emit(
      'preview',
      props.achievement.proof_file,
    )
  }


  function initials(
    name?: string | null,
  ): string {
    if (!name?.trim()) {
      return 'AC'
    }

    return name
      .trim()
      .split(/\s+/)
      .filter(Boolean)
      .map(word => word.charAt(0))
      .join('')
      .slice(0, 2)
      .toUpperCase()
  }


  function formatAchievementDate(
    date?: string | null,
  ): string | null {
    if (!date) {
      return null
    }

    return formatDate(
      date,
      {
        year: 'numeric',
      },
    )
  }
</script>


<template>
  <article class="
            group/card
            relative

            h-full
            w-full

            rounded-2xl
            border
            border-zinc-200/70

            p-5

            transition-all
            duration-300

            hover:border-zinc-300
            hover:bg-zinc-50/50

            dark:border-zinc-800/80
            dark:hover:border-zinc-700
            dark:hover:bg-zinc-900/30
        ">

    <div class="
                flex
                items-start
                gap-4

                sm:gap-5
            ">

      <!-- Proof Preview -->

      <button v-if="achievement.proof_file" type="button" aria-label="Preview achievement proof" class="
                    group/proof
                    relative

                    h-14
                    w-14
                    shrink-0
                    overflow-hidden

                    rounded-xl

                    border
                    border-zinc-200/80

                    bg-zinc-100

                    outline-none

                    transition-all
                    duration-300

                    hover:border-zinc-300

                    focus-visible:ring-2
                    focus-visible:ring-brand/30
                    focus-visible:ring-offset-2

                    dark:border-zinc-800
                    dark:bg-zinc-900
                    dark:hover:border-zinc-700
                    dark:focus-visible:ring-offset-zinc-950
                " @click="openProof">

        <img :src="achievement.proof_file" :alt="`${achievement.title || 'Achievement'} supporting evidence`" class="
                        h-full
                        w-full

                        object-cover

                        grayscale

                        transition-all
                        duration-500

                        group-hover/proof:scale-105
                        group-hover/proof:grayscale-0
                    " />


        <span class="
                        absolute
                        inset-0

                        flex
                        items-center
                        justify-center

                        bg-zinc-950/40

                        opacity-0

                        transition-opacity
                        duration-300

                        group-hover/proof:opacity-100
                        group-focus-visible/proof:opacity-100
                    ">
          <Eye aria-hidden="true" class="h-4 w-4 text-white" />
        </span>

      </button>


      <!-- Initials -->

      <div v-else aria-hidden="true" class="
                    flex
                    h-14
                    w-14
                    shrink-0
                    items-center
                    justify-center

                    rounded-xl

                    border
                    border-zinc-200/80

                    bg-zinc-50

                    dark:border-zinc-800
                    dark:bg-zinc-900
                ">
        <AppText tag="span" font="redhat" size="xs" weight="semibold" tracking="wide" class="
                        text-zinc-400
                        dark:text-zinc-500
                    ">
          {{ initials(achievement.issuer) }}
        </AppText>
      </div>


      <!-- Content -->

      <div class="
                    min-w-0
                    flex-1
                    pt-0.5
                ">

        <!-- Title -->

        <AppHeading tag="h3" font="lora" size="md" weight="semibold" class="
                        text-zinc-900

                        transition-colors
                        duration-300

                        group-hover/card:text-brand

                        dark:text-white
                        dark:group-hover/card:text-brand
                    ">
          {{ achievement.title || 'Untitled Achievement' }}
        </AppHeading>


        <!-- Issuer -->

        <div v-if="achievement.issuer" class="
                        mt-1.5

                        flex
                        items-center
                        gap-1.5
                    ">

          <AppText tag="span" font="redhat" size="xs" weight="medium" color="muted" class="truncate">
            {{ achievement.issuer }}
          </AppText>


          <VerifiedBadge v-if="achievement.is_verified" size="
                            h-3.5
                            w-3.5
                            shrink-0

                            text-zinc-400
                            dark:text-zinc-500
                        " />

        </div>


        <!-- Description -->

        <AppText v-if="achievement.description" tag="p" font="lora" size="sm" leading="relaxed" color="muted" :clamp="3"
          class="mt-4">
          {{ achievement.description }}
        </AppText>


        <!-- Footer -->

        <div v-if="
          achievement.date
          || achievement.proof_file
        " class="
                        mt-5
                        flex
                        items-center
                        justify-between
                        gap-4

                        border-t
                        border-zinc-200/60

                        pt-3

                        dark:border-zinc-800/80
                    ">

          <!-- Date -->

          <div v-if="achievement.date" class="
                            flex
                            items-center
                            gap-1.5

                            text-zinc-400
                            dark:text-zinc-500
                        ">

            <Calendar aria-hidden="true" class="
                                h-3.5
                                w-3.5
                                shrink-0
                            " />


            <AppText tag="span" font="redhat" size="xs" color="muted">
              {{
                formatAchievementDate(
                  achievement.date,
                )
              }}
            </AppText>

          </div>


          <!-- Proof Action -->

          <button v-if="achievement.proof_file" type="button" class="
                            ml-auto

                            font-redhat
                            text-[11px]
                            font-semibold
                            uppercase
                            tracking-wide

                            text-zinc-400

                            transition-colors
                            duration-300

                            hover:text-brand

                            focus-visible:outline-none
                            focus-visible:text-brand

                            dark:text-zinc-500
                            dark:hover:text-brand
                        " @click="openProof">
            View proof
          </button>

        </div>

      </div>

    </div>

  </article>
</template>