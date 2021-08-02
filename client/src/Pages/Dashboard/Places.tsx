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
import { PlaceForm } from "../../Components/PlaceForm";
import { EmptyTableState } from "../../Components/EmptyTableState";
import { LoadingState } from "../../Components/LoadingState";
import { DeletePrompt } from "../../Components/DeletePrompt";

import { useAppSelector, fetchplaces, deleteEntity } from "../../reducers";

import { useDispatch } from "react-redux";
import { Place } from "../../Types";

type PlaceRowProps = {
  place: Place;
  onEdit: (place: Place) => void;
  onDelete: (id: string | number) => void;
};

const PlaceRow: FC<PlaceRowProps> = ({ place, onEdit, onDelete }) => {
  return (
    <Tr>
      <Td>{place.name}</Td>
      <Td>{place.country}</Td>
      <Td>{place.date_updated}</Td>
      <Td>
        <HStack spacing={4}>
          <Button
            variant="nativeBlue"
            size="sm"
            onClick={() => {
              onEdit(place);
            }}
          >
            Edit
          </Button>
          <Button
            variant="nativeRed"
            size="sm"
            onClick={() => {
              onDelete(place.id);
            }}
          >
            Delete
          </Button>
        </HStack>
      </Td>
    </Tr>
  );
};

export const Places: FC = () => {
  const emptyFormState: Partial<Place> = {
    name: "",
    country: "",
  };
  const [data, setData] = useState<Partial<Place>>(emptyFormState);
  const [deletePlace, setDeletePlace] = useState<Place | null>(null);

  const [mode, setMode] = useState<"add" | "edit">("add");
  const places = useAppSelector(({ places }) => places);
  const dispatch = useDispatch();
  const toast = useToast();
  const { isOpen, onOpen, onClose } = useDisclosure();
  const {
    isOpen: deleteOpen,
    onOpen: onDeleteOpen,
    onClose: onDeleteClose,
  } = useDisclosure();

  useEffect(() => {
    if (!places.places) {
      dispatch(fetchplaces());
    }
    if (places.status === "failed") {
      toast({
        title: "Failed to load places. Try again",
        status: "error",
        duration: 5000,
      });
    }
  }, [dispatch, places, toast]);

  const onEdit = (place: Partial<Place>) => {
    setData(place);
    setMode("edit");
    onOpen();
    return;
  };

  const deletePrompt = (place: Place) => {
    setDeletePlace(place);
    onDeleteOpen();
  };

  const onDelete = async (id: string | number) => {
    if (!id) return;
    deleteEntity(
      id,
      () => {
        dispatch(fetchplaces());
      },
      "places"
    );
  };

  return (
    <Box pt={10}>
      <Text color="black" fontSize="2xl" fontWeight="bold" mb={5}>
        🌎 Places/Locations
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
      {places.status === "pending" ? (
        <LoadingState />
      ) : places.places?.length ? (
        <Table variant="simple" mt={8} bg="brand.light" borderRadius="lg">
          <TableCaption>Places</TableCaption>
          <Thead>
            <Tr>
              <Th>Title</Th>
              <Th>Country</Th>
              <Th>Last Updated</Th>
            </Tr>
          </Thead>
          <Tbody width="100%">
            {places.places.map((place: Place) => (
              <PlaceRow place={place} onEdit={onEdit} onDelete={()=> deletePrompt(place)} />
            ))}
          </Tbody>
        </Table>
      ) : (
        <EmptyTableState />
      )}
      <FormModal title="Add/Edit Place" isOpen={isOpen} onClose={onClose}>
        <PlaceForm mode={mode} place={data} onClose={onClose} />
      </FormModal>
      <DeletePrompt
        title="Delete Place"
        onClose={onDeleteClose}
        isOpen={deleteOpen}
        decline={() => {}}
        confirm={() => onDelete(deletePlace?.id || 0)}
      />
    </Box>
  );
};
