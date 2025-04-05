<template>
  <div class="py-5">
    <input type="file" @change="handleFileUpload" />
    <div v-if="imageList.length" >
      <div
        v-for="(image, index) in imageList"
        :key="image.id"
        class="image-item bg-white p-2"
          @click="addClipart(image.src)"
      >
        <img
          :src="image.src"
          alt="Uploaded Image"
          :width="image.width"
          :height="image.height"
        />
        <button @click="deleteImage(image.id)">Delete</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { openDB } from "idb";

defineProps({
  addClipart: {
    type: Function,
    required: true,
  },
});

const imageList = ref([]);

// Tạo và mở IndexedDB bằng idb
const dbPromise = openDB("imageDB", 1, {
  upgrade(db) {
    if (!db.objectStoreNames.contains("images")) {
      db.createObjectStore("images", { keyPath: "id" });
    }
  },
});

// Lưu ảnh vào IndexedDB
const saveImageToDB = async (imageData) => {
  const db = await dbPromise;
  const tx = db.transaction("images", "readwrite");
  const store = tx.objectStore("images");
  store.add(imageData);
  await tx.done;
};

// Lấy danh sách ảnh từ IndexedDB
const loadImageListFromDB = async () => {
  const db = await dbPromise;
  const tx = db.transaction("images", "readonly");
  const store = tx.objectStore("images");
  return store.getAll();
};

// Xóa ảnh khỏi IndexedDB
const deleteImageFromDB = async (id) => {
  const db = await dbPromise;
  const tx = db.transaction("images", "readwrite");
  const store = tx.objectStore("images");
  store.delete(id);
  await tx.done;
};

// Hàm xử lý upload ảnh
const handleFileUpload = async (event) => {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = async () => {
      const imageData = {
        id: Date.now(), // using timestamp as the unique ID
        src: reader.result, // base64 string
        width: 100, // You can adjust width and height
        height: 100,
      };
      await saveImageToDB(imageData);
      loadImageList();
    };
    reader.readAsDataURL(file);
  }
};

// Hàm xóa ảnh
const deleteImage = async (id) => {
  await deleteImageFromDB(id);
  loadImageList();
};

// Tải danh sách ảnh khi component được mounted
const loadImageList = async () => {
  const images = await loadImageListFromDB();
  imageList.value = images;
};

onMounted(() => {
  loadImageList();
});
</script>

<style scoped>
.image-item {
  display: flex;
  align-items: center;
  margin: 10px 0;
}
.image-item img {
  margin-right: 10px;
}
</style>
