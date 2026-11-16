
export type FigureAlign = 'start' | 'center' | 'end'

export type FigureWidth = 'full' | 'wide' | 'medium' | 'narrow'

export type FigureSpacing = 'none' | 'sm' | 'md' | 'lg'

export type FigureCaptionAlign = 'start' | 'center' | 'end'

export const figureAlignVariants: Record<FigureAlign, string> = {
  start: 'mr-auto',
  center: 'mx-auto',
  end: 'ml-auto',
}

export const figureWidthVariants: Record<FigureWidth, string> = {
  full: 'w-full',
  wide: 'w-full max-w-5xl',
  medium: 'w-full max-w-3xl',
  narrow: 'w-full max-w-xl',
}

export const figureSpacingVariants: Record<FigureSpacing, string> = {
  none: 'mt-0',
  sm: 'mt-2',
  md: 'mt-3',
  lg: 'mt-5',
}

export const figureCaptionAlignVariants: Record<FigureCaptionAlign, string> = {
  start: 'text-left',
  center: 'text-center',
  end: 'text-right',
}
