import type { Component } from 'vue'

export type FilterPrimitive = string | number | boolean

export type FilterValue =
    | FilterPrimitive
    | null
    | undefined

export interface FilterOption<TValue extends FilterValue = FilterValue> {
    value: TValue
    label: string
    disabled?: boolean
    [key: string]: unknown
}

export interface FilterField<TValue extends FilterValue = FilterValue> {
    name?: string
    label?: string
    placeholder?: string
    allLabel?: string
    options?: FilterOption<TValue>[]
    min?: number
    max?: number
    step?: number
    unit?: string
    icon?: Component
    error?: string
    required?: boolean
    fullWidth?: boolean
    [key: string]: unknown
}

export type FilterModel =
    | FilterValue
    | FilterValue[]
