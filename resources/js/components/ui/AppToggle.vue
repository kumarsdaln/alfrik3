<template>
  <div class="flex">
    <label class="toggle-switch">
      <input type="checkbox" :checked="modelValue" @change="$emit('update:modelValue', $event.target.checked)" />
      <span class="slider"></span>
    </label>
    <div class="ml-2 text-gray-700 dark:text-gray-200">
      {{ modelValue ? trueLabel : falseLabel }}
    </div>

  </div>
</template>

<script setup>
  const props = defineProps({
    modelValue: {
      type: Boolean,
      required: true,
    },
    trueLabel: {
      type: String,
      default: 'Live',
    },
    falseLabel: {
      type: String,
      default: 'Draft',
    },
  })
  defineEmits(['update:modelValue'])
</script>

<style scoped>
  .toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
  }

  .toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: 0.4s;
    border-radius: 24px;
  }

  .slider::before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
  }

  input:checked+.slider {
    background-color: #e0006c;
  }

  input:checked+.slider::before {
    transform: translateX(26px);
  }

  .status-label {
    margin-left: 10px;
    font-weight: 500;
    color: #333;
  }
</style>