<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <section v-if="product" class="w-full flex justify-center bg-white">
    <div :key="product.id" class="max-w-[1280px] container p-5">
      <a-flex class="w-full justify-between max-lg:flex-col">
        <a-flex class="w-full flex-1">
          <h1 class="text-black max-md:text-center text-[32px] font-semibold">
            {{ product.name }}
          </h1>
        </a-flex>
        <a-flex class="lg:w-1/3 justify-end gap-1 flex-col mb-2">
          <a-flex
            class="max-lg:w-full justify-center gap-5 lg:justify-between mx-6"
          >
            <a-flex vertical class="items-end">
              <a-flex
                class="justify-center items-center text-[30px] text-red-400 font-medium gap-1 flex-1"
                >{{ formattedPrice }}239.000
                <span class="font-bold mb-1">₫</span></a-flex
              >
              <a-flex class="text-[15px]">239.000 đ / sp</a-flex>
            </a-flex>
            <a-flex class="h-full justify-center items-center gap-1"
              ><span class="text-[15px]">Số lượng:</span
              ><a-input-number
                v-model:value="value1"
                size="large"
                :min="1"
                :max="99"
            /></a-flex>
          </a-flex>
          <a-flex class="relative mx-5">
            <a-flex class="w-full justify-center items-center gap-5">
              <a-button
                class="flex-1 bg-[#FE4344] flex justify-center items-center p-6 opacity-80 rounded-md hover:opacity-100 cursor-pointer"
                @click="handleAddToCart(product)"
              >
                <span class="text-white text-[20px] font-medium">Đặt hàng</span>
              </a-button>
              <a-button
                class="flex-1 bg-[#38B6AC] flex justify-center items-center p-6 rounded-md opacity-80 hover:opacity-100 cursor-pointer"
                @click="addToComparison(product)"
              >
                <span class="text-white text-[20px] font-medium">So Sánh</span>
              </a-button>
            </a-flex>
            <div
              v-if="compare"
              class="bg-[#f0fffb] absolute z-30 top-[44px] mt-[5px] pb-[5px] rounded-[5px] right-0 border-[#ededed] border-[1px] flex flex-col w-[calc(50%-10px)] items-center"
            >
              <span class="font-medium"> Thêm so sánh thành công </span>
              <a
                href="/compareProducts"
                class="hover:bg-[#f0fffb] text-[#4bc4a3] text-[14px] leading-[16px]"
              >
                Xem so sánh sản phẩm
              </a>
            </div>
          </a-flex></a-flex
        >
      </a-flex>
      <div class="w-full flex flex-wrap gap-2 max-lg:flex-col">
        <div class="flex-1 flex flex-col">
          <!-- img -->
          <div class="flex-1 flex my-3 gap-10">
            <a-image-preview-group>
              <div
                class="flex-1 flex justify-center items-center rounded-md overflow-hidden relative"
              >
                <a-flex class="relative w-full h-full">
                  <!-- Ảnh chính -->
                  <a-image
                    :src="activeImage"
                    alt="Product Image"
                    :previewMask="false"
                    :preview="false"
                    width="100%"
                    ><template #placeholder>
                      <img
                        src="@/assets/error_img.png"
                        alt="Loading..."
                        class="w-full h-full object-cover"
                      />
                    </template>
                  </a-image>
                  <div
                    class="absolute -z-10 inset-0 opacity-95"
                    :style="{ backgroundColor: variant.colorValue }"
                  ></div>
                </a-flex>
                <!-- Mũi tên trái -->
                <a-button
                  v-if="product.gallery.length > 1"
                  @click="previousImage"
                  shape="circle"
                  type="default"
                  class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-transparent border-none flex justify-center items-center"
                >
                  <template #icon>
                    <AnOutlinedLeft
                      class="text-[35px] text-white opacity-50 hover:opacity-100"
                    />
                    <!-- Mũi tên trái -->
                  </template>
                </a-button>
                <!-- Mũi tên phải -->
                <a-button
                  v-if="product.gallery.length > 1"
                  @click="nextImage"
                  shape="circle"
                  type="default"
                  class="absolute right-2 top-1/2 transform -translate-y-1/2 border-none bg-transparent flex justify-center items-center"
                >
                  <template #icon>
                    <AnOutlinedRight
                      class="text-[35px] text-white opacity-50 hover:opacity-100"
                    />
                    <!-- Mũi tên phải -->
                  </template>
                </a-button>
              </div>

              <!-- Ảnh thu nhỏ -->
              <div class="flex flex-col gap-2 items-center">
                <div class="flex flex-col gap-2 relative">
                  <!-- Nút Lên -->
                  <div
                    v-if="startIndex > 0"
                    @click="scrollUp"
                    type="primary"
                    size="small"
                    class="absolute top-2 left-[50%] translate-x-[-50%] w-10 h-10 rounded-[50%] z-10 bg-white flex justify-center items-center cursor-pointer"
                  >
                    <AkChevronUp class="text-[18px]" />
                  </div>
                  <div
                    v-for="(item, index) in visibleImages"
                    :key="index"
                    @click="setActiveImage(item)"
                    :class="[
                      'rounded-md border cursor-pointer transition-all duration-300 relative overflow-hidden',
                      item === activeImage ? 'ring-1 ring-red-400' : '',
                    ]"
                  >
                    <a-image
                      :src="item"
                      :width="100"
                      :height="100"
                      alt="Product Image"
                      :previewMask="false"
                      :preview="false"
                      ><template #placeholder>
                        <img
                          src="@/assets/error_img.png"
                          alt="Loading..."
                          class="w-full h-full object-cover"
                        />
                      </template>
                    </a-image>
                    <div
                      class="absolute -z-10 inset-0 opacity-95"
                      :style="{ backgroundColor: variant.colorValue }"
                    ></div>
                  </div>
                  <!-- Nút Xuống -->
                  <div
                    v-if="startIndex + maxVisible < allImages.length"
                    @click="scrollDown"
                    type="primary"
                    size="small"
                    class="absolute bottom-2 left-[50%] translate-x-[-50%] w-10 h-10 rounded-[50%] z-10 bg-white flex justify-center items-center cursor-pointer"
                  >
                    <AkChevronDown class="text-[18px]" />
                  </div>
                </div>
              </div>
            </a-image-preview-group>
          </div>
          <!-- share n description -->
          <div class="flex items-center gap-3 max-lg:justify-center">
            <span class="font-bold text-[17px]"> Chia sẻ: </span>
            <a-flex class="gap-2"
              ><a href="https://x.com"
                ><a-flex
                  class="justify-center items-center gap-1 px-2 py-1 text-[17px] text-[#1DA1F2] rounded-sm bg-[#1DA1F2] bg-opacity-10 hover:text-white hover:bg-opacity-100"
                  ><AkTwitterFill /><span class="text-[14px] font-semibold"
                    >Twitter</span
                  ></a-flex
                ></a
              >
              <a href="https://facebook.com"
                ><a-flex
                  class="justify-center items-center gap-1 px-2 py-1 text-[17px] text-[#3B5998] rounded-sm bg-[#3B5998] bg-opacity-10 hover:text-white hover:bg-opacity-100"
                  ><ThFacebook /><span class="text-[14px] font-semibold"
                    >Facebook</span
                  ></a-flex
                ></a
              >
              <a href="https://pinterest.com"
                ><a-flex
                  class="justify-center items-center gap-1 px-2 py-1 text-[17px] text-[#BD081C] rounded-sm bg-[#BD081C] bg-opacity-10 hover:text-white hover:bg-opacity-100"
                  ><CoBrandPinterestP /><span class="text-[14px] font-semibold"
                    >Pinterest</span
                  ></a-flex
                ></a
              ></a-flex
            >
          </div>
          <div v-html="product.description" class="text-[16px]"></div>
        </div>
        <div class="w-1/3 max-lg:w-full flex gap-2 flex-col">
          <a-flex vertical class="mx-5"
            ><a-flex
              class="p-5 border-y-[1px] border-gray-200 justify-center items-center my-3"
            >
              <RouterLink
                :to="`/tu-thiet-ke/${product.slug}`"
                class="flex gap-2 justify-center items-center bg-[#2BC784] opacity-85 hover:opacity-100 text-[15px] border-none p-3 rounded-md"
                style="color: white !important"
                ><BsPencilSquare /><span class=""
                  >Chỉnh sửa thiết kế</span
                ></RouterLink
              >
            </a-flex>
            <a-flex vertical>
              <!-- color -->
              <a-flex vertical class="my-1">
                <a-flex
                  ><strong class="my-2 font-semibold">Màu sắc</strong></a-flex
                >
                <a-flex>
                  <a-flex class="justify-evenly w-full">
                    <div
                      v-for="color in availableColors"
                      :key="color.id"
                      @click="selectColor(color.value)"
                    >
                      <a-popover>
                        <template #content>
                          <p class="font-semibold">{{ color.name }}</p>
                        </template>
                        <div
                          class="w-10 h-10 rounded-full transition-all border-2 border-gray-200 hover:shadow-gray-400 hover:border-white hover:shadow-md flex items-center justify-center cursor-pointer"
                          :style="{
                            backgroundColor: color.value,
                            position: 'relative',
                          }"
                        >
                          <AkCheck
                            v-if="variant.colorValue === color.value"
                            :class="
                              variant.colorValue == '#FFFFFF'
                                ? 'text-black text-2xl'
                                : 'text-white text-2xl'
                            "
                          />
                        </div>
                      </a-popover></div></a-flex></a-flex></a-flex
            ></a-flex>
            <!-- size -->
            <a-flex vertical class="my-1"
              ><a-flex><strong class="my-2 font-semibold">Size</strong></a-flex>
              <a-flex>
                <a-flex class="justify-evenly w-full">
                  <a-select
                    v-model:value="variant.size"
                    size="large"
                    :options="availableSizes"
                    class="w-full"
                    placeholder="--Chọn size--"
                  ></a-select></a-flex></a-flex
            ></a-flex>
            <!-- mặt in -->
            <a-flex vertical class="my-1"
              ><a-flex
                ><strong class="my-2 font-semibold">Mặt in</strong></a-flex
              >
              <a-flex>
                <a-flex class="justify-evenly w-full">
                  <a-select
                    v-model:value="variant.printed_side"
                    size="large"
                    :options="printed_side"
                    class="w-full"
                    placeholder="--Chọn mặt in--"
                  ></a-select></a-flex></a-flex
            ></a-flex>
            <!-- giới tính -->
            <a-flex vertical class="my-1"
              ><a-flex
                ><strong class="my-2 font-semibold">Giới tính</strong></a-flex
              >
              <a-flex>
                <a-flex class="justify-evenly w-full">
                  <a-select
                    v-model:value="variant.sex"
                    size="large"
                    :options="sex"
                    class="w-full"
                    placeholder="--Chọn giới tính--"
                  ></a-select></a-flex></a-flex
            ></a-flex>
          </a-flex>
        </div>
      </div>
      <div class="my-4 w-full flex max-lg:block">
        <a-flex class="flex-1">
          <a-tabs v-model:activeKey="activeKey" size="large">
            <a-tab-pane key="1"
              ><template #tab
                ><span class="font-bold text-[20px]">Giới thiệu</span></template
              >
              <ProductPosts v-if="product.post != null" :items="product.post"
            /></a-tab-pane>
            <a-tab-pane key="2" force-render>
              <template #tab
                ><span class="font-bold text-[20px]"
                  >Mô tả sản phẩm</span
                ></template
              ><ProductSpecifications
                v-if="product.property != null"
                :items="product.property"
            /></a-tab-pane>
          </a-tabs>
        </a-flex>
        <a-flex class="w-1/3">
          <a-flex class="p-3">
            <h2 class="font-bold text-[20px]">Có thể bạn quan tâm</h2>
          </a-flex>
        </a-flex>
      </div>
    </div>
  </section>
  <a-flex class="w-full justify-center items-center h-[100px]" v-else
    ><a-spin
  /></a-flex>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import { onMounted, ref, inject, reactive, computed } from "vue";
