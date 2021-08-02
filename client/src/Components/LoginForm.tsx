import React, { FC, useState } from "react";
import {
  Box,
  Input,
  FormControl,
  FormLabel,
  FormErrorMessage,
  Button,
  Text,
  useToast,
} from "@chakra-ui/react";
import { useHistory } from "react-router";
import { useDispatch } from "react-redux";
import { put } from "../reducers/authSlice";
import axios, { AxiosResponse } from "axios";
import { BASE_URL } from "../Constants";
import { HTTPResponseType, Auth } from "../Types/Auth";

const loginPath = `${BASE_URL}/Login/transact/login`;

export const LoginForm: FC<{}> = () => {
  const history = useHistory();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [isLoading, setLoading] = useState(false);
  const toast = useToast();
  const dispatch = useDispatch();
  const login = async () => {
    setLoading(true);
    try {
      const { data } = await axios.post<
        { email: string; password: string },
        AxiosResponse<HTTPResponseType<Auth>>
      >(loginPath, { email, password });
      const { message, data: auth } = data;
      if (!auth) {
        toast({
          title: message || "Unknown Error Try Again",
          status: "error",
          description:
            message || "An Unknown Error Has occured please try again",
          duration: 5000,
          isClosable: true,
        });

        return;
      }

      dispatch(put({ ...auth }));
      history.push("/dashboard");
    } catch (e) {
      toast({
        title: "Unknown Error Try Again",
        status: "error",
        description: "An Unknown Error Has occured please try again",
        duration: 5000,
        isClosable: true,
      });
      setLoading(false);
    }
  };
  return (
    <Box px={10} py={10} width="xl" bg="brand.light" borderRadius="lg">
      <Text fontSize="3xl" fontWeight="bold" color="brand.full" mb={10}>
        Dataphyte
      </Text>
      <FormErrorMessage></FormErrorMessage>
      <FormControl mb={4}>
        <FormLabel>Email</FormLabel>
        <Input
          type="email"
          name="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          bg="white"
          isRequired
        />
      </FormControl>
      <FormControl mb={4}>
        <FormLabel>Password</FormLabel>
        <Input
          type="password"
          name="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          bg="white"
          isRequired
        />
      </FormControl>
      <Box>
        <Button
          isLoading={isLoading}
          variant="primary"
          size="md"
          onClick={() => {
            login();
          }}
        >
          Login
        </Button>
      </Box>
    </Box>
  );
};
