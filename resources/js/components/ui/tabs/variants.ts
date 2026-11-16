
export type TabsOrientation = 'horizontal' | 'vertical'

export type TabsVariant = 'default' | 'underline' | 'pills' | 'bordered'

export type TabsSize = 'sm' | 'md' | 'lg'

export const tabsOrientationVariants: Record<TabsOrientation, string> = {
  horizontal: 'flex-row',
  vertical: 'flex-col',
}

export const tabsVariantVariants: Record<TabsVariant, string> = {
  default: 'bg-muted p-1 rounded-lg',
  underline: 'border-b border-border',
  pills: 'gap-1',
  bordered: 'border-b border-border',
}

export const tabsSizeVariants: Record<TabsSize, string> = {
  sm: 'px-3 py-1.5 text-xs',
  md: 'px-4 py-2 text-sm',
  lg: 'px-5 py-2.5 text-base',
}

export const tabsTriggerVariants: Record<TabsVariant, string> = {
  default:
    'rounded-md text-muted-foreground data-[state=active]:bg-card data-[state=active]:text-foreground data-[state=active]:shadow-sm',

  underline:
    'rounded-none border-b-2 border-transparent text-muted-foreground data-[state=active]:border-brand data-[state=active]:text-brand',

  pills:
    'rounded-full text-muted-foreground data-[state=active]:bg-brand-soft data-[state=active]:text-brand',

  bordered:
    'rounded-t-md border border-transparent text-muted-foreground data-[state=active]:border-border data-[state=active]:border-b-card data-[state=active]:bg-card data-[state=active]:text-foreground',
}
