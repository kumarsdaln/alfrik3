import type { Component } from 'vue'
import type { FormDataConvertible } from '@inertiajs/core'

// Core
export type RowId = number

// Common
export type Align =
    | 'left'
    | 'center'
    | 'right'

export type Sticky =
    | 'left'
    | 'right'

export type SortDirection =
    | 'asc'
    | 'desc'

// Row
export interface TableRow {
    id: RowId
    [key: string]: unknown
}

// Header
export interface TableHeader {
    key: string
    label: string
    sortable?: boolean
    width?: string
    align?: Align
    sticky?: Sticky
    class?: string
}

// Column
export type TableColumnType =
    | 'text'
    | 'number'
    | 'currency'
    | 'image'
    | 'badge'
    | 'boolean'
    | 'status'
    | 'email'
    | 'date'
    | 'datetime'
    | 'html'
    | 'custom'

export interface TableColumn {
    key: string
    slot?: string
    type?: TableColumnType
    sortable?: boolean
    searchable?: boolean
    hidden?: boolean
    width?: string
    align?: Align
    class?: string
}

// Bulk Actions
export interface BulkAction {
    key: string
    label: string
    icon?: Component
    danger?: boolean
    disabled?: boolean
}

// Sort
export interface SortState {
    key: string
    direction: SortDirection
}

// Pagination
export interface PaginationState {
    total: number
    currentPage: number
    lastPage: number
    prevPageUrl?: string | null
    nextPageUrl?: string | null
}

// Filters
export type TableFilterValue =
    FormDataConvertible

export interface TableFilters {
    [key: string]: TableFilterValue
}