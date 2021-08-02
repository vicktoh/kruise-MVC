import React, { FC } from "react";
import { Route, Switch } from "react-router-dom";
import { Login, Dashboard } from "../Pages";


export const Routes: FC = () => {
  return (
    <Switch>
      <Route exact path="/">
        <h1>Hi there</h1>
      </Route>
      <Route path="/login">
        <Login />
      </Route>
      <Route path="/dashboard">
        <Dashboard />
      </Route>
      <Route path="/data/:id">
        <h1>Hello there I'm a particular Data</h1>
      </Route>
    </Switch>
  );
};
