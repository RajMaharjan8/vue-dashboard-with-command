<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import logo from '@/components/assets/logo.jpg';
defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <img :src="logo" alt="Company Logo">
</template>
