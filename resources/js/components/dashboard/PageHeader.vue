<script setup>
import { ChevronLeft } from '@lucide/vue';


const props = defineProps({
    title: String
})

function goBack() {
    history.back()
}
</script>
<template>
    <div class="flex justify-between items-center p-4
            bg-white
            text-gray-900
            border-b border-gray-200
            dark:bg-zinc-900
            dark:text-white
            dark:border-gray-700">

        <!-- Left: Back + Title -->
        <div class="flex items-center gap-3">
            <button type="button" @click="goBack()" class="flex items-center justify-center w-9 h-9
             rounded-lg
             text-gray-600 hover:text-gray-900
             hover:bg-gray-100
             dark:text-gray-300 dark:hover:text-white
             dark:hover:bg-gray-800
             transition" aria-label="Go back">
                <ChevronLeft class="w-5 h-5" />
            </button>

            <h4 class="card-title font-semibold text-lg">
                {{ title }}
            </h4>
        </div>

        <!-- Right: Actions -->
        <slot name="header-actions"></slot>
    </div>
</template>