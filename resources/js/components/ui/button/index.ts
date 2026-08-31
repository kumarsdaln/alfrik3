import type { VariantProps } from "class-variance-authority"
import { cva } from "class-variance-authority"

export { default as Button } from "./Button.vue"

export const buttonVariants = cva(
  [
    "inline-flex items-center justify-center gap-2 cursor-pointer",
    "whitespace-nowrap",
    "font-redhat text-sm font-medium",
    "transition-colors duration-200",
    "select-none",
    "shrink-0",
    "outline-none",
    "disabled:pointer-events-none disabled:opacity-50",
    "focus-visible:outline-none",
    "focus-visible:ring-2 focus-visible:ring-neutral-900/15",
    "dark:focus-visible:ring-white/20",
    "[&_svg]:pointer-events-none",
    "[&_svg:not([class*='size-'])]:size-4",
    "[&_svg]:shrink-0",
  ].join(" "),
  {
    variants: {
      variant: {
        /*
        |--------------------------------------------------------------------------
        | Primary
        |--------------------------------------------------------------------------
        */

        primary: [
          "rounded-none",
          "border border-neutral-950",
          "bg-neutral-950",
          "text-white",
          "hover:bg-neutral-800",
          "hover:border-neutral-800",
        ].join(" "),

        /*
        |--------------------------------------------------------------------------
        | Default
        |--------------------------------------------------------------------------
        */

        default: [
          "rounded-none",
          "border border-neutral-950",
          "bg-neutral-950",
          "text-white",
          "hover:bg-neutral-800",
          "hover:border-neutral-800",
        ].join(" "),

        /*
        |--------------------------------------------------------------------------
        | Destructive
        |--------------------------------------------------------------------------
        */

        destructive: [
          "rounded-none",
          "border border-red-600",
          "bg-red-600",
          "text-white",
          "hover:bg-red-700",
          "hover:border-red-700",
        ].join(" "),

        /*
        |--------------------------------------------------------------------------
        | Outline
        |--------------------------------------------------------------------------
        */

        outline: [
          "rounded-none",
          "border border-neutral-900",
          "bg-transparent",
          "text-neutral-950",
          "hover:bg-neutral-950",
          "hover:text-white",
          "dark:border-white/70",
          "dark:text-white",
          "dark:hover:bg-white",
          "dark:hover:text-neutral-950",
        ].join(" "),

        /*
        |--------------------------------------------------------------------------
        | Secondary
        |--------------------------------------------------------------------------
        */

        secondary: [
          "rounded-none",
          "border border-neutral-200",
          "bg-neutral-100",
          "text-neutral-900",
          "hover:bg-neutral-200",
          "dark:border-neutral-700",
          "dark:bg-neutral-800",
          "dark:text-white",
          "dark:hover:bg-neutral-700",
        ].join(" "),

        /*
        |--------------------------------------------------------------------------
        | Ghost
        |--------------------------------------------------------------------------
        */

        ghost: [
          "rounded-none",
          "border border-transparent",
          "bg-transparent",
          "text-neutral-700",
          "hover:bg-neutral-100",
          "hover:text-neutral-950",
          "dark:text-neutral-300",
          "dark:hover:bg-neutral-800",
          "dark:hover:text-white",
        ].join(" "),

        /*
        |--------------------------------------------------------------------------
        | Link
        |--------------------------------------------------------------------------
        */

        link: [
          "rounded-none",
          "border-0",
          "bg-transparent",
          "px-0",
          "text-neutral-950",
          "underline-offset-4",
          "hover:underline",
          "dark:text-white",
        ].join(" "),
      },

      size: {
        default: [
          "h-10",
          "px-5",
          "text-[13px]",
        ].join(" "),

        sm: [
          "h-9",
          "px-4",
          "text-[12px]",
        ].join(" "),

        lg: [
          "h-11",
          "px-6",
          "text-[13px]",
        ].join(" "),

        icon: [
          "size-10",
        ].join(" "),

        "icon-sm": [
          "size-9",
        ].join(" "),

        "icon-lg": [
          "size-11",
        ].join(" "),
      },
    },

    defaultVariants: {
      variant: "primary",
      size: "default",
    },
  },
)

export type ButtonVariants = VariantProps<typeof buttonVariants>