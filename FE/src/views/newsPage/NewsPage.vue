<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <div class="w-full flex justify-center bg-white text-black">
    <div class="max-w-[1280px] container p-5">
      <a-flex
        class="py-[1rem] w-full text-[#38b6ac] text-[18px] border-b-[1px] border-[#dbe0f0] mb-3"
      >
        <span class="font-medium">BLOG</span>
      </a-flex>
      <ItemNavNewsComponent />
      <ItemLatestNewsComponent />
    </div>
  </div>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import "./news.css";
import ItemLatestNewsComponent from "@/components/news/latestNews/ItemLatestNewsComponent.vue";
import ItemNavNewsComponent from "@/components/news/navNews/ItemNavNewsComponent.vue";
</script>

<style scoped></style>
