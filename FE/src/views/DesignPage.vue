<template>
  <div class="select-none">
    <NavDesignComponet
      :formattedPrice="formattedPrice"
      :exportToJson="exportToJson"
      :saveAsImage="saveAsImage"
      :handleAddToCart="handleAddToCart"
    />
    <div class="relative flex pt-14 mb-14">
      <div class="w-[420px] bg-[#3F4652] h-screen fixed">
        <a-tabs
          v-model:activeKey="activeKey"
          tab-position="left"
          type="card"
          class="h-screen pb-14 pr-6"
          :tabBarStyle="{
            backgroundColor: '#272C33',
          }"
        >
          <a-tab-pane :key="2" class="bg-[#3F4652] h-[500px]">
            <template #tab>
              <div class="flex flex-col items-center text-white py-1 w-[40px]">
                <BsBoxFill class="text-[20px]" />
                <span class="text-[10px] font-medium">SẢN PHẨM</span>
              </div>
            </template>
            <div class="py-2">
              <h1 class="text-[20px] text-white">{{ product?.name }}</h1>
              <div>
                <span
                  class="bg-gray-400 text-[18px] p-1 text-white font-semibold rounded-sm"
                  >{{ formatCurrency(price) }}</span
                >
              </div>
            </div>
            <a-flex vertical class="border-gray-400 border-t-[1px]">
              <a-flex vertical class="my-1">
                <a-flex
                  ><strong class="my-2 text-white font-semibold"
                    >Màu sắc</strong
                  ></a-flex
                >
                <a-flex>
                  <a-flex class="justify-evenly w-full">
                    <div
                      v-for="color in availableColors"
                      :key="color.id"
                      @click="selectColor(color)"
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
                            v-if="variant?.colorValue === color.value"
                            :class="
                              variant.colorValue == '#FFFFFF'
                                ? 'text-black text-2xl'
                                : 'text-white text-2xl'
                            "
                          />
                        </div>
                      </a-popover>
                    </div>
                  </a-flex>
                </a-flex>
              </a-flex>
            </a-flex>
            <a-flex vertical class="my-1"
              ><a-flex
                ><strong class="my-2 font-semibold text-white"
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
                  ></a-select></a-flex></a-flex
            ></a-flex>
            <a-flex vertical class="my-1"
              ><a-flex
                ><strong class="my-2 font-semibold text-white"
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
            <a-flex class="my-1 mt-5 justify-between"
              ><a-flex
                ><strong class="my-2 font-semibold text-white"
                  >Số lượng:</strong
                ></a-flex
              >
              <a-flex>
                <a-flex class="justify-evenly w-full">
                  <a-input-number
                    id="inputNumber"
                    v-model:value="count"
                    :min="1"
                    :max="10" /></a-flex></a-flex
            ></a-flex>
            <a-flex vertical class="my-1"
              ><a-flex
                ><strong class="my-2 font-semibold text-white"
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
          </a-tab-pane>
          <a-tab-pane key="4" class="bg-[#3F4652] h-[500px]">
            <template #tab>
              <div class="flex flex-col items-center text-white py-1 w-[40px]">
                <UiPicture class="text-[20px]" />
                <span class="text-[10px] font-medium">TẢI HÌNH<br />ẢNH </span>
              </div>
            </template>
            <UploadImageComponent :addClipart="addClipart" />
            <button @click="addClipart(frontImage)">Thêm ảnh</button>
          </a-tab-pane>
          <a-tab-pane key="5" class="bg-[#3F4652] h-[500px]">
            <template #tab>
              <div class="flex flex-col items-center text-white py-1 w-[40px]">
                <CoTextSquare class="text-[20px]" />
                <span class="text-[10px] font-medium"> VĂN BẢN </span>
              </div>
            </template>
            <button @click="addText">Thêm văn bản</button>
          </a-tab-pane>
          <a-tab-pane key="6" class="bg-[#3F4652] h-[500px]">
            <template #tab>
              <div class="flex flex-col items-center text-white py-1 w-[40px]">
                <LiDiamond class="text-[20px]" />
                <span class="text-[10px] font-medium">SHAPES</span>
              </div>
            </template>
            <div class="flex flex-wrap gap-5 py-5">
              <svg
                width="70"
                height="50"
                class="cursor-pointer"
                @click="addShape('rect')"
              >
                <rect
                  width="70"
                  height="50"
                  fill="gray"
                  class="hover:fill-[#41C7BA]"
                />
              </svg>
              <svg
                width="50"
                height="50"
                class="cursor-pointer"
                @click="addShape('square')"
              >
                <rect
                  width="50"
                  height="50"
                  fill="gray"
                  class="hover:fill-[#41C7BA]"
                />
              </svg>
              <svg
                width="50"
                height="50"
                class="cursor-pointer"
                @click="addShape('circle')"
              >
                <circle
                  cx="25"
                  cy="25"
                  r="25"
                  fill="gray"
                  class="hover:fill-[#41C7BA]"
                />
              </svg>
              <svg
                width="50"
                height="50"
                class="cursor-pointer"
                @click="addShape('triangle')"
              >
                <polygon
                  points="25,0 50,50 0,50"
                  fill="gray"
                  class="hover:fill-[#41C7BA]"
                />
              </svg>
              <svg
                width="70"
                height="50"
                class="cursor-pointer"
                @click="addShape('ellipse')"
              >
                <ellipse
                  cx="35"
                  cy="25"
                  rx="35"
                  ry="25"
                  fill="gray"
                  class="hover:fill-[#41C7BA]"
                />
              </svg>
            </div>
          </a-tab-pane>
          <a-tab-pane key="7" class="bg-[#3F4652] h-[500px]">
            <template #tab>
              <div class="flex flex-col items-center text-white py-1 w-[40px]">
                <IoLayers class="text-[20px]" />
                <span class="text-[10px] font-medium">LAYERS</span>
              </div>
            </template>
            <button @click="moveItemUp" :disabled="!selectedId">
              <BsChevronUp />
            </button>
            <button @click="moveItemDown" :disabled="!selectedId">
              <BsChevronDown />
            </button>
            <button @click="moveItemToTop" :disabled="!selectedId">
              <BsChevronDoubleUp />
            </button>
            <button @click="moveItemToBottom" :disabled="!selectedId">
              <BsChevronDoubleDown />
            </button>
            <div>
              <a-list
                item-layout="horizontal"
                :data-source="items"
                :bordered="true"
              >
                <template #renderItem="{ item, index }">
                  <a-list-item
                    @click="selectItem(index)"
                    :class="{
                      'bg-gray-500 rounded-md': selectedItem?.id === item.id,
                    }"
                  >
                    <span class="text-white">{{
                      item.name || `Layer ${item.id + 1}`
                    }}</span>
                    <div class="text-white text-[20px] cursor-pointer">
                      <AkEyeSlashed
                        v-if="item.visible"
                        @click="visible(index)"
                      />
                      <AkEyeOpen v-else @click="visible(index)" />
                    </div>
                    <div
                      @click="deleteSelected"
                      class="text-white text-[20px] cursor-pointer"
                    >
                      <IoSharpTrashBin class="text-[20px]" />
                    </div>
                  </a-list-item>
                </template>
              </a-list>
            </div>
          </a-tab-pane>
        </a-tabs>
        <div class="toolbar"></div>
      </div>
      <div
        class="w-full bg-[#EBECED] h-[100vh] ml-[420px] flex justify-center items-center relative"
      >
        <div
          v-if="transformerActive"
          class="absolute bg-white w-full h-[50px] top-0 left-0 flex items-center gap-1 px-2"
        >
          <input
            type="color"
            class="w-[40px] h-[45px]"
            v-model="selectedColor"
            @input="updateColor"
          />
          <button @click="deleteSelected">
            <IoSharpTrashBin class="text-[20px]" />
          </button>
          <div
            v-if="selectedType === 'text' && selectedItem"
            class="flex items-center gap-2 text-black"
          >
            <input
              v-model="selectedItem.text"
              placeholder="Edit text"
              class="text-black border-[2px] rounded-sm h-[40px] border-gray-300 px-2"
            />
            <span class="font-semibold text-[17px]">Size:</span>
            <input
              type="number"
              v-model="selectedItem.fontSize"
              min="10"
              max="100"
              class="text-black border-[2px] w-14 rounded-sm h-[40px] border-gray-300 px-2"
            />

            <span class="font-semibold text-[17px]">Font:</span>
            <select
              v-model="selectedItem.fontFamily"
              class="text-black border-[2px] rounded-sm h-[40px] border-gray-300 px-2"
            >
              <option value="Arial">Arial</option>
              <option value="Times New Roman">Times New Roman</option>
              <option value="Courier New">Courier New</option>
              <option value="Verdana">Verdana</option>
            </select>

            <span class="font-semibold text-[17px]">Lề:</span>
            <select
              v-model="selectedItem.align"
              class="text-black border-[2px] rounded-sm h-[40px] w-18 border-gray-300 px-2"
            >
              <option value="left">Left</option>
              <option value="center">Center</option>
              <option value="right">Right</option>
            </select>

            <span class="font-semibold text-[17px]">Gap:</span>
            <input
              type="number"
              v-model="selectedItem.lineHeight"
              min="0.5"
              max="3"
              step="0.1"
              class="text-black border-[2px] w-14 rounded-sm h-[40px] border-gray-300 px-2"
            />
            <button
              @click="toggleBold"
              class="border rounded-md w-10"
              :class="{
                'font-bold bg-gray-200':
                  selectedItem.fontStyle.includes('bold'),
              }"
            >
              B
            </button>
            <button
              @click="toggleItalic"
              class="border rounded-md w-10 italic"
              :class="{
                'bg-gray-200': selectedItem.fontStyle.includes('italic'),
              }"
            >
              I
            </button>
            <button
              @click="toggleUnderline"
              class="rounded-md w-10 underline"
              :class="{
                'bg-gray-200': selectedItem.textDecoration === 'underline',
              }"
            >
              U
            </button>
            <button
              @click="toggleLineThrough"
              class="border rounded-md w-10 line-through"
              :class="{
                'bg-gray-200': selectedItem.textDecoration === 'line-through',
              }"
            >
              S
            </button>
          </div>
        </div>
        <!-- Khu vực thiết kế áo -->
        <div class="w-full flex justify-center items-center">
          <div class="relative w-[400px] flex justify-center">
            <div
              class="absolute inset-0 opacity-95 z-0"
              :style="{ backgroundColor: variant.colorValue }"
            ></div>
            <img
              :src="currentSide === 'front' ? frontImage : backImage"
              class="inset-0 z-0"
              alt="Áo mẫu"
            />
            <div
              class="absolute bg-transparent top-[52%] z-10 translate-y-[-50%] border-dashed border-[1px] border-gray-500 rounded-md"
            >
              <v-stage
                :config="stageSize"
                class="w-full h-full"
                ref="stageRef"
                @click="handleStageClick"
              >
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
                      :config="{
                        ...item,
                        draggable: selectedId === item.id,
                      }"
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
                  <v-transformer ref="transformerRef" class="z-50" />
                </v-layer>
              </v-stage>
            </div>
          </div>
        </div>
      </div>
      <div class="flex justify-center bottom-0 right-0 gap-4 mb-4 fixed">
        <button @click="toggleSide">
          Chuyển sang mặt {{ currentSide === "front" ? "sau" : "trước" }}
        </button>
      </div>
    </div>
  </div>
