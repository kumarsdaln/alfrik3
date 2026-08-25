<script setup lang="ts">
import {
    computed,
    nextTick,
    onMounted,
    onUnmounted,
    ref,
    useSlots,
    watch,
} from 'vue'

import X from '@/Icons/X.vue'

type ModalPosition = 'center' | 'bottom' | 'right' | 'left'

type MaxWidth =
    | 'sm'
    | 'md'
    | 'lg'
    | 'xl'
    | '2xl'
    | '4xl'
    | 'full'

interface Props {
    show?: boolean
    mobilePosition?: ModalPosition | null
    desktopPosition?: ModalPosition | null
    maxWidth?: MaxWidth
    closeable?: boolean
    padding?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    show: false,
    mobilePosition: null,
    desktopPosition: null,
    maxWidth: '2xl',
    closeable: true,
    padding: true,
})

const emit = defineEmits<{
    close: []
}>()

const slots = useSlots()

/*
|--------------------------------------------------------------------------
| SSR / CLIENT STATE
|--------------------------------------------------------------------------
|
| During SSR there is no window/document.
| We therefore don't render the modal until Vue has mounted on the client.
|
*/
const mounted = ref(false)
const isMobile = ref(false)

const checkScreen = () => {
    if (typeof window === 'undefined') {
        return
    }

    isMobile.value = window.innerWidth < 1024
}

/*
|--------------------------------------------------------------------------
| ACTIVE POSITION
|--------------------------------------------------------------------------
*/

const activePosition = computed<ModalPosition>(() => {
    if (isMobile.value) {
        return props.mobilePosition ?? 'center'
    }

    return props.desktopPosition ?? 'center'
})

/*
|--------------------------------------------------------------------------
| MAX WIDTH
|--------------------------------------------------------------------------
*/

const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
        '4xl': 'sm:max-w-4xl',
        full: 'sm:max-w-full',
    }[props.maxWidth]
})

/*
|--------------------------------------------------------------------------
| PANEL CLASSES
|--------------------------------------------------------------------------
*/

const panelClasses = computed(() => {
    const base = `
        relative
        bg-white
        dark:bg-zinc-900
        border border-black/5
        dark:border-white/10
        shadow-2xl
        shadow-black/20
        overflow-hidden
    `

    switch (activePosition.value) {
        case 'center':
            return `
                ${base}
                w-full
                ${maxWidthClass.value}
                rounded-3xl
            `

        case 'bottom':
            return `
                ${base}
                w-full
                max-h-[90vh]
                flex
                flex-col
                rounded-t-[2rem]
            `

        case 'right':
            return `
                ${base}
                absolute
                right-0
                top-0
                bottom-0
                h-full
                w-full
                max-w-md
            `

        case 'left':
            return `
                ${base}
                absolute
                left-0
                top-0
                bottom-0
                h-full
                w-full
                max-w-md
            `
    }
})

/*
|--------------------------------------------------------------------------
| POSITION WRAPPER
|--------------------------------------------------------------------------
*/

const wrapperClasses = computed(() => {
    switch (activePosition.value) {
        case 'center':
            return 'flex items-center justify-center p-4'

        case 'bottom':
            return 'flex items-end justify-center'

        case 'right':
            return 'flex justify-end'

        case 'left':
            return 'flex justify-start'
    }
})

/*
|--------------------------------------------------------------------------
| TRANSITION CLASSES
|--------------------------------------------------------------------------
*/

const enterFromClass = computed(() => {
    switch (activePosition.value) {
        case 'bottom':
            return 'translate-y-full'

        case 'right':
            return 'translate-x-full'

        case 'left':
            return '-translate-x-full'

        case 'center':
        default:
            return 'opacity-0 scale-95'
    }
})

const leaveToClass = computed(() => {
    switch (activePosition.value) {
        case 'bottom':
            return 'translate-y-full'

        case 'right':
            return 'translate-x-full'

        case 'left':
            return '-translate-x-full'

        case 'center':
        default:
            return 'opacity-0 scale-95'
    }
})

/*
|--------------------------------------------------------------------------
| CLOSE
|--------------------------------------------------------------------------
*/

const close = () => {
    if (!props.closeable) {
        return
    }

    emit('close')
}

/*
|--------------------------------------------------------------------------
| ESCAPE
|--------------------------------------------------------------------------
*/

const closeOnEscape = (event: KeyboardEvent) => {
    if (
        event.key === 'Escape' &&
        props.show &&
        props.closeable
    ) {
        close()
    }
}

/*
|--------------------------------------------------------------------------
| BODY SCROLL LOCK
|--------------------------------------------------------------------------
*/

