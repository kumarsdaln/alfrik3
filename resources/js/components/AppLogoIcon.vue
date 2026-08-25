<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg 
        xmlns="http://www.w3.org/2000/svg" 
        xmlns:xlink="http://www.w3.org/1999/xlink" 
        version="1.1" x="0px" y="0px"
        viewBox="0 0 100 100" 
        enable-background="new 0 0 100 100" 
        xml:space="preserve" 
        fill="currentColor"
        :class="className"
        v-bind="$attrs">
        <g display="none">
            <rect x="-4.011" y="-246.52" display="inline" fill="#e0006c" stroke="#FFFFFF" stroke-linecap="round"
                stroke-linejoin="round" stroke-miterlimit="10" width="1216.349" height="602.747" />
        </g>
        <g>
            <polygon points="91.73,33.246 72.872,11.873 63.683,11.873 69.102,33.246  " />
            <polygon points="64.976,33.246 59.575,11.944 59.854,11.873 37.906,11.873 38.185,11.944 32.784,33.246  " />
            <polygon points="50.98,87.69 93.524,37.246 69.012,37.246  " />
            <polygon points="6.476,37.246 46.936,88.127 28.748,37.246  " />
            <polygon points="32.995,37.246 48.88,83.682 64.764,37.246  " />
            <polygon points="28.657,33.246 34.077,11.873 24.903,11.873 8.028,33.246  " />
        </g>
    </svg>
</template>
