<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <div class="w-full flex justify-center bg-white">
    <div class="max-w-[1280px] container p-5">
      <section class="w-full flex flex-col gap-[30px]">
        <a-flex vertical class="w-full">
          <a-flex
            class="mb-[1rem] border-t-[1px] border-b-[1px] border-[#dbe0f0] bg-[#f6f9ff] gap-[24px] w-full"
          >
            <a-flex class="p-[18px] px-[15px] border-b border-b-[#02b6ac]">
              <h1 class="text-[16px] font-bold text-[#02b6ac] capitalize">
                {{ categoryData.name }}
              </h1>
            </a-flex>
          </a-flex>
          <a-form></a-form>
          <a-flex
            v-if="Object.keys(selectedFilter).length > 0"
            class="flex-wrap gap-2"
          >
            <a-flex
              v-for="(item, filterId) in selectedFilter"
              :key="filterId"
              class="p-1 px-3 bg-[#FDC400] rounded-md mb-[15px]"
            >
              <span class="font-normal text-black">{{ item[0] }}</span>
            </a-flex>
          </a-flex>
          <a-flex class="mb-4">
            <section>
              <a-flex wrap="wrap" gap="small">
                <a-badge
                  :count="Object.keys(selectedFilter).length"
                  :offset="[-15, 0]"
                >
                  <a-flex class="gap-1 items-center sort_item"
                    ><PhLightFunnel class="text-[18px] font-medium" /><span
                      class="text-[14px]"
                      >Bộ lọc</span
                    ></a-flex
                  >
                </a-badge>
                <div v-for="filter in filterData" :key="filter.id">
                  <a-dropdown :trigger="['click']" arrow>
                    <a
                      @click.prevent
                      type="primary"
                      class="gap-1 items-center sort_item flex"
                    >
                      <span class="text-black">{{ filter.name }}</span
                      ><AkChevronDownSmall class="text-black" />
                    </a>
                    <template #overlay>
                      <a-flex vertical class="gap-2 p-0">
                        <a-menu>
                          <a-flex class="flex-wrap gap-2 max-w-[400px]">
                            <div
                              v-for="(item, index) in filter.options"
                              :key="index"
                            >
                              <a-menu-item
                                v-if="filter.type === 'radiobutton'"
                                :key="item.label"
                                @click="selectOption(filter.code, item.label)"
                                class="border"
                              >
                                {{ item.label }}
                              </a-menu-item>
                              <a-menu-item
                                v-if="filter.type === 'range'"
                                :key="item.label"
                                @click="
                                  rangeOption(
                                    filter.code,
                                    item.label,
                                    item.min,
                                    item.max
                                  )
                                "
                                class="border"
                              >
                                {{ item.label }}
                              </a-menu-item>
                            </div>
                          </a-flex>
                        </a-menu>
                        <a-flex
                          class="w-full gap-3 py-2 px-6 bg-white z-10 justify-center"
                          ><a-button
                            class="text-red-500 border-red-500 px-8"
                            @click="clearOption(filter.code)"
                          >
                            <span class="font-bold">Bỏ chọn</span> </a-button
                          ><a-button
                            class="text-white px-5 bg-[#02b6ac]"
                            @click="applyFilter"
                          >
                            <span class="font-bold">Xem kết quả</span>
                          </a-button></a-flex
                        >
                      </a-flex>
                    </template>
                  </a-dropdown>
                </div>
                <div key="price">
                  <a-dropdown :trigger="['click']" arrow>
                    <a
                      @click.prevent
                      type="primary"
                      class="gap-2 items-center sort_item flex text-black"
                    >
                      <CdTag class="text-[17px]" />Giá
                    </a>
                    <template #overlay>
                      <a-flex vertical class="gap-2 p-0">
                        <a-menu>
                          <a-flex class="flex-wrap gap-2 max-w-[335px]">
                            <a-menu-item
                              v-for="item in price"
                              :key="item.label"
                              @click="
                                rangeOption(
                                  'price',
                                  item.label,
                                  item.min,
                                  item.max
                                )
                              "
                              class="border"
                            >
                              {{ item.label }}
                            </a-menu-item>
                          </a-flex>
                        </a-menu>
                        <a-flex
                          class="w-full gap-3 py-2 px-6 bg-white z-10 justify-center"
                          ><a-button
                            class="text-red-500 border-red-500 px-8"
                            @click="clearOption('price')"
                          >
                            <span class="font-bold">Bỏ chọn</span> </a-button
                          ><a-button
                            class="text-white px-5 bg-[#02b6ac]"
                            @click="applyFilter"
                          >
                            <span class="font-bold">Xem kết quả</span>
                          </a-button></a-flex
                        >
                      </a-flex>
                    </template>
                  </a-dropdown>
                </div>
              </a-flex>
            </section>
          </a-flex>
          <a-flex class="justify-between items-center mb-4">
            <a-flex
              ><span class="text-black"
                >Hiển thị tất cả
                <span class="font-bold">{{ productCurrentData.length }}</span>
                kết quả</span
              ></a-flex
            >
            <a-flex
              ><a-select
                ref="select"
                v-model:value="value1"
                style="width: 250px"
                :options="options1"
                @change="handleChange"
              ></a-select>
            </a-flex>
          </a-flex>
          <a-flex horizontal class="flex-wrap gap-8 justify-center">
            <ProductItemComponent
              v-for="product in productCurrentData"
              :key="product.id"
              :product="product"
            />
          </a-flex>
        </a-flex>
      </section>
    </div>
  </div>

  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import ProductItemComponent from "@/components/ProductItemComponent.vue";
