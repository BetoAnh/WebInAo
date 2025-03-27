<template>
  <a-descriptions layout="horizontal" bordered size="small" :column="1">
    <a-descriptions-item
      :style="{ border: '1px solid black' }"
      v-for="(item, index) in items"
      :key="index"
    >
      <template #label>
        <span class="font-bold text-[#4F81BD]">{{ item.label }}</span>
      </template>
      <div v-html="formattedValue(item.value)"></div>
    </a-descriptions-item>
  </a-descriptions>
</template>

<script setup>
defineProps({
  items: {
    type: Array,
    required: true,
  },
});

const formattedValue = (value) => {
  return value.replace(/\n/g, "<br>");
};
</script>

<style scoped></style>
