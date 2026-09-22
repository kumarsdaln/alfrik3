/**
 * Reports module.
 *
 * Reports are structured research/insight publications.
 */

import type { FormOption } from '@/types'

/* -------------------------------------------------------------------------- */
/* Enums                                                                     */
/* -------------------------------------------------------------------------- */

export enum ReportType {
    Research = 'research',
    Industry = 'industry',
    Market = 'market',
    Consumer = 'consumer',
    Trend = 'trend',
    Analysis = 'analysis',
    Whitepaper = 'whitepaper',
    Annual = 'annual',
    Survey = 'survey',
    General = 'general',
}

export enum ReportStatus {
    Draft = 'draft',
    Published = 'published',
    Archived = 'archived',
}

export enum ReportContentBlockType {
    Text = 'text',
    Heading = 'heading',
    Quote = 'quote',
    Image = 'image',
    Table = 'table',
    Statistic = 'statistic',
    Chart = 'chart',
    Finding = 'finding',
    Recommendation = 'recommendation',
}

/* -------------------------------------------------------------------------- */
/* Supporting Types                                                          */
/* -------------------------------------------------------------------------- */

export interface ReportResearch {
    id: number
    title: string
}

export interface ReportAuthor {
    id: number
    name: string
}

export type ReportTypeOption = FormOption<ReportType>

export type ReportStatusOption = FormOption<ReportStatus>

export type ReportContentBlockTypeOption =
    FormOption<ReportContentBlockType>

/* -------------------------------------------------------------------------- */
/* Content Block                                                              */
/* -------------------------------------------------------------------------- */

export type ReportContentValue = Record<
    string,
    unknown
>

export interface ReportContentBlock {
    id: number
    report_section_id: number

    type: ReportContentBlockTypeOption

    title: string | null

    description: string | null

    content: ReportContentValue | null

    position: number

    created_at: string
    updated_at: string
}

/* -------------------------------------------------------------------------- */
/* Section                                                                    */
/* -------------------------------------------------------------------------- */

export interface ReportSection {
    id: number

    report_id: number

    title: string

    subtitle: string | null

    content: string | null

    position: number

    content_blocks?: ReportContentBlock[]

    created_at: string

    updated_at: string
}

/* -------------------------------------------------------------------------- */
/* Report                                                                     */
/* -------------------------------------------------------------------------- */

export interface Report {
    id: number

    research_id: number | null

    research?: ReportResearch | null

    title: string

    slug: string

    subtitle: string | null

    description: string | null

    summary: string | null

    type: ReportTypeOption

    status: ReportStatusOption

    author_id: number | null

    author?: ReportAuthor | null

    featured: boolean

    published_at: string | null

    report_date: string | null

    sections?: ReportSection[]

    created_at: string

    updated_at: string
}

/* -------------------------------------------------------------------------- */
/* Filters                                                                    */
/* -------------------------------------------------------------------------- */

export interface ReportFilters {
    search?: string | null

    status?: ReportStatus | null

    type?: ReportType | null
}