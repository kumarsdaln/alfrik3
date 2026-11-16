
export type ProgressSize = 'sm' | 'md' | 'lg'

export type ProgressVariant =
  | 'default'
  | 'success'
  | 'warning'
  | 'danger'
  | 'gold'

export const progressSizeVariants: Record<ProgressSize, string> = {
  sm: 'h-1',
  md: 'h-2',
  lg: 'h-3',
}

export const progressVariantVariants: Record<ProgressVariant, string> = {
  default: 'bg-primary',
  success: 'bg-emerald-600',
  warning: 'bg-amber-500',
  danger: 'bg-red-600',
  gold: 'bg-gold',
}
