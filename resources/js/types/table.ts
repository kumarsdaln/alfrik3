export type TableAlign = 'left' | 'center' | 'right'

export interface TableColumn<T = any> {
    key: string
    label: string
    width?: string
    align?: TableAlign
    class?: string
    sortable?: boolean
}