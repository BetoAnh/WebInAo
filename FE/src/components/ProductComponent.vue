<template>
  <a-flex vertical class="px-[100px] max-w-[100%] content">
    <a-flex
      justify="center"
      vertical
      class="gap-[40px] justify-between max-w-[100%]"
    >
      <a-flex class="justify-center">
        <RouterLink :to="`/danh-muc/${categoryData.slug}`">
          <span
            class="text-[28px] text-[#02b6ac] font-bold uppercase text-center"
            >{{ categoryData.name }}</span
          ></RouterLink
        >
      </a-flex>
      <a-flex
        horizontal
        class="max-w-[100%] overflow-hidden justify-center gap-[30px]"
        v-if="productData.length > 0"
      >
        <a-flex
          v-for="product in displayedItems"
          :key="product.id"
          class="max-w-[100%] min-w-[100px]"
        >
          <a-flex vertical class="bg-[#F3F4F6] rounded-lg pb-[20px] w-full">
            <a-flex vertical align="center" class="flex-1">
              <div class="w-full relative pt-[20px] justify-center flex">
                <img
                  class="w-[250px]"
                  :src="
                    product.image?.path ||
                    'http://cptudong.vmts.vn/content/images/thumbs/default-image_450.png'
                  "
                />
              </div>
              <a-flex class="px-[10px] w-[70%] text-center max-w-[200px] py-5">
                <a-flex gap="5" vertical class="flex-1 w-[100%]">
                  <a
                    :href="`/san-pham/${product.slug}`"
                    class="text-[16px] font-bold max-w-[100%] text-black hover:bg-[#F3F4F6] hover:text-[#02B6AC] cursor-pointer text-ellipsis overflow-hidden whitespace-nowrap"
                  >
                    {{ product.name ? product.name : "Chưa có tên" }}
                  </a>
                  <span class="text-[16px] font-bold text-[#02B6AC]">
                    {{
                      product.variant[0]?.price
                        ? formatCurrency(product.variant[0]?.price)
                        : "Chưa có giá"
                    }}
                  </span>
                  <a-flex vertical class="gap-[10px] text-[16px]">
                    <button
                      class="flex-1 font-sans border-[1px] border-[#4fa8e7] px-[12px] py-[10px] rounded-full text-white bg-[#02b6ac] hover:bg-[linear-gradient(270deg,_#ccf7fb_2.05%,_#fff_100%)] hover:text-[#424242]"
                      @click="handleProductDetail(product.slug)"
                    >
                      Chi tiết
                    </button>
                  </a-flex>
                </a-flex>
              </a-flex>
            </a-flex>
          </a-flex>
        </a-flex>
      </a-flex>
      <a-flex v-else class="flex-1 justify-center mt-[-40px]"
        >Không có dữ liệu để hiển thị</a-flex
      >
    </a-flex>
  </a-flex>
</template>

<script setup>
import { onMounted, ref, defineProps, onUnmounted, computed } from "vue";
import { RouterLink, useRouter } from "vue-router";
import "./ProductComponent.css";
import axios from "axios";

const router = useRouter();
const categoryData = ref([]);
const productData = ref([]);
const props = defineProps({
  categorySlug: [String, String],
});

const formatCurrency = (value) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
};

const fetchData = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_CATEGORY}/category/${
        props.categorySlug
      }`
    );
    categoryData.value = response.data?.category;
    productData.value = response.data?.products;
  } catch (error) {
    console.error("Lỗi khi lấy dữ liệu:", error);
  }
};

const handleProductDetail = (items) => {
  router.push(`/san-pham/${items}`);
};

onMounted(() => fetchData());

const screenWidth = ref(window.innerWidth);
const maxItems = computed(() => {
  if (screenWidth.value < 720) return 1;
  if (screenWidth.value < 992) return 2;
  if (screenWidth.value < 1200) return 3;
  if (screenWidth.value < 1400) return 4;
  return 4;
});

const updateScreenWidth = () => {
  screenWidth.value = window.innerWidth;
};

onMounted(() => {
  window.addEventListener("resize", updateScreenWidth);
});

onUnmounted(() => {
  window.removeEventListener("resize", updateScreenWidth);
});

const displayedItems = computed(() =>
  productData.value.slice(0, maxItems.value)
);
</script>

<style scoped>
.nav::v-deep(.ant-tabs-tab) {
  margin: 0;
  padding-inline: 0.5rem;
  padding-inline: 16px;
}

.nav::v-deep(.ant-tabs-nav .ant-tabs-tab-btn) {
  font-size: 18px;
  font-weight: 500;
}
.bestseller span::after {
  content: "";
  display: block;
  width: 0;
  height: 0;
  border-right: 10px solid #e3c849;
  border-bottom: 10px solid transparent;
  position: absolute;
  bottom: -10px;
  right: 0;
}

.bestseller span {
  text-align: center;
  font-size: 14px;
  line-height: 13px;
  font-weight: 700;
  min-height: 38px;
  justify-content: center;
  display: flex;
  padding: 7px 10px;
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
  position: relative;
}
:deep(.ant-tabs-nav .ant-tabs-tab-btn::first-letter) {
  text-transform: uppercase;
}
@media only screen and (max-width: 480px) {
  .content {
    height: auto;
    padding-inline: 0;
  }
}
</style>
