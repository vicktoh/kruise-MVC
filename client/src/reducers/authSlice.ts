import { createSlice, PayloadAction } from "@reduxjs/toolkit";
import { Auth } from "../Types/Auth";

const initialState: Auth = {};

const authSlice = createSlice({
  name: "auth",
  initialState,
  reducers: {
    put: (state, action: PayloadAction<Auth>) => {
      const { email, userId } = action.payload;
      state.email = email;
      state.userId = userId;
      return state
    },
  },
});

export const auth = authSlice.reducer;
export const {put} = authSlice.actions;