import { useRoute } from "vue-router";
import ProductSpecifications from "@/components/ProductSpecifications.vue";
import ProductPosts from "@/components/ProductPosts.vue";
import store from "@/store/store";
import axios from "axios";
import {
  CoBrandPinterestP,
  AkTwitterFill,
  ThFacebook,
  AnOutlinedRight,
  AnOutlinedLeft,
  BsPencilSquare,
  AkCheck,
  AkChevronUp,
  AkChevronDown,
} from "@kalimahapps/vue-icons";

const { setBreadcrumb } = inject("breadcrumb");
const value1 = ref(3);
const route = useRoute();
const product = ref(null);
const formattedPrice = ref(null);
const activeImage = ref(null);
const activeKey = ref("1");
const compare = ref(false);
const currentIndex = ref(0);
const maxVisible = 5;
const startIndex = ref(0);

const allImages = computed(() => {
  let images = product.value?.gallery?.map((img) => img.path) || [];
  if (product.value?.category?.front_image?.path) {
    images.push(product.value.category.front_image.path);
  }
  if (product.value?.category?.back_image?.path) {
    images.push(product.value.category.back_image.path);
  }
  activeImage.value = images[0];
  return images;
});

const visibleImages = computed(() => {
  return allImages.value.slice(startIndex.value, startIndex.value + maxVisible);
});

