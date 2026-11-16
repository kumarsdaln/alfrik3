<script setup lang="ts">
    import { onBeforeUnmount, onMounted, ref, watch } from 'vue'
    import Chart from 'chart.js/auto'
    import type {
        ChartData,
        ChartOptions,
        ChartType,
    } from 'chart.js'

    interface Props {
        type: ChartType
        data: ChartData
        options?: ChartOptions
        height?: number
        ariaLabel?: string
    }

    const props = withDefaults(defineProps<Props>(), {
        height: 320,
        ariaLabel: 'Data chart',
    })

    const canvas = ref<HTMLCanvasElement | null>(null)

    let chart: Chart | null = null

    function renderChart() {
        if (!canvas.value) return

        chart?.destroy()

        chart = new Chart(canvas.value, {
            type: props.type,
            data: props.data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                ...props.options,
            },
        })
    }

    onMounted(renderChart)

    watch(
        () => [props.type, props.data, props.options],
        renderChart,
        { deep: true },
    )

    onBeforeUnmount(() => {
        chart?.destroy()
        chart = null
    })
</script>

<template>
    <div class="relative w-full" :style="{ height: `${height}px` }">
        <canvas ref="canvas" role="img" :aria-label="ariaLabel" />
    </div>
</template>
