<script setup>
    defineProps({
        title: String,
        value: [String, Number],
        icon: String,
        color: String,
    })
</script>

<template>
    <div class="
            rounded-3xl
            border
            border-zinc-200
            dark:border-zinc-800
            bg-white
            dark:bg-zinc-900
            p-6
        ">
        <div class="flex justify-between items-start">
            <div>
                <p class="
                        text-xs
                        uppercase
                        tracking-wider
                        text-zinc-500
                    ">
                    {{ title }}
                </p>

                <h2 class="
                        mt-3
                        text-4xl
                        font-black
                        text-zinc-900
                        dark:text-white
                    ">
                    {{ value }}
                </h2>
            </div>

            <div class="
                    h-12
                    w-12
                    rounded-2xl
                    flex
                    items-center
                    justify-center
                " :style="{
                    backgroundColor:
                        `${color}15`
                }">
                <slot />
            </div>
        </div>
    </div>
</template>