const scrollUp = () => {
  if (startIndex.value > 0) {
    startIndex.value--;
  }
};

const scrollDown = () => {
  if (startIndex.value + maxVisible < allImages.value.length) {
    startIndex.value++;
  }
};

const setActiveImage = (path) => {
  activeImage.value = path;
  currentIndex.value = allImages.value.findIndex((item) => item === path);
};

const previousImage = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--;
    activeImage.value = allImages.value[currentIndex.value];
  }
};

const nextImage = () => {
  if (currentIndex.value < allImages.value.length - 1) {
    currentIndex.value++;
    activeImage.value = allImages.value[currentIndex.value];
  }
};

const sex = ref([
  { id: 1, value: "Nam" },
  { id: 2, value: "Nữ" },
]);

const printed_side = ref([
  { id: 1, value: "In 1 mặt" },
  { id: 2, value: "In 2 mặt" },
]);

const sizes = ref([
  { id: 1, value: "XS" },
  { id: 2, value: "S" },
  { id: 3, value: "M" },
  { id: 4, value: "L" },
  { id: 5, value: "XL" },
  { id: 6, value: "2XL" },
  { id: 7, value: "3XL" },
  { id: 8, value: "4XL" },
  { id: 9, value: "5XL" },
]);

const colors = ref([
  { id: 1, name: "Trắng", value: "#FFFFFF" },
  { id: 2, name: "Đen", value: "#000000" },
  { id: 3, name: "Hồng", value: "#FFC0CB" },
  { id: 4, name: "Xanh", value: "#1E90FF" },
  { id: 5, name: "Vàng", value: "#FFD700" },
  { id: 6, name: "Xám", value: "#808080" },
  { id: 7, name: "Đỏ", value: "#FF0000" },
]);

