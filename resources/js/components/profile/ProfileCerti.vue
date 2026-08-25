<script setup lang="ts">
    import {
        onBeforeUnmount,
        ref,
        watch,
    } from 'vue'

    import { X } from '@lucide/vue'

    import Award from '@/Icons/Award.vue'

    import AppHeading from '@/Components/Ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'

    import CertificateCard from '../Cards/CertificateCard.vue'
import { Certification } from '@/types/profile'


    interface Props {
        certifications?: Certification[]
    }


    const props = withDefaults(
        defineProps<Props>(),
        {
            certifications: () => [],
        },
    )


    const showImage = ref(false)

    const activeImage = ref<string | null>(null)


    function openImage(
        image?: string | null,
    ): void {
        if (!image) {
            return
        }

        activeImage.value = image
        showImage.value = true
    }


    function closeImage(): void {
        showImage.value = false
        activeImage.value = null
    }


    function handleKeydown(
        event: KeyboardEvent,
    ): void {
        if (
            event.key === 'Escape'
            && showImage.value
        ) {
            closeImage()
        }
    }


    watch(
        showImage,
        (isOpen) => {
            if (typeof document === 'undefined') {
                return
            }

            document.body.style.overflow =
                isOpen
                    ? 'hidden'
                    : ''

            if (isOpen) {
                window.addEventListener(
                    'keydown',
                    handleKeydown,
                )
            }
            else {
                window.removeEventListener(
                    'keydown',
                    handleKeydown,
                )
            }
        },
    )


    onBeforeUnmount(() => {
        window.removeEventListener(
            'keydown',
            handleKeydown,
        )

        if (typeof document !== 'undefined') {
            document.body.style.overflow = ''
        }
    })
</script>


<template>
    <section v-if="props.certifications.length" class="w-full">

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
                    Credentials
                </AppHeading>


                <AppHeading tag="h2" font="prata" size="xl" weight="normal">
                    Certifications
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
                    dark:border-border-dark

                    text-content-lightMuted
                    dark:text-content-darkMuted
                ">
                <Award class="h-5 w-5" />
            </div>

        </div>


        <!-- Certification Grid -->

        <div class="
                grid
                grid-cols-1

                gap-6

                md:grid-cols-2
                md:gap-8
            ">

            <article v-for="(certificate, index) in props.certifications" :key="certificate.id
                ?? certificate.name
                ?? certificate.title
                ?? index
                " class="
                    animate-certificate
                    min-w-0
                    w-full
                " :style="{
                    animationDelay: `${index * 60}ms`,
                }">

                <CertificateCard :certificate="certificate" :i="index" @preview="openImage" />

            </article>

        </div>


        <!-- Image Preview -->

        <Teleport to="body">

            <Transition enter-active-class="
                    transition-opacity
                    duration-300
                    ease-out
                " enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="
                    transition-opacity
                    duration-200
                    ease-in
                " leave-from-class="opacity-100" leave-to-class="opacity-0">

                <div v-if="showImage && activeImage" role="dialog" aria-modal="true"
                    aria-label="Certification document preview" class="
                        fixed
                        inset-0
                        z-[100]

                        flex
                        items-center
                        justify-center

                        bg-black/70

                        p-4

                        backdrop-blur-md

                        sm:p-10
                    " @click="closeImage">

                    <!-- Close -->

                    <button type="button" aria-label="Close preview" class="
                            absolute
                            right-4
                            top-4
                            z-10

                            flex
                            h-10
                            w-10
                            items-center
                            justify-center

                            rounded-full

                            border
                            border-white/10

                            bg-white/10

                            text-white

                            backdrop-blur-md

                            transition-colors
                            duration-200

                            hover:bg-white/20

                            focus-visible:outline-none
                            focus-visible:ring-2
                            focus-visible:ring-white/40

                            sm:right-8
                            sm:top-8
                            sm:h-11
                            sm:w-11
                        " @click.stop="closeImage">
                        <X aria-hidden="true" class="h-4 w-4" />
                    </button>


                    <!-- Document -->

                    <div class="
                            flex
                            w-full
                            max-w-5xl
                            flex-col
                            items-center
                        " @click.stop>

                        <img :src="activeImage" alt="Certification document" class="
                                max-h-[78vh]
                                max-w-full

                                rounded-lg

                                border
                                border-white/10

                                bg-white

                                object-contain

                                shadow-2xl

                                dark:bg-canvas-dark
                            " />


                        <AppText tag="p" font="redhat" size="xs" weight="semibold" tracking="wide" class="
                                mt-5
                                uppercase
                                text-white/70
                            ">
                            Credential Document
                        </AppText>

                    </div>

                </div>

            </Transition>

        </Teleport>

    </section>
</template>


<style scoped>
    .animate-certificate {
        opacity: 0;

        animation:
            certificate-enter 500ms cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }


    @keyframes certificate-enter {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }


    @media (prefers-reduced-motion: reduce) {
        .animate-certificate {
            opacity: 1;
            animation: none;
        }
    }
</style>