<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

import Heading from '@/components/Heading.vue'
import Avatar from '@/components/profile/Avatar.vue'
import ProfileFilters from '@/components/profile/ProfileFilters.vue'
import FilterControl from '@/components/filters/FilterControl.vue'

import { router } from '@inertiajs/vue3'
import { useFilters } from '@/composables/useFilters'

interface Position {
    id: number
    name: string
}

interface Country {
    id: number
    name: string
    code: string
}

interface Language {
    id: number
    name: string
    native: string
    code: string
}

interface Industry {
    id: number
    name: string
}

interface Profile {
    id: number
    name: string
    username: string
    avatar: string | null
    headline: string | null
    position?: Position | null
    country?: Country | null
    languages?: Language[]
    industries?: Industry[]
}

interface PaginationLink {
    url: string | null
    label: string
    active: boolean
}

interface Profiles {
    data: Profile[]
    links: PaginationLink[]
    current_page: number
    last_page: number
    total: number
}

interface Props {
    profiles: Profiles
    countries: Country[]
    positions: Position[]
    languages: Language[]
    industries: Industry[]
}

const props = defineProps<Props>()

const countryOptions = computed(() =>
    props.countries.map(country => ({
        value: country.id,
        label: country.name,
    })),
)

const positionOptions = computed(() =>
    props.positions.map(position => ({
        value: position.id,
        label: position.name,
    })),
)

const languageOptions = computed(() =>
    props.languages.map(language => ({
        value: language.id,
        label: `${language.name} (${language.native})`,
    })),
)

const industryOptions = computed(() =>
    props.industries.map(industry => ({
        value: industry.id,
        label: industry.name,
    })),
)    

const {
    filters,
    filterCount,
    clearFilters,
} = useFilters({
    search: '',
    countryId: undefined as number | undefined,
    positionId: undefined as number | undefined,
    languageIds: [] as number[],
    industryIds: [] as number[],
})

function applyFilters() {
    router.get(
        '/profiles',
        {
            search: filters.search || undefined,
            country_id: filters.countryId,
            position_id: filters.positionId,
            language_ids: filters.languageIds,
            industry_ids: filters.industryIds,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    )
}
</script>

<template>
    <Head title="Profiles" />
     <!-- Header -->
        <div class="flex flex-col gap-2">
            <p v-if="profiles.meta.total" class="mt-2 text-sm text-muted-foreground">
                Showing
                <span class="font-medium text-foreground">
                    {{ profiles.data.length }}
                </span>
                of
                <span class="font-medium text-foreground">
                    {{ profiles.meta.total }}
                </span>
                profiles
            </p>
        </div>
    <div class="flex flex-col space-y-8">
        <!-- Filters -->
        <FilterControl
            v-model:search="filters.search"
            search-placeholder="Search profiles..."
            :filter-count="filterCount"
            @clear="clearFilters"
            @apply="applyFilters"
        >
            <ProfileFilters
                v-model:country-id="filters.countryId"
                v-model:position-id="filters.positionId"
                v-model:language-ids="filters.languageIds"
                v-model:industry-ids="filters.industryIds"
                :countries="countryOptions"
                :positions="positionOptions"
                :languages="languageOptions"
                :industries="industryOptions"
            />
        </FilterControl>


        <!-- Profiles -->

        <div
            v-if="profiles.data.length"
            class="
                grid
                gap-px
                overflow-hidden
                rounded-xl
                border
                border-border
                bg-border
                sm:grid-cols-2
                lg:grid-cols-3
            "
        >
            <Link
                v-for="profile in profiles.data"
                :key="profile.id"
                :href="`/profiles/${profile.username}`"
                class="
                    group
                    flex
                    min-h-[290px]
                    flex-col
                    bg-background
                    p-7
                    outline-none
                    transition-colors
                    hover:bg-muted/30
                    focus-visible:z-10
                    focus-visible:ring-2
                    focus-visible:ring-ring
                    focus-visible:ring-inset
                "
            >

                <!-- Identity -->

                <div class="flex flex-col items-center text-center">

                    <Avatar
                        :image="profile.avatar"
                        :name="profile.name"
                        size="size-28 sm:size-32"
                        text-size="text-2xl"
                        rounded="full"
                    />

                    <h2
                        class="
                            mt-4
                            text-base
                            font-semibold
                            tracking-tight
                            text-foreground
                        "
                    >
                        {{ profile.name }}
                    </h2>

                    <p class="mt-0.5 text-sm text-muted-foreground">
                        @{{ profile.username }}
                    </p>

                </div>


                <!-- Headline -->

                <div class="mt-6 flex-1 text-center">
                    <p
                        v-if="profile.headline"
                        class="
                            mx-auto
                            max-w-xs
                            line-clamp-3
                            text-sm
                            leading-6
                            text-muted-foreground
                        "
                    >
                        {{ profile.headline }}
                    </p>
                </div>


                <!-- Information -->

                <div
                    class="
                        mt-6
                        flex
                        flex-col
                        items-center
                        gap-1
                        text-center
                    "
                >
                    <p
                        v-if="profile.position"
                        class="text-sm font-medium text-foreground"
                    >
                        {{ profile.position.name }}
                    </p>

                    <p
                        v-if="profile.country"
                        class="text-xs text-muted-foreground"
                    >
                        {{ profile.country.name }}
                    </p>
                </div>


                <!-- Profile Link -->

                <div
                    class="
                        mt-6
                        flex
                        items-center
                        justify-center
                        gap-1.5
                        text-sm
                        font-medium
                        text-muted-foreground
                        transition-colors
                        group-hover:text-foreground
                    "
                >
                    <span>
                        View profile
                    </span>

                    <svg
                        class="
                            h-4
                            w-4
                            transition-transform
                            duration-200
                            group-hover:translate-x-1
                        "
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M5 12h14" />
                        <path d="m13 6 6 6-6 6" />
                    </svg>
                </div>

            </Link>
        </div>


        <!-- Empty -->

        <div
            v-else
            class="
                flex
                min-h-80
                flex-col
                items-center
                justify-center
                rounded-xl
                border
                border-border
                px-6
                text-center
            "
        >
            <Avatar
                :name="'Profiles'"
                size="size-14"
                text-size="text-lg"
                rounded="full"
            />

            <h2 class="mt-4 text-sm font-semibold">
                No profiles found
            </h2>

            <p
                class="
                    mt-1
                    max-w-sm
                    text-sm
                    leading-6
                    text-muted-foreground
                "
            >
                Try adjusting your search or filters to find
                other profiles.
            </p>
        </div>


        <!-- Pagination -->

        <div
            v-if="profiles.last_page > 1"
            class="
                flex
                items-center
                justify-center
                gap-1
                pt-2
            "
        >
            <Link
                v-for="link in profiles.links"
                :key="link.label"
                :href="link.url ?? '#'"
                :class="[
                    'inline-flex h-9 min-w-9 items-center justify-center rounded-md px-3 text-sm transition-colors',
                    link.active
                        ? 'bg-foreground text-background'
                        : link.url
                            ? 'text-muted-foreground hover:bg-muted hover:text-foreground'
                            : 'pointer-events-none text-muted-foreground/30',
                ]"
                v-html="link.label"
            />
        </div>

    </div>
</template>