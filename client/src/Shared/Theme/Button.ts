import { ComponentStyleConfig } from "@chakra-ui/theme";

export const Button: ComponentStyleConfig = {
  baseStyle: {
    fontWeight: "bold",
  },
  variants: {
    primary: {
      bg: "brand.full",
      color: "white",
    },
    secondary: {
      bg: "brand.alt",
      color: "white",
    },
    outline: {
      border: "2px solid",
      color: "brand.full",
      borderColor: "brand.full",
    },
    nativeRed: {
      bg: "brand.red",
      color: "white",
    },
    nativeBlue: {
      bg: "brand.blue",
      color: "white",
    },
  },
  defaultProps: {
    size: "md",
    variant: "outline",
  },
};
