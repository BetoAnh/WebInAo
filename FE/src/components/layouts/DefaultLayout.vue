<template>
  <!-- eslint-disable vue/no-v-model-argument -->
  <div class="bg-white select-none">
    <a-flex
      class="justify-center bg-white sticky top-0 z-50 border-b-[1px] border-white"
    >
      <HeaderComponent />
    </a-flex>
    <main>
      <a-flex vertical class="">
        <BreadcrumbComponent v-if="showBreadcrumb" />
        <slot class="container"></slot>
        <a-flex class="justify-center bg-[#EAEBF4] bottom-0">
          <FooterComponent />
        </a-flex>
      </a-flex>
    </main>
    <div
      class="fixed top-[80%] right-[2%] bg-[red] z-[90] p-2 rounded-lg border-[1px] border-gray-600"
    >
      <button class="text-[30px] text-white" @click="hanldeOpenChatBot">
        ChatBot
      </button>
    </div>
    <a-modal v-model:open="openChat" @ok="handleOk" :style="{ top: '50px' }">
      <ChatBotComponent />
    </a-modal>
  </div>
  <!-- eslint-disable vue/no-v-model-argument -->
</template>

<script setup>
import ChatBotComponent from "@/components/chatBot/ChatBotComponent.vue";
import FooterComponent from "@/components/nav/FooterComponent.vue";
import HeaderComponent from "@/components/nav/HeaderComponent.vue";
import BreadcrumbComponent from "../BreadcrumbComponent.vue";
import { ref, computed } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const openChat = ref(false);

const excludedRoutes = ["/", "/login", "/profile", "/blogs"];

const excludedPrefixes = ["/detailNews/"];

const showBreadcrumb = computed(() => {
  const isExactExcluded = excludedRoutes.includes(route.path);
  const isPrefixExcluded = excludedPrefixes.some((prefix) =>
    route.path.startsWith(prefix)
  );
  return !isExactExcluded && !isPrefixExcluded;
});

const hanldeOpenChatBot = () => {
  openChat.value = true;
};
const handleOk = () => {
  openChat.value = false;
};
</script>

<style scoped></style>
