<template>
  <a-flex class="flex-1 w-[100%] flex-col gap-[50px]">
    <HomeHeroSlideComponent />
    <div class="pb-10 flex flex-col gap-10">
      <ProductComponent :categorySlug="'ao-thun'" />
      <ProductComponent :categorySlug="'ao-hoodie'" />
    </div>
  </a-flex>
</template>

<script setup>
import HomeHeroSlideComponent from "@/components/HomeHeroSlideComponent.vue";
import ProductComponent from "@/components/ProductComponent.vue";
</script>

<style scoped></style>
