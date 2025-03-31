<template>
  <div>
    <NavDesignComponet />
    <div class="relative flex pt-14 mb-14">
      <div class="w-[420px] bg-[#3F4652] h-screen fixed">
        <a-tabs
          v-model:activeKey="activeKey"
          tab-position="left"
          @tabScroll="callback"
          type="card"
          class="h-screen pb-14"
          :tabBarStyle="{
            backgroundColor: '#272C33',
          }"
        >
          <a-tab-pane key="1" class="bg-[#3F4652] h-[500px]">
            <template #tab>
              <div class="flex flex-col items-center text-white py-1 w-[40px]">
                <AkStar class="text-[20px]" /><span
                  class="text-[10px] font-medium"
                  >TEMPLATES</span
                >
              </div>
            </template>
            Content of Tab Pane 1
          </a-tab-pane>
          <a-tab-pane key="2" class="bg-[#3F4652] h-[500px]"
            ><template #tab>
              <div class="flex flex-col items-center text-white py-1 w-[40px]">
                <BsBoxFill class="text-[20px]" /><span
                  class="text-[10px] font-medium"
                  >SẢN PHẨM</span
                >
              </div>
            </template>
            Content of Tab Pane 2</a-tab-pane
          >
          <a-tab-pane key="3" class="bg-[#3F4652] h-[500px]">
            <template #tab>
              <div class="flex flex-col items-center text-white py-1 w-[40px]">
                <AkHeart class="text-[20px]" /><span
                  class="text-[10px] font-medium"
                  >CLIPARTS</span
                >
              </div> </template
            >Content of Tab Pane 3</a-tab-pane
          >
          <a-tab-pane key="4" class="bg-[#3F4652] h-[500px]">
            <template #tab>
              <div class="flex flex-col items-center text-white py-1 w-[40px]">
                <UiPicture class="text-[20px]" /><span
                  class="text-[10px] font-medium"
                  >TẢI HÌNH<br />ẢNH</span
                >
              </div> </template
            ><button @click="addImage">Add Image</button>
            <input type="file" @change="handleFileUpload" accept="image/*"
          /></a-tab-pane>
          <a-tab-pane key="5" class="bg-[#3F4652] h-[500px]">
            <template #tab>
              <div class="flex flex-col items-center text-white py-1 w-[40px]">
                <CoTextSquare class="text-[20px]" /><span
                  class="text-[10px] font-medium"
                  >VĂN BẢN</span
                >
              </div> </template
            ><button @click="addText">Add Text</button
            ><input
              v-if="selectedType === 'text' && selectedItem"
              v-model="selectedItem.text"
              placeholder="Edit text" />
            <input type="color" v-model="selectedColor" @input="updateColor"
          /></a-tab-pane>
          <a-tab-pane key="6" class="bg-[#3F4652] h-[500px]">
            <template #tab>
              <div class="flex flex-col items-center text-white py-1 w-[40px]">
                <LiDiamond class="text-[20px]" /><span
                  class="text-[10px] font-medium"
                  >SHAPES</span
                >
              </div> </template
            ><button @click="addShape('rect')">Add Rectangle</button>
            <button @click="addShape('square')">Add Square</button>
            <button @click="addShape('circle')">Add Circle</button>
            <button @click="addShape('triangle')">Add Triangle</button>
            <button @click="addShape('ellipse')">Add Ellipse</button>
            <input type="color" v-model="selectedColor" @input="updateColor"
          /></a-tab-pane>
          <a-tab-pane key="7" class="bg-[#3F4652] h-[500px]">
            <template #tab>
              <div class="flex flex-col items-center text-white py-1 w-[40px]">
                <IoLayers class="text-[20px]" /><span
                  class="text-[10px] font-medium"
                  >LAYERS</span
                >
              </div>
            </template>
            <button @click="deselectItem">Bỏ chọn</button>
            <button @click="deleteSelected">Delete Selected</button>

            <button @click="moveItemUp" :disabled="!selectedId">Move Up</button>
            <button @click="moveItemDown" :disabled="!selectedId">
              Move Down
            </button>
            <button @click="moveItemToTop" :disabled="!selectedId">
              Move To Top
            </button>
            <button @click="moveItemToBottom" :disabled="!selectedId">
              Move To Bottom
            </button></a-tab-pane
          >
        </a-tabs>
        <div class="toolbar"></div>
      </div>
      <div
        class="w-full bg-white h-[100vh] ml-[420px] flex justify-center items-center"
      >
        <!-- Khu vực thiết kế áo -->
        <div class="w-full flex justify-center items-center">
          <div class="relative w-[400px] flex justify-center">
            <img :src="image" class="inset-0" alt="Áo mẫu" />
            <div
              class="absolute bg-transparent top-[52%] translate-y-[-50%] border-dashed border-[1px] border-gray-500 rounded-md"
            >
              <v-stage :config="stageSize" ref="stageRef">
                <v-layer ref="layerRef">
                  <template v-for="(item, i) in items" :key="i">
                    <v-rect
                      v-if="item.type === 'rect' || item.type === 'square'"
                      :config="{ ...item, draggable: selectedId === item.id }"
                      @dragmove="updateShape(i, $event)"
                      @transformend="updateShape(i, $event)"
                      @click="() => selectItem(i)"
                      ref="itemRefs"
                    />
                    <v-circle
                      v-if="item.type === 'circle'"
                      :config="{ ...item, draggable: selectedId === item.id }"
                      @dragmove="updateShape(i, $event)"
                      @transformend="updateShape(i, $event)"
                      @click="() => selectItem(i)"
                      ref="itemRefs"
                    />
                    <v-ellipse
                      v-if="item.type === 'ellipse'"
                      :config="{ ...item, draggable: selectedId === item.id }"
                      @dragmove="updateShape(i, $event)"
                      @transformend="updateShape(i, $event)"
                      @click="() => selectItem(i)"
                      ref="itemRefs"
                    />
                    <v-text
                      v-if="item.type === 'text'"
                      :config="{ ...item, draggable: selectedId === item.id }"
                      @dragmove="updateShape(i, $event)"
                      @transformend="updateShape(i, $event)"
                      @click="() => selectItem(i)"
                      ref="itemRefs"
                    />
                    <v-image
                      v-if="item.type === 'image'"
                      :config="{ ...item, draggable: selectedId === item.id }"
                      @dragmove="updateShape(i, $event)"
                      @transformend="updateShape(i, $event)"
                      @click="() => selectItem(i)"
                      ref="itemRefs"
                    />
                    <v-path
                      v-if="item.type === 'triangle'"
                      :config="{ ...item, draggable: selectedId === item.id }"
                      @dragmove="updateShape(i, $event)"
                      @transformend="updateShape(i, $event)"
                      @click="() => selectItem(i)"
                      ref="itemRefs"
                    />
                  </template>
                  <v-transformer ref="transformerRef" />
                </v-layer>
              </v-stage>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Konva from "konva";