</template>
<script setup>
import Konva from "konva";
import NavDesignComponet from "@/components/nav/NavDesignComponet.vue";
import { onMounted, ref, reactive, computed, nextTick, toRaw } from "vue";
import { notification } from "ant-design-vue";
import { useRoute } from "vue-router";
import axios from "axios";
import { addToCart } from "@/store/cartDB";
import {
  BsBoxFill,
  UiPicture,
  CoTextSquare,
  LiDiamond,
  IoLayers,
  IoSharpTrashBin,
  AkEyeOpen,
  AkEyeSlashed,
  BsChevronDoubleUp,
  BsChevronDoubleDown,
  BsChevronDown,
  BsChevronUp,
  AkCheck,
} from "@kalimahapps/vue-icons";
import UploadImageComponent from "@/components/UploadImageComponent.vue";

const activeKey = ref(2);

const route = useRoute();
const frontImage = ref(null);
const backImage = ref(null);
const stageRef = ref(null);
const layerRef = ref(null);
const product = ref(null);

const currentSide = ref("front"); // 'front' hoặc 'back'
const itemsFront = ref([]);
const itemsBack = ref([]);

const stageSize = {
  width: 200,
  height: 280,
};

const items = computed({
  get() {
    return currentSide.value === "front" ? itemsFront.value : itemsBack.value;
  },
  set(val) {
    if (currentSide.value === "front") {
      itemsFront.value = val;
    } else {
      itemsBack.value = val;
    }
  },
});
const selectedId = ref(null);
const selectedType = ref(null);
const selectedColor = ref("#000000");
const itemRefs = ref([]);
const transformerRef = ref(null);
const transformerActive = ref(null);
const count = ref(1);

