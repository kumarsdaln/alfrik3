<template>
    <div class="block-wrap">
        <button v-if="!isPublished" @click="$emit('add', index)">+</button>
        <button v-if="!isPublished" @click="$emit('delete', block.id)">✕</button>

        <component :is="tag" contenteditable class="block-content" @input="onInput" :data-placeholder="'Type here...'">
            {{ block.content }}
        </component>
    </div>
</template>

<script setup>
    import { computed } from "vue";

    const props = defineProps({
        block: Object,
        index: Number,
        blocks: Array,
        isPublished: Boolean,
    });

    const emit = defineEmits(["update", "add", "delete"]);

    const tag = computed(() => {
        if (props.block.type === "h1") return "h1";
        if (props.block.type === "h2") return "h2";
        return "div";
    });

    const onInput = (e) => {
        emit("update", {
            id: props.block.id,
            content: e.target.innerText,
        });
    };
</script>