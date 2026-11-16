
export type ListType = 'unordered' | 'ordered' | 'none'

export type ListMarker =
  | 'bullet'
  | 'number'
  | 'decimal'
  | 'lower-alpha'
  | 'upper-alpha'
  | 'lower-roman'
  | 'upper-roman'
  | 'disc'
  | 'circle'
  | 'square'

export type ListSize = 'sm' | 'md' | 'lg'

export type ListSpacing = 'none' | 'sm' | 'md' | 'lg'

export type ListColor =
  | 'default'
  | 'muted'
  | 'primary'
  | 'berry'
  | 'gold'

export const listMarkerVariants: Record<ListMarker, string> = {
  bullet: 'list-disc',
  number: 'list-decimal',
  decimal: 'list-decimal',
  'lower-alpha': 'list-[lower-alpha]',
  'upper-alpha': 'list-[upper-alpha]',
  'lower-roman': 'list-[lower-roman]',
  'upper-roman': 'list-[upper-roman]',
  disc: 'list-disc',
  circle: 'list-[circle]',
  square: 'list-[square]',
}

export const listSizeVariants: Record<ListSize, string> = {
  sm: 'text-xs leading-5',
  md: 'text-sm leading-6',
  lg: 'text-base leading-7',
}

export const listSpacingVariants: Record<ListSpacing, string> = {
  none: 'space-y-0',
  sm: 'space-y-1',
  md: 'space-y-2',
  lg: 'space-y-4',
}

export const listColorVariants: Record<ListColor, string> = {
  default: 'text-foreground',
  muted: 'text-muted-foreground',
  primary: 'text-primary',
  berry: 'text-brand',
  gold: 'text-gold',
}

export const listItemSizeVariants: Record<ListSize, string> = {
  sm: 'text-xs leading-5',
  md: 'text-sm leading-6',
  lg: 'text-base leading-7',
}
