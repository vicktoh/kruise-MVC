import React, { FC, useEffect, useState } from "react";

import { Switch, Route, useHistory, useRouteMatch } from "react-router";
import { useDispatch } from "react-redux";

import { Spinner, Flex, Text, Box, Grid } from "@chakra-ui/react";
import { Home } from "./Home";
import { Categories } from "./Categories";
import { Places } from "./Places";
import { SideNav } from "../../Components/SideNav";

import { useAppSelector } from "../../reducers";
import { LOGIN_STATUS_URL } from "../../Constants";
import { put, fetchCategories } from "../../reducers";
import axios, { AxiosResponse } from "axios";

import { HTTPResponseType, Auth } from "../../Types/Auth";

export const Dashboard: FC = () => {
  const [isLoading, setLoading] = useState<boolean>(true);
  const history = useHistory();
  const dispatch = useDispatch();

  const { path } = useRouteMatch();
  const auth = useAppSelector(({ auth }) => auth);
  //check auth
  useEffect(() => {
    const checkLoginStatus = async () => {
      const { data: response } = await axios.post<
        HTTPResponseType<Auth>,
        AxiosResponse<HTTPResponseType<Auth>>
      >(LOGIN_STATUS_URL);
      if (!response.data) {
        history.push("/login");
        return;
      }
      setLoading(false);
      put(response.data);
    };

    checkLoginStatus();
  }, [history]);

  useEffect(()=>{
    if(auth) {
      dispatch(fetchCategories());
    }
  }, [auth, dispatch]);

  return isLoading && !auth?.email ? (
    <Flex
      width="100vw"
      height="100vh"
      direction="column"
      alignItems="center"
      justifyContent="center"
    >
      <Spinner size="lg" />
    </Flex>
  ) : (
    <Switch>
      <Box>
        <Grid gap={6} templateColumns="240px 1fr  auto">
          <SideNav />
          <Flex direction="column">
            <>
              <Route exact path={`${path}/`}>
                <Home />
              </Route>
              <Route exact path={`${path}/places`}>
                <Places />
              </Route>
              <Route exact path={`${path}/categories`}>
                <Categories />
              </Route>
              <Route exact path={`${path}/settings`}>
                <Text>Hello I am in the settings Page</Text>
              </Route>
            </>
          </Flex>
          <Flex direction="column" borderColor="brand.full">
            <Text>Hey Iam side nave</Text>
          </Flex>
        </Grid>
      </Box>
    </Switch>
  );
};
