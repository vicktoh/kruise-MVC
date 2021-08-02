import React, { FC } from "react";
import { Center, Text } from "@chakra-ui/react";

export const EmptyTableState: FC = () => {
  return (
    <Center
      direction="column"
      alignItems="center"
      justifyContent="center"
      height="5.2em"
      mt={10}
    >
      <Text color="brand.alt" size="lg" fontWeight="bold">
        📪 There is no data on the table at the moment
      </Text>
    </Center>
  );
};
