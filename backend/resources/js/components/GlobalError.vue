<template>
    <div v-if="message" class="fixed bottom-4 right-4 bg-red-600 text-white px-4 py-2 rounded shadow-md">
        {{ message }}
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const message = ref('');

onMounted(() => {
    window.addEventListener('global-error', (e) => {
        message.value = e.detail;
        setTimeout(() => message.value = '', 4000);
    });
});
</script>
