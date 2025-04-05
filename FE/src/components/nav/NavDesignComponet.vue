<template>
  <div
    class="w-full bg-[#3FC7BA] flex justify-between fixed items-center select-none z-20"
  >
    <a-flex class="text-white font-bold">
      <a-flex class="justify-center items-center mx-7">
        <img
          src="@/assets/logo.png"
          alt="Logo"
          class="h-full object-contain max-w-[130px] invert brightness-0"
        />
      </a-flex>
      <a-flex class="items-center">
        <div class="bg-[#f5efef] opacity-50 h-[25px] w-[1px] mx-2"></div>
        <a-dropdown
          :arrow="true"
          :trigger="['click']"
          class="cursor-pointer p-4"
          @openChange="(open) => handleMenuVisible(open, 'tep')"
        >
          <span
            class="text-[16px] font-normal"
            :class="{ 'z-30': activePopover == 'tep' }"
            @click.prevent
          >
            Tệp
          </span>
          <template #overlay>
            <a-menu>
              <a-menu-item>
                <button @click="exportToJson">Export to JSON</button>
              </a-menu-item>
              <a-menu-item>
                <a href="javascript:;">2nd menu item</a>
              </a-menu-item>
              <a-menu-item>
                <a href="javascript:;">3rd menu item</a>
              </a-menu-item>
            </a-menu>
          </template>
        </a-dropdown>
        <a-popover
          title="Title"
          class="cursor-pointer p-4"
          trigger="click"
          @openChange="(open) => handleMenuVisible(open, 'thiet-ke')"
        >
          <template #content>
            <p>Content</p>
            <p>Content</p>
          </template>
          <span
            class="text-[16px] font-normal"
            :class="{ 'z-30': activePopover == 'thiet-ke' }"
            >Thiết kế</span
          >
        </a-popover>
        <a-popover
          title="Title"
          class="cursor-pointer p-4"
          trigger="click"
          @openChange="(open) => handleMenuVisible(open, 'in')"
        >
          <template #content>
            <button @click="saveAsImage">In thiết kế</button>
            <p>Content</p>
          </template>
          <span
            class="text-[16px] font-normal"
            :class="{ 'z-30': activePopover == 'in' }"
            >In</span
          >
        </a-popover>
        <a-popover
          title="Title"
          class="cursor-pointer p-4"
          trigger="click"
          @openChange="(open) => handleMenuVisible(open, 'tro-giup')"
        >
          <template #content>
            <p>Content</p>
            <p>Content</p>
          </template>
          <span
            class="text-[16px] font-normal"
            :class="{ 'z-30': activePopover == 'tro-giup' }"
            >Trợ giúp</span
          >
        </a-popover>
        <div class="bg-[#f5efef] opacity-50 h-[25px] w-[1px] mx-2"></div>
      </a-flex>
      <a-flex></a-flex>
    </a-flex>
    <a-flex class="justify-center items-center">
      <a-flex class="items-center gap-3">
        <span class="text-[20px] text-white font-semibold">{{
          formatCurrency(formattedPrice)
        }}</span>
        <a-badge count="0" show-zero :offset="[0, 5]">
          <a-avatar
            shape="square"
            size="large"
            class="flex justify-center items-center"
          >
            <CoCart class="text-[20px]"
          /></a-avatar>
        </a-badge>
      </a-flex>
      <a-flex class="gap-2 mx-5">
        <a-button
          class="bg-[#3f4652] border-none py-5 hover:bg-[#2b3039] flex justify-center items-center"
        >
          <span class="text-white font-bold">THÊM GIỎ HÀNG</span></a-button
        >
        <RouterLink to="/">
          <a-button
            class="bg-[#3f4652] border-none py-5 hover:bg-[#2b3039] flex justify-center items-center"
          >
            <span class="text-white font-bold">QUAY LẠI CỬA HÀNG</span>
          </a-button>
        </RouterLink>
      </a-flex>
    </a-flex>
    <!-- Overlay -->
    <div
      v-show="activePopover"
      class="absolute h-screen w-screen top-0 left-0 z-10 bg-black bg-opacity-50"
      @click="closeMenu"
    ></div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { CoCart } from "@kalimahapps/vue-icons";
const activePopover = ref(null);

defineProps({
  formattedPrice: {
    type: Number,
    required: true,
  },
  exportToJson: {
    type: Function,
    required: true,
  },
  saveAsImage: {
    type: Function,
    required: true,
  },
});

const formatCurrency = (value) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
};
const handleMenuVisible = (open, id) => {
  activePopover.value = open ? id : null;
};

// Đóng popover khi click ra ngoài
const closeMenu = () => {
  activePopover.value = null;
};
</script>

<style scoped></style>
