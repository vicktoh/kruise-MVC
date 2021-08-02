import React, {
  FC,
  useState,
  useEffect,
  KeyboardEvent,
  ChangeEvent,
} from "react";

import {
  Button,
  FormControl,
  FormLabel,
  FormErrorMessage,
  FormHelperText,
  Flex,
  HStack,
  Input,
  Select,
  Tag,
  TagLabel,
  TagCloseButton,
  Text,
  Textarea,
  useToast,
} from "@chakra-ui/react";
import { useDispatch } from "react-redux";
import { Formik, Form, Field, FormikHelpers, FieldProps } from "formik";
import { fetchplaces, useAppSelector } from "../reducers";
import { PUT_DATA_URL, UPDATE_DATA_URL } from "../Constants";
import axios from "axios";
import { HTTPResponseType } from "../Types";
import { Data, AcceptedFileTypes } from "../Types";

export const DataForm: FC<{
  data: Partial<Data>;
  mode: "add" | "edit";
  onClose: () => void;
}> = ({ data, mode, onClose }) => {
  const toast = useToast();
  const dispatch = useDispatch();
  const [page, setPage] = useState(1);
  const [tagInput, setTagInput] = useState("");
  const [file, setFile] = useState<Blob & {name?: string} | null>(null);
  const { places } = useAppSelector(({ places }) => places);
  const { categories } = useAppSelector(({ categories }) => categories);
  const initialValues: Partial<Data> =
    mode === "add"
      ? {
          title: "",
          description: "",
          tags: [],
        }
      : data;

  useEffect(() => {
    if (!places) {
      dispatch(fetchplaces());
    }
  }, [places, dispatch]);

  const saveData = async (
    values: Partial<Data>,
    setLoading: (val: boolean) => void
  ) => {
    console.log(values);
    const requestUrl =
      mode === "add" ? PUT_DATA_URL : `${UPDATE_DATA_URL}/${data?.id}`;

    const formdata = new FormData();
    for (const [key, value] of Object.entries(values)) {
      let val = typeof value === "object" ? value.join(",") : value;
      if (key) formdata.append(key, val.toString());
      if(file) formdata.append("file", file);
    }
    try {
      setLoading(true);
      console.log({formdata})
      const response = await axios.post<HTTPResponseType<{ id: number }>>(
        requestUrl,
        formdata
      );
      console.log({response})
      if (response.data?.data) {
        dispatch(fetchplaces());
        toast({
          title: `success`,
          status: "success",
          duration: 2000,
        });
        onClose();
      }
      // console.log(response);
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

  const handleTagInput = (
    e: KeyboardEvent<HTMLInputElement>,
    value: string[] | undefined,
    setFieldValue: (name: string, value: any) => void
  ) => {
    if (e.key !== "Enter") return;

    if (value && !value.includes(tagInput)) {
      setFieldValue("tags", [...value, tagInput]);
      setTagInput("");
    }
  };

  const deleteTag = (
    index: number,
    tags: string[] | undefined,
    setFieldValue: (name: string, value: any) => void
  ) => {
    if (!tags) return;
    tags.splice(index, 1);

    setFieldValue("tags", [...tags]);
  };

  const onFileChange = (e: ChangeEvent<HTMLInputElement>) => {
    if (e.target.files) {
      console.log(e.target.files[0]);
      let file = e.target.files[0];
      if (!Object.keys(AcceptedFileTypes).includes(file.type)) {
        toast({
          title: "Invalid File format",
          description: "Please select a valid file format",
          status: "error",
        });
        return;
      }
      setFile(file);
    }
  };

  return (
    <Formik
      initialValues={initialValues}
      onSubmit={(values, { setSubmitting }: FormikHelpers<Partial<Data>>) => {
        saveData(values, setSubmitting);
      }}
    >
      {({ isSubmitting, values }) => (
        <Form>
          {page === 1 ? (
            <>
              <Text mb={5} color="brand.dark">
                Page 1 of 2
              </Text>
              <Field name="title">
                {({ field, meta: { error, touched }, form }: FieldProps) => (
                  <FormControl
                    mb={4}
                    isInvalid={!!error && !!touched}
                    isRequired
                  >
                    <FormLabel htmlFor="title">Title</FormLabel>
                    <Input {...field} id="title" placeholder="Title" />
                    <FormErrorMessage>{form.errors.name}</FormErrorMessage>
                  </FormControl>
                )}
              </Field>
              <Field name="description">
                {({ field, meta: { error, touched }, form }: FieldProps) => (
                  <FormControl
                    mb={4}
                    isInvalid={!!error && !!touched}
                    isRequired
                  >
                    <FormLabel htmlFor="description">Description</FormLabel>
                    <Textarea {...field} id="description" />
                  </FormControl>
                )}
              </Field>
              <Field name="category_id">
                {({ field, meta: { error, touched }, form }: FieldProps) => (
                  <FormControl
                    mb={4}
                    isInvalid={!!error && !!touched}
                    isRequired
                  >
                    <FormLabel htmlFor="category_id">Category</FormLabel>
                    <Select placeholder="Category">
                      {categories.length &&
                        categories.map(({ title, id }, index) => (
                          <option key={index} value={id}>
                            {title}
                          </option>
                        ))}
                    </Select>
                    <FormErrorMessage>{form.errors.name}</FormErrorMessage>
                  </FormControl>
                )}
              </Field>
              <Field name="location_id">
                {({ field, meta: { error, touched }, form }: FieldProps) => (
                  <FormControl mb={4} isInvalid={!!error && !!touched}>
                    <FormLabel htmlFor="location_id">Location</FormLabel>
                    <Select placeholder="Location" id="location_id">
                      {places &&
                        places.length &&
                        places.map(({ name, id }, index) => (
                          <option key={index} value={id}>
                            {name}
                          </option>
                        ))}
                    </Select>
                    <FormErrorMessage>{form.errors.name}</FormErrorMessage>
                  </FormControl>
                )}
              </Field>
            </>
          ) : (
            <>
              <Text mb={5} color="brand.dark">
                Page 2 of 2
              </Text>
              <Field name="source">
                {({ field, meta: { error, touched }, form }: FieldProps) => (
                  <FormControl mb={4} isInvalid={!!error && !!touched}>
                    <FormLabel htmlFor="source">Source</FormLabel>
                    <Input
                      {...field}
                      id="source"
                      placeholder="Name of source"
                    />
                    <FormErrorMessage>{form.errors.source}</FormErrorMessage>
                  </FormControl>
                )}
              </Field>
              <Field name="source_url">
                {({ field, meta: { error, touched }, form }: FieldProps) => (
                  <FormControl mb={4} isInvalid={!!error && !!touched}>
                    <FormLabel htmlFor="source_url">Source Url</FormLabel>
                    <Input
                      {...field}
                      id="source_url"
                      placeholder="Link to Source"
                    />
                    <FormErrorMessage>{form.errors.name}</FormErrorMessage>
                  </FormControl>
                )}
              </Field>
              <Field name="url">
                {({ field, meta: { error, touched }, form }: FieldProps) => (
                  <FormControl mb={4} isInvalid={!!error && !!touched}>
                    <FormLabel htmlFor="file_url">🔗File Url</FormLabel>
                    <Input
                      {...field}
                      id="file_url"
                      placeholder="Link to File"
                    />
                    <FormErrorMessage>{form.errors.name}</FormErrorMessage>
                  </FormControl>
                )}
              </Field>
              <Field name="file">
                {({
                  field: { name, value },
                  meta: { error, touched },
                  form: { setFieldValue, errors },
                }: FieldProps) => (
                  <FormControl mb={4} isInvalid={!!error && !!touched}>
                    <FormLabel htmlFor="file">File Upload </FormLabel>
                    <FormLabel
                      htmlFor="file"
                      display="flex"
                      height="5rem"
                      flexDirection="column"
                      width="100%"
                      borderColor="brand.alt"
                      borderWidth="1px"
                      borderRadius="lg"
                      borderStyle="dashed"
                      justifyContent="center"
                      alignItems="center"
                      color="brand.light"
                    >
                      {(file && file.name) || " DropFiles Here"}
                    </FormLabel>
                    <Input
                      name={name}
                      value={value}
                      type="file"
                      display={{ base: "none" }}
                      id="file"
                      onChange={onFileChange}
                    />
                    <FormErrorMessage>{errors.name}</FormErrorMessage>
                    {file && file.type && (
                      <Tag colorScheme="blue">
                        {AcceptedFileTypes[file.type]}
                      </Tag>
                    )}
                  </FormControl>
                )}
              </Field>
              <Field name="tags">
                {({
                  field,
                  meta: { error, touched },
                  form: { setFieldValue },
                }: FieldProps<Data>) => (
                  <>
                    <FormControl mb={4}>
                      <FormLabel htmlFor="file_type">#️⃣Tag</FormLabel>
                      <Input
                        value={tagInput}
                        onKeyUp={(e) => {
                          handleTagInput(e, values?.tags, setFieldValue);
                        }}
                        id="file_url"
                        placeholder="Search/Add Tag"
                        onChange={(e) => {
                          setTagInput(e.target.value);
                        }}
                      />
                      <FormHelperText>
                        search tag and press enter to tag this dataset
                      </FormHelperText>
                    </FormControl>
                    <HStack spacing={3}>
                      {values?.tags &&
                        values.tags.length &&
                        values.tags.map((tag, index) => (
                          <Tag key={index}>
                            <TagLabel>{tag}</TagLabel>
                            <TagCloseButton
                              onClick={() =>
                                deleteTag(index, values?.tags, setFieldValue)
                              }
                            />
                          </Tag>
                        ))}
                    </HStack>
                  </>
                )}
              </Field>
            </>
          )}

          <Flex direction="row" justifyContent="space-between" mt={5}>
            {page > 1 && (
              <Button
                variant="secondary"
                alignSelf="flex-start"
                onClick={() => setPage(1)}
              >
                Back
              </Button>
            )}
            {page > 1 && (
              <Button
                isLoading={isSubmitting}
                variant="primary"
                alignSelf="flex-end"
                type="submit"
              >
                Save
              </Button>
            )}

            {page <= 1 && (
              <Button
                variant="primary"
                alignSelf="flex-end"
                onClick={() => setPage(2)}
                ml="auto"
              >
                Next
              </Button>
            )}
          </Flex>
        </Form>
      )}
    </Formik>
  );
};