import NavDesignComponet from "@/components/nav/NavDesignComponet.vue";
import { onMounted, onBeforeUnmount, ref, reactive, computed } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";
import {
  AkStar,
  BsBoxFill,
  AkHeart,
  UiPicture,
  CoTextSquare,
  LiDiamond,
  IoLayers,
} from "@kalimahapps/vue-icons";

const activeKey = ref(1);
const callback = (val) => {
  console.log(val);
};

const route = useRoute();
const image = ref(null);
const stageRef = ref(null);
const layerRef = ref(null);
const product = ref(null);

const stageSize = { width: 200, height: 280 };
const items = ref([]);
const selectedId = ref(null);
const selectedType = ref(null);
const selectedColor = ref("#000000");
const itemRefs = ref([]);
const transformerRef = ref(null);

const shapeConfigs = {
  rect: { width: 100, height: 80, fill: "#ff0000" },
  square: { width: 80, height: 80, fill: "#0000ff" },
  circle: { radius: 50, fill: "#008000" },
  ellipse: { radiusX: 60, radiusY: 40, fill: "#800080" },
  triangle: { data: "M 0 100 L 50 0 L 100 100 Z", fill: "#ffa500" },
};

const deselectItem = () => {
  selectedId.value = null;
  transformerRef.value.getNode().nodes([]); // Xóa transformer
};

const addShape = (type) => {
  items.value.push({
    type,
    x: 50,
    y: 50,
    id: `${type}-${Date.now()}`,
    ...shapeConfigs[type],
  });
};

const addText = () => {
  items.value.push({
    type: "text",
    x: 100,
    y: 100,
    text: "Sample Text",
    fontSize: 24,
    fill: "#0000000",
    id: `text-${Date.now()}`,
  });
};

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = () => {
      const img = new window.Image();
      img.src = reader.result;
      img.onload = () => {
        items.value.push({
          type: "image",
          x: 10,
          y: 10,
          image: img,
          width: stageSize.width * 0.7,
          height: stageSize.height * 0.7,
          id: `image-${Date.now()}`,
        });
      };
    };
    reader.readAsDataURL(file);
  }
};

