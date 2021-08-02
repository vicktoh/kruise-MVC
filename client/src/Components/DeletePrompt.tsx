import React, { FC, useRef, useState } from "react";

import {
  AlertDialog,
  AlertDialogBody,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogContent,
  AlertDialogOverlay,
  Button,
} from "@chakra-ui/react";

import { FormModalProps } from "./FormModal";

type DeletePromptProps = FormModalProps & {
  confirm: () => void;
  decline: () => void;
};

export const DeletePrompt: FC<DeletePromptProps> = ({
  isOpen,
  onClose,
  title,
  confirm,
  decline,
}) => {
  const cancelRef = useRef(null);
  const [isLoading, setIsLoading] = useState<boolean>(false);

  return (
    <AlertDialog
      isOpen={isOpen}
      leastDestructiveRef={cancelRef}
      onClose={onClose}
    >
      <AlertDialogOverlay>
        <AlertDialogContent>
          <AlertDialogHeader fontSize="lg" fontWeight="bold">
            {title}
          </AlertDialogHeader>

          <AlertDialogBody>
            Are you sure? You can't undo this action afterwards.
          </AlertDialogBody>

          <AlertDialogFooter>
            <Button
              ref={cancelRef}
              onClick={() => {
                decline();
                onClose();
              }}
              variant = "primary"
            >
              Cancel
            </Button>
            <Button
              variant = "outline"
              onClick={async () => {
                setIsLoading(true);
                await confirm();
                setIsLoading(false);
                onClose();
              }}
              ml={3}
              isLoading = {isLoading}
            >
              Delete
            </Button>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialogOverlay>
    </AlertDialog>
  );
};
