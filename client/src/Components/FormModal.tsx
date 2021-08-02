import React, { FC } from "react";

import {
  Button,
  Modal,
  ModalOverlay,
  ModalContent,
  ModalHeader,
  ModalFooter,
  ModalBody,
  ModalCloseButton,
} from "@chakra-ui/react";

export type FormModalProps = {
  title: string;
  isOpen: boolean;
  onClose: () => void;
  size ?: "xl" | "sm" | "md" | "lg"
};

export const FormModal: FC<FormModalProps> = ({
  title,
  children,
  isOpen,
  onClose,
  size = "xl"
}) => {
  return (
    <Modal
      isOpen={isOpen}
      onClose={onClose}
      isCentered={true}
      closeOnOverlayClick={false}
      size= {size}
    >
      <ModalOverlay />
      <ModalContent>
        <ModalHeader>{title}</ModalHeader>
        <ModalCloseButton />
        <ModalBody>{children}</ModalBody>
        <ModalFooter>
          <Button variant="secondary" onClick={onClose}>
            Close
          </Button>
        </ModalFooter>
      </ModalContent>
    </Modal>
  );
};
