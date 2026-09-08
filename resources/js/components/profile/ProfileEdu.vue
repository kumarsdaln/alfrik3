<script setup lang="ts">
  import { BadgeCheck, Check } from '@lucide/vue'

  import GraduationCap from '@/icons/GraduationCap.vue'

  import AppHeading from '@/Components/Ui/AppHeading.vue'
  import AppText from '@/Components/Ui/AppText.vue'

  import EducationCard from '../Cards/EducationCard.vue'


  interface Education {
    id?: number | string

    institution_name?: string | null
    degree?: string | null
    field_of_study?: string | null

    start_date?: string | null
    end_date?: string | null

    institution_verified?: boolean
  }


  interface Props {
    education?: Education[]
  }


  const props = withDefaults(
    defineProps < Props > (),
    {
      education: () => [],
    },
  )
</script>


<template>
  <section v-if="props.education.length" class="w-full">

    <!-- Header -->

    <div class="
                mb-8
                flex
                items-start
                justify-between
                gap-6

                sm:mb-10
            ">

      <div>

        <AppHeading tag="span" font="redhat" size="xs" weight="bold" tracking="wide" uppercase color="brand" class="
                        mb-2
                        block
                    ">
          Academic Foundation
        </AppHeading>


        <AppHeading tag="h2" font="prata" size="xl" weight="normal" class="
                        tracking-tight
                        text-content-light
                        dark:text-content-dark
                    ">
          Education
        </AppHeading>

      </div>


      <!-- Icon -->

      <div aria-hidden="true" class="
                    flex
                    h-11
                    w-11
                    shrink-0
                    items-center
                    justify-center

                    rounded-full

                    border
                    border-border-light

                    text-content-lightMuted

                    dark:border-border-dark
                    dark:text-content-darkMuted
                ">
        <GraduationCap class="h-5 w-5" />
      </div>

    </div>


    <!-- Timeline -->

    <div class="relative">

      <!-- Vertical Spine -->

      <div aria-hidden="true" class="
                    absolute

                    bottom-5
                    left-[19px]
                    top-5

                    w-px

                    bg-border-light
                    dark:bg-border-dark
                " />


      <!-- Education Items -->

      <div class="space-y-10">

        <article v-for="(item, index) in props.education" :key="item.id
          ?? `${item.institution_name}-${item.start_date}-${index}`
          " class="
                        group
                        relative

                        flex
                        items-start
                        gap-5

                        sm:gap-6
                    ">

          <!-- Timeline Node -->

          <div class="
                            relative
                            z-10

                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center

                            rounded-full
                            border

                            bg-canvas-light

                            transition-all
                            duration-300

                            dark:bg-canvas-dark
                        " :class="!item.end_date
                            ? `
                                    border-brand/40
                                    text-brand
                                    dark:border-brand/50
                                `
                            : `
                                    border-border-light
                                    text-content-lightMuted
                                    dark:border-border-dark
                                    dark:text-content-darkMuted
                                `
                          ">

            <!-- Completed -->

            <Check v-if="item.end_date" aria-hidden="true" class="
                                h-3.5
                                w-3.5

                                transition-colors
                                duration-300

                                group-hover:text-content-light
                                dark:group-hover:text-content-dark
                            " />


            <!-- Current -->

            <span v-else aria-hidden="true" class="
                                h-2
                                w-2

                                rounded-full
                                bg-brand
                            " />

          </div>


          <!-- Content -->

          <div class="
                            min-w-0
                            flex-1
                            pt-0.5
                        ">

            <EducationCard :item="item" />


            <!-- Metadata -->

            <div v-if="
              item.institution_verified
              || !item.end_date
            " class="
                                mt-3

                                flex
                                flex-wrap
                                items-center

                                gap-x-4
                                gap-y-2
                            ">

              <!-- Verified Credential -->

              <div v-if="item.institution_verified" class="
                                    flex
                                    items-center
                                    gap-1.5

                                    text-content-lightMuted
                                    dark:text-content-darkMuted
                                ">
                <BadgeCheck aria-hidden="true" class="
                                        h-3.5
                                        w-3.5
                                        shrink-0
                                    " />


                <AppText tag="span" font="redhat" size="xs" color="muted">
                  Verified credential
                </AppText>

              </div>


              <!-- Currently Studying -->

              <div v-if="!item.end_date" class="
                                    flex
                                    items-center
                                    gap-2
                                ">
                <span aria-hidden="true" class="
                                        h-1.5
                                        w-1.5

                                        rounded-full
                                        bg-brand
                                    " />


                <AppText tag="span" font="redhat" size="xs" weight="medium" class="text-brand">
                  Currently studying
                </AppText>

              </div>

            </div>

          </div>

        </article>

      </div>

    </div>

  </section>
</template>