const toggleSide = () => {
  selectedId.value = null;
  transformerRef.value.getNode().nodes([]);
  currentSide.value = currentSide.value === "front" ? "back" : "front";
};

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

const shapeConfigs = {
  rect: { width: 100, height: 80, fill: "#ff0000" },
  square: { width: 80, height: 80, fill: "#0000ff" },
  circle: { radius: 50, fill: "#008000" },
  ellipse: { radiusX: 60, radiusY: 40, fill: "#800080" },
  triangle: { data: "M 0 100 L 50 0 L 100 100 Z", fill: "#ffa500" },
};

const saveAsImage = () => {
  selectedId.value = null;
  transformerActive.value = false;
  transformerRef.value.getNode().nodes([]);
  const stage = stageRef.value.getStage(); // Lấy đối tượng stage
  const dataURL = stage.toDataURL({ pixelRatio: 3 }); // Chuyển canvas thành URL ảnh

  // Tạo một liên kết và tải ảnh về
  const link = document.createElement("a");
  link.href = dataURL;
  link.download = "canvas-image.png";
  link.click();
};

const handleStageClick = (e) => {
  if (e.target === e.target.getStage()) {
    selectedId.value = null;
    transformerActive.value = false;
    transformerRef.value.getNode().nodes([]); // Xóa transformer
    return;
  }
};

