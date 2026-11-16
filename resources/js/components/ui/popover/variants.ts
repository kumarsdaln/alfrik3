import type { InjectionKey, Ref } from 'vue'

export type PopoverSide = 'top' | 'bottom' | 'left' | 'right'

export type PopoverAlign = 'start' | 'center' | 'end'

export type PopoverSize = 'sm' | 'md' | 'lg'

export interface PopoverContext {
  open: Ref<boolean>
  contentId: string
  toggle: () => void
  close: () => void
}

export const popoverContextKey: InjectionKey<PopoverContext> =
  Symbol('popover-context')


export const popoverSideVariants: Record<PopoverSide, string> = {
  top: 'bottom-full',
  bottom: 'top-full',
  left: 'right-full',
  right: 'left-full',
}

export const popoverAlignVariants: Record<
  PopoverSide,
  Record<PopoverAlign, string>
> = {
  top: {
    start: 'left-0',
    center: 'left-1/2 -translate-x-1/2',
    end: 'right-0',
  },
  bottom: {
    start: 'left-0',
    center: 'left-1/2 -translate-x-1/2',
    end: 'right-0',
  },
  left: {
    start: 'top-0',
    center: 'top-1/2 -translate-y-1/2',
    end: 'bottom-0',
  },
  right: {
    start: 'top-0',
    center: 'top-1/2 -translate-y-1/2',
    end: 'bottom-0',
  },
}

export const popoverSizeVariants: Record<PopoverSize, string> = {
  sm: 'w-56 p-3',
  md: 'w-72 p-4',
  lg: 'w-96 p-5',
}
