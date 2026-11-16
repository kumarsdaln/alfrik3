
export type DescriptionListLayout = 'vertical' | 'horizontal'

export type DescriptionListGap = 'none' | 'sm' | 'md' | 'lg'

export type DescriptionListSize = 'sm' | 'md' | 'lg'

export type DescriptionListBreakpoint = 'sm' | 'md' | 'lg'

export type DescriptionListResponsive = Partial<
  Record<DescriptionListBreakpoint, DescriptionListLayout>
>

export const descriptionListLayoutVariants: Record<
  DescriptionListLayout,
  string
> = {
  vertical: 'grid-cols-1',
  horizontal: 'grid-cols-[minmax(7rem,0.35fr)_minmax(0,1fr)]',
}

export const descriptionListResponsiveVariants: Record<
  DescriptionListBreakpoint,
  Record<DescriptionListLayout, string>
> = {
  sm: {
    vertical: 'sm:grid-cols-1',
    horizontal: 'sm:grid-cols-[minmax(7rem,0.35fr)_minmax(0,1fr)]',
  },
  md: {
    vertical: 'md:grid-cols-1',
    horizontal: 'md:grid-cols-[minmax(7rem,0.35fr)_minmax(0,1fr)]',
  },
  lg: {
    vertical: 'lg:grid-cols-1',
    horizontal: 'lg:grid-cols-[minmax(7rem,0.35fr)_minmax(0,1fr)]',
  },
}

export const descriptionListGapVariants: Record<
  DescriptionListGap,
  string
> = {
  none: 'gap-0',
  sm: 'gap-y-2',
  md: 'gap-y-4',
  lg: 'gap-y-6',
}

export const descriptionListSizeVariants: Record<
  DescriptionListSize,
  string
> = {
  sm: 'text-xs leading-5',
  md: 'text-sm leading-6',
  lg: 'text-base leading-7',
}

export const descriptionTermSizeVariants: Record<
  DescriptionListSize,
  string
> = {
  sm: 'text-xs',
  md: 'text-sm',
  lg: 'text-sm',
}

export const descriptionListTermSizeVariants: Record<
  DescriptionListSize,
  string
> = {
  sm: 'text-xs',
  md: 'text-sm',
  lg: 'text-sm',
}