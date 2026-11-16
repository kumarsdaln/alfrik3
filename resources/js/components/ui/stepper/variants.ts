
export type StepperOrientation = 'horizontal' | 'vertical'

export type StepperVariant = 'default' | 'outlined' | 'minimal'

export type StepperSize = 'sm' | 'md' | 'lg'

export type StepStatus = 'completed' | 'active' | 'upcoming'

export const stepperOrientationVariants: Record<
  StepperOrientation,
  string
> = {
  horizontal: 'flex-row',
  vertical: 'flex-col',
}

export const stepperVariantVariants: Record<
  StepperVariant,
  string
> = {
  default: '',
  outlined: 'rounded-lg border border-border p-4',
  minimal: '',
}

export const stepperSizeVariants: Record<StepperSize, {
  indicator: string
  text: string
  description: string
}> = {
  sm: {
    indicator: 'size-7 text-xs',
    text: 'text-xs',
    description: 'text-xs',
  },
  md: {
    indicator: 'size-9 text-sm',
    text: 'text-sm',
    description: 'text-xs',
  },
  lg: {
    indicator: 'size-11 text-base',
    text: 'text-base',
    description: 'text-sm',
  },
}

export const stepStatusVariants: Record<StepStatus, {
  indicator: string
  title: string
  description: string
}> = {
  completed: {
    indicator: 'border-primary bg-primary text-primary-foreground',
    title: 'text-foreground',
    description: 'text-muted-foreground',
  },
  active: {
    indicator: 'border-primary bg-brand-soft text-brand',
    title: 'font-semibold text-foreground',
    description: 'text-muted-foreground',
  },
  upcoming: {
    indicator: 'border-border bg-surface text-muted-foreground',
    title: 'text-muted-foreground',
    description: 'text-muted-foreground',
  },
}
