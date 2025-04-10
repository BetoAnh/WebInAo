<template>
  <a-flex class="w-full justify-center items-center bg-[#F6F9FC]">
    <a-flex class="flex-1 justify-between p-5 max-w-[1280px] container">
      <a-breadcrumb>
        <a-breadcrumb-item>
          <router-link to="/"
            ><a-flex class="justify-center items-center gap-1"
              ><AnOutlinedHome class="text-black" />Trang chủ</a-flex
            ></router-link
          >
        </a-breadcrumb-item>
        <a-breadcrumb-item
          v-for="(item, index) in breadcrumbItems"
          :key="index"
        >
          <router-link :to="item.url">{{ item.name }}</router-link>
        </a-breadcrumb-item>
      </a-breadcrumb>
    </a-flex>
  </a-flex>
</template>

<script setup>
import { inject } from "vue";
import { AnOutlinedHome } from "@kalimahapps/vue-icons";
const { breadcrumbItems } = inject("breadcrumb");
</script>
