<template>
  <div>
    <div class="flex flex-col md:flex-row gap-4 mb-4">
      <a-select
        v-model:value="selectedProvince"
        placeholder="Chọn tỉnh/thành phố"
        :options="provinceOptions"
        @change="fetchDistricts"
        class="w-full md:w-1/2"
        show-search
        :filter-option="filterOption"
      />
      <a-select
        v-model:value="selectedDistrict"
        placeholder="Chọn quận/huyện"
        :options="districtOptions"
        class="w-full md:w-1/2"
        show-search
        :filter-option="filterOption"
        :disabled="!selectedProvince"
        @change="centerToDistrict"
      />
    </div>

    <div ref="mapContainer" class="w-full h-[300px] mb-4"></div>
    <strong>Vị trí bạn chọn:</strong>
    <div class="flex flex-col gap-4">
      <input
        v-model="selectedAddress"
        type="text"
        placeholder="Địa chỉ được chọn"
        disabled
        class="px-4 py-2 border border-gray-300 rounded"
      />
      <p v-if="error" class="text-red-500">{{ error }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from "vue";
import { message } from "ant-design-vue";
const emit = defineEmits(["addressData"]);

const mapContainer = ref(null);
const selectedAddress = ref("");
const error = ref(null);

const selectedProvince = ref(null);
const selectedDistrict = ref(null);
const provinceOptions = ref([]);
const districtOptions = ref([]);

let map = null;
let platform = null;
let locationPickerMarker = null;

const filterOption = (input, option) =>
  option.label.toLowerCase().includes(input.toLowerCase());

const loadProvinces = async () => {
  try {
    const res = await fetch("https://provinces.open-api.vn/api/?depth=1");
    const data = await res.json();
    provinceOptions.value = data.map((item) => ({
      label: item.name,
      value: item.code,
    }));
  } catch (err) {
    message.error("Lỗi khi tải danh sách tỉnh/thành.");
  }
};

const fetchDistricts = async (provinceCode) => {
  selectedDistrict.value = null;
  districtOptions.value = [];

  try {
    const res = await fetch(
      `https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`
    );
    const data = await res.json();
    districtOptions.value = data.districts.map((item) => ({
      label: item.name,
      value: item.code,
    }));
  } catch (err) {
    message.error("Lỗi khi tải danh sách quận/huyện.");
  }
};

const centerToDistrict = async () => {
  const provinceName = provinceOptions.value.find(
    (p) => p.value === selectedProvince.value
  )?.label;

  const districtName = districtOptions.value.find(
    (d) => d.value === selectedDistrict.value
  )?.label;

  if (!provinceName || !districtName) return;

  const fullAddress = `${districtName}, ${provinceName}, Việt Nam`;

  try {
    const url = `https://geocode.search.hereapi.com/v1/geocode?q=${encodeURIComponent(
      fullAddress
    )}&lang=vi-VN&apikey=${import.meta.env.VITE_MAP_API_KEY}`;
    const res = await fetch(url);
    const data = await res.json();

    if (data.items && data.items.length > 0) {
      const { lat, lng } = data.items[0].position;
      map.setCenter({ lat, lng });
      map.setZoom(14); // hoặc 13 tuỳ độ rộng bạn muốn
    } else {
      message.warning("Không tìm thấy vị trí quận/huyện đã chọn.");
    }
  } catch (err) {
    message.error("Lỗi khi tìm vị trí quận/huyện.");
    console.error(err);
  }
};

const loadMap = async () => {
  await nextTick();

  platform = new H.service.Platform({
    apikey: import.meta.env.VITE_MAP_API_KEY,
  });

  const defaultLayers = platform.createDefaultLayers();
  map = new H.Map(mapContainer.value, defaultLayers.vector.normal.map, {
    center: { lat: 21.0316, lng: 105.7847 },
    zoom: 13,
  });

  new H.mapevents.Behavior(new H.mapevents.MapEvents(map));
  H.ui.UI.createDefault(map, defaultLayers);

  map.addEventListener("tap", handleMapClick);
};

const handleMapClick = async (evt) => {
  const coord = map.screenToGeo(
    evt.currentPointer.viewportX,
    evt.currentPointer.viewportY
  );

  const lat = coord.lat.toFixed(6);
  const lng = coord.lng.toFixed(6);

  placePickerMarker(lat, lng);
  await reverseGeocode(lat, lng);
};

const placePickerMarker = (lat, lng) => {
  if (locationPickerMarker) {
    map.removeObject(locationPickerMarker);
  }

  const icon = new H.map.Icon(
    "https://cdn-icons-png.flaticon.com/512/4781/4781517.png",
    { size: { w: 32, h: 32 } }
  );

  locationPickerMarker = new H.map.Marker({ lat, lng }, { icon });
  map.addObject(locationPickerMarker);
  map.setCenter({ lat, lng });
};

const reverseGeocode = async (lat, lng) => {
  try {
    const url = `https://geocode.search.hereapi.com/v1/revgeocode?at=${lat},${lng}&lang=vi-VN&apikey=${
      import.meta.env.VITE_MAP_API_KEY
    }`;
    const response = await fetch(url);
    const data = await response.json();
    const address = data.items[0].address; 
    emit("addressData", address);
    if (data.items && data.items.length > 0) {
      selectedAddress.value = data.items[0].address.label;
    } else {
      selectedAddress.value = "Không tìm thấy địa chỉ.";
    }
  } catch (err) {
    error.value = "Lỗi khi lấy địa chỉ.";
  }
};

onMounted(() => {
  loadMap();
  loadProvinces();
});
</script>
