<template>
  <div>
    <div class="flex text-black gap-10 text-lg">
      <RouterLink to="/">Trang chủ</RouterLink>
      <a-dropdown>
        <a class="ant-dropdown-link" @click.prevent> Sản phẩm</a>
        <template #overlay>
          <a-menu>
            <a-menu-item v-for="category in categories" :key="category.id"
              ><RouterLink :to="`/danh-muc/${category.slug}`"
                ><span>{{ category.name }}</span></RouterLink
              ></a-menu-item
            >
          </a-menu>
        </template>
      </a-dropdown>
      <RouterLink to="/blogs">Blog</RouterLink>
      <RouterLink to="/">Hướng dẫn thiết kế</RouterLink>
      <RouterLink to="/about">Giới thiệu</RouterLink>
      <RouterLink to="/lien-he">Liên hệ</RouterLink>
    </div>
  </div>
</template>
<script setup>
import axios from "axios";
import { onMounted, ref, onUnmounted, computed } from "vue";

const categories = ref([]);

const fetchData = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_CATEGORY}/categories`
    );
    categories.value = response.data;
  } catch (error) {
    console.error("Lỗi khi lấy dữ liệu:", error);
  }
};
onMounted(() => {
  fetchData();
});
</script>

<style scoped></style>
