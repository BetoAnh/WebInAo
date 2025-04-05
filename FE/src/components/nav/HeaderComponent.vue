<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <a-flex class="w-full justify-center items-center">
    <a-flex class="flex-1 justify-between p-5 max-w-[1280px] container">
      <a-flex class="flex justify-center items-center">
        <router-link to="/">
          <img
            src="@/assets/logo.png"
            alt="Logo"
            class="w-[300px] min-w-[100px] opacity-95"
          />
        </router-link>
      </a-flex>
      <a-flex
        class="justify-center items-center w-full px-2 max-w-[650px]"
        vertical
      >
        <a-input
          placeholder="Chúng tôi có thể giúp bạn tìm kiếm?"
          @focus="searchInputHover = true"
          @blur="handleBlur"
          v-model:value="searchInput"
          class="w-full max-w-[650px]"
        >
          <template #suffix>
            <BxSearch />
          </template>
        </a-input>
      </a-flex>
      <a-flex class="flex md:hidden justify-center items-center p-3">
        <AnOutlinedMenu class="text-[20px]" @click="showMenu" />
        <MenuComponent v-if="isOpenMenu" @close-menu="showMenu" />
      </a-flex>
      <a-flex class="items-center justify-end gap-4 md:flex hidden">
        <!-- <BxSearch class="" @click="showSearch" /> -->
        <SearchComponent v-if="isOpenSearch" @close-search="showSearch" />
        <a-badge
          :count="quantityProductInCart"
          show-zero
          :number-style="{
            backgroundColor: '#fff',
            color: 'black',
            boxShadow: '0 0 0 1px #d9d9d9 inset',
          }"
        >
          <BsCart2 class="text-[20px]" @click="showCart" />
        </a-badge>
        <!-- <AnOutlinedMenu class="icon iconHidden" @click="showMenu" /> -->
        <MenuComponent v-if="isOpenMenu" @close-menu="showMenu" />
        <RouterLink to="/san-pham/ao-thun-basic-unisex" class="px-3 text-black"
          >TEst</RouterLink
        >

        <a-flex class="items-center whitespace-nowrap">
          <a-flex
            vertical
            v-if="isLogin"
            class="icon iconShow group relative items-center"
            ><CaUserAvatarFilledAlt class="text-[40px] text-black" /><span
              class="text-[13px] font-medium mb-2 text-black"
              >Hello, {{ firstName }}</span
            >

            <div
              class="hidden group-hover:flex flex-col absolute bg-white text-black left-[-10px] border-[1px] border-gray-200 top-[50px] rounded-md mt-3 text-[17px] overflow-hidden"
            >
              <RouterLink
                to="/profile"
                class="hover:bg-[#02B6AC] hover:text-white px-3"
                >Profile</RouterLink
              >
              <RouterLink
                to="#"
                @click="showLogoutConfirm"
                class="hover:bg-[#02B6AC] hover:text-white px-3"
              >
                Logout
              </RouterLink>
            </div>
          </a-flex>
          <RouterLink to="/login" v-else>
            <a-flex
              class="px-4 py-2 justify-center items-center bg-white border text-[15px] text-black font-bold rounded-md cursor-pointer peer-hover:animate-ping transition-transform hover:scale-105"
              >Đăng nhập</a-flex
            >
          </RouterLink>
        </a-flex>
      </a-flex>
    </a-flex>
    <a-flex></a-flex>
  </a-flex>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import MenuComponent from "../MenuComponent.vue";
import SearchComponent from "../SearchComponent.vue";
import { ref, onMounted, computed, watchEffect, watch, createVNode } from "vue";
import { ExclamationCircleOutlined } from "@ant-design/icons-vue";
import { Modal } from "ant-design-vue";
import {
  BxSearch,
  BsCart2,
  AnOutlinedMenu,
  CaUserAvatarFilledAlt,
} from "@kalimahapps/vue-icons";
import store from "@/store/store";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();
const route = useRoute();
const isLogin = ref(false);
const firstName = ref("");
const searchInputHover = ref(false);

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
});

const isOpenMenu = ref(false);
const isOpenSearch = ref(false);
const quantityProductInCart = computed(() => {
  return store.getters["product/getDataStoreCart"].length;
});
const showCart = () => {
  router.push("/cart");
};

const showMenu = () => {
  isOpenMenu.value = !isOpenMenu.value;
};

const showSearch = () => {
  isOpenSearch.value = !isOpenSearch.value;
};

const searchInput = ref("");

const handleBlur = () => {
  setTimeout(() => {
    searchInputHover.value = false;
  }, 200);
};
</script>

<style scoped></style>
