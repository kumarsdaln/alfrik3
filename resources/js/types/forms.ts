import type { Component } from 'vue'

export type FormPrimitiveValue = string | number | boolean

export type FormValue =
    | FormPrimitiveValue
    | null
    | undefined

export interface FormOption<TValue extends FormValue = FormValue> {
    value: TValue
    label: string
    disabled?: boolean
    color?: string
    icon?: Component
    [key: string]: unknown
}

export type FormOptionInput<TValue extends FormValue = FormValue> =
    | FormOption<TValue>
    | TValue

export type FormErrorBag = Record<string, string | undefined>

export interface GalleryUploadItem {
    id?: string | number | null
    file?: File | null
    preview: string
    description?: string
    isNew?: boolean
    [key: string]: unknown
}

export interface FilePreviewItem {
    file: File
    name: string
    url: string | null
}
