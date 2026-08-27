<script setup lang="ts">
    import ContentCard from '@/components/cards/ContentCard.vue';
    import Avatar from '@/components/profile/Avatar.vue';
    import AppText from '@/components/ui/AppText.vue';
    import { show as interviewShow } from '@/actions/App/Http/Controllers/Public/Interview/InterviewController';
    import { InfiniteScroll } from '@inertiajs/vue3';
    import LoadingSpinner from '@/components/ui/LoadingSpinner.vue';
    import AppPageHeader from '@/components/ui/AppPageHeader.vue';
    import AppFilterLayout from '@/components/filters/layout/AppFilterLayout.vue';
    import FilterInput from '@/components/filters/fields/FilterInput.vue';
    import FilterSelect from '@/components/filters/fields/FilterSelect.vue';
    import { useInfiniteFilters } from '@/composables/useInfiniteFilters';

    const props = defineProps({
        interviews: {
            type: Object,
            required: true,
        },
        qfilters: {
            type: Object,
            default: () => ({}),
        },
        types: {
            type: Array,
            required: true
        }
    })

    const {
        filters,
        applyFilters,
        resetFilters,
    } = useInfiniteFilters({
        route: 'interviews.index',
        dataKey: 'interviews',
        initialFilters: {
            search: props.qfilters.search ?? '',
            type: props.qfilters.type ?? '',
        },
    })
</script>

<template>
    <AppPageHeader 
        class="hidden sm:block" 
        kicker="The Network" 
        title="Interviews" 
        description="Real conversations with founders, creators, and people doing meaningful 
                         work — sharing their journey, lessons, struggles, and ideas." />

    <!-- Sticky Filter Section with improved "Elevated" look -->
    <div class="sticky top-4 z-40 mb-16 sm:top-20">
        <AppFilterLayout :filters="filters" @apply="applyFilters" @reset="resetFilters">
            <template #search>
                <FilterInput v-model="filters.search" 
                :field="{
                    placeholder: 'Search the archive...'
                }" />
            </template>

            <template #inline-filters>
                <FilterSelect v-model="filters.type" :field="{
                    placeholder: 'All Formats'
                }" :options="types" />
            </template>
        </AppFilterLayout>
    </div>

    <!-- The Grid -->
    <div class="relative">
        <InfiniteScroll 
            data="interviews" 
            :key="[filters.search, filters.type].join('-')"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-10 gap-y-20 relative z-10">
            <ContentCard v-for="interview in interviews.data" :key="interview.id"
                :href="interviewShow(interview.slug).url" :image="interview.thumbnail ?? ''" :title="interview.title"
                :subtitle="interview.description ?? ''"
                :category="interview.interview_type ? { name: interview.interview_type } : null"
                :created_at="interview.published_at ?? ''">
                <!-- Interview-specific detail the shared card has no concept of. -->
                <template #meta>
                    <div v-if="interview.participants?.length" class="mb-2 flex items-center gap-3">
                        <div class="flex -space-x-2">
                            <Avatar v-for="participant in interview.participants.slice(0, 3)" 
                                :key="participant.id"
                                :name="participant.user?.name" 
                                :image="participant.user?.avatar" 
                                size="w-7 h-7"
                                class="ring-2 ring-surface-light dark:ring-surface-dark" />
                        </div>
                        <AppText 
                            tag="span" 
                            font="redhat" 
                            size="xs" 
                            color="muted" 
                            truncate>
                            {{interview.participants.map((p) => p.user?.name).filter(Boolean).join(', ')}}
                        </AppText>
                    </div>
                </template>
            </ContentCard>
            <template #loading>
                <div class="col-span-full flex justify-center py-20">
                    <LoadingSpinner :loading="true" />
                </div>
            </template>
        </InfiniteScroll>
    </div>
</template>