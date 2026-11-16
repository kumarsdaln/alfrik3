
export type SplitRatio =
  | 'equal'
  | '1-2'
  | '2-1'
  | '1-3'
  | '3-1'
  | '1-4'
  | '4-1'

export type SplitGap = 'none' | 'sm' | 'md' | 'lg' | 'xl' | '2xl'

export type SplitBreakpoint = 'sm' | 'md' | 'lg' | 'xl'

export type SplitDirection = 'row' | 'row-reverse'

export type SplitStack = 'always' | 'never' | SplitBreakpoint

export const splitRatioVariants: Record<SplitRatio, string> = {
  equal: 'grid-cols-2',
  '1-2': 'grid-cols-[1fr_2fr]',
  '2-1': 'grid-cols-[2fr_1fr]',
  '1-3': 'grid-cols-[1fr_3fr]',
  '3-1': 'grid-cols-[3fr_1fr]',
  '1-4': 'grid-cols-[1fr_4fr]',
  '4-1': 'grid-cols-[4fr_1fr]',
}

export const splitResponsiveRatioVariants: Record<
  SplitBreakpoint,
  Record<SplitRatio, string>
> = {
  sm: {
    equal: 'sm:grid-cols-2',
    '1-2': 'sm:grid-cols-[1fr_2fr]',
    '2-1': 'sm:grid-cols-[2fr_1fr]',
    '1-3': 'sm:grid-cols-[1fr_3fr]',
    '3-1': 'sm:grid-cols-[3fr_1fr]',
    '1-4': 'sm:grid-cols-[1fr_4fr]',
    '4-1': 'sm:grid-cols-[4fr_1fr]',
  },
  md: {
    equal: 'md:grid-cols-2',
    '1-2': 'md:grid-cols-[1fr_2fr]',
    '2-1': 'md:grid-cols-[2fr_1fr]',
    '1-3': 'md:grid-cols-[1fr_3fr]',
    '3-1': 'md:grid-cols-[3fr_1fr]',
    '1-4': 'md:grid-cols-[1fr_4fr]',
    '4-1': 'md:grid-cols-[4fr_1fr]',
  },
  lg: {
    equal: 'lg:grid-cols-2',
    '1-2': 'lg:grid-cols-[1fr_2fr]',
    '2-1': 'lg:grid-cols-[2fr_1fr]',
    '1-3': 'lg:grid-cols-[1fr_3fr]',
    '3-1': 'lg:grid-cols-[3fr_1fr]',
    '1-4': 'lg:grid-cols-[1fr_4fr]',
    '4-1': 'lg:grid-cols-[4fr_1fr]',
  },
  xl: {
    equal: 'xl:grid-cols-2',
    '1-2': 'xl:grid-cols-[1fr_2fr]',
    '2-1': 'xl:grid-cols-[2fr_1fr]',
    '1-3': 'xl:grid-cols-[1fr_3fr]',
    '3-1': 'xl:grid-cols-[3fr_1fr]',
    '1-4': 'xl:grid-cols-[1fr_4fr]',
    '4-1': 'xl:grid-cols-[4fr_1fr]',
  },
}

export const splitGapVariants: Record<SplitGap, string> = {
  none: 'gap-0',
  sm: 'gap-2',
  md: 'gap-4',
  lg: 'gap-6',
  xl: 'gap-8',
  '2xl': 'gap-12',
}

export const splitStackVariants: Record<SplitBreakpoint, string> = {
  sm: 'grid-cols-1 sm:grid-cols-2',
  md: 'grid-cols-1 md:grid-cols-2',
  lg: 'grid-cols-1 lg:grid-cols-2',
  xl: 'grid-cols-1 xl:grid-cols-2',
}
