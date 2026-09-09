<script setup lang="ts" generic="T">
    import {
        Table,
        TableHeader,
        TableBody,
        TableRow,
        TableHead,
        TableCell,
    } from '@/components/ui/table'

    import type { TableColumn } from '@/types/table'

    interface Props {
        columns: TableColumn<T>[]
        data: T[]
        rowKey?: keyof T | string
        emptyText?: string
        loading?: boolean
    }

    const props = withDefaults(
        defineProps<Props>(),
        {
            rowKey: 'id',
            emptyText: 'No records found.',
            loading: false,
        },
    )

    /**
     * Resolve a value from an object using dot notation.
     *
     * Examples:
     *
     * getValue(row, 'name')
     * getValue(row, 'createdBy.name')
     */
    const getValue = (
        row: T,
        key: string,
    ): any => {
        return key
            .split('.')
            .reduce(
                (value, property) => {
                    if (
                        value !== null &&
                        typeof value === 'object'
                    ) {
                        return value[property]
                    }

                    return undefined
                },
                row as any,
            )
    }

    /**
     * Get the unique key for a row.
     */
    const getRowKey = (
        row: T,
        index: number,
    ): string | number => {
        const key = String(props.rowKey)

        const value = getValue(row, key)

        if (
            typeof value === 'string' ||
            typeof value === 'number'
        ) {
            return value
        }

        return index
    }

    /**
     * Generate alignment class.
     */
    const getAlignClass = (
        column: TableColumn<T>,
    ): string | undefined => {
        switch (column.align) {
            case 'center':
                return 'text-center'

            case 'right':
                return 'text-right'

            default:
                return undefined
        }
    }
</script>

<template>
    <div class="w-full overflow-x-auto">
        <Table>
            <!-- Header -->
            <TableHeader>
                <TableRow>
                    <TableHead v-for="column in props.columns" :key="column.key" :class="[
                        column.class,
                        getAlignClass(column),
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
                <TableRow v-if="props.loading">
                    <TableCell :colspan="props.columns.length" class="py-16 text-center">
                        <slot name="loading">
                            <span class="
                                    font-redhat
                                    text-xs
                                    uppercase
                                    tracking-wide
                                    text-content-light-muted
                                    dark:text-content-dark-muted
                                ">
                                Loading...
                            </span>
                        </slot>
                    </TableCell>
                </TableRow>

                <!-- Data -->
                <template v-else-if="props.data.length > 0">
                    <TableRow v-for="(row, index) in props.data" :key="getRowKey(row, index)">
                        <TableCell v-for="column in props.columns" 
                            :key="column.key" 
                            :class="[
                                column.class,
                                getAlignClass(column),
                            ]">
                            <slot :name="`cell-${column.key}`" 
                                  :row="row" 
                                  :value="getValue(row,column.key,)" 
                                  :column="column" 
                                  :index="index">
                                {{getValue(row,column.key,) ?? '—'}}
                            </slot>
                        </TableCell>
                    </TableRow>
                </template>

                <!-- Empty -->
                <TableRow v-else>
                    <TableCell :colspan="props.columns.length" class="py-16 text-center">
                        <slot name="empty">
                            <span class="
                                    font-lora
                                    text-sm
                                    italic
                                    text-content-light-muted
                                    dark:text-content-dark-muted
                                ">
                                {{ props.emptyText }}
                            </span>
                        </slot>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>
