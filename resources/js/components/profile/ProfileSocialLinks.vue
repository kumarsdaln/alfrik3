<script setup lang="ts">
    import { ArrowUpRight } from '@lucide/vue'

    import AppHeading from '@/Components/Ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'


    interface Platform {
        name: string
        icon: string
    }


    interface SocialLink {
        url: string
        platform: Platform
    }


    interface Props {
        links?: SocialLink[]
        title?: string
    }


    const props = withDefaults(
        defineProps < Props > (),
        {
            links: () => [],
            title: 'Digital Presence',
        },
    )


    function formatUrl(url: string): string {
        try {
            const parsedUrl = new URL(url)

            return `${parsedUrl.hostname.replace(/^www\./, '')}${parsedUrl.pathname === '/' ? '' : parsedUrl.pathname}`
        }
        catch {
            return url
                .replace(/^https?:\/\/(www\.)?/, '')
                .replace(/\/$/, '')
        }
    }
</script>


<template>
    <section v-if="props.links.length" class="w-full">

        <!-- Section Label -->

        <AppHeading tag="h2" font="redhat" size="xs" weight="bold" tracking="wide" uppercase color="brand" class="
                mb-5
            ">
            {{ title }}
        </AppHeading>


        <!-- Links -->

        <div class="
                divide-y
                divide-border-light
                border-y
                border-border-light

                dark:divide-border-dark
                dark:border-border-dark
            ">

            <a v-for="link in props.links" :key="link.url" :href="link.url" target="_blank" rel="noopener noreferrer"
                class="
                    group

                    flex
                    items-center
                    gap-4

                    py-4

                    outline-none

                    transition-colors
                    duration-300

                    focus-visible:ring-2
                    focus-visible:ring-brand/30
                ">

                <!-- Platform Icon -->

                <div class="
                        flex
                        h-10
                        w-10
                        shrink-0
                        items-center
                        justify-center

                        rounded-full

                        bg-canvas-light
                        dark:bg-white/5

                        transition-all
                        duration-300

                        group-hover:bg-brand/10
                        dark:group-hover:bg-brand/10
                    ">
                    <img :src="`/${link.platform.icon}`" :alt="`${link.platform.name} icon`" class="
                            h-4
                            w-4

                            object-contain

                            opacity-50
                            grayscale

                            transition-all
                            duration-300

                            group-hover:opacity-100
                            group-hover:grayscale-0

                            dark:invert
                            dark:group-hover:invert-0
                        " />
                </div>


                <!-- Content -->

                <div class="min-w-0 flex-1">

                    <AppText tag="span" font="redhat" size="xs" weight="semibold" class="
                            block
                            text-content-light

                            transition-colors
                            duration-300

                            group-hover:text-brand

                            dark:text-content-dark
                            dark:group-hover:text-brand
                        ">
                        {{ link.platform.name }}
                    </AppText>


                    <AppText tag="span" font="redhat" size="xs" color="muted" class="
                            mt-1
                            block
                            truncate
                            lowercase
                        ">
                        {{ formatUrl(link.url) }}
                    </AppText>

                </div>


                <!-- External Link -->

                <div class="
                        flex
                        h-8
                        w-8
                        shrink-0
                        items-center
                        justify-center

                        rounded-full

                        text-content-lightMuted

                        transition-all
                        duration-300

                        group-hover:bg-canvas-light
                        group-hover:text-brand

                        dark:text-content-darkMuted
                        dark:group-hover:bg-white/5
                        dark:group-hover:text-brand
                    ">
                    <ArrowUpRight aria-hidden="true" class="
                            h-4
                            w-4

                            transition-transform
                            duration-300
                            ease-out

                            group-hover:-translate-y-0.5
                            group-hover:translate-x-0.5
                        " />
                </div>

            </a>

        </div>

    </section>
</template>


<style scoped>
    a {
        -webkit-tap-highlight-color: transparent;
    }
</style>