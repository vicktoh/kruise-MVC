import React, { FC } from "react";
import { Center, Spinner } from "@chakra-ui/react";

export const LoadingState: FC = () => {
  return (
    <Center
      direction="column"
      alignItems="center"
      justifyContent="center"
      width="100%"
      height="8.2rem"
      mt={10}
    >
      <Spinner size="lg" />
    </Center>
  );
};
