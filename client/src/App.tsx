import * as React from "react";
import { Provider } from "react-redux";
import { BrowserRouter as Router } from "react-router-dom";
import { Routes } from "./Routes";
import { ChakraProvider } from "@chakra-ui/react";

import { theme } from "./Shared";

import { store } from "./reducers/store";

export const App = () => (
  <Provider store={store}>
    <ChakraProvider theme={theme}>
      <Router>
        <Routes />
      </Router>
    </ChakraProvider>
  </Provider>
);
