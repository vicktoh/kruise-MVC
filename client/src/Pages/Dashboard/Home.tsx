import React, { FC, useState, useEffect, useCallback } from "react";
import {
  Box,
  Button,
  Input,
  InputGroup,
  InputRightElement,
  Icon,
  Table,
  TableCaption,
  Tbody,
  Text,
  Thead,
  Tr,
  Th,
  useDisclosure,
  useToast,
} from "@chakra-ui/react";
import { EmptyTableState } from "../../Components/EmptyTableState";
import { LoadingState } from "../../Components/LoadingState";
import { DataForm } from "../../Components/DataForm";
import { FormModal } from "../../Components/FormModal";
import { DeletePrompt } from "../../Components/DeletePrompt";

import { deleteEntity } from "../../reducers";
import { MdSearch } from "react-icons/md";
import { BACK_TABLE_DATA_URL } from "../../Constants";
import axios, { AxiosResponse } from "axios";
import { DataRequestType, Data } from "../../Types/Data";
import { HTTPResponseType } from "../../Types";

type Dataset = Data[];




export const Home: FC = () => {
  const [dataset, setDataset] = useState<Dataset>([]);
  const [data, setData] = useState<Partial<Data>>({
    title: ""
  });
  const [deleteData, setDeleteData] = useState<Partial<Data> | null>(null)
  const [mode, setMode] = useState<"add" | "edit">("add");

  const [loading, setLoading] = useState(false);
  const [title, setTitle] = useState("");
  const [page, setPage] = useState(1);
  const {isOpen, onOpen, onClose} = useDisclosure();
  const {
    isOpen: deleteOpen,
    onOpen: onDeleteOpen,
    onClose: onDeleteClose,
  } = useDisclosure();

  const toast = useToast();
  const fetch_data = useCallback(async () => {
    setLoading(true);
    let option: DataRequestType = { page, options: { title: "" } };
    try {
      const response = await axios.post<
        HTTPResponseType<Dataset>,
        AxiosResponse<HTTPResponseType<Dataset>>
      >(BACK_TABLE_DATA_URL, option);
      const result = response.data;
      if (!result.data || result?.message) {
        toast({
          title: result?.message || "Unknown Error Occured",
          status: "error",
          duration: 5000,
          isClosable: true,
        });
        return;
      }
      setDataset(result.data);
    } catch (e) {
      console.log(e);
      toast({
        title: "Could not reach the server",
        status: "error",
        duration: 5000,
        isClosable: true,
      });
    } finally {
      setLoading(false);
    }
  }, [page,toast]);
  useEffect(() => {
    fetch_data();
  }, [fetch_data]);


  const onDelete = async (id: string | number | undefined) => {
    if (!id) return;
    await deleteEntity(
      id,
      () => {
        console.log("dispatching....");
        setPage(page=> page)
      },
      "categories"
    );
  };
  const openNewDataForm = ()=>{
    setMode("add");
    onOpen();
  }
  return (
    <Box pt={10}>
      <Text color = "black" fontSize = "2xl" fontWeight = "bold" mb = {5}>📂Datasets</Text>
      <form
        onSubmit={(e) => {
          e.preventDefault();
        }}
      >
        <InputGroup>
          <Input
            variant="filled"
            bg="brand.light"
            size="md"
            value={title}
            placeholder="Search Datasets by Title"
            isRequired
            onChange={(e) => {
              setTitle(e.target.value);
            }}
          />
          <InputRightElement
            pointerEvents="none"
            children={<Icon as={MdSearch} />}
          />
        </InputGroup>
      </form>

      <Button ml="auto" size="sm" variant="solid" colorScheme="blue" mt={10} onClick = {openNewDataForm}>
        Add New
      </Button>
      {loading ? (
        <LoadingState />
      ) : dataset.length ? (
        <Table variant="simple" mt={8}>
          <TableCaption>Datasets</TableCaption>
          <Thead>
            <Tr>
              <Th>Title</Th>
              <Th>Last Updated</Th>
              <Th>Actions</Th>
            </Tr>
          </Thead>
          <Tbody width="100%" borderColor="brand.full" borderWidth={2}></Tbody>
        </Table>
      ) : (
        <EmptyTableState />
      )}
      <FormModal title = "Add Dataset" isOpen = {isOpen} onClose = {onClose}>
        <DataForm data = {data} onClose = {onClose} mode = {mode} />
      </FormModal>
      <DeletePrompt
        title="Delete "
        onClose={onDeleteClose}
        isOpen={deleteOpen}
        decline={() => {}}
        confirm={() => onDelete(deleteData?.id)}
      />
    </Box>
  );
};
