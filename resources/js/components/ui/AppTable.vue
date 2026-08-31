<script setup lang="ts">
    import {
        Table,
        TableHeader,
        TableBody,
        TableRow,
        TableHead,
        TableCell
    } from '@/components/ui/table'

    export interface AppTableColumn {
        key: string
        label: string
        align?: 'left' | 'center' | 'right'
        width?: string
        class?: string
    }

    const props = withDefaults(
        defineProps<{
            columns: AppTableColumn[]
            data: Record<string, any>[]
            rowKey?: string
            emptyText?: string
            loading?: boolean
        }>(),
        {
            rowKey: 'id',
            emptyText: 'No records found.',
            loading: false,
        },
    )

    const getValue = (
        row: Record<string, any>,
        key: string,
    ) => {
        return key
            .split('.')
            .reduce(
                (value, property) => value?.[property],
                row,
            )
    }

    const getRowKey = (
        row: Record<string, any>,
        index: number,
    ) => {
        return row[props.rowKey] ?? index
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