<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { Link } from '@inertiajs/vue3'
import { EllipsisVertical } from '@lucide/vue'

const props = defineProps({
    actions: {
        type: Array,
        default: () => [],
    }
})
const isOpen = ref(false)
const dropdown = ref(null)

//TOGGLE
const toggleDropdown = () => {
    isOpen.value = !isOpen.value
}

//CLOSE
const closeDropdown = () => {
    isOpen.value = false
}

//OUTSIDE CLICK
const handleClickOutside = (event) => {
    if (
        dropdown.value &&
        !dropdown.value.contains(event.target)
    ) {
        closeDropdown()
    }
}

onMounted(() => {
    document.addEventListener(
        'click',
        handleClickOutside
    )
})

onBeforeUnmount(() => {
    document.removeEventListener(
        'click',
        handleClickOutside
    )
})
</script>

<template>

    <div ref="dropdown"
        class="relative inline-block text-left">

        <!-- BUTTON -->
        <button @click.stop="toggleDropdown"
            class="w-10 h-10 rounded-2xl
                   border border-black/5
                   dark:border-white/10
                   bg-white dark:bg-white/[0.03]
                   hover:bg-gray-100
                   dark:hover:bg-white/[0.05]
                   transition-all duration-200
                   flex items-center justify-center">
                   <EllipsisVertical class="w-4 h-4 text-gray-600 dark:text-gray-300"/>
        </button>

        <!-- DROPDOWN -->
        <Transition
            enter-active-class="transition-all duration-200"
            enter-from-class="opacity-0 scale-95 -translate-y-1"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition-all duration-150"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95">
            <div v-if="isOpen"
                class="absolute right-0 mt-2 z-50
                       w-52 rounded-2xl
                       border border-black/5
                       dark:border-white/10
                       bg-white/95
                       dark:bg-zinc-900/95
                       backdrop-blur-2xl
                       shadow-2xl shadow-black/10
                       overflow-hidden">
                <div class="p-2">
                    <template v-for="(action, index) in actions" :key="index">
                        <!-- LINK -->
                        <Link v-if="action.href"
                            :href="action.href"
                            @click="closeDropdown"
                            class="w-full px-3 py-2.5 rounded-xl
                                   flex items-center gap-3
                                   text-sm font-medium
                                   transition-all duration-200"
                            :class="[action.danger
                                    ? 'text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10'
                                    : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-white/[0.05]'
                            ]">
                            <component
                                v-if="action.icon"
                                :is="action.icon"
                                class="w-4 h-4" />
                            <span>
                                {{ action.label }}
                            </span>
                        </Link>

                        <!-- BUTTON -->
                        <button v-else
                            @click="
                              () => {
                                  action.action?.()
                                  closeDropdown()
                              }
                            "
                            class="w-full px-3 py-2.5 rounded-xl
                                   flex items-center gap-3
                                   text-sm font-medium
                                   transition-all duration-200"
                            :class="[
                                action.danger
                                    ? 'text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10'
                                    : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-white/[0.05]'
                            ]">
                            <component v-if="action.icon" :is="action.icon" class="w-4 h-4"/>
                            <span>
                                {{ action.label }}
                            </span>
                        </button>
                    </template>
                </div>
            </div>
        </Transition>
    </div>
</template>