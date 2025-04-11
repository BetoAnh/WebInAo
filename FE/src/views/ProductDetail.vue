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
                >{{ formatCurrency(formattedPrice) }}</a-flex
              >
              <a-flex class="text-[15px] text-black"
                >{{ formatCurrency(price) }}/sp</a-flex
              >
            </a-flex>
            <a-flex class="h-full justify-center items-center gap-1"
              ><span class="text-[15px] text-black">Số lượng:</span
              ><a-input-number
                v-model:value="count"
                size="large"
                :min="1"
                :max="99"
            /></a-flex>
          </a-flex>
          <a-flex v-if="product.avaiable == 1" class="relative mx-5">
            <a-flex class="w-full justify-center items-center gap-5">
              <a-button
                class="flex-1 bg-[#FE4344] flex justify-center items-center p-6 opacity-80 rounded-md hover:opacity-100 cursor-pointer"
                @click="handleAddToCart(product)"
              >
                <span class="text-white text-[20px] font-medium">Đặt hàng</span>
              </a-button>
            </a-flex>
          </a-flex>
        </a-flex>
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
                  <div
                    class="absolute z-0 inset-0 opacity-95"
                    :style="{ backgroundColor: variant.colorValue }"
                  ></div>
                  <!-- Ảnh chính -->
                  <a-image
                    :src="activeImage?.path"
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
                    v-if="
                      activeImage?.type === 'front' &&
                      product?.front_template?.path
                    "
                    class="absolute w-[50%] z-10 left-[50%] translate-x-[-50%] top-[50%] translate-y-[-50%] flex justify-center items-center"
                  >
                    <a-image
                      :src="product?.front_template?.path"
                      alt="Template Image"
                      :previewMask="false"
                      :preview="false"
                      width="90%"
                    ></a-image>
                  </div>
                  <div
                    v-if="
                      activeImage?.type === 'back' &&
                      product?.back_template?.path
                    "
                    class="absolute w-[50%] z-10 left-[50%] translate-x-[-50%] top-[50%] translate-y-[-50%] flex justify-center items-center"
                  >
                    <a-image
                      :src="product?.back_template?.path"
                      alt="Template Image"
                      :previewMask="false"
                      :preview="false"
                      width="90%"
                    ></a-image>
                  </div>
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
                    <AkChevronUp class="text-[18px] text-black" />
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
                    <div
                      v-if="item.type === 'front' || item.type === 'back'"
                      class="absolute z-0 inset-0 opacity-95"
                      :style="{ backgroundColor: variant.colorValue }"
                    ></div>
                    <a-image
                      :src="item.path"
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
                      v-if="
                        item.type === 'front' && product?.front_template?.path
                      "
                      class="absolute z-10 left-[50%] translate-x-[-50%] top-[55%] translate-y-[-50%] flex"
                    >
                      <a-image
                        :src="product?.front_template?.path"
                        alt="Template Image"
                        :previewMask="false"
                        :preview="false"
                        width="100%"
                      ></a-image>
                    </div>
                    <div
                      v-if="
                        item.type === 'back' && product?.back_template?.path
                      "
                      class="absolute z-10 left-[50%] translate-x-[-50%] top-[55%] translate-y-[-50%] flex"
                    >
                      <a-image
                        :src="product?.back_template?.path"
                        alt="Template Image"
                        :previewMask="false"
                        :preview="false"
                        width="100%"
                      ></a-image>
                    </div>
                  </div>
                  <!-- Nút Xuống -->
                  <div
                    v-if="startIndex + maxVisible < allImages.length"
                    @click="scrollDown"
                    type="primary"
                    size="small"
                    class="absolute bottom-2 left-[50%] translate-x-[-50%] w-10 h-10 rounded-[50%] z-10 bg-white flex justify-center items-center cursor-pointer"
                  >
                    <AkChevronDown class="text-[18px] text-black" />
                  </div>
                </div>
              </div>
            </a-image-preview-group>
          </div>
          <!-- share n description -->
          <div class="flex items-center gap-3 max-lg:justify-center">
            <span class="font-bold text-black text-[17px]"> Chia sẻ: </span>
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
              v-if="product.avaiable == 1"
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
                  ><strong class="my-2 font-semibold text-black"
                    >Màu sắc</strong
                  ></a-flex
                >
                <a-flex>
                  <a-flex class="justify-evenly w-full">
                    <div v-for="color in availableColors" :key="color.id">
                      <a-popover>
                        <template #content>
                          <div class="flex flex-col items-center">
                            <p class="font-semibold">{{ color.name }}</p>
                            <p
                              v-if="
                                !isColorAvailableForSize(
                                  variant.size,
                                  color.name
                                )
                              "
                              class="text-red-400 text-[12px]"
                            >
                              (Hết hàng)
                            </p>
                          </div>
                        </template>
                        <div
                          v-if="
                            isColorAvailableForSize(variant.size, color.name)
                          "
                          @click="selectColor(color)"
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
                        <div
                          v-else
                          class="w-10 h-10 rounded-full transition-all border-2 border-gray-200 hover:shadow-gray-400 hover:border-white hover:shadow-md flex items-center justify-center cursor-not-allowed"
                          :style="{
                            backgroundColor: color.value,
                            position: 'relative',
                          }"
                        ></div>
                      </a-popover></div></a-flex></a-flex></a-flex
            ></a-flex>
            <!-- size -->
            <a-flex vertical class="my-1"
              ><a-flex
                ><strong class="my-2 font-semibold text-black"
                  >Size</strong
                ></a-flex
              >
              <a-flex>
                <a-flex class="justify-evenly w-full">
                  <a-select
                    v-model:value="variant.size"
                    size="large"
                    :options="availableSizes"
                    class="w-full"
                    placeholder="--Chọn size--"
                    @change="changeSize"
                  ></a-select></a-flex></a-flex
            ></a-flex>
            <!-- mặt in -->
            <a-flex vertical class="my-1"
              ><a-flex
                ><strong class="my-2 font-semibold text-black"
                  >Mặt in</strong
                ></a-flex
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
                ><strong class="my-2 font-semibold text-black"
                  >Giới tính</strong
                ></a-flex
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
</template>

