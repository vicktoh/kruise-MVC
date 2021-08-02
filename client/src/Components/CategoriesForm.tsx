import React, { FC, useState } from "react";

import {
  Button,
  FormControl,
  FormLabel,
  FormHelperText,
  Input,
  useToast,
} from "@chakra-ui/react";
import { useDispatch } from "react-redux";
import { fetchCategories } from "../reducers";
import { PUT_CATEGORY_URL, UPDATE_CATEGORY_URL } from "../Constants";
import axios from "axios";
import { HTTPResponseType } from "../Types";
import { Category } from "../Types";

export const CategoriesForm: FC<{
  category?: Partial<Category>;
  mode: "add" | "edit";
  onClose: () => void;
}> = ({ category, mode, onClose }) => {
  const [loading, setLoading] = useState<boolean>(false);
  const [title, setTitle] = useState(mode === "add" ? "" : category?.title);
  const toast = useToast();
  const dispatch = useDispatch();

  const saveCategory = async () => {
    const requestUrl =
      mode === "add"
        ? PUT_CATEGORY_URL
        : `${UPDATE_CATEGORY_URL}/${category?.id}`;

    try {
      if (!title) return;
      setLoading(true);
      const response = await axios.post<HTTPResponseType<{ id: number }>>(
        requestUrl,
        { title }
      );
      if (response.data?.data) {
        dispatch(fetchCategories());
        toast({
          title: `success`,
          status: "success",
          duration: 2000,
        });
        onClose();
      }
      console.log(response);
      if (response.data?.message) {
        toast({
          title: response.data.message,
          status: "error",
          duration: 5000,
        });
      }
    } catch (e) {
      toast({
        status: "error",
        title: "Could not add category, Please Try again",
        duration: 5000,
      });
    } finally {
      setLoading(false);
    }
  };

  return (
    <form
      onSubmit={(e) => {
        e.preventDefault();
        saveCategory();
      }}
    >
      <FormControl>
        <FormLabel>Title</FormLabel>
        <Input
          value={title}
          type="text"
          onChange={(e) => setTitle(e.target.value)}
          isRequired
          name="title"
        />
        <FormHelperText></FormHelperText>
      </FormControl>
      <Button variant="primary" mt={3} type="submit" isLoading={loading}>
        Save
      </Button>
    </form>
  );
};
