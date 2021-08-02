import { createSlice, createAsyncThunk, PayloadAction } from "@reduxjs/toolkit";
import axios, { AxiosResponse } from "axios";

import { FETCH_PLACES_URL, DELETE_ENTITY_URL } from "../Constants";

import { Place, HTTPResponseType, ResponseType } from "../Types";
type PlaceReducerType = {
  places: null | Place[];
  loading: boolean;
  status: "success" | "pending" | "failed" | "";
};

const initialState: PlaceReducerType = {
  places: null,
  loading: false,
  status: "",
};
export const fetchplaces = createAsyncThunk("places/fetchplaces", async () => {
  try {
    const response = await axios.post<
      Place[],
      AxiosResponse<HTTPResponseType<Place[]>>
    >(FETCH_PLACES_URL, { page: 1 });
    // console.log({response});
    if (response.data?.data) {
      return response.data.data;
    }
  } catch (e) {
    return null;
  }
});

export const deleteEntity = async (
  id: number | string,
  dispatchCallback: () => void,
  entity: string
) => {
  try {
    const response = await axios.post<
    HTTPResponseType<{id?:number}>
    >(`${DELETE_ENTITY_URL}${id}`, { table: entity });
    console.log({ response });
    if (
      response.data?.status &&
      response.data?.status === ResponseType.OkayResponse
    ) {
      dispatchCallback();
      return;
    }
  } catch (e) {
    return null;
  }
};

export const placesSlice = createSlice({
  name: "places",
  initialState,
  reducers: {},
  extraReducers: {
    [fetchplaces.pending as any]: (state, action) => {
      state.loading = true;
      state.status = "pending";
      return state;
    },
    [fetchplaces.fulfilled as any]: (state, action: PayloadAction<Place[]>) => {
      state.loading = false;
      if (action.payload) {
        state.places = action.payload;
        state.status = "success";
        return state;
      }
      state.status = "failed";
      return state;
    },
  },
});

export const places = placesSlice.reducer;
