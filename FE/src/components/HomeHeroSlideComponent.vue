<template>
  <swiper
    :pagination="{
      clickable: true,
    }"
    :modules="modules"
    class="mySlide max-w-[100%]"
  >
    <swiper-slide>
      <img
        src="../assets/Banner-trang-chu-101.jpg"
      />
    </swiper-slide>
    <swiper-slide>
      <img
        src="../assets/qua-tang-83.jpg"
      />
    </swiper-slide>
  </swiper>
</template>

<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";

import { Pagination } from "swiper";
import "swiper/css";
import "swiper/css/pagination";

const modules = [Pagination];
</script>

<style scoped>
.mySlide::v-deep(.swiper-pagination) {
  width: auto;
  display: flex;
  gap: 15px;
  border-radius: 15px;
  background: #f5f1f1;
  padding: 12px 15px;
  bottom: 24px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 2;
}
.mySlide::v-deep(.swiper-pagination-bullet) {
  background-color: #706a6a;
  opacity: 1;
}

.mySlide::v-deep(.swiper-pagination-bullet-active),
.mySlide::v-deep(.swiper-pagination-bullet):hover {
  background-color: #5893ed;
}

.mySlide img {
  cursor: pointer;
}
</style>