const variant = reactive({
  color: null,
  size: null,
  colorValue: null,
  printed_side: printed_side.value.find((item) => item.id == 1).value,
  sex: sex.value.find((item) => item.id == 1).value,
});

const selectColor = (value) => {
  variant.colorValue = value;
};

const availableSizes = computed(() => {
  if (!product.value.variant || product.value.variant.length === 0) return []; // Kiểm tra nếu variants chưa có dữ liệu
  const variantSizes = new Set(product.value.variant.map((v) => v.size));
  return sizes.value.filter((size) => variantSizes.has(size.value));
});

const availableColors = computed(() => {
  if (!product.value.variant || product.value.variant.length === 0) return [];
  const variantColors = new Set(product.value.variant.map((v) => v.color));
  return colors.value.filter((color) => variantColors.has(color.name));
});

onMounted(async () => {
  try {
    const { slug } = route.params;
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_PRODUCT}/product/${slug}`
    );
    product.value = response.data;
    if (product.value.variant && product.value.variant.length > 0) {
      const firstVariant = product.value.variant[0];
      variant.colorValue = colors.value.find(
        (color) => color.name == firstVariant.color
      ).value;
      variant.size = firstVariant.size;
      variant.color = firstVariant.color;
    }
    console.log(variant);

    const breadcrumbItems = [
      {
        name: product.value.category?.parent?.name,
        url: `/danh-muc/${product.value.category?.parent?.slug}`,
      },
      {
        name: product.value.category?.name,
        url: `/danh-muc/${product.value.category?.parent?.slug}/${product.value.category?.slug}`,
      },
      { name: product.value.name, url: `/san-pham/${product.value.slug}` },
    ];

    setBreadcrumb(breadcrumbItems);
  } catch (error) {
    console.error("Không tìm thấy sản phẩm:", error);
  }
});

const handleAddToCart = async (data) => {
  const currentCart = store.getters["product/getDataStoreCart"] || [];

  let itemExists = false;
  const updatedCart = currentCart.map((item) => {
    if (item.id === data.id) {
      itemExists = true;
      return { id: item.id, quantity: (item.quantity || 1) + 1 };
    }
    return item;
  });

  if (!itemExists) {
    updatedCart.push({ id: data.id, quantity: 1 });
  }

  store.commit("product/setDataStoreCart", {
    dataStoreCart: updatedCart,
  });
};

const addToComparison = (product) => {
  if (!product || !product.id) {
    alert("Thêm thất bại: Sản phẩm không hợp lệ");
    return;
  }

  const currentProducts = store.getters["product/getDataStoreProducts"] ?? [];

  const differentProduct = currentProducts.find(
    (item) => item.category?.name !== product.category?.name
  );

  if (differentProduct) {
    alert("Sản phẩm bạn chọn không cùng chuyên mục");
    return;
  }

  const existProduct = currentProducts.some((item) => item.id === product.id);
  if (existProduct) {
    alert("Sản phẩm đã tồn tại trong danh sách so sánh");
    compare.value = true;
    return;
  }

  const updatedProducts = [...currentProducts, product];
  store.commit("product/setDataStoreProducts", {
    dataStoreProducts: updatedProducts,
  });

  compare.value = true;
};
</script>

<style>
.description ul {
  list-style: none;
  padding: 0;
}

.description ul li {
  position: relative;
  padding-left: 1.5em;
  margin-top: 10px;
}

.description ul li::before {
  content: "•";
  color: #38b6ac;
  font-size: 1.5em;
  position: absolute;
  left: 0;
  top: -5px;
}

.gallery-image {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 4px;
}
</style>
<style scoped>
:deep(.ant-image) {
  display: flex;
  justify-content: center;
}
</style>
