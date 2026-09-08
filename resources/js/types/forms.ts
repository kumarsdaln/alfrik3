import type { Component } from 'vue'

export interface Option<T = string> {
    value: T
    label: string
    color?: string
}

/*
|--------------------------------------------------------------------------
| Form Values
|--------------------------------------------------------------------------
|
| Generic form controls can work with strings, numbers and booleans.
|
*/

export type FormPrimitiveValue =
    | string
    | number
    | boolean

export type FormValue =
    | FormPrimitiveValue
    | null
    | undefined


/*
|--------------------------------------------------------------------------
| Select Values
|--------------------------------------------------------------------------
|
| Reka UI Select does not accept boolean values.
| Use this type for Select / Combobox options.
|
*/

export type FormSelectValue =
    | string
    | number

export interface FormOption<
    TValue extends FormSelectValue = FormSelectValue,
> {
    value: TValue
    label: string
    disabled?: boolean
    color?: string
    icon?: Component
    [key: string]: unknown
}

export type FormOptionInput<
    TValue extends FormSelectValue = FormSelectValue,
> =
    | FormOption<TValue>
    | TValue


/*
|--------------------------------------------------------------------------
| Errors
|--------------------------------------------------------------------------
*/

export type FormErrorBag =
    Record<string, string | undefined>


/*
|--------------------------------------------------------------------------
| Gallery Upload
|--------------------------------------------------------------------------
*/

export interface GalleryUploadItem {
    id?: string | number | null
    file?: File | null
    preview: string
    description?: string
    isNew?: boolean
    [key: string]: unknown
}


/*
|--------------------------------------------------------------------------
| File Preview
|--------------------------------------------------------------------------
*/

export interface FilePreviewItem {
    file: File
    name: string
    url: string | null
}