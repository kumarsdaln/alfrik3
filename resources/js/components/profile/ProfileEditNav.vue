<script setup>
    import { Link } from '@inertiajs/vue3'

    // Wayfinder route imports
    import { edit as profileBasicEdit } from '@/routes/profile/basic'
    import { index as profileSpecializationIndex } from '@/routes/profile/specialization'
    import { index as profileSocialIndex } from '@/routes/profile/social'
    import { index as profileIndustriesIndex } from '@/routes/profile/industries'
    import { index as profileLanguagesIndex } from '@/routes/profile/languages'
    import { index as profileEducationIndex } from '@/routes/profile/education'
    import { index as profileCertificationsIndex } from '@/routes/profile/certifications'
    import { index as profileSkillsIndex } from '@/routes/profile/skills'
    import { index as profileExperiencesIndex } from '@/routes/profile/experiences'
    import { index as profileAchievementsIndex } from '@/routes/profile/achievements'

    // Icons
    import User from "@/icons/User.vue"
    import Crown from "@/icons/Crown.vue"
    import GraduationCap from "@/icons/GraduationCap.vue"
    import Award from "@/icons/Award.vue"
    import Briefcase from "@/icons/Briefcase.vue"
    import Network from "@/icons/Network.vue"
    import Globe from "@/icons/Globe.vue"
    import Language from "@/icons/Language.vue"

    const props = defineProps({
        isExpert: {
            type: Boolean,
            default: false
        }
    })

    const tabs = [
        { name: 'Basic Info', route: profileBasicEdit().url, icon: User, active: 'profile.basic.*', visible: true },
        { name: 'Specialization', route: profileSpecializationIndex().url, icon: Crown, active: 'profile.specialization.*', visible: props.isExpert },
        { name: 'Social', route: profileSocialIndex().url, icon: Network, active: 'profile.social.*', visible: props.isExpert },
        { name: 'Industries', route: profileIndustriesIndex().url, icon: Globe, active: 'profile.industries.*', visible: props.isExpert },
        { name: 'Languages', route: profileLanguagesIndex().url, icon: Language, active: 'profile.languages.*', visible: props.isExpert },
        { name: 'Education', route: profileEducationIndex().url, icon: GraduationCap, active: 'profile.education.*', visible: props.isExpert },
        { name: 'Certification', route: profileCertificationsIndex().url, icon: Award, active: 'profile.certifications.*', visible: props.isExpert },
        { name: 'Skills', route: profileSkillsIndex().url, icon: Briefcase, active: 'profile.skills.*', visible: props.isExpert },
        { name: 'Experience', route: profileExperiencesIndex().url, icon: Briefcase, active: 'profile.experiences.*', visible: props.isExpert },
        { name: 'Achievements', route: profileAchievementsIndex().url, icon: Globe, active: 'profile.achievements.*', visible: props.isExpert },
    ]

    const visibleTabs = tabs.filter(tab => tab.visible !== false)

    const isActive = (pattern) => route().current(pattern)
</script>

<template>
    <div class="grid gap-6 lg:grid-cols-[248px_minmax(0,1fr)] lg:gap-10">

        <!-- Section navigation: left sidebar on desktop, horizontal scroller on mobile -->
        <aside class="lg:sticky lg:top-6 h-fit">
            <div class="rounded-2xl border border-border-light bg-surface-light p-2.5 shadow-editorial dark:border-border-dark dark:bg-surface-dark dark:shadow-editorial-dark">

                <p class="hidden px-3 pb-1.5 pt-2 font-redhat text-[10px] font-bold uppercase tracking-[0.22em] text-content-lightMuted dark:text-content-darkMuted lg:block">
                    Profile sections
                </p>

                <nav class="flex gap-1 overflow-x-auto no-scrollbar lg:flex-col lg:overflow-visible">
                    <Link v-for="tab in visibleTabs" :key="tab.name" :href="tab.route"
                        class="group flex items-center gap-3 whitespace-nowrap rounded-xl px-3 py-2.5 font-redhat text-sm font-medium transition"
                        :class="isActive(tab.active)
                            ? 'bg-brand-light text-brand dark:bg-brand/10'
                            : 'text-content-lightMuted hover:bg-canvas-light hover:text-content-light dark:text-content-darkMuted dark:hover:bg-white/5 dark:hover:text-content-dark'">

                        <component :is="tab.icon" class="h-4 w-4 shrink-0" />
                        <span>{{ tab.name }}</span>
                    </Link>
                </nav>
            </div>
        </aside>

        <!-- Page content -->
        <div class="min-w-0">
            <slot />
        </div>
    </div>
</template>

<style scoped>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
