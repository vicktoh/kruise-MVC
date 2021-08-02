import { configureStore } from "@reduxjs/toolkit";

import {auth, categories, places } from "./"

export const store = configureStore({
  reducer: {
    auth,
    categories,
    places
  },
});

export type RootState = ReturnType<typeof store.getState>;
export type AppDispatch = typeof store.dispatch;
