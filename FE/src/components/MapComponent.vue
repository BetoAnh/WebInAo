<template>
  <div class="flex flex-col items-center gap-2 max-w-[400px] h-[400px] max-h-[700px]">
    <div class="mt-4 flex flex-wrap gap-2">
      <button
        @click="relocateUser"
        :disabled="loading"
        class="px-4 py-2 rounded text-white bg-green-600 hover:bg-green-700 disabled:opacity-60 disabled:cursor-not-allowed"
      >
        {{ loading ? "Đang định vị..." : "📍 Định vị vị trí hiện tại" }}
      </button>

      <button
        @click="drawRouteToStore"
        :disabled="!userLocation"
        class="px-4 py-2 rounded text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-60 disabled:cursor-not-allowed"
      >
        🛣️ Vẽ đường đến cửa hàng
      </button>
    </div>
    <div ref="mapContainer" class="w-full h-[500px]"></div>

    <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from "vue";

const mapContainer = ref(null);
const loading = ref(false);
const error = ref(null);

let map = null;
let platform = null;
let userMarker = null;
let storeMarker = null;
let routeLine = null;

const userLocation = ref(null);
const STORE_COORDS = { lat: 21.03164288826122, lng: 105.78473773311794 };

const loadMap = async () => {
  await nextTick();
  platform = new H.service.Platform({
    apikey: import.meta.env.VITE_MAP_API_KEY,
  });

  const defaultLayers = platform.createDefaultLayers();

  map = new H.Map(mapContainer.value, defaultLayers.vector.normal.map, {
    center: STORE_COORDS,
    zoom: 17,
  });

  new H.mapevents.Behavior(new H.mapevents.MapEvents(map));
  H.ui.UI.createDefault(map, defaultLayers);

  setTimeout(() => map.getViewPort().resize(), 200);
  initStoreMarker();
};

const relocateUser = () => {
  loading.value = true;
  error.value = null;

  if (!navigator.geolocation) {
    error.value = "Trình duyệt không hỗ trợ định vị.";
    loading.value = false;
    return;
  }

  navigator.geolocation.getCurrentPosition(
    (position) => {
      const { latitude, longitude } = position.coords;
      userLocation.value = { lat: latitude, lng: longitude };
      initUserMarker(latitude, longitude);
      loading.value = false;
    },
    (err) => {
      error.value = "Không thể lấy vị trí: " + err.message;
      loading.value = false;
    },
    { enableHighAccuracy: true }
  );
};

const drawRouteToStore = () => {
  if (!userLocation.value) {
    error.value = "Bạn cần định vị trước khi vẽ đường.";
    return;
  }

  drawRoute(userLocation.value, STORE_COORDS);
};

const initUserMarker = (lat, lon) => {
  if (!map) return;

  if (userMarker) map.removeObject(userMarker);

  const userIcon = new H.map.Icon(
    "https://cdn-icons-png.flaticon.com/512/4781/4781517.png",
    { size: { w: 32, h: 32 } }
  );

  userMarker = new H.map.Marker({ lat, lng: lon }, { icon: userIcon });
  map.addObject(userMarker);
  map.setCenter({ lat, lng: lon });

  userMarker.addEventListener("tap", () => {
    alert(`Vị trí của bạn:\nKinh độ: ${lon}\nVĩ độ: ${lat}`);
  });

  userMarker.addEventListener("pointerenter", () => {
    map.getElement().style.cursor = "pointer";
  });

  userMarker.addEventListener("pointerleave", () => {
    map.getElement().style.cursor = "default";
  });
};

const initStoreMarker = () => {
  if (!map) return;

  if (storeMarker) map.removeObject(storeMarker);

  const storeIcon = new H.map.Icon(
    "https://cdn-icons-png.freepik.com/512/1596/1596389.png",
    { size: { w: 32, h: 32 } }
  );

  storeMarker = new H.map.Marker(STORE_COORDS, { icon: storeIcon });
  map.addObject(storeMarker);
};

const drawRoute = (start, end) => {
  const router = platform.getRoutingService(null, 8);
  const routeRequestParams = {
    routingMode: "fast",
    transportMode: "pedestrian",
    origin: `${start.lat},${start.lng}`,
    destination: `${end.lat},${end.lng}`,
    return: "polyline",
  };

  router.calculateRoute(routeRequestParams, onRouteSuccess, onRouteError);
};

const onRouteSuccess = (result) => {
  if (!result.routes.length) {
    error.value = "Không tìm thấy đường đi.";
    return;
  }

  const route = result.routes[0];
  const routeShape = route.sections[0].polyline;
  const linestring = H.geo.LineString.fromFlexiblePolyline(routeShape);

  if (routeLine) map.removeObject(routeLine);

  routeLine = new H.map.Polyline(linestring, {
    style: { strokeColor: "#007bff", lineWidth: 5 },
  });

  map.addObject(routeLine);
  map.getViewModel().setLookAtData({
    bounds: routeLine.getBoundingBox(),
  });
};

const onRouteError = (err) => {
  console.error(err);
  error.value = "Lỗi khi vẽ tuyến đường.";
};

onMounted(loadMap);
</script>
