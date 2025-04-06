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
        <a-popover
          title="Giỏ hàng của tôi"
          class="cursor-pointer p-2"
          trigger="click"
          @openChange="(open) => handleMenuVisible(open, 'cart')"
        >
          <template #content>
            <div class="min-w-[250px] max-h-[300px] overflow-y-auto space-y-3">
              <div
                v-if="cartItems.length === 0"
                class="text-center text-gray-400"
              >
                Giỏ hàng trống
              </div>
              <div
                v-for="item in cartItems"
                :key="item.id"
                class="border p-2 rounded shadow"
              >
                <div class="flex gap-2">
                  <div
                    class="relative"
                    :style="{ backgroundColor: item.variant.colorValue }"
                  >
                    <img
                      :src="item.product.frontImage"
                      class="w-[100px] h-[100px] object-cover z-30"
                    />
                    <img
                      :src="item.frontImage"
                      class="w-[50px] h-[60px] object-cover absolute top-[55%] translate-y-[-50%] left-[50%] translate-x-[-50%]"
                    />
                  </div>
                  <div class="flex-1">
                    <p class="text-sm font-semibold truncate w-[250px]">
                      {{ item.product.name }}
                    </p>
                    <p class="text-xs text-gray-500">
                      Giá: {{ item.price.toLocaleString() }}₫
                    </p>
                    <p class="text-xs">SL: {{ item.quantity }}</p>
                  </div>
                  <a
                    @click="handleRemoveItem(item.id)"
                    class="text-red-500 text-xs cursor-pointer"
                    >Xoá</a
                  >
                </div>
              </div>
              <div class="py-2">
                <RouterLink
                  class="p-2 border-[1px] border-black rounded-md"
                  to="/cart"
                  >Chi tiết giỏ hàng</RouterLink
                >
              </div>
            </div>
          </template>
          <a-badge :count="cartItems.length" show-zero :offset="[-5, 12]">
            <a-avatar
              shape="square"
              size="large"
              class="flex justify-center items-center"
            >
              <CoCart class="text-[20px]"
            /></a-avatar>
          </a-badge>
        </a-popover>
      </a-flex>
      <a-flex class="gap-2 mx-5">
        <a-button
          class="bg-[#3f4652] border-none py-5 hover:bg-[#2b3039] flex justify-center items-center"
          @click="handleAddToCartAndFetch"
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
import { ref, onMounted } from "vue";
import { CoCart } from "@kalimahapps/vue-icons";
import { getCartItems, removeCartItem } from "@/store/cartDB";
const activePopover = ref(null);
const { handleAddToCart } = defineProps({
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
  handleAddToCart: {
    type: Function,
    required: true,
  },
});
const cartItems = ref([]);

const fetchCartItems = async () => {
  cartItems.value = await getCartItems();
};

const handleAddToCartAndFetch = async () => {
  await handleAddToCart();
  await fetchCartItems();
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
};

const handleMenuVisible = async (open, id) => {
  activePopover.value = open ? id : null;
  if (open && id === "cart") {
    await fetchCartItems();
  }
};

const handleRemoveItem = async (id) => {
  await removeCartItem(id);
  await fetchCartItems();
};

const closeMenu = () => {
  activePopover.value = null;
};

onMounted(() => {
  fetchCartItems();
});
</script>

<style scoped></style>
