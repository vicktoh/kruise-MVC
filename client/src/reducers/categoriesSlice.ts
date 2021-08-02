import { createSlice, createAsyncThunk, PayloadAction } from "@reduxjs/toolkit";
import axios, { AxiosResponse } from "axios";

import { FETCH_CATEGORIES_URL } from "../Constants";

import { Category, HTTPResponseType } from "../Types";
type CategoryReducerType = {
  categories: Category[];
  loading: boolean;
  status: "success" | "pending" | "failed" | "";
};

const initialState: CategoryReducerType = {
  categories: [],
  loading: false,
  status: "",
};
export const fetchCategories = createAsyncThunk(
  "categories/fetchCategories",
  async () => {
    try {
      const response = await axios.post<
        Category[],
        AxiosResponse<HTTPResponseType<Category[]>>
      >(FETCH_CATEGORIES_URL, { page: 1 });
      console.log({response});
      if (response.data?.data) {
        return response.data.data;
      }
    } catch (e) {
      return null;
    }
  }
);

export const categoriesSlice = createSlice({
  name: "categories",
  initialState,
  reducers: {},
  extraReducers: {
    [fetchCategories.pending as any]: (state, action) => {
      state.loading = true;
      state.status = "pending";
      return state;
    },
    [fetchCategories.fulfilled as any]: (
      state,
      action: PayloadAction<Category[]>
    ) => {
      state.loading = false;
      if (action.payload) {
        state.categories = action.payload;
        state.status = "success";
        return state;
      }
      state.status = "failed";
      return state;
    },
  },
});

export const categories = categoriesSlice.reducer;
