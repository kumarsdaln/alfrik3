export const tableVariants = {
    default: `
        border
        border-neutral-200
        dark:border-neutral-800
    `,

    striped: `
        [&_tbody_tr:nth-child(even)]:bg-neutral-50
        dark:[&_tbody_tr:nth-child(even)]:bg-neutral-900/50
    `,
}