<script setup lang="ts">
interface Props {
    rows?: number
    colspan?: number
}

withDefaults(
    defineProps<Props>(),
    {
        rows: 5,
        colspan: 100,
    }
)
</script>

<template>
    <tbody>
        <tr>
            <td
                :colspan="colspan"
                class="p-6"
            >
                <div class="space-y-3">
                    <div
                        v-for="row in rows"
                        :key="row"
                        class="h-10 rounded-xl
                               animate-pulse
                               bg-neutral-100
                               dark:bg-neutral-800"
                    />
                </div>
            </td>
        </tr>
    </tbody>
</template>