const lockBodyScroll = (show: boolean) => {
    if (!mounted.value) {
        return
    }

    document.body.style.overflow = show ? 'hidden' : ''
}

watch(
    () => props.show,
    (show) => {
        lockBodyScroll(show)
    },
    { immediate: true },
)

/*
|--------------------------------------------------------------------------
| MOUNT
|--------------------------------------------------------------------------
*/

onMounted(async () => {
    mounted.value = true

    checkScreen()

    window.addEventListener(
        'resize',
        checkScreen,
        { passive: true },
    )

    document.addEventListener(
        'keydown',
        closeOnEscape,
    )

    await nextTick()

    lockBodyScroll(props.show)
})

/*
|--------------------------------------------------------------------------
| UNMOUNT
|--------------------------------------------------------------------------
*/

onUnmounted(() => {
    window.removeEventListener(
        'resize',
        checkScreen,
    )

    document.removeEventListener(
        'keydown',
        closeOnEscape,
    )

    document.body.style.overflow = ''
})
</script>

<template>
    <!--
        IMPORTANT:
        Do not render the modal during SSR.

        This prevents:
        SSR DOM
            !=
        Client hydration DOM
    -->
    <Teleport
        v-if="mounted"
        to="body"
    >
        <!-- OVERLAY -->
        <Transition
            enter-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="props.show"
                class="fixed inset-0 z-[9999]"
                role="presentation"
            >
                <!-- BACKDROP -->
                <div
                    class="
                        absolute inset-0
                        bg-black/60
                        backdrop-blur-sm
                    "
                    aria-hidden="true"
                    @click="close"
                />

                <!-- WRAPPER -->
                <div
                    class="absolute inset-0"
                    :class="wrapperClasses"
                    role="presentation"
                >
                    <!-- PANEL -->
                    <Transition
                        enter-active-class="transition-all duration-300"
                        :enter-from-class="enterFromClass"
                        enter-to-class="
                            translate-x-0
                            translate-y-0
                            opacity-100
                            scale-100
                        "
                        leave-active-class="transition-all duration-200"
                        leave-from-class="
                            translate-x-0
                            translate-y-0
                            opacity-100
                            scale-100
                        "
                        :leave-to-class="leaveToClass"
                    >
                        <div
                            v-if="props.show"
                            :class="panelClasses"
                            role="dialog"
                            aria-modal="true"
                            :aria-label="
                                slots.header
                                    ? undefined
                                    : 'Dialog'
                            "
                            @click.stop
                        >
                            <!-- MOBILE HANDLE -->
                            <div
                                v-if="activePosition === 'bottom'"
                                class="flex justify-center pt-3"
                                aria-hidden="true"
                            >
                                <div
                                    class="
                                        h-1.5
                                        w-14
                                        rounded-full
                                        bg-gray-300
                                        dark:bg-white/10
                                    "
                                />
                            </div>

                            <!-- HEADER -->
                            <div
                                v-if="slots.header"
                                class="
                                    flex
                                    items-center
                                    justify-between
                                    gap-4
                                    px-5
                                    py-4
                                    border-b
                                    border-black/5
                                    dark:border-white/10
                                "
                            >
                                <div class="min-w-0 flex-1">
                                    <slot name="header" />
                                </div>

                                <!-- CLOSE -->
                                <button
                                    v-if="props.closeable"
                                    type="button"
                                    class="
                                        flex
                                        h-10
                                        w-10
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-2xl
                                        border
                                        border-black/5
                                        dark:border-white/10
                                        hover:bg-gray-100
                                        dark:hover:bg-white/[0.05]
                                        transition-all
                                        duration-200
                                        focus:outline-none
                                        focus:ring-2
                                        focus:ring-brand/30
                                    "
                                    aria-label="Close dialog"
                                    @click="close"
                                >
                                    <X
                                        class="h-4 w-4"
                                        aria-hidden="true"
                                    />
                                </button>
                            </div>

                            <!-- CONTENT -->
                            <div
                                class="flex-1 overflow-y-auto"
                                :class="{
                                    'p-5': props.padding,
                                }"
                            >
                                <slot />
                            </div>

                            <!-- FOOTER -->
                            <div
                                v-if="slots.footer"
                                class="
                                    border-t
                                    border-black/5
                                    dark:border-white/10
                                    bg-white/80
                                    dark:bg-zinc-900/80
                                    backdrop-blur-xl
                                    p-5
                                "
                                :class="{
                                    'rounded-b-3xl':
                                        activePosition === 'center',
                                }"
                            >
                                <slot name="footer" />
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>