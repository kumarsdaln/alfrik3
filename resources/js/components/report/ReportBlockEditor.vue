<script setup lang="ts">
    import { computed } from 'vue'

    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'

    interface Props {
        type: string
        modelValue: Record<string, unknown>
    }

    const props = defineProps<Props>()

    const emit = defineEmits<{
        'update:modelValue': [
            value: Record<string, unknown>,
        ]
    }>()

    const content = computed({
        get: () => props.modelValue,

        set: (value) => {
            emit('update:modelValue', value)
        },
    })

    const update = (
        key: string,
        value: unknown,
    ) => {
        content.value = {
            ...content.value,
            [key]: value,
        }
    }

    const headingLevels = [
        {
            value: 2,
            label: 'Heading 2',
        },
        {
            value: 3,
            label: 'Heading 3',
        },
        {
            value: 4,
            label: 'Heading 4',
        },
    ]

    const chartTypes = [
        {
            value: 'bar',
            label: 'Bar Chart',
        },
        {
            value: 'line',
            label: 'Line Chart',
        },
        {
            value: 'pie',
            label: 'Pie Chart',
        },
        {
            value: 'doughnut',
            label: 'Doughnut Chart',
        },
    ]
</script>

<template>
    <!-- TEXT -->
    <div v-if="type === 'text'" class="space-y-5">
        <AppFormControl label="Text" required>
            <AppTextarea :model-value="String(content.text ?? '')" placeholder="Write your content..." rows="10"
                @update:model-value="
                    update('text', $event)
                    " />
        </AppFormControl>
    </div>

    <!-- HEADING -->
    <div v-else-if="type === 'heading'" class="space-y-5">
        <AppFormControl label="Heading" required>
            <AppInput :model-value="String(content.text ?? '')" placeholder="Enter heading" @update:model-value="
                update('text', $event)
                " />
        </AppFormControl>

        <AppFormControl label="Heading Level" required>
            <AppSelect :model-value="Number(content.level ?? 2)" :options="headingLevels" @update:model-value="
                update('level', $event)
                " />
        </AppFormControl>
    </div>

    <!-- QUOTE -->
    <div v-else-if="type === 'quote'" class="space-y-5">
        <AppFormControl label="Quote" required>
            <AppTextarea :model-value="String(content.quote ?? '')" placeholder="Enter quotation..." rows="6"
                @update:model-value="
                    update('quote', $event)
                    " />
        </AppFormControl>

        <AppFormControl label="Author">
            <AppInput :model-value="String(content.author ?? '')" placeholder="Author name" @update:model-value="
                update('author', $event)
                " />
        </AppFormControl>

        <AppFormControl label="Source">
            <AppInput :model-value="String(content.source ?? '')" placeholder="Source" @update:model-value="
                update('source', $event)
                " />
        </AppFormControl>
    </div>

    <!-- IMAGE -->
    <div v-else-if="type === 'image'" class="space-y-5">
        <AppFormControl label="Image URL" required>
            <AppInput :model-value="String(content.url ?? '')" placeholder="https://example.com/image.jpg"
                @update:model-value="
                    update('url', $event)
                    " />
        </AppFormControl>

        <AppFormControl label="Alt Text" required>
            <AppInput :model-value="String(content.alt ?? '')" placeholder="Describe the image" @update:model-value="
                update('alt', $event)
                " />
        </AppFormControl>

        <AppFormControl label="Caption">
            <AppInput :model-value="String(content.caption ?? '')" placeholder="Image caption" @update:model-value="
                update('caption', $event)
                " />
        </AppFormControl>
    </div>

    <!-- TABLE -->
    <div v-else-if="type === 'table'" class="space-y-5">
        <AppFormControl label="Columns" description="Enter column names separated by commas." required>
            <AppInput :model-value="Array.isArray(content.columns)
                    ? content.columns.join(', ')
                    : ''
                " placeholder="Year, Revenue, Growth" @update:model-value="
                    update(
                        'columns',
                        $event
                            .split(',')
                            .map((value: string) => value.trim())
                            .filter(Boolean),
                    )
                    " />
        </AppFormControl>

        <AppFormControl label="Rows" description="Enter each row on a new line. Separate values with commas." required>
            <AppTextarea :model-value="Array.isArray(content.rows)
                    ? content.rows
                        .map(
                            (row) =>
                                Array.isArray(row)
                                    ? row.join(', ')
                                    : '',
                        )
                        .join('\n')
                    : ''
                " placeholder="2024, ₹10 Cr, 12% 2025, ₹15 Cr, 20%" rows="8" @update:model-value="
    update(
        'rows',
        $event
            .split('\n')
            .map(
                (row: string) =>
                    row
                        .split(',')
                        .map(
                            (value) =>
                                value.trim(),
                        ),
            )
            .filter(
                (row) => row.length > 0,
            ),
    )
    " />
        </AppFormControl>
    </div>

    <!-- STATISTIC -->
    <div v-else-if="type === 'statistic'" class="space-y-5">
        <AppFormControl label="Value" required>
            <AppInput :model-value="String(content.value ?? '')" placeholder="72%" @update:model-value="
                update('value', $event)
                " />
        </AppFormControl>

        <AppFormControl label="Label" required>
            <AppInput :model-value="String(content.label ?? '')" placeholder="Consumers prefer sustainable products"
                @update:model-value="
                    update('label', $event)
                    " />
        </AppFormControl>

        <AppFormControl label="Source">
            <AppInput :model-value="String(content.source ?? '')" placeholder="Alfrik Research" @update:model-value="
                update('source', $event)
                " />
        </AppFormControl>
    </div>

    <!-- CHART -->
    <div v-else-if="type === 'chart'" class="space-y-5">
        <AppFormControl label="Chart Type" required>
            <AppSelect :model-value="String(
                content.chart_type ?? 'bar',
            )
                " :options="chartTypes" @update:model-value="
                    update('chart_type', $event)
                    " />
        </AppFormControl>

        <AppFormControl label="Labels" description="Enter labels separated by commas." required>
            <AppInput :model-value="Array.isArray(content.labels)
                    ? content.labels.join(', ')
                    : ''
                " placeholder="2023, 2024, 2025" @update:model-value="
                    update(
                        'labels',
                        $event
                            .split(',')
                            .map((value: string) => value.trim())
                            .filter(Boolean),
                    )
                    " />
        </AppFormControl>

        <AppFormControl label="Dataset Label" required>
            <AppInput :model-value="Array.isArray(content.datasets) &&
                    content.datasets[0]
                    ? String(
                        (
                            content.datasets[0] as Record<
                                string,
                                unknown
                            >
                        ).label ?? '',
                    )
                    : ''
                " placeholder="Revenue" @update:model-value="
                    update(
                        'datasets',
                        [
                            {
                                label: $event,
                                data:
                                    Array.isArray(
                                        content.datasets,
                                    ) &&
                                        content.datasets[0]
                                        ? (
                                            content
                                                .datasets[0] as Record<
                                                    string,
                                                    unknown
                                                >
                                        ).data ?? []
                                        : [],
                            },
                        ],
                    )
                    " />
        </AppFormControl>

        <AppFormControl label="Dataset Values" description="Enter numeric values separated by commas." required>
            <AppInput :model-value="Array.isArray(content.datasets) &&
                    content.datasets[0] &&
                    Array.isArray(
                        (
                            content.datasets[0] as Record<
                                string,
                                unknown
                            >
                        ).data,
                    )
                    ? (
                        (
                            content
                                .datasets[0] as Record<
                                    string,
                                    unknown
                                >
                        ).data as unknown[]
                    ).join(', ')
                    : ''
                " placeholder="10, 14, 19" @update:model-value="
                    update(
                        'datasets',
                        [
                            {
                                label:
                                    Array.isArray(
                                        content.datasets,
                                    ) &&
                                        content.datasets[0]
                                        ? String(
                                            (
                                                content
                                                    .datasets[0] as Record<
                                                        string,
                                                        unknown
                                                    >
                                            ).label ?? '',
                                        )
                                        : '',
                                data:
                                    $event
                                        .split(',')
                                        .map(
                                            (value: string) =>
                                                Number(
                                                    value.trim(),
                                                ),
                                        )
                                        .filter(
                                            (value) =>
                                                !Number.isNaN(
                                                    value,
                                                ),
                                        ),
                            },
                        ],
                    )
                    " />
        </AppFormControl>
    </div>

    <!-- FINDING -->
    <div v-else-if="type === 'finding'" class="space-y-5">
        <AppFormControl label="Finding" required>
            <AppTextarea :model-value="String(content.finding ?? '')
                " placeholder="Enter the key research finding..." rows="7" @update:model-value="
                    update('finding', $event)
                    " />
        </AppFormControl>

        <AppFormControl label="Evidence">
            <AppTextarea :model-value="String(content.evidence ?? '')
                " placeholder="Describe the evidence supporting this finding..." rows="6" @update:model-value="
                    update('evidence', $event)
                    " />
        </AppFormControl>
    </div>

    <!-- RECOMMENDATION -->
    <div v-else-if="type === 'recommendation'" class="space-y-5">
        <AppFormControl label="Recommendation" required>
            <AppTextarea :model-value="String(
                content.recommendation ?? '',
            )
                " placeholder="Enter the recommended action..." rows="7" @update:model-value="
                    update(
                        'recommendation',
                        $event,
                    )
                    " />
        </AppFormControl>

        <AppFormControl label="Rationale">
            <AppTextarea :model-value="String(content.rationale ?? '')
                " placeholder="Explain why this recommendation is appropriate..." rows="6" @update:model-value="
                    update('rationale', $event)
                    " />
        </AppFormControl>
    </div>

    <!-- FALLBACK -->
    <div v-else class="rounded-md border border-dashed p-6 text-center">
        <p class="text-sm font-medium">
            {{ type }}
        </p>

        <p class="mt-1 text-sm text-muted-foreground">
            No editor is available for this block type yet.
        </p>
    </div>
</template>