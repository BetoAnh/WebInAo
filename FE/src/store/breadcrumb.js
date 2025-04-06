import { ref } from "vue";

const breadcrumbItems = ref([]);

export const useBreadcrumb = () => {
  const setBreadcrumb = (items) => {
    breadcrumbItems.value = items;
  };

  return { breadcrumbItems, setBreadcrumb };
};
