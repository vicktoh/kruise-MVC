import React, { FC } from "react";
import { useRouteMatch } from "react-router";
import { Link } from "react-router-dom";

import { Flex, Text, VStack, Avatar, Button, Icon } from "@chakra-ui/react";
import {
  MdDashboard,
  MdSettings,
  MdPlace,
  MdStyle,
  MdPowerSettingsNew,
} from "react-icons/md";

export const SideNav: FC = () => {
  const { path } = useRouteMatch();
  const isSettingsPage = !!useRouteMatch("/dashboard/settings");
  const isPlacesPage = !!useRouteMatch("/dashboard/places");
  const isCategories = !!useRouteMatch("/dashboard/categories");
  const isDashboardPage =
    !!useRouteMatch("/dashboard") &&
    !isSettingsPage &&
    !isPlacesPage &&
    !isCategories;

  return (
    <Flex
      direction="column"
      borderWidth="1px"
      borderRadius="md"
      height="100vh"
      bg="brand.light"
      px={3}
      pt={3}
      alignItems="center"
    >
      <Text fontSize="md" fontWeight="bold" my={4}>
        DATAPHYTE
      </Text>
      <Avatar name="Kunle Adelowo" size="lg" as={Link} to="/dasboard/profile" />
      <Text fontSize="md" my={2}>
        Hello Kunle Adelowo
      </Text>

      <VStack width="100%" align="stretch" mt={6} spacing={6} px={8}>
        <Button
          as={Link}
          to={`${path}`}
          size="md"
          variant={isDashboardPage ? "outline" : "solid"}
          bg="white"
          color="brand.dark"
          leftIcon={<Icon as={MdDashboard} />}
        >
          Dashboard
        </Button>
        <Button
          as={Link}
          to={`${path}/places`}
          size="md"
          variant={isPlacesPage ? "outline" : "solid"}
          bg="white"
          color="brand.dark"
          leftIcon={<Icon as={MdPlace} />}
        >
          Places
        </Button>
        <Button
          as={Link}
          to={`${path}/categories`}
          size="md"
          variant={isCategories ? "outline" : "solid"}
          bg="white"
          color="brand.dark"
          leftIcon={<Icon as={MdStyle} />}
        >
          Categories
        </Button>
        <Button
          as={Link}
          to={`${path}/settings`}
          size="md"
          variant={isSettingsPage ? "outline" : "solid"}
          bg="white"
          color="brand.dark"
          leftIcon={<Icon as={MdSettings} />}
        >
          Settings
        </Button>
      </VStack>
      <Button
        size="md"
        variant="secondary"
        mt={14}
        leftIcon={<Icon as={MdPowerSettingsNew} />}
      >
        Log Out
      </Button>
    </Flex>
  );
};
