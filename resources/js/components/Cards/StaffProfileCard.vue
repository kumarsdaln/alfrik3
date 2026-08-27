<script setup>
import { Link } from '@inertiajs/vue3'
import Avatar from '@/Components/Ui/AppAvatar.vue'
import { show as companyStaffShow } from '@/routes/company/staff'

const props = defineProps({
  id: Number,
  name: String,
  email: String,
  position: String,
  photo: String,
  companyId: Number,
  clickable: {
    type: Boolean,
    default: true
  }
})
</script>

<template>
  <component
    :is="clickable ? Link : 'div'"
    :href="clickable ? companyStaffShow([companyId, id]).url : null"
    class="group flex items-center gap-3 bg-white border border-gray-200
           hover:border-gray-300 hover:bg-gray-50
           transition-all duration-150 rounded-xl p-3 shadow-sm"
  >

    <!-- Avatar -->
    <div class="relative">
      <Avatar :src="photo" :alt="name" class="w-10 h-10 rounded-full" />

      <!-- online dot example (optional) -->
      <!-- <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border border-white"></span> -->
    </div>

    <!-- Staff Info -->
    <div class="flex flex-col flex-1 min-w-0">
      <!-- Name -->
      <span
        class="font-semibold text-gray-800 truncate
               group-hover:text-gray-900 transition"
      >
        {{ name }}
      </span>

      <!-- Email -->
      <span
        v-if="email"
        class="text-gray-500 text-xs truncate"
      >
        {{ email }}
      </span>

      <!-- Position Tag -->
      <span
        v-if="position"
        class="inline-block text-xs mt-1 w-fit
               px-2 py-0.5 rounded-md
               bg-blue-50 text-blue-700
               border border-blue-100"
      >
        {{ position }}
      </span>
    </div>

    <!-- Arrow (only if clickable) -->
    <svg
      v-if="clickable"
      xmlns="http://www.w3.org/2000/svg"
      class="h-4 w-4 text-gray-400 group-hover:text-gray-600 transition"
      fill="none"
      viewBox="0 0 24 24"
      stroke="currentColor"
    >
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M9 5l7 7-7 7" />
    </svg>

  </component>
</template>

<style scoped>
.group:hover {
  transform: translateY(-1px);
}
</style>