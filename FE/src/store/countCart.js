import { ref } from "vue";
import { getCartItems } from "@/store/cartDB";

const countCart = ref(0);

export const useCountCart = () => {
  const fetchCartCount = async () => {
    const itemsCart = await getCartItems();
    countCart.value = itemsCart.length;
  };
  fetchCartCount();

  return { countCart, fetchCartCount };
};
