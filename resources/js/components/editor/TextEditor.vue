<template>
  <div class="border rounded-lg overflow-hidden flex flex-col w-full shadow-sm
         bg-white border-gray-300
         dark:bg-gray-900 dark:border-gray-700">

    <div class="flex flex-wrap items-center gap-2 p-2 select-none
         bg-gray-50 border-b border-gray-200
         dark:bg-gray-800 dark:border-gray-700">

      <div class="relative">
        <select class="h-9 pl-2 pr-8 text-sm rounded cursor-pointer
         border border-gray-300 bg-white text-gray-700
         hover:border-gray-400 focus:outline-none

         dark:bg-gray-900
         dark:border-gray-600
         dark:text-gray-200
         dark:hover:border-gray-500" @change="exec('formatBlock', $event.target.value)">
          <option value="">Text Style</option>
          <option value="p">Paragraph</option>
          <option value="h1">Heading 1</option>
          <option value="h2">Heading 2</option>
          <option value="h3">Heading 3</option>
          <option value="blockquote">Quote</option>
        </select>
      </div>

      <div class="w-px h-6 bg-gray-300 mx-1 dark:bg-gray-600"></div>

      <button v-for="btn in buttons" :key="btn.cmd" @click="exec(btn.cmd)" type="button" :title="btn.title"
        class="p-2 rounded transition-colors duration-150 flex items-center justify-center" :class="isActive(btn.cmd)
            ? 'bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400'
            : 'text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-700'
          ">
        <span v-html="btn.icon"></span>
      </button>

      <div class="w-px h-6 bg-gray-300 mx-1 dark:bg-gray-600"></div>

      <div class="flex items-center border rounded px-1
         bg-white border-gray-300 hover:bg-gray-100
         dark:bg-gray-900 dark:border-gray-600 dark:hover:bg-gray-700" title="Text Color">
        <span class="text-xs font-bold text-gray-500 mr-1 dark:text-gray-300">A</span>
        <input type="color" class="w-6 h-6 border-0 bg-transparent cursor-pointer p-0"
          @input="exec('foreColor', $event.target.value)">
      </div>

      <div class="w-px h-6 bg-gray-300 mx-1 dark:bg-gray-600"></div>

      <label class="cursor-pointer p-2 rounded
         text-gray-600 hover:bg-gray-200
         dark:text-gray-300 dark:hover:bg-gray-700" title="Insert Image">
        <input type="file" accept="image/*" class="hidden" @change="handleImageUpload">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </label>

      <button @click="addLink" type="button" class="p-2 rounded
         text-gray-600 hover:bg-gray-200
         dark:text-gray-300 dark:hover:bg-gray-700" title="Link">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
        </svg>
      </button>

    </div>

    <div ref="editorRef"
      class="editor-content flex-grow p-4 min-h-[300px] outline-none text-base leading-relaxed overflow-y-auto"
      contenteditable="true" @input="onInput" @mouseup="checkState" @keyup="checkState" @paste="onPaste"></div>
  </div>
</template>