import {
  PhLightFunnel,
  AkChevronDownSmall,
  CdTag,
} from "@kalimahapps/vue-icons";

import axios from "axios";
import { ref, onMounted, reactive, watch, inject } from "vue";
import { useRoute } from "vue-router";
const { setBreadcrumb } = inject("breadcrumb");

const route = useRoute();
const categoryData = ref([]);
const productData = ref([]);
const selectedFilter = reactive({});
const productCurrentData = ref([]);
const filterData = ref([]);

const selectOption = (filterId, label) => {
  selectedFilter[filterId] = [label];
  // console.log(selectedFilter);
};

const rangeOption = (filterId, label, min, max) => {
  selectedFilter[filterId] = [label, min, max];
  // console.log(selectedFilter);
};

const clearOption = (filterId) => {
  delete selectedFilter[filterId];
  applyFilter();
};

const applyFilter = async () => {
  if (Object.keys(selectedFilter).length === 0) {
    productCurrentData.value = productData.value;
    return;
  }

  productCurrentData.value = productData.value.filter((product) => {
    return Object.keys(selectedFilter).every((filterId) => {
      const filterValue = selectedFilter[filterId];
      if (!filterValue || filterValue.length === 0) return true;

      if (filterId === "price") {
        const [label, min, max] = filterValue;
        const minVal = min ? parseInt(min) : Number.NEGATIVE_INFINITY;
        const maxVal = max ? parseInt(max) : Number.POSITIVE_INFINITY;

        return product.variant.some(
          (v) => v.price >= minVal && v.price <= maxVal
        );
      }

      // Các bộ lọc như color, size
      return product.variant.some((v) => {
        const productValue = v[filterId];
        if (!productValue) return false;

        return filterValue.includes(productValue);
      });
    });
  });
};
const fetchCategory = async () => {
  try {
    const { slug } = route.params;
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_CATEGORY}/category/${slug}`
    );
    categoryData.value = response.data.category;
    productData.value = response.data.products;
    productCurrentData.value = productData.value;
    const response2 = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_CATEGORY}/filter/${slug}`
    );
    filterData.value = response2.data;
    const breadcrumbItems = [
      {
        name: categoryData.value?.name,
        url: `/danh-muc/${categoryData.value?.slug}`,
      },
    ];

    setBreadcrumb(breadcrumbItems);
  } catch (error) {
    console.error("Error fetching category:", error);
  }
};
onMounted(() => {
  fetchCategory();
});

// Theo dõi sự thay đổi của `selectedFilter` để cập nhật sản phẩm
watch(
  selectedFilter,
  () => {
    applyFilter();
  },
  { deep: true }
);

const price = ref([
  {
    label: "Dưới 100k",
    min: "",
    max: "100000",
  },
  {
    label: "Từ 100k - 300k",
    min: "100000",
    max: "300000",
  },
  {
    label: "Từ 300k - 500k",
    min: "300000",
    max: "500000",
  },
  {
    label: "Trên 500k",
    min: "500000",
    max: "",
  },
]);

const value1 = ref("default");
const options1 = ref([
  {
    value: "default",
    label: "Thứ tự mặc định",
  },
  {
    value: "min-max",
    label: "Thứ tự theo giá: thấp đến cao",
  },
  {
    value: "max-min",
    label: "Thứ tự theo giá: cao đến thấp",
  },
]);

const handleChange = (value) => {
  sortProducts(value);
};

const sortProducts = (order) => {
  switch (order) {
    case "min-max":
      productCurrentData.value.sort(
        (a, b) => a.variant[0]?.price - b.variant[0]?.price
      );
      break;
    case "max-min":
      productCurrentData.value.sort(
        (a, b) => b.variant[0]?.price - a.variant[0]?.price
      );
      break;
    default:
      // Sắp xếp mặc định (có thể sắp xếp theo `created_at` hoặc trả về danh sách gốc)
      productCurrentData.value.sort(
        (a, b) => new Date(a.created_at) - new Date(b.created_at)
      );
      break;
  }
};

watch(
  () => route.params.slug,
  () => {
    fetchCategory();
  }
);
</script>

<style scoped>
.sort_item {
  border-color: rgb(164, 158, 158);
  padding: 7px 16px;
  border-radius: 0.375rem /* 6px */;
  border-width: 1px;
}
.sort_item:hover {
  cursor: pointer;
  border-color: #02b6ac;
  color: #02b6ac;
  background: white;
}
</style>
