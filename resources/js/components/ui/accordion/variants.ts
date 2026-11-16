
export type AccordionType = 'single' | 'multiple'

export type AccordionVariant = 'default' | 'bordered' | 'separated'

export type AccordionSize = 'sm' | 'md' | 'lg'

export const accordionVariantClasses: Record<AccordionVariant, string> = {
  default: 'divide-y divide-border',
  bordered: 'overflow-hidden rounded-lg border border-border divide-y divide-border',
  separated: 'space-y-3',
}

export const accordionItemVariants: Record<AccordionVariant, string> = {
  default: '',
  bordered: '',
  separated: 'overflow-hidden rounded-lg border border-border bg-card',
}

export const accordionTriggerSizeVariants: Record<AccordionSize, string> = {
  sm: 'py-3 text-sm',
  md: 'py-4 text-sm',
  lg: 'py-5 text-base',
}

export const accordionContentSizeVariants: Record<AccordionSize, string> = {
  sm: 'pb-3 text-xs leading-5',
  md: 'pb-4 text-sm leading-6',
  lg: 'pb-5 text-base leading-7',
}