<script setup>
  import { ref, onMounted, watch } from 'vue';

  const props = defineProps({
    modelValue: {
      type: String,
      default: ''
    }
  });

  const emit = defineEmits(['update:modelValue']);

  const editorRef = ref(null);
  const activeStates = ref([]); // Stores which commands are currently active (e.g., ['bold', 'italic'])

  // Toolbar Button Configuration
  const buttons = [
    { cmd: 'bold', title: 'Bold', icon: '<b class="font-bold font-serif">B</b>' },
    { cmd: 'italic', title: 'Italic', icon: '<i class="italic font-serif">I</i>' },
    { cmd: 'underline', title: 'Underline', icon: '<u class="font-serif">U</u>' },
    { cmd: 'insertUnorderedList', title: 'Bullet List', icon: '<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="currentColor"><path d="M104 112C90.7 112 80 122.7 80 136L80 184C80 197.3 90.7 208 104 208L152 208C165.3 208 176 197.3 176 184L176 136C176 122.7 165.3 112 152 112L104 112zM256 128C238.3 128 224 142.3 224 160C224 177.7 238.3 192 256 192L544 192C561.7 192 576 177.7 576 160C576 142.3 561.7 128 544 128L256 128zM256 288C238.3 288 224 302.3 224 320C224 337.7 238.3 352 256 352L544 352C561.7 352 576 337.7 576 320C576 302.3 561.7 288 544 288L256 288zM256 448C238.3 448 224 462.3 224 480C224 497.7 238.3 512 256 512L544 512C561.7 512 576 497.7 576 480C576 462.3 561.7 448 544 448L256 448zM80 296L80 344C80 357.3 90.7 368 104 368L152 368C165.3 368 176 357.3 176 344L176 296C176 282.7 165.3 272 152 272L104 272C90.7 272 80 282.7 80 296zM104 432C90.7 432 80 442.7 80 456L80 504C80 517.3 90.7 528 104 528L152 528C165.3 528 176 517.3 176 504L176 456C176 442.7 165.3 432 152 432L104 432z"/></svg>' },
    { cmd: 'insertOrderedList', title: 'Numbered List', icon: '<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="currentColor"><path d="M64 136C64 122.8 74.7 112 88 112L136 112C149.3 112 160 122.7 160 136L160 240L184 240C197.3 240 208 250.7 208 264C208 277.3 197.3 288 184 288L88 288C74.7 288 64 277.3 64 264C64 250.7 74.7 240 88 240L112 240L112 160L88 160C74.7 160 64 149.3 64 136zM94.4 365.2C105.8 356.6 119.7 352 134 352L138.9 352C172.6 352 200 379.4 200 413.1C200 432.7 190.6 451 174.8 462.5L150.8 480L184 480C197.3 480 208 490.7 208 504C208 517.3 197.3 528 184 528L93.3 528C77.1 528 64 514.9 64 498.7C64 489.3 68.5 480.5 76.1 475L146.6 423.7C150 421.2 152 417.3 152 413.1C152 405.9 146.1 400 138.9 400L134 400C130.1 400 126.3 401.3 123.2 403.6L102.4 419.2C91.8 427.2 76.8 425 68.8 414.4C60.8 403.8 63 388.8 73.6 380.8L94.4 365.2zM288 128L544 128C561.7 128 576 142.3 576 160C576 177.7 561.7 192 544 192L288 192C270.3 192 256 177.7 256 160C256 142.3 270.3 128 288 128zM288 288L544 288C561.7 288 576 302.3 576 320C576 337.7 561.7 352 544 352L288 352C270.3 352 256 337.7 256 320C256 302.3 270.3 288 288 288zM288 448L544 448C561.7 448 576 462.3 576 480C576 497.7 561.7 512 544 512L288 512C270.3 512 256 497.7 256 480C256 462.3 270.3 448 288 448z"/></svg>' },
    { cmd: 'justifyLeft', title: 'Align Left', icon: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="17" y1="10" x2="3" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="17" y1="18" x2="3" y2="18"></line></svg>' },
    { cmd: 'justifyCenter', title: 'Align Center', icon: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="10" x2="6" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="18" y1="18" x2="6" y2="18"></line></svg>' },
  ];

  // 1. Initialize Content
  onMounted(() => {
    if (editorRef.value) {
      editorRef.value.innerHTML = props.modelValue;
    }
  });

  // 2. Watch for external changes (prevent cursor jumping)
  watch(() => props.modelValue, (newVal) => {
    if (editorRef.value && editorRef.value.innerHTML !== newVal) {
      // Only update if content is actually different
      // Note: This is a simplistic check. In production, deep compare is better.
      editorRef.value.innerHTML = newVal;
    }
  });

  // 3. Core formatting function
  const exec = (command, value = null) => {
    document.execCommand(command, false, value);
    editorRef.value.focus(); // Keep focus in editor
    checkState(); // Update active buttons immediately
  };

  // 4. Update Button States (The "Research" part: queryCommandState)
  const checkState = () => {
    const states = [];
    buttons.forEach(btn => {
      if (document.queryCommandState(btn.cmd)) {
        states.push(btn.cmd);
      }
    });
    activeStates.value = states;
  };

  const isActive = (cmd) => activeStates.value.includes(cmd);

  // 5. Handle Input
  const onInput = () => {
    emit('update:modelValue', editorRef.value.innerHTML);
  };

  // 6. Handle Image Upload (Native File Reader)
  const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        exec('insertImage', e.target.result);
      };
      reader.readAsDataURL(file);
    }
    // Reset input
    event.target.value = '';
  };

  // 7. Add Link
  const addLink = () => {
    const url = prompt('Enter URL:');
    if (url) exec('createLink', url);
  };

  // 8. Sanitize Paste (Prevent Word bloat)
  const onPaste = (e) => {
    e.preventDefault();
    // Get plain text (removes styles). 
    // For advanced HTML sanitization, you'd need a library like DOMPurify here.
    const text = (e.originalEvent || e).clipboardData.getData('text/plain');
    document.execCommand('insertText', false, text);
  };
</script>

<style scoped>

  /* Scoped styles to ensure the editor content looks nice */
  .editor-content {
    /* Mimic standard word processor spacing */
  }

  .editor-content :deep(p) {
    margin-bottom: 0.75em;
  }

  .editor-content :deep(h1) {
    font-size: 2em;
    font-weight: bold;
    margin-bottom: 0.5em;
  }

  .editor-content :deep(h2) {
    font-size: 1.5em;
    font-weight: bold;
    margin-bottom: 0.5em;
  }

  .editor-content :deep(ul) {
    list-style-type: disc;
    padding-left: 1.5em;
    margin-bottom: 0.75em;
  }

  .editor-content :deep(ol) {
    list-style-type: decimal;
    padding-left: 1.5em;
    margin-bottom: 0.75em;
  }

  .editor-content :deep(blockquote) {
    border-left: 4px solid #e5e7eb;
    padding-left: 1rem;
    color: #4b5563;
    font-style: italic;
  }

  .editor-content :deep(img) {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    margin: 10px 0;
  }
</style>