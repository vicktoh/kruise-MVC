import React, { FC } from "react";

import {
  Button,
  FormControl,
  FormLabel,
  FormErrorMessage,
  Input,
  useToast,
} from "@chakra-ui/react";
import { useDispatch } from "react-redux";
import { Formik, Form, Field, FormikHelpers, FieldProps } from "formik";
import { fetchplaces } from "../reducers";
import { PUT_PLACE_URL, UPDATE_PLACE_URL } from "../Constants";
import axios from "axios";
import { HTTPResponseType } from "../Types";
import { Place } from "../Types";

export const PlaceForm: FC<{
  place: Partial<Place>;
  mode: "add" | "edit";
  onClose: () => void;
}> = ({ place, mode, onClose }) => {
  
  const toast = useToast();
  const dispatch = useDispatch();
  const initialValues: Partial<Place> =
    mode === "add"
      ? {
          name: "",
          country: "",
          lat: 0,
          lng: 0,
        }
      : place;

    const savePlace = async (values: Partial<Place>, setLoading: (val: boolean)=> void) => {
      const requestUrl = mode === "add" ? PUT_PLACE_URL : `${UPDATE_PLACE_URL}/${place?.id}`;

      try {
        setLoading(true);
        const response = await axios.post<HTTPResponseType<{ id: number }>>(
          requestUrl,
          values
        );
        if (response.data?.data) {
          dispatch(fetchplaces());
          toast(
              {
                  title : `success`,
                  status: "success",
                  duration: 2000
              }
          )
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
          title: "Could not add place, Please Try again",
          duration: 5000,
        });
      } finally {
        setLoading(false);
      }
    };

  return (
    <Formik
      initialValues={initialValues}
      onSubmit={(values, {setSubmitting}: FormikHelpers<Partial<Place>>) => {
          savePlace(values, setSubmitting);
      }}
    >
      {({  isSubmitting }) => (
        <Form>
          <Field name="name">
            {({ field, meta: { error, touched }, form }: FieldProps) => (
              <FormControl isInvalid={!!error && !!touched} isRequired>
                <FormLabel htmlFor="name">Name of Place</FormLabel>
                <Input {...field} id="name" placeholder="name" />
                <FormErrorMessage>{form.errors.name}</FormErrorMessage>
              </FormControl>
            )}
          </Field>
          <Field name="country">
            {({ field, meta: { error, touched }, form }: FieldProps) => (
              <FormControl isInvalid={!!error && !!touched} isRequired>
                <FormLabel htmlFor="name">Country</FormLabel>
                <Input {...field} id="name" placeholder="Country" />
                <FormErrorMessage>{form.errors.name}</FormErrorMessage>
              </FormControl>
            )}
          </Field>
          <Field name="lat" type = "number">
            {({ field, meta: { error, touched }, form }: FieldProps) => (
              <FormControl isInvalid={!!error && !!touched}>
                <FormLabel htmlFor="name">Latitude</FormLabel>
                <Input {...field} id="name" placeholder="lat" />
                <FormErrorMessage>{form.errors.name}</FormErrorMessage>
              </FormControl>
            )}
          </Field>
          <Field name="lng" type = "number">
            {({ field, meta: { error, touched }, form }: FieldProps) => (
              <FormControl isInvalid={!!error && !!touched}>
                <FormLabel htmlFor="name">Longitude</FormLabel>
                <Input {...field} id="name" placeholder="lng" />
                <FormErrorMessage>{form.errors.name}</FormErrorMessage>
              </FormControl>
            )}
          </Field>
          <Button
            variant="primary"
            mt={3}
            type="submit"
            isLoading={isSubmitting}
          >
            Save
          </Button>
        </Form>
      )}
    </Formik>
  );
};
