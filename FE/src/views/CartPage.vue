<template>
  <div class="w-full flex justify-center bg-white">
    <div class="max-w-[1280px] container p-5">
      <div class="text-black">
        <h1 class="font-bold text-2xl">Chi tiết đơn hàng</h1>
      </div>
      <div class="w-full overflow-x-auto" v-if="haveData">
        <div class="flex w-full gap-16">
          <div class="flex-1">
            <div
              v-for="item in cartItems"
              :key="item.id"
              class="w-full border-b-[1px] border-gray-300"
            >
              <div class="flex justify-between w-full">
                <div class="flex">
                  <div
                    class="relative w-[150px] cursor-pointer m-5"
                    @click="item.showBack = !item.showBack"
                  >
                    <div
                      v-if="item.showBack"
                      :style="{ backgroundColor: item.variant.colorValue }"
                    >
                      <img
                        :src="item.product.backImage"
                        class="w-[150px] h-[150px] object-cover z-30"
                      />
                      <img
                        :src="item.backImage"
                        class="w-[75px] object-cover absolute top-[53%] left-[50%] translate-x-[-50%] translate-y-[-50%]"
                      />
                    </div>
                    <div
                      v-else
                      :style="{ backgroundColor: item.variant.colorValue }"
                    >
                      <img
                        :src="item.product.frontImage"
                        class="w-[150px] h-[150px] object-cover z-30"
                      />
                      <img
                        :src="item.frontImage"
                        class="w-[75px] object-cover absolute top-[53%] left-[50%] translate-x-[-50%] translate-y-[-50%]"
                      />
                    </div>
                  </div>
                  <div class="flex flex-col justify-between py-2">
                    <RouterLink :to="`/san-pham/${item.product.slug}`"
                      ><h2 class="text-black font-semibold text-lg">
                        {{ item.product.name }}
                      </h2></RouterLink
                    >
                    <div>
                      <p>
                        <span class="text-gray-500">Số mặt in: </span>
                        <span class="text-black">2</span>
                      </p>
                      <p>
                        <span class="text-gray-500">Màu sắc: </span>
                        <span class="text-black">{{ item.variant.color }}</span>
                      </p>
                      <p>
                        <span class="text-gray-500">Kích thước: </span>
                        <span class="text-black">{{ item.variant.size }}</span>
                      </p>
                      <p>
                        <span class="text-gray-500">Mặt in: </span>
                        <span class="text-black">{{
                          item.variant.printed_side
                        }}</span>
                      </p>
                      <p>
                        <span class="text-gray-500">Giới tính: </span>
                        <span class="text-black">{{ item.variant.sex }}</span>
                      </p>
                    </div>
                    <div>
                      <span class="text-[#4E54CA] font-semibold text-xl">{{
                        formatPrice(item.price * item.quantity)
                      }}</span>
                    </div>
                  </div>
                </div>
                <div class="gap-3 flex flex-col justify-center">
                  <div class="flex flex-col gap-1">
                    <strong class="text-black">Số lượng</strong>
                    <a-input-number
                      id="inputNumber"
                      v-model:value="item.quantity"
                      :min="1"
                      :max="100"
                      size="large"
                      @change="handleChangeQuantity(toRaw(item))"
                    />
                  </div>
                  <div>
                    <button @click="deleteItem(item.id)">
                      <span
                        class="flex gap-1 justify-center items-center text-[15px] text-red-500"
                        ><FlDelete class="text-[20px]" />Xóa</span
                      >
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div></div>
          </div>
          <div class="w-[350px] shadow-lg rounded-lg bg-white">
            <div class="mx-10 border-b-[1px] border-gray-300 py-7">
              <div class="flex justify-center items-center p-5">
                <h2 class="text-black font-semibold text-lg">Thành tiền</h2>
              </div>
              <div class="flex justify-between">
                <span class="text-black text-[15px] font-medium"
                  >Tổng tiền:</span
                ><span class="text-black text-[15px] font-medium">{{
                  formatPrice(totalPrice)
                }}</span>
              </div>
            </div>
            <div
              class="mx-10 border-b-[1px] border-gray-300 py-5 flex justify-center items-center"
            >
              <span class="text-black text-xl font-semibold">{{
                formatPrice(totalPrice)
              }}</span>
            </div>
            <div
              class="bg-[#3fb696] p-4 text-white text-center flex-1 cursor-pointer m-10 rounded-md"
              @click="handlePayment"
            >
              <span class="text-lg font-bold">Proceed to checkout</span>
            </div>
          </div>
        </div>
      </div>
      <a-flex v-else vertical class="my-[20px]" gap="3">
        <span class="font-semibold text-black"
          >Your cart is currently empty.</span
        >
        <p>
          <button
            @click="handleHome"
            class="justify-start flex bg-[#DCD7E3] px-4 py-[0.618em] rounded-[3px] font-bold text-[#515151]"
          >
            Return to shop
          </button>
        </p>
      </a-flex>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, inject, toRaw, computed } from "vue";
import { useRouter } from "vue-router";
import { getCartItems, removeCartItem, updateCartItem } from "@/store/cartDB";
import { FlDelete } from "@kalimahapps/vue-icons";
import { notification } from "ant-design-vue";
const { setBreadcrumb } = inject("breadcrumb");
const { fetchCartCount } = inject("countCart");
const router = useRouter();
const cartItems = ref([]);
const haveData = ref(false);

const totalPrice = computed(() => {
  return cartItems.value.reduce((total, item) => {
    return total + item.price * item.quantity;
  }, 0);
});

onMounted(() => {
  fetchData();
  const breadcrumbItems = [
    {
      name: "Giỏ hàng",
      url: "/cart",
    },
  ];
  setBreadcrumb(breadcrumbItems);
});

const fetchData = async () => {
  const items = await getCartItems();
  if (items.length > 0) {
    cartItems.value = items;
    haveData.value = true;
  } else {
    haveData.value = false;
  }
  fetchCartCount();
};

const formatPrice = (value) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
};

const handleChangeQuantity = async (item) => {
  if (item.quantity !== 0 && item.quantity !== null) {
    await updateCartItem(item.id, item);
    fetchData(); // Làm mới giỏ hàng sau khi cập nhật số lượng
    notification.success({
      message: `Cập nhật giỏ hàng thành công`,
      placement: "bottomLeft",
    });
  }
};

const deleteItem = async (itemId) => {
  await removeCartItem(itemId);
  fetchData(); // Làm mới giỏ hàng sau khi xóa món hàng
};

const handlePayment = () => {
  router.push("/payment");
};

const handleHome = () => {
  router.push("/"); // Trở về trang chủ
};
</script>

<style scoped></style>
