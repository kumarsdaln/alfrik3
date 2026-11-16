
export type MasonryColumns = 1 | 2 | 3 | 4 | 5 | 6

export type MasonryGap = 'none' | 'xs' | 'sm' | 'md' | 'lg' | 'xl'

export type MasonryBreakpoint = 'sm' | 'md' | 'lg' | 'xl' | '2xl'

export type MasonryResponsiveColumns = Partial<
  Record<MasonryBreakpoint, MasonryColumns>
>

export const masonryColumnVariants: Record<MasonryColumns, string> = {
  1: 'columns-1',
  2: 'columns-2',
  3: 'columns-3',
  4: 'columns-4',
  5: 'columns-5',
  6: 'columns-6',
}

export const masonryResponsiveColumnVariants: Record<
  MasonryBreakpoint,
  Record<MasonryColumns, string>
> = {
  sm: {
    1: 'sm:columns-1',
    2: 'sm:columns-2',
    3: 'sm:columns-3',
    4: 'sm:columns-4',
    5: 'sm:columns-5',
    6: 'sm:columns-6',
  },
  md: {
    1: 'md:columns-1',
    2: 'md:columns-2',
    3: 'md:columns-3',
    4: 'md:columns-4',
    5: 'md:columns-5',
    6: 'md:columns-6',
  },
  lg: {
    1: 'lg:columns-1',
    2: 'lg:columns-2',
    3: 'lg:columns-3',
    4: 'lg:columns-4',
    5: 'lg:columns-5',
    6: 'lg:columns-6',
  },
  xl: {
    1: 'xl:columns-1',
    2: 'xl:columns-2',
    3: 'xl:columns-3',
    4: 'xl:columns-4',
    5: 'xl:columns-5',
    6: 'xl:columns-6',
  },
  '2xl': {
    1: '2xl:columns-1',
    2: '2xl:columns-2',
    3: '2xl:columns-3',
    4: '2xl:columns-4',
    5: '2xl:columns-5',
    6: '2xl:columns-6',
  },
}

export const masonryGapVariants: Record<MasonryGap, string> = {
  none: 'gap-0',
  xs: 'gap-1',
  sm: 'gap-2',
  md: 'gap-4',
  lg: 'gap-6',
  xl: 'gap-8',
}
