<script setup lang="ts" generic="T extends Record<string, any>">
    import {
        Table,
        TableHeader,
        TableBody,
        TableRow,
        TableHead,
        TableCell,
    } from '@/components/ui/table'

    export interface AppTableColumn {
        key: string
        label: string
        align?: 'left' | 'center' | 'right'
        width?: string
        class?: string
    }

    interface Props {
        columns: AppTableColumn[]
        data: T[]
        rowKey?: keyof T | string
        emptyText?: string
        loading?: boolean
    }

    const props = withDefaults(defineProps<Props>(), {
        rowKey: 'id',
        emptyText: 'No records found.',
        loading: false,
    })

    const getValue = (
        row: T,
        key: string,
    ): unknown => {
        return key
            .split('.')
            .reduce<unknown>((value, property) => {
                if (
                    value !== null &&
                    typeof value === 'object'
                ) {
                    return (value as Record<string, unknown>)[property]
                }

                return undefined
            }, row)
    }

    const getRowKey = (
        row: T,
        index: number,
    ) => {
        const key = props.rowKey as string

        if (
            row !== null &&
            typeof row === 'object' &&
            key in row
        ) {
            return (row as Record<string, unknown>)[key] ?? index
        }

        return index
    }
</script>

<template>
    <div class="w-full overflow-x-auto">
        <Table>
            <!-- Header -->
            <TableHeader>
                <TableRow>
                    <TableHead v-for="column in columns" :key="column.key" :class="[
                        column.class,
                        {
                            'text-center':
                                column.align === 'center',

                            'text-right':
                                column.align === 'right',
                        },
                    ]" :style="{
                            width: column.width,
                        }">
                        <slot :name="`header-${column.key}`" :column="column">
                            {{ column.label }}
                        </slot>
                    </TableHead>
                </TableRow>
            </TableHeader>

            <!-- Body -->
            <TableBody>
                <!-- Loading -->
                <TableRow v-if="loading">
                    <TableCell :colspan="columns.length" class="py-16 text-center">
                        <slot name="loading">
                            <div class="
                                    font-redhat
                                    text-xs
                                    uppercase
                                    tracking-wide
                                    text-content-light-muted
                                    dark:text-content-dark-muted
                                ">
                                Loading...
                            </div>
                        </slot>
                    </TableCell>
                </TableRow>

                <!-- Rows -->
                <template v-else-if="data.length">
                    <TableRow v-for="(row, index) in data" :key="getRowKey(row, index)">
                        <TableCell v-for="column in columns" :key="column.key" :class="[
                            column.class,
                            {
                                'text-center':
                                    column.align === 'center',

                                'text-right':
                                    column.align === 'right',
                            },
                        ]">
                            <slot :name="`cell-${column.key}`" :row="row" :value="getValue(row, column.key)"
                                :column="column" :index="index">
                                {{ getValue(row, column.key) ?? '—' }}
                            </slot>
                        </TableCell>
                    </TableRow>
                </template>

                <!-- Empty -->
                <TableRow v-else>
                    <TableCell :colspan="columns.length" class="py-16 text-center">
                        <slot name="empty">
                            <span class="
                                    font-lora
                                    text-sm
                                    italic
                                    text-content-light-muted
                                    dark:text-content-dark-muted
                                ">
                                {{ emptyText }}
                            </span>
                        </slot>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>