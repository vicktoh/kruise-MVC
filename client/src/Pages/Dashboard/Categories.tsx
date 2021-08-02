import React, { FC, useState, useEffect } from "react";
import {
  Box,
  Button,
  Table,
  TableCaption,
  Tbody,
  Td,
  Text,
  Thead,
  Tr,
  Th,
  useDisclosure,
  useToast,
  HStack,
} from "@chakra-ui/react";
import { FormModal } from "../../Components/FormModal";
import { EmptyTableState } from "../../Components/EmptyTableState";
import { LoadingState } from "../../Components/LoadingState";
import { CategoriesForm } from "../../Components/CategoriesForm";
import { DeletePrompt } from "../../Components/DeletePrompt";

import { useAppSelector, fetchCategories, deleteEntity } from "../../reducers";

import { useDispatch } from "react-redux";
import { Category } from "../../Types";

type CategoryRowProps = {
  category: Category;
  onEdit: (category: Category) => void;
  onDelete: (id: string | number) => void;
};

const CategoryRow: FC<CategoryRowProps> = ({ category, onEdit, onDelete }) => {
  return (
    <Tr>
      <Td>{category.title}</Td>
      <Td>{category.date_updated}</Td>
      <Td>
        <HStack spacing={4}>
          <Button
            variant="nativeBlue"
            size="sm"
            onClick={() => {
              onEdit(category);
            }}
          >
            Edit
          </Button>
          <Button
            variant="nativeRed"
            size="sm"
            onClick={() => {
              onDelete(category.id);
            }}
          >
            Delete
          </Button>
        </HStack>
      </Td>
    </Tr>
  );
};

export const Categories: FC = () => {
  const [data, setData] = useState<Partial<Category>>({ title: "" });
  const [mode, setMode] = useState<"add" | "edit">("add");
  const [deleteCategory, setDeleteCategory] = useState<Category | null>(null);
  const categories = useAppSelector(({ categories }) => categories);
  const dispatch = useDispatch();
  const toast = useToast();
  const { isOpen, onOpen, onClose } = useDisclosure();
  const {
    isOpen: deleteOpen,
    onOpen: onDeleteOpen,
    onClose: onDeleteClose,
  } = useDisclosure();

  useEffect(() => {
    if (!categories) {
      dispatch(fetchCategories());
    }
    if (categories.status === "failed") {
      toast({
        title: "Failed to load categories. Try again",
        status: "error",
        duration: 5000,
      });
    }
  }, [dispatch, categories, toast]);

  const onEdit = (category: Partial<Category>) => {
    setData(category);
    setMode("edit");
    onOpen();
    return;
  };

  const deletePrompt = (category: Category) => {
    setDeleteCategory(category);
    onDeleteOpen();
  };
  const onDelete = async (id: string | number | undefined) => {
    if (!id) return;
    await deleteEntity(
      id,
      () => {

        dispatch(fetchCategories());
      },
      "categories"
    );
  };

  return (
    <Box pt={10}>
      <Text color="black" fontSize="2xl" fontWeight="bold" mb={5}>
        #️⃣ Categories
      </Text>
      <Button
        ml="auto"
        size="sm"
        variant="solid"
        colorScheme="blue"
        mt={10}
        onClick={() => {
          setMode("add");
          onOpen();
        }}
      >
        Add New
      </Button>
      {categories.status === "pending" ? (
        <LoadingState />
      ) : categories.categories.length ? (
        <Table variant="simple" mt={8} bg="brand.light" borderRadius="lg">
          <TableCaption>Datasets</TableCaption>
          <Thead>
            <Tr>
              <Th>Title</Th>
              <Th>Last Updated</Th>
              <Th>Actions</Th>
            </Tr>
          </Thead>
          <Tbody width="100%">
            {categories.categories.map((category: Category, index) => (
              <CategoryRow
                key={index}
                category={category}
                onEdit={onEdit}
                onDelete={() => deletePrompt(category)}
              />
            ))}
          </Tbody>
        </Table>
      ) : (
        <EmptyTableState />
      )}
      <FormModal title="Add/Edit Categories" isOpen={isOpen} onClose={onClose}>
        <CategoriesForm onClose={onClose} category={data} mode={mode} />
      </FormModal>
      <DeletePrompt
        title="Delete "
        onClose={onDeleteClose}
        isOpen={deleteOpen}
        decline={() => {}}
        confirm={() => onDelete(deleteCategory?.id)}
      />
    </Box>
  );
};