const addShape = (type) => {
  items.value.push({
    type,
    x: 50,
    y: 50,
    id: `${type}-${Date.now()}`,
    visible: true,
    ...shapeConfigs[type],
  });
};

const getCORSImageURL = (url) => {
  const base = import.meta.env.VITE_APP_URL_API_UPLOAD;
  if (url.startsWith(base)) {
    const relativePath = url.replace(base, "");
    return `${
      import.meta.env.VITE_APP_URL_API_IMAGE
    }/image-proxy/${relativePath}`;
  }
  return url;
};

const addClipart = (clipart) => {
  const img = new window.Image();
  img.crossOrigin = "anonymous";
  img.src = getCORSImageURL(clipart);
  img.onload = () => {
    items.value.push({
      id: `Clipart-${Date.now()}`,
      type: "image",
      image: img,
      x: 10,
      y: 10,
      width: 180,
      height: 230,
      visible: true,
    });
  };
};

const addTemplate = (items, clipart) => {
  const img = new window.Image();
  img.crossOrigin = "anonymous";
  img.src = getCORSImageURL(clipart);
  img.onload = () => {
    items.value.push({
      id: `Clipart-${Date.now()}`,
      type: "image",
      image: img,
      x: 10,
      y: 10,
      width: 180,
      height: 230,
      visible: true,
    });
  };
};

const addText = () => {
  items.value.push({
    type: "text",
    x: 10,
    y: 10,
    text: "Sample Text",
    fontFamily: "Arial",
    fontSize: 16,
    fontStyle: "normal",
    fontVariant: "normal",
    textDecoration: "",
    align: "left",
    verticalAlign: "top",
    lineHeight: 1,
    fill: "#000000",
    id: `text-${Date.now()}`,
    visible: true,
  });
};

const toggleBold = () => {
  if (selectedItem.value.fontStyle.includes("bold")) {
    selectedItem.value.fontStyle =
      selectedItem.value.fontStyle.replace("bold", "").trim() || "normal";
  } else {
    selectedItem.value.fontStyle =
      selectedItem.value.fontStyle === "normal"
        ? "bold"
        : selectedItem.fontStyle + " bold";
  }
};

const toggleItalic = () => {
  if (selectedItem.value.fontStyle.includes("italic")) {
    selectedItem.value.fontStyle =
      selectedItem.value.fontStyle.replace("italic", "").trim() || "normal";
  } else {
    selectedItem.value.fontStyle =
      selectedItem.value.fontStyle === "normal"
        ? "italic"
        : "italic " + selectedItem.value.fontStyle;
  }
};

const toggleUnderline = () => {
  selectedItem.value.textDecoration =
    selectedItem.value.textDecoration === "underline" ? "" : "underline";
};

const toggleLineThrough = () => {
  selectedItem.value.textDecoration =
    selectedItem.value.textDecoration === "line-through" ? "" : "line-through";
};

const updateShape = (index, event) => {
  const node = event.target;
  const newWidth = node.width() * node.scaleX();
  const newHeight = node.height() * node.scaleY();
  items.value[index] = {
    ...items.value[index],
    x: node.x(),
    y: node.y(),
    width: newWidth,
    height: newHeight,
  };
  node.scaleX(1);
  node.scaleY(1);
  transformerRef.value.getNode().nodes([node]);
};

