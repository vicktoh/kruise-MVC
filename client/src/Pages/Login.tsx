import React, { FC } from "react";

import { Flex } from "@chakra-ui/react";
import { LoginForm } from "../Components/LoginForm";

// type User = {
//   name: string;
//   password: string;
//   email: string;
// };

// const registerAdminPath = `${BASE_URL}/Login/registerAdmin`;
export const Login: FC<{}> = () => {
  // useEffect(() => {
  //   const newUser: User = {
  //     name: "Adekunle Adelowo",
  //     password: "love4lovelace",
  //     email: "kunle@procurementmonitor.org",
  //   };
  //   const resgisterNewUser = async () => {
  //     const result = await axios.post(registerAdminPath, newUser);
  //     console.log(result);
  //   };
  //   resgisterNewUser();
  // });
  return (
    <Flex
      direction="column"
      alignItems="center"
      justifyContent="center"
      height="100vh"
      width="100vw"
    >
      <LoginForm />
    </Flex>
  );
};
