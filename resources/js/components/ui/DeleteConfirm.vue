<script setup>
import { router } from '@inertiajs/vue3'
import { Trash2, X } from '@lucide/vue'
import { ref } from 'vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },

    deleteUrl: {
        type: String,
        required: true,
    },

    title: {
        type: String,
        default: 'Delete Item',
    },

    message: {
        type: String,
        default: 'This action cannot be undone.',
    },

    confirmText: {
        type: String,
        default: 'Delete',
    },

    cancelText: {
        type: String,
        default: 'Cancel',
    },

    preserveScroll: {
        type: Boolean,
        default: true,
    }
})

const emit = defineEmits([
    'close',
    'success',
    'error'
])

//STATES
const isLoading = ref(false)
const closeModal = () => {
    if (isLoading.value) return
    emit('close')
}

//DELETE
const handleDelete = () => {
    if (!props.deleteUrl) return
    isLoading.value = true
    router.delete(
        props.deleteUrl,
        {
            preserveScroll:props.preserveScroll,
            onSuccess: (page) => {
                emit('success', page)
            },
            onError: (errors) => {
                emit('error', errors)
            },
            onFinish: () => {
                isLoading.value = false
                emit('close')
            }
        }
    )

}
</script>

<template>
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"

        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0">

        <div v-if="show"
            class="fixed inset-0 z-[9999]
                   flex items-center justify-center
                   px-4 py-6">

            <!-- BACKDROP -->
            <div @click="closeModal"
                class="absolute inset-0
                       bg-black/60
                       backdrop-blur-md">
            </div>

            <!-- MODAL -->
            <Transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 scale-95 translate-y-4"
                enter-to-class="opacity-100 scale-100 translate-y-0"

                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100 scale-100 translate-y-0"
                leave-to-class="opacity-0 scale-95 translate-y-4"
            >

                <div
                    v-if="show"

                    class="relative z-10
                           w-full max-w-md
                           overflow-hidden
                           rounded-3xl
                           border border-black/5
                           dark:border-white/10
                           bg-white/90
                           dark:bg-zinc-900/90
                           backdrop-blur-2xl
                           shadow-2xl shadow-black/20">

                    <!-- GLOW -->
                    <div
                        class="absolute inset-0

                               bg-gradient-to-br

                               from-red-500/[0.03]
                               via-transparent
                               to-brand/[0.03]

                               pointer-events-none"
                    ></div>

                    <!-- CONTENT -->
                    <div class="relative z-10 p-6">

                        <!-- HEADER -->
                        <div
                            class="flex items-start justify-between gap-4"
                        >

                            <div
                                class="flex items-center gap-4"
                            >

                                <!-- ICON -->
                                <div
                                    class="w-14 h-14 rounded-2xl

                                           bg-red-500/10

                                           border border-red-500/10

                                           flex items-center justify-center"
                                >

                                    <Trash2
                                        class="w-6 h-6
                                               text-red-500"
                                    />

                                </div>

                                <!-- TEXT -->
                                <div>

                                    <h3
                                        class="text-xl font-semibold

                                               text-gray-900
                                               dark:text-white"
                                    >
                                        {{ title }}
                                    </h3>

                                    <p
                                        class="mt-1 text-sm

                                               text-gray-500
                                               dark:text-gray-400

                                               leading-relaxed"
                                    >
                                        {{ message }}
                                    </p>

                                </div>

                            </div>

                            <!-- CLOSE -->
                            <button
                                @click="closeModal"

                                class="w-10 h-10 rounded-2xl

                                       border border-black/5
                                       dark:border-white/10

                                       bg-white
                                       dark:bg-white/[0.03]

                                       hover:bg-black
                                       dark:hover:bg-white

                                       transition-all duration-200

                                       flex items-center justify-center"
                            >

                                <X
                                    class="w-4 h-4

                                           text-gray-600
                                           dark:text-gray-300

                                           hover:text-white
                                           dark:hover:text-black

                                           transition"
                                />

                            </button>

                        </div>

                        <!-- ACTIONS -->
                        <div
                            class="mt-8 flex items-center justify-end gap-3"
                        >

                            <!-- CANCEL -->
                            <button
                                @click="closeModal"

                                :disabled="isLoading"

                                class="h-11 px-5 rounded-2xl

                                       border border-black/5
                                       dark:border-white/10

                                       bg-white
                                       dark:bg-white/[0.03]

                                       text-gray-700
                                       dark:text-gray-200

                                       hover:bg-gray-100
                                       dark:hover:bg-white/[0.05]

                                       disabled:opacity-50

                                       transition-all duration-200"
                            >
                                {{ cancelText }}
                            </button>

                            <!-- DELETE -->
                            <button
                                @click="handleDelete"

                                :disabled="isLoading"

                                class="h-11 px-5 rounded-2xl

                                       bg-red-500
                                       hover:bg-red-600

                                       text-white

                                       shadow-lg shadow-red-500/20

                                       disabled:opacity-50

                                       transition-all duration-200

                                       flex items-center gap-2"
                            >

                                <svg
                                    v-if="isLoading"

                                    class="animate-spin
                                           w-4 h-4"

                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    />

                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8v8H4z"
                                    />

                                </svg>
                                <Trash2
                                    v-else
                                    class="w-4 h-4"
                                />

                                <span>
                                    {{
                                        isLoading
                                            ? 'Deleting...'
                                            : confirmText
                                    }}
                                </span>

                            </button>

                        </div>

                    </div>

                </div>

            </Transition>

        </div>

    </Transition>

</template>