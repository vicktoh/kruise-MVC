import { extendTheme, Theme } from "@chakra-ui/react";
import { Button } from "./Button";

export const theme: Theme = extendTheme({
  colors: {
    brand: {
      full: "#845EC2",
      dark: "#7652AF",
      light: "#ECEFF9",
      alt: "#979BAB",
      text: "#807575",
      red: "#AD4444",
      blue: "#599AF2",
    },
  },
  components: {
    Button,
  },
});
