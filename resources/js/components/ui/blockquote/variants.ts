
export type BlockquoteVariant =
  | 'default'
  | 'editorial'
  | 'bordered'
  | 'accent'

export type BlockquoteSize = 'sm' | 'md' | 'lg' | 'xl'

export type BlockquoteAlign = 'left' | 'center'

export type BlockquoteSpacing = 'none' | 'sm' | 'md' | 'lg'

export const blockquoteVariantClasses: Record<BlockquoteVariant, string> = {
  default: 'border-l-2 border-border pl-5',
  editorial: 'border-l-2 border-brand pl-6',
  bordered: 'rounded-lg border border-border bg-card p-6',
  accent: 'border-l-2 border-gold bg-gold-soft pl-6 pr-5 py-5',
}

export const blockquoteSizeClasses: Record<BlockquoteSize, string> = {
  sm: 'text-lg',
  md: 'text-xl',
  lg: 'text-2xl',
  xl: 'text-3xl',
}

export const blockquoteAlignClasses: Record<BlockquoteAlign, string> = {
  left: 'text-left',
  center: 'text-center',
}

export const blockquoteSpacingClasses: Record<BlockquoteSpacing, string> = {
  none: 'my-0',
  sm: 'my-3',
  md: 'my-6',
  lg: 'my-10',
}

export const attributionSizeClasses: Record<BlockquoteSize, string> = {
  sm: 'text-xs',
  md: 'text-sm',
  lg: 'text-sm',
  xl: 'text-base',
}
