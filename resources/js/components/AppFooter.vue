<script setup lang="ts">
    import InstagramIcon from '@/icons/InstagramIcon.vue';
    import LinkedinIcon from '@/icons/LinkedinIcon.vue';
    import MediumIcon from '@/icons/MediumIcon.vue';
    import PinterestIcon from '@/icons/PinterestIcon.vue';
    import { Link } from '@inertiajs/vue3'
    import {
        ArrowUpRight,
        Mail,
        MapPin,
    } from '@lucide/vue'
</script>

<template>
    <footer class="
            border-t
            border-border-light
            bg-surface-light
            dark:border-border-dark
            dark:bg-surface-dark
        ">
        <!--
        |--------------------------------------------------------------------------
        | Main Footer
        |--------------------------------------------------------------------------
        -->

        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="
                    grid
                    gap-12
                    py-16
                    sm:py-20
                    lg:grid-cols-[1.5fr_1fr_1fr_1.2fr]
                    lg:gap-16
                ">
                <!--
                |--------------------------------------------------------------------------
                | Brand / Introduction
                |--------------------------------------------------------------------------
                -->

                <div class="max-w-md">
                    <Link href="/" class="
                            inline-block
                            font-prata
                            text-3xl
                            font-normal
                            tracking-tight
                            text-content-light
                            transition-colors
                            hover:text-brand
                            dark:text-content-dark
                        ">
                        Alfrik
                    </Link>

                    <p class="
                            mt-5
                            max-w-sm
                            font-lora
                            text-sm
                            leading-7
                            text-content-lightMuted
                            dark:text-content-darkMuted
                        ">
                        An independent platform for research, ideas,
                        conversations and informed perspectives on the
                        issues shaping our world.
                    </p>

                    <!-- Newsletter -->

                    <div class="mt-8">
                        <p class="
                                font-redhat
                                text-[11px]
                                font-bold
                                uppercase
                                tracking-[0.2em]
                                text-content-light
                                dark:text-content-dark
                            ">
                            Stay informed
                        </p>

                        <Link href="/newsletter" class="
                                group
                                mt-3
                                inline-flex
                                items-center
                                gap-2
                                border-b
                                border-content-light/30
                                pb-1
                                font-redhat
                                text-sm
                                font-semibold
                                text-content-light
                                transition-colors
                                hover:border-brand
                                hover:text-brand
                                dark:border-content-dark/30
                                dark:text-content-dark
                            ">
                            Subscribe to our newsletter

                            <ArrowUpRight class="
                                    h-4
                                    w-4
                                    transition-transform
                                    duration-300
                                    group-hover:translate-x-0.5
                                    group-hover:-translate-y-0.5
                                " />
                        </Link>
                    </div>
                </div>

                <!--
                |--------------------------------------------------------------------------
                | Explore
                |--------------------------------------------------------------------------
                -->

                <div>
                    <h3 class="
                            font-redhat
                            text-[11px]
                            font-bold
                            uppercase
                            tracking-[0.2em]
                            text-content-light
                            dark:text-content-dark
                        ">
                        Explore
                    </h3>

                    <nav class="mt-5">
                        <ul class="space-y-3">
                            <li>
                                <Link href="/research" class="
                                        font-redhat
                                        text-sm
                                        text-content-lightMuted
                                        transition-colors
                                        hover:text-brand
                                        dark:text-content-darkMuted
                                    ">
                                    Research
                                </Link>
                            </li>

                            <li>
                                <Link href="/reports" class="
                                        font-redhat
                                        text-sm
                                        text-content-lightMuted
                                        transition-colors
                                        hover:text-brand
                                        dark:text-content-darkMuted
                                    ">
                                    Reports
                                </Link>
                            </li>

                            <li>
                                <Link href="/surveys" class="
                                        font-redhat
                                        text-sm
                                        text-content-lightMuted
                                        transition-colors
                                        hover:text-brand
                                        dark:text-content-darkMuted
                                    ">
                                    Surveys
                                </Link>
                            </li>

                            <li>
                                <Link href="/interviews" class="
                                        font-redhat
                                        text-sm
                                        text-content-lightMuted
                                        transition-colors
                                        hover:text-brand
                                        dark:text-content-darkMuted
                                    ">
                                    Interviews
                                </Link>
                            </li>

                            <li>
                                <Link href="/magazine" class="
                                        font-redhat
                                        text-sm
                                        text-content-lightMuted
                                        transition-colors
                                        hover:text-brand
                                        dark:text-content-darkMuted
                                    ">
                                    Magazine
                                </Link>
                            </li>

                            <li>
                                <Link href="/events" class="
                                        font-redhat
                                        text-sm
                                        text-content-lightMuted
                                        transition-colors
                                        hover:text-brand
                                        dark:text-content-darkMuted
                                    ">
                                    Events
                                </Link>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!--
                |--------------------------------------------------------------------------
                | About
                |--------------------------------------------------------------------------
                -->

                <div>
                    <h3 class="
                            font-redhat
                            text-[11px]
                            font-bold
                            uppercase
                            tracking-[0.2em]
                            text-content-light
                            dark:text-content-dark
                        ">
                        About
                    </h3>

                    <nav class="mt-5">
                        <ul class="space-y-3">
                            <li>
                                <Link href="/about" class="
                                        font-redhat
                                        text-sm
                                        text-content-lightMuted
                                        transition-colors
                                        hover:text-brand
                                        dark:text-content-darkMuted
                                    ">
                                    About Alfrik
                                </Link>
                            </li>

                            <li>
                                <Link href="/people" class="
                                        font-redhat
                                        text-sm
                                        text-content-lightMuted
                                        transition-colors
                                        hover:text-brand
                                        dark:text-content-darkMuted
                                    ">
                                    Our People
                                </Link>
                            </li>

                            <li>
                                <Link href="/contributors" class="
                                        font-redhat
                                        text-sm
                                        text-content-lightMuted
                                        transition-colors
                                        hover:text-brand
                                        dark:text-content-darkMuted
                                    ">
                                    Contributors
                                </Link>
                            </li>

                            <li>
                                <Link href="/contact" class="
                                        font-redhat
                                        text-sm
                                        text-content-lightMuted
                                        transition-colors
                                        hover:text-brand
                                        dark:text-content-darkMuted
                                    ">
                                    Contact
                                </Link>
                            </li>

                            <li>
                                <Link href="/careers" class="
                                        font-redhat
                                        text-sm
                                        text-content-lightMuted
                                        transition-colors
                                        hover:text-brand
                                        dark:text-content-darkMuted
                                    ">
                                    Careers
                                </Link>
                            </li>

                            <li>
                                <Link href="/constitution" class="
                                        font-redhat
                                        text-sm
                                        text-content-lightMuted
                                        transition-colors
                                        hover:text-brand
                                        dark:text-content-darkMuted
                                    ">
                                    Constitution
                                </Link>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!--
                |--------------------------------------------------------------------------
                | Contact
                |--------------------------------------------------------------------------
                -->

                <div>
                    <h3 class="
                            font-redhat
                            text-[11px]
                            font-bold
                            uppercase
                            tracking-[0.2em]
                            text-content-light
                            dark:text-content-dark
                        ">
                        Connect
                    </h3>

                    <div class="mt-5 space-y-5">
                        <!-- Email -->

                        <a href="mailto:hello@alfrik.com" class="
                                group
                                flex
                                items-start
                                gap-3
                            ">
                            <Mail class="
                                    mt-0.5
                                    h-4
                                    w-4
                                    shrink-0
                                    text-brand
                                " />

                            <span class="
                                    font-redhat
                                    text-sm
                                    leading-6
                                    text-content-lightMuted
                                    transition-colors
                                    group-hover:text-brand
                                    dark:text-content-darkMuted
                                ">
                                hello@alfrik.com
                            </span>
                        </a>

                        <!-- Location -->

                        <div class="flex items-start gap-3">
                            <MapPin class="
                                    mt-0.5
                                    h-4
                                    w-4
                                    shrink-0
                                    text-brand
                                " />

                            <span class="
                                    font-redhat
                                    text-sm
                                    leading-6
                                    text-content-lightMuted
                                    dark:text-content-darkMuted
                                ">
                                Independent research<br />
                                and ideas platform
                            </span>
                        </div>

                        <!-- Social -->

                        <!-- Social -->

                        <div class="flex items-center gap-3 pt-2">
                            <a href="#" aria-label="Instagram" class="
            flex
            h-9
            w-9
            items-center
            justify-center
            border
            border-border-light
            text-content-lightMuted
            transition-colors
            hover:border-brand
            hover:bg-brand
            hover:text-white
            dark:border-border-dark
            dark:text-content-darkMuted
        ">
                                <InstagramIcon class="h-4 w-4" />
                            </a>

                            <a href="#" aria-label="LinkedIn" class="
            flex
            h-9
            w-9
            items-center
            justify-center
            border
            border-border-light
            text-content-lightMuted
            transition-colors
            hover:border-brand
            hover:bg-brand
            hover:text-white
            dark:border-border-dark
            dark:text-content-darkMuted
        ">
                                <LinkedinIcon class="h-4 w-4" />
                            </a>

                            <a href="#" aria-label="Medium" class="
            flex
            h-9
            w-9
            items-center
            justify-center
            border
            border-border-light
            text-content-lightMuted
            transition-colors
            hover:border-brand
            hover:bg-brand
            hover:text-white
            dark:border-border-dark
            dark:text-content-darkMuted
        ">
                                <MediumIcon class="h-4 w-4" />
                            </a>

                            <a href="#" aria-label="Pinterest" class="
            flex
            h-9
            w-9
            items-center
            justify-center
            border
            border-border-light
            text-content-lightMuted
            transition-colors
            hover:border-brand
            hover:bg-brand
            hover:text-white
            dark:border-border-dark
            dark:text-content-darkMuted
        ">
                                <PinterestIcon class="h-4 w-4" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--
            |--------------------------------------------------------------------------
            | Editorial Statement
            |--------------------------------------------------------------------------
            -->

            <div class="
                    border-y
                    border-border-light
                    py-8
                    dark:border-border-dark
                ">
                <div class="
                        flex
                        flex-col
                        gap-4
                        md:flex-row
                        md:items-center
                        md:justify-between
                    ">
                    <p class="
                            max-w-2xl
                            font-lora
                            text-sm
                            italic
                            leading-6
                            text-content-lightMuted
                            dark:text-content-darkMuted
                        ">
                        Independent thinking. Evidence-based inquiry.
                        Conversations that matter.
                    </p>

                    <Link href="/research" class="
                            group
                            inline-flex
                            shrink-0
                            items-center
                            gap-2
                            font-redhat
                            text-xs
                            font-bold
                            uppercase
                            tracking-[0.12em]
                            text-content-light
                            transition-colors
                            hover:text-brand
                            dark:text-content-dark
                        ">
                        Explore our work

                        <ArrowUpRight class="
                                h-4
                                w-4
                                transition-transform
                                duration-300
                                group-hover:translate-x-0.5
                                group-hover:-translate-y-0.5
                            " />
                    </Link>
                </div>
            </div>

            <!--
            |--------------------------------------------------------------------------
            | Bottom Bar
            |--------------------------------------------------------------------------
            -->

            <div class="
                    flex
                    flex-col
                    gap-4
                    py-6
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                ">
                <p class="
                        font-redhat
                        text-[11px]
                        text-content-lightMuted
                        dark:text-content-darkMuted
                    ">
                    © {{ new Date().getFullYear() }} Alfrik. All rights reserved.
                </p>

                <nav>
                    <ul class="
                            flex
                            flex-wrap
                            gap-x-6
                            gap-y-2
                        ">
                        <li>
                            <Link href="/privacy" class="
                                    font-redhat
                                    text-[11px]
                                    text-content-lightMuted
                                    transition-colors
                                    hover:text-brand
                                    dark:text-content-darkMuted
                                ">
                                Privacy
                            </Link>
                        </li>

                        <li>
                            <Link href="/terms" class="
                                    font-redhat
                                    text-[11px]
                                    text-content-lightMuted
                                    transition-colors
                                    hover:text-brand
                                    dark:text-content-darkMuted
                                ">
                                Terms
                            </Link>
                        </li>

                        <li>
                            <Link href="/accessibility" class="
                                    font-redhat
                                    text-[11px]
                                    text-content-lightMuted
                                    transition-colors
                                    hover:text-brand
                                    dark:text-content-darkMuted
                                ">
                                Accessibility
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </footer>
</template>