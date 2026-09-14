<script setup lang="ts">
</script>

<template>
    <div class="flex h-[calc(100vh-64px)] min-h-0 flex-col">
        <!-- Page Header -->
        <header class="shrink-0">
            <slot name="header" />
        </header>

        <!-- Scrollable Table -->
        <main class="min-h-0 flex-1 overflow-hidden">
            <div class="h-full min-h-0 overflow-auto">
                <slot />
            </div>
        </main>

        <!-- Fixed Footer -->
        <footer
            class="
                shrink-0
                border-t
                border-black/5
                bg-white
                dark:border-white/10
                dark:bg-zinc-950
            "
        >
            <slot name="footer" />
        </footer>
    </div>
</template>