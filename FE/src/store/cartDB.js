// cartDB.js
import { openDB } from "idb";

const DB_NAME = "myCartDB";
const STORE_NAME = "cart";

export const getDB = () => {
  return openDB(DB_NAME, 1, {
    upgrade(db) {
      if (!db.objectStoreNames.contains(STORE_NAME)) {
        db.createObjectStore(STORE_NAME, {
          keyPath: "id",
          autoIncrement: true,
        });
      }
    },
  });
};

export const addToCart = async (item) => {
  const db = await getDB();
  await db.add(STORE_NAME, item);
};

export const getCartItems = async () => {
  const db = await getDB();
  return await db.getAll(STORE_NAME);
};

export const clearCart = async () => {
  const db = await getDB();
  await db.clear(STORE_NAME);
};
