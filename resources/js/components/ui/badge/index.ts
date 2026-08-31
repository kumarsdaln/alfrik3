import type { VariantProps } from 'class-variance-authority'
import { cva } from 'class-variance-authority'

export { default as Badge } from './Badge.vue'

export const badgeVariants = cva(
    [
        'inline-flex items-center justify-center gap-1.5',
        'w-fit shrink-0 whitespace-nowrap',
        'border px-2.5 py-1',
        'text-[10px] font-semibold uppercase tracking-[0.12em]',
        'transition-colors',
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring/50',
        '[&>svg]:size-3 [&>svg]:shrink-0 [&>svg]:pointer-events-none',
    ].join(' '),
    {
        variants: {
            variant: {
                default: [
                    'border-primary/30',
                    'bg-primary/5',
                    'text-primary',
                    'hover:bg-primary/10',
                ].join(' '),

                secondary: [
                    'border-border',
                    'bg-secondary',
                    'text-secondary-foreground',
                    'hover:bg-secondary/80',
                ].join(' '),

                outline: [
                    'border-border',
                    'bg-transparent',
                    'text-muted-foreground',
                    'hover:border-primary',
                    'hover:text-primary',
                ].join(' '),

                destructive: [
                    'border-destructive/30',
                    'bg-destructive/5',
                    'text-destructive',
                    'hover:bg-destructive/10',
                ].join(' '),
            },
        },

        defaultVariants: {
            variant: 'default',
        },
    },
)

export type BadgeVariants = VariantProps<typeof badgeVariants>