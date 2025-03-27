<script setup>
import { computed } from "vue";
import { useRoute } from "vue-router";
import layouts from "./components/layouts";
const route = useRoute();
const layout = computed(() => layouts[route.meta.layout] || layouts.default);
</script>
<template>
  <a-config-provider>
    <component :is="layout">
      <router-view />
    </component>
  </a-config-provider>
</template>

<style scoped></style>
