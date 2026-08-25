<script setup>
    import Briefcase from '@/Icons/Briefcase.vue';
    import Setting from '@/Icons/Setting.vue';
    import Edit from '@/Icons/Edit.vue';
    import User from '@/Icons/User.vue';
    import { ref, defineProps } from 'vue';
    import { Link } from '@inertiajs/vue3';
    import { show as adminUsersShow } from '@/routes/admin/users';
    import { edit as adminUsersEdit } from '@/routes/admin/users';
    const props = defineProps({
        user: {
            type: Object,
        },
        currentTab: {
            type: String,
        }
    });
    const navTabs = [
        { name: 'Overview', label: 'Overview', href: adminUsersShow(props.user.external_id).url, icon: User },
        { name: 'Edit Profile', label: 'Edit Profile', href: adminUsersEdit(props.user.external_id).url, icon: Edit },
        // { name: 'Portfolio', label: 'Portfolio', href: route('profile.portfolio'), icon: Briefcase },
        // { name: 'Account', label: 'Account Settings', href: route('profile.settings'), icon: Setting },
    ];
</script>
<template>
    <nav class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md
           border-b border-slate-200 dark:border-slate-800
           sticky top-0 z-30">
        <div class="w-full flex flex-wrap justify-start sm:justify-start
               items-center gap-4 sm:gap-8 px-4 py-3">
            <Link v-for="tab in navTabs" :key="tab.name" :href="tab.href" @click.prevent="currentTab = tab.name"
                class="relative font-medium text-sm sm:text-base py-2 transition-colors" :class="{
                    'text-brand dark:text-brand after:absolute after:bottom-0 after:left-0 after:w-full after:h-[2px] after:bg-brand dark:after:bg-brand':
                        currentTab === tab.name,
                    'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200':
                        currentTab !== tab.name
                }">
                <component :is="tab.icon" class="inline w-4 h-4 mr-1.5 align-text-bottom" />
                {{ tab.label }}
            </Link>
        </div>
    </nav>
</template>