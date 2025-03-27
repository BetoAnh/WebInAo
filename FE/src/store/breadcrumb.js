import { ref } from "vue";

const breadcrumbItems = ref([]);

export const useBreadcrumb = () => {  // ✅ Sửa thành export const
  const setBreadcrumb = (items) => {
    breadcrumbItems.value = items;
  };

  return { breadcrumbItems, setBreadcrumb };
};