const addImage = () => {
  const img = new window.Image();
  img.src = "https://placehold.co/600x400/EEE/31343C";
  img.onload = () => {
    items.value.push({
      type: "image",
      x: 50,
      y: 50,
      image: img,
      width: 100,
      height: 100,
      id: `image-${Date.now()}`,
    });
  };
};

const updateShape = (index, event) => {
  const node = event.target;

  // Tính toán kích thước mới
  const newWidth = node.width() * node.scaleX();
  const newHeight = node.height() * node.scaleY();

  // Cập nhật lại item
  items.value[index] = {
    ...items.value[index],
    x: node.x(),
    y: node.y(),
    width: newWidth,
    height: newHeight,
  };

  // Đặt lại scale của node để tránh tăng vô hạn
  node.scaleX(1);
  node.scaleY(1);

  // Cập nhật Transformer
  transformerRef.value.getNode().nodes([node]);
};

const selectItem = (index) => {
  selectedId.value = items.value[index].id;
  selectedType.value = items.value[index].type;
  selectedColor.value = items.value[index].fill || "#000000";
  transformerRef.value.getNode().nodes([itemRefs.value[index].getNode()]);
};

const selectedItem = computed(() => {
  return items.value.find((item) => item.id === selectedId.value) || null;
});

const updateColor = () => {
  if (selectedId.value) {
    const item = items.value.find((item) => item.id === selectedId.value);
    if (item) item.fill = selectedColor.value;
  }
};

const deleteSelected = () => {
  if (selectedId.value) {
    items.value = items.value.filter((item) => item.id !== selectedId.value);
    selectedId.value = null;
    transformerRef.value.getNode().nodes([]);
  }
};

const moveItemUp = () => {
  if (!selectedId.value) return;
  const index = items.value.findIndex((item) => item.id === selectedId.value);
  if (index === -1 || index === items.value.length - 1) return;
  swapItems(index, index + 1);
  const itemNode = getSelectedItemNode();
  if (itemNode) itemNode.moveUp();
  layerRef.value.getNode().batchDraw();
};

const moveItemDown = () => {
  if (!selectedId.value) return;
  const index = items.value.findIndex((item) => item.id === selectedId.value);
  if (index === -1 || index === 0) return;
  swapItems(index, index - 1);
  const itemNode = getSelectedItemNode();
  if (itemNode) itemNode.moveDown();
  layerRef.value.getNode().batchDraw();
};

const moveItemToTop = () => {
  if (!selectedId.value) return;
  const index = items.value.findIndex((item) => item.id === selectedId.value);
  if (index === -1 || index === items.value.length - 1) return;
  items.value.push(items.value.splice(index, 1)[0]);
  const itemNode = getSelectedItemNode();
  if (itemNode) itemNode.moveToTop();
  layerRef.value.getNode().batchDraw();
};

const moveItemToBottom = () => {
  if (!selectedId.value) return;
  const index = items.value.findIndex((item) => item.id === selectedId.value);
  if (index === -1 || index === 0) return;
  items.value.unshift(items.value.splice(index, 1)[0]);
  const itemNode = getSelectedItemNode();
  if (itemNode) itemNode.moveToBottom();
  layerRef.value.getNode().batchDraw();
};

const swapItems = (index1, index2) => {
  [items.value[index1], items.value[index2]] = [
    items.value[index2],
    items.value[index1],
  ];
};

const getSelectedItemNode = () => {
  const index = items.value.findIndex((item) => item.id === selectedId.value);
  return index !== -1 ? itemRefs.value[index]?.getNode() : null;
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
    console.log(product.value);
    image.value = product.value.category.front_image.path;

    if (product.value.variant && product.value.variant.length > 0) {
      const firstVariant = product.value.variant[0];
      variant.colorValue = colors.value.find(
        (color) => color.name == firstVariant.color
      ).value;
      variant.size = firstVariant.size;
      variant.color = firstVariant.color;
    }
    console.log(variant);
  } catch (error) {
    console.error("Không tìm thấy sản phẩm:", error);
  }
});
</script>

<style scoped>
::v-deep(.ant-tabs-card .ant-tabs-tab-active) {
  background-color: #3f4652 !important;
  color: white !important;
  border-radius: 0px !important;
  border: none !important;
}

/* Đổi màu chữ khi tab active */
::v-deep(.ant-tabs-tab-active .ant-tabs-tab-btn) {
  color: white !important;
}
.designer-container {
  text-align: center;
}

.toolbar {
  margin-bottom: 15px;
}

button {
  margin: 5px;
  padding: 8px;
  background: #007bff;
  color: white;
  border: none;
  cursor: pointer;
}

button:hover {
  background: #0056b3;
}
</style>
