<template>
  <div class="w-full flex justify-center bg-white">
    <div class="max-w-[1280px] container p-5 flex justify-between">
      <div class="w-1/2">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">
          Liên hệ với ARTWEAR:
        </h1>
        <ul class="list-disc pl-6 text-gray-700">
          <li>Địa chỉ: Số 53, Ngách 76/2 Duy Tân, Cầu Giấy, Hà Nội</li>
          <li>Hotline: 0618122007</li>
          <li>
            Email:
            <a href="mailto:info@artwear.vn" class="text-red-500"
              >info@artwear.vn</a
            >
          </li>
          <li>
            Facebook:
            <a href="https://www.facebook.com/artwear" class="text-red-500"
              >https://www.facebook.com/artwear</a
            >
          </li>
          <li>
            Instagram:
            <a href="https://www.instagram.com/artwear/" class="text-red-500"
              >https://www.instagram.com/artwear/</a
            >
          </li>
          <li>
            Zalo OA:
            <a href="https://zalo.me/artwear" class="text-red-500"
              >https://zalo.me/artwear</a
            >
          </li>
        </ul>
      </div>
      <div class="w-1/2"><MapComponent /></div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, inject } from "vue";
const { setBreadcrumb } = inject("breadcrumb");
import MapComponent from "@/components/MapComponent.vue";
onMounted(() => {
  const breadcrumbItems = [
    {
      name: "Liên hệ",
      url: `/lien-he`,
    },
  ];

  setBreadcrumb(breadcrumbItems);
});
</script>

<style scoped></style>