const selectItem = (index) => {
  selectedId.value = items.value[index].id;
  selectedType.value = items.value[index].type;
  selectedColor.value = items.value[index].fill || "#000000";
  transformerActive.value = true;
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
    transformerActive.value = false;
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
const visible = (index) => {
  items.value[index].visible = !items.value[index].visible;
};

const exportToJson = () => {
  const stage = stageRef.value.getStage(); // Lấy đối tượng Stage từ ref
  const shapes = stage.find("Shape"); // Lấy tất cả các shape trên stage

  // Chuyển đổi các shape thành một mảng đối tượng JSON
  const itemsJson = shapes.map((shape) => {
    // Lấy đối tượng JSON của shape
    const shapeJson = shape.toJSON();

    // Chuyển chuỗi JSON thành đối tượng JavaScript (nếu cần thiết)
    const shapeObject = JSON.parse(shapeJson);

    // Kiểm tra nếu shape là hình ảnh và thêm dữ liệu Base64
    if (shapeObject.className === "Image") {
      const imageDataUrl = shape.image().src; // Lấy đường dẫn hình ảnh
      if (imageDataUrl) {
        shapeObject.imageData = imageDataUrl; // Thêm chuỗi Base64 vào đối tượng
      }
    }
    return shapeObject;
  });

  // Tạo file JSON từ mảng itemsJson
  const jsonBlob = new Blob([JSON.stringify(itemsJson, null, 2)], {
    type: "application/json",
  });

  // Tạo link tải file JSON
  const url = URL.createObjectURL(jsonBlob);
  const a = document.createElement("a");
  a.href = url;
  a.download = "shapes.json"; // Tên file tải về
  a.click();
  URL.revokeObjectURL(url); // Giải phóng URL
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
  if (!product.value?.variant || product.value?.variant?.length === 0)
    return []; // Kiểm tra nếu variants chưa có dữ liệu
  const variantSizes = new Set(product.value?.variant?.map((v) => v.size));
  return sizes.value?.filter((size) => variantSizes.has(size.value));
});

const availableColors = computed(() => {
  if (!product.value?.variant || product.value?.variant?.length === 0)
    return [];
  const variantColors = new Set(product.value?.variant?.map((v) => v.color));
  return colors.value?.filter((color) => variantColors.has(color.name));
});

const fetchProduct = async () => {
  try {
    const { slug } = route.params;
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_PRODUCT}/product/${slug}`
    );
    product.value = response.data;
    frontImage.value = product.value?.category?.front_image?.path;
    backImage.value = product.value?.category?.back_image?.path;
    if (product.value?.front_template?.path) {
      addTemplate(itemsFront, product.value?.front_template?.path);
    }
    if (product.value?.back_template?.path) {
      addTemplate(itemsBack, product.value?.back_template?.path);
    }
    if (product.value.variant && product.value.variant.length > 0) {
      const firstVariant = product.value.variant[0];
      variant.colorValue = colors.value.find(
        (color) => color.name == firstVariant.color
      ).value;
      variant.size = firstVariant.size;
      variant.color = firstVariant.color;
    }
  } catch (error) {
    console.error("Không tìm thấy sản phẩm:", error);
  }
};

const exportCurrentView = (stageRef) => {
  const stage = stageRef.value.getStage();
  return stage.toDataURL({ pixelRatio: 3 });
};

const delay = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

const handleAddToCart = async () => {
  if (product.value.avaiable === 0) {
    notification.warning({
      message: "Không thể thêm sản phẩm này!",
    });
    return null;
  } else {
    selectedId.value = null;
    transformerActive.value = false;
    transformerRef.value.getNode().nodes([]);
    // Lưu trạng thái mặt hiện tại
    const originalSide = currentSide.value;

    // 👉 Export front
    currentSide.value = "front";
    await nextTick(); // Đợi DOM cập nhật
    await delay(100); // Đợi canvas render (nếu cần)
    const frontImage = exportCurrentView(stageRef);

    // 👉 Export back
    currentSide.value = "back";
    await nextTick();
    await delay(100);
    const backImage = exportCurrentView(stageRef);

    // 👉 Trả lại trạng thái ban đầu
    currentSide.value = originalSide;

    // 👉 Lưu vào IndexedDB

    const cartItem = {
      product: {
        id: product.value.id,
        name: product.value.name,
        slug: product.value.slug,
        frontImage: product.value?.category?.front_image?.path,
        backImage: product.value?.category?.back_image?.path,
      },
      variant: toRaw(variant),
      frontImage,
      backImage,
      quantity: toRaw(count.value),
      price: toRaw(price.value),
      createdAt: Date.now(),
    };

    await addToCart(cartItem);
    console.log("Thêm thiết kế vào giỏ thành công!");
  }
};

onMounted(() => {
  fetchProduct();
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