<script setup>
import { onMounted, ref, inject, reactive, computed, toRaw, watch } from "vue";
import { useRoute } from "vue-router";
import ProductSpecifications from "@/components/ProductSpecifications.vue";
import ProductPosts from "@/components/ProductPosts.vue";
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
import { addToCart } from "@/store/cartDB";
const { setBreadcrumb } = inject("breadcrumb");
const { fetchCartCount } = inject("countCart");
const count = ref(1);
const route = useRoute();
const product = ref(null);
const activeImage = ref([]);
const activeKey = ref("1");
const currentIndex = ref(0);
const maxVisible = 5;
const startIndex = ref(0);

const price = computed(() => {
  const selectedVariant = product.value?.variant?.find(
    (item) => item.color === variant.color && item.size === variant.size
  );
  if (variant.printed_side == "In 1 mặt")
    return selectedVariant ? selectedVariant.price : 0;
  if (variant.printed_side == "In 2 mặt")
    return selectedVariant ? selectedVariant.price + 50000 : 0;
});

const formattedPrice = computed(() => {
  return price.value * count.value;
});

const formatCurrency = (value) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(value);
};

const allImages = computed(() => {
  let images = [];
  if (product.value?.gallery?.length) {
    images.push(
      ...product.value.gallery.map((img) => ({
        type: "gallery",
        path: img.path,
      }))
    );
  }
  if (product.value?.category?.front_image?.path) {
    images.push({
      type: "front",
      path: product.value.category.front_image.path,
    });
  }
  if (product.value?.category?.back_image?.path) {
    images.push({
      type: "back",
      path: product.value.category.back_image.path,
    });
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

const selectColor = (color) => {
  variant.colorValue = color.value;
  variant.color = color.name;
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

function isColorAvailableForSize(size, color) {
  return product.value.variant.some(
    (v) => v.size === size && v.color === color
  );
}

const changeSize = () => {
  const matchedVariant = product.value.variant.find(
    (v) => v.size === variant.size
  );
  if (matchedVariant) {
    const matchedColor = colors.value.find(
      (v) => v.name === matchedVariant.color
    );
    selectColor(matchedColor);
  } else {
    console.log("Không tìm thấy biến thể phù hợp");
  }
};

const fetchProduct = async () => {
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
    const breadcrumbItems = [
      {
        name: product.value.category?.name,
        url: `/danh-muc/${product.value.category?.slug}`,
      },
      { name: product.value.name, url: `/san-pham/${product.value.slug}` },
    ];

    setBreadcrumb(breadcrumbItems);
  } catch (error) {
    console.error("Không tìm thấy sản phẩm:", error);
  }
};

onMounted(() => {
  fetchProduct();
});

watch(
  () => route.params.slug,
  () => {
    fetchProduct();
  }
);

const getCORSImageURL = (url) => {
  if (!url) {
    return null;
  }
  const base = import.meta.env.VITE_APP_URL_API_UPLOAD;
  if (url.startsWith(base)) {
    const relativePath = url.replace(base, "");
    return `${
      import.meta.env.VITE_APP_URL_API_IMAGE
    }/image-proxy/${relativePath}`;
  }
  return url;
};

const urlToBase64 = (url) => {
  if (!url) return null;
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.crossOrigin = "Anonymous";
    img.onload = () => {
      const scale = 0.3;
      const canvas = document.createElement("canvas");
      canvas.width = img.width * scale;
      canvas.height = img.height * scale;
      const ctx = canvas.getContext("2d");
      ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
      const dataURL = canvas.toDataURL("image/png");
      resolve(dataURL);
    };
    img.onerror = (error) => {
      reject(error);
    };
    img.src = url;
  });
};

const handleAddToCart = async () => {
  const frontImageURL = getCORSImageURL(
    toRaw(product.value?.front_template?.path)
  );
  const backImageURL = getCORSImageURL(
    toRaw(product.value?.back_template?.path)
  );
  let frontImageBase64 = null;
  let backImageBase64 = null;
  try {
    frontImageBase64 = await urlToBase64(frontImageURL);
    backImageBase64 = await urlToBase64(backImageURL);
  } catch (error) {
    console.error("Không thể chuyển đổi hình ảnh thành Base64:", error);
  }
  const cartItem = {
    product: {
      id: product.value.id,
      name: product.value.name,
      slug: product.value.slug,
      frontImage: product.value?.category?.front_image?.path,
      backImage: product.value?.category?.back_image?.path,
    },
    variant: toRaw(variant),
    frontImage: frontImageBase64,
    backImage: backImageBase64,
    quantity: toRaw(count.value),
    price: toRaw(price.value),
    createdAt: Date.now(),
  };
  await addToCart(cartItem);
  console.log("Thêm thiết kế vào giỏ thành công!");
  fetchCartCount();
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
