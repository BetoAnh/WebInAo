<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <a-flex vertical class="w-full justify-center items-center shadow-md">
    <a-flex
      class="flex-1 justify-between items-center px-5 pt-5 max-w-[1280px] container"
    >
      <a-flex class="flex justify-center items-center">
        <router-link to="/">
          <img
            src="@/assets/logo.png"
            alt="Logo"
            class="max-w-[100px] opacity-95"
          />
        </router-link>
      </a-flex>
      <a-dropdown>
        <a-input
          placeholder="Chúng tôi có thể giúp bạn tìm kiếm?"
          v-model:value="searchInput"
          class="w-full max-w-[650px] h-[40px] mx-5 my-1"
          @click.prevent
        >
          <template #suffix>
            <BxSearch />
          </template>
        </a-input>
        <template #overlay>
          <a-menu v-if="filteredSearchData">
            <a-menu-item v-for="item in filteredSearchData" :key="item.id">
              <RouterLink :to="`/san-pham/${item.slug}`">
                <div class="flex justify-between items-center p-2">
                  <span class="font-semibold">{{ item.name }}</span>
                  <div class="w-[50px]">
                    <img v-if="item.image" :src="item.image" />
                    <img v-else src="../../assets/error_img.png" />
                  </div>
                </div>
              </RouterLink>
            </a-menu-item>
          </a-menu>
        </template>
      </a-dropdown>
      <a-flex class="flex md:hidden justify-center items-center p-3">
        <AnOutlinedMenu
          class="text-[20px] text-black cursor-pointer"
          @click="showMenu"
        />
        <MenuComponent v-if="isOpenMenu" @close-menu="showMenu" />
      </a-flex>
      <a-flex class="items-center justify-end gap-5 md:flex hidden">
        <a-badge
          :count="countCart"
          show-zero
          :number-style="{
            backgroundColor: '#fff',
            color: 'black',
            boxShadow: '0 0 0 1px #d9d9d9 inset',
          }"
        >
          <BsCart2 class="text-[20px]" @click="showCart" />
        </a-badge>
        <MenuComponent v-if="isOpenMenu" @close-menu="showMenu" />
        <a-flex class="items-center whitespace-nowrap">
          <a-dropdown v-if="isLogin" class="icon iconShow">
            <div class="flex flex-col items-center" @click.prevent>
              <CaUserAvatarFilledAlt class="text-[30px] text-black" />
              <span class="text-[13px] font-medium text-black"
                >Hello, {{ firstName }}</span
              >
            </div>
            <template #overlay>
              <a-menu>
                <a-menu-item key="1"
                  ><RouterLink
                    to="/profile"
                    class="hover:bg-[#02B6AC] hover:text-white px-3"
                    >Profile</RouterLink
                  ></a-menu-item
                >
                <a-menu-item key="2"
                  ><RouterLink
                    to="#"
                    @click="showLogoutConfirm"
                    class="hover:bg-[#02B6AC] hover:text-white px-3"
                  >
                    Logout
                  </RouterLink></a-menu-item
                >
              </a-menu>
            </template>
          </a-dropdown>
          <RouterLink to="/login" v-else>
            <a-flex
              class="px-4 py-2 justify-center items-center bg-white border text-[15px] text-black font-bold rounded-md cursor-pointer peer-hover:animate-ping transition-transform hover:scale-105"
              >Đăng nhập</a-flex
            >
          </RouterLink>
        </a-flex>
      </a-flex>
    </a-flex>
    <a-flex class="w-full p-3 max-w-[1280px] container justify-center"
      ><NavHeaderComponent
    /></a-flex>
  </a-flex>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import MenuComponent from "../MenuComponent.vue";
import NavHeaderComponent from "./NavHeaderComponent.vue";
import {
  ref,
  onMounted,
  watchEffect,
  watch,
  createVNode,
  inject,
  computed,
} from "vue";
import { ExclamationCircleOutlined } from "@ant-design/icons-vue";
import { Modal } from "ant-design-vue";
import {
  BxSearch,
  BsCart2,
  AnOutlinedMenu,
  CaUserAvatarFilledAlt,
} from "@kalimahapps/vue-icons";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";
const { countCart } = inject("countCart");

const router = useRouter();
const route = useRoute();
const isLogin = ref(false);
const firstName = ref("");
const searchData = ref([]);
const searchInput = ref("");

const filteredSearchData = computed(() => {
  if (!searchInput.value) {
    return null;
  }
  return searchData.value.filter((product) =>
    product.name.toLowerCase().includes(searchInput.value.toLowerCase())
  );
});

const fetchSearch = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_APP_URL_API_PRODUCT}/search`
    );
    searchData.value = response.data;
  } catch (error) {
    console.error(error);
  }
};

const getUser = async () => {
  try {
    const response = await axios.post(
      `${import.meta.env.VITE_APP_URL_API_USER}/profile`,
      {},
      {
        withCredentials: true,
      }
    );
    if (response.status === 204) {
      console.log("Token lỗi hoặc đã hết hạn!");
      sessionStorage.removeItem("user");
      isLogin.value = false;
      return;
    }
    if (response.status === 205) {
      return;
    } else if (response.data) {
      const user = response.data;
      console.log(user.value);
      sessionStorage.setItem("user", JSON.stringify(user));
      firstName.value = user.first_name;
      isLogin.value = true;
    } else {
      isLogin.value = false;
    }
  } catch (error) {
    console.error("Failed to fetch user profile:", error);
    if (error.response && error.response.status === 401) {
      console.log("Chưa đăng nhập");
      sessionStorage.removeItem("user");
    }
    isLogin.value = false;
  }
};

const getUserSession = () => {
  const storedUser = sessionStorage.getItem("user");
  if (storedUser) {
    const user = JSON.parse(storedUser);
    firstName.value = user.first_name;
    isLogin.value = true;
    return true;
  }
  return false;
};

const checkUserSession = () => {
  if (!getUserSession()) {
    getUser();
  } else {
    return;
  }
};
const showLogoutConfirm = () => {
  Modal.confirm({
    title: "Chắc chắn đăng xuất?",
    icon: createVNode(ExclamationCircleOutlined),
    onOk() {
      handleLogout();
    },
    onCancel() {},
    class: "test",
  });
};

const handleLogout = async () => {
  try {
    const response = await axios.post(
      `${import.meta.env.VITE_APP_URL_API}/logout`,
      {},
      { withCredentials: true }
    );

    if (response.data) {
      sessionStorage.removeItem("user");
      isLogin.value = false;
      router.push("/");
    }
  } catch (error) {
    console.error("Đăng xuất thất bại:", error.response?.data || error.message);
    alert("Đăng xuất thất bại! Vui lòng kiểm tra lại.");
  }
};

watch(
  () => route.fullPath,
  () => {
    getUserSession();
  }
);

watchEffect(() => {
  isLogin.value = !!firstName.value;
});

const fetchData = () => {
  isLogin.value = !!firstName.value;
};

onMounted(() => {
  fetchData();
  checkUserSession();
  fetchSearch();
});

const isOpenMenu = ref(false);

const showCart = () => {
  router.push("/cart");
};

const showMenu = () => {
  isOpenMenu.value = !isOpenMenu.value;
};
</script>

<style scoped></style>
