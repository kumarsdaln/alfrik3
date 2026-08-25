<script setup>
    import { ref, onMounted, onBeforeUnmount } from 'vue'
    import { Link, router } from '@inertiajs/vue3'
    import Setting from '@/Icons/Setting.vue';
    import Logout from '@/Icons/Logout.vue';
    import User from '@/Icons/User.vue';
    import { LayoutGrid } from '@lucide/vue'
    import { logout as logoutRoute } from '@/routes'
    import { view as profileView } from '@/routes/profile'
    import { index as accountModules } from '@/routes/account/modules'
    import { index as profileSettingsIndex } from '@/routes/profile/settings'

    const props = defineProps({
        user: {
            type: Object,
            required: true
        }
    })

    const showProfileMenu = ref(false)
    const profileMenuRef = ref(null)

    const logout = () => {
        router.post(logoutRoute().url)
    }

    const handleClickOutside = (event) => {
        if (
            profileMenuRef.value &&
            !profileMenuRef.value.contains(event.target)
        ) {
            showProfileMenu.value = false
        }
    }

    onMounted(() => {
        document.addEventListener('click', handleClickOutside)
    })

    onBeforeUnmount(() => {
        document.removeEventListener('click', handleClickOutside)
    })
</script>

<template>
    <div ref="profileMenuRef" class="relative ml-2">
        <!-- Profile Button -->
        <button @click="showProfileMenu = !showProfileMenu"
            class="group flex items-center gap-2 p-1.5 pr-4 rounded-full bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark hover:border-brand/40 hover:shadow-lg hover:shadow-brand/10 transition-all duration-300">
            <!-- Profile Image -->
            <img v-if="user.avatar" :src="user.avatar" :alt="user.name"
                class="w-9 h-9 rounded-full object-cover ring-2 ring-white dark:ring-canvas-dark" />

            <!-- Fallback Avatar -->
            <div v-else
                class="w-9 h-9 rounded-full bg-brand flex items-center justify-center text-white text-sm font-bold uppercase">
                {{ user.name.charAt(0) }}
            </div>

            <!-- Name -->
            <div class="hidden sm:flex flex-col items-start leading-tight">
                <span class="text-sm font-semibold text-content-light dark:text-content-dark truncate max-w-[100px]">
                    {{ user.name.split(' ')[0] }}
                </span>

                <span class="text-[11px] text-content-lightMuted dark:text-content-darkMuted">
                    Online
                </span>
            </div>
        </button>

        <!-- Dropdown -->
        <Transition enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform scale-95 opacity-0 translate-y-2"
            enter-to-class="transform scale-100 opacity-100 translate-y-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="transform scale-100 opacity-100 translate-y-0"
            leave-to-class="transform scale-95 opacity-0 translate-y-2">
            <div v-if="showProfileMenu"
                class="absolute right-0 mt-3 w-72 bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-3xl shadow-editorial dark:shadow-editorial-dark overflow-hidden z-[70] backdrop-blur-xl">
                <!-- User Info -->
                <div class="p-5 border-b border-border-light dark:border-border-dark">
                    <div class="flex items-center gap-3">
                        <img v-if="user.avatar" :src="user.avatar" class="w-12 h-12 rounded-full object-cover" />

                        <div v-else
                            class="w-12 h-12 rounded-full bg-brand flex items-center justify-center text-white font-bold text-lg uppercase">
                            {{ user.name.charAt(0) }}
                        </div>

                        <div class="min-w-0">
                            <h4 class="text-sm font-semibold text-content-light dark:text-content-dark truncate">
                                {{ user.name }}
                            </h4>

                            <p class="text-xs text-content-lightMuted dark:text-content-darkMuted truncate">
                                {{ user.email }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Menu -->
                <div class="p-2">
                    <Link :href="accountModules().url"
                        class="flex items-center gap-3 p-3 rounded-2xl hover:bg-canvas-light dark:hover:bg-white/5 transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-brand-light dark:bg-brand/10 flex items-center justify-center">
                            <LayoutGrid class="w-5 h-5 text-brand" />
                        </div>

                        <div>
                            <p class="text-sm font-medium text-content-light dark:text-content-dark">
                                Your Modules
                            </p>

                            <p class="text-xs text-content-lightMuted dark:text-content-darkMuted">
                                Expert · Learning · Recognition · BioLink
                            </p>
                        </div>
                    </Link>

                    <Link :href="profileView().url"
                        class="flex items-center gap-3 p-3 rounded-2xl hover:bg-canvas-light dark:hover:bg-white/5 transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-canvas-light dark:bg-white/5 flex items-center justify-center">
                            <User class="w-5 h-5 text-content-light dark:text-content-dark" />
                        </div>

                        <div>
                            <p class="text-sm font-medium text-content-light dark:text-content-dark">
                                Profile
                            </p>

                            <p class="text-xs text-content-lightMuted dark:text-content-darkMuted">
                                Manage your profile
                            </p>
                        </div>
                    </Link>

                    <Link :href="profileSettingsIndex().url"
                        class="flex items-center gap-3 p-3 rounded-2xl hover:bg-canvas-light dark:hover:bg-white/5 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-canvas-light dark:bg-white/5 flex items-center justify-center">
                            <Setting class="w-5 h-5 text-content-light dark:text-content-dark" />
                        </div>

                        <div>
                            <p class="text-sm font-medium text-content-light dark:text-content-dark">
                                Account Settings
                            </p>

                            <p class="text-xs text-content-lightMuted dark:text-content-darkMuted">
                                Privacy & preferences
                            </p>
                        </div>
                    </Link>

                    <!-- Logout -->
                    <button @click="logout"
                        class="w-full flex items-center gap-3 p-3 rounded-2xl hover:bg-red-50 dark:hover:bg-red-500/10 transition-all">
                        <div
                            class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center">
                            <Logout class="w-5 h-5 text-red-500" />
                        </div>

                        <div class="text-left">
                            <p class="text-sm font-medium text-red-500">
                                Sign Out
                            </p>

                            <p class="text-xs text-red-400">
                                Logout from account
                            </p>
                        </div>
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>