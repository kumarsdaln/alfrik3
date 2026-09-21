/**
 * Research module — academic-style research papers & studies.
 */

import { FormOption } from './forms'
import { Profile } from './user'

/* =========================================================
 * ENUMS
 * ========================================================= */

export enum ResearchType {
    Industry = 'industry',
    Market = 'market',
    Consumer = 'consumer',
    Trend = 'trend',
    Brand = 'brand',
    Academic = 'academic',
    DataAnalysis = 'data_analysis',
    General = 'general',
}

export enum ResearchStatus {
    Draft = 'draft',
    Published = 'published',
    Archived = 'archived',
}

export enum ResearchSourceType {
    Government = 'government',
    Academic = 'academic',
    Industry = 'industry',
    Organization = 'organization',
    Survey = 'survey',
    Interview = 'interview',
    Dataset = 'dataset',
    Website = 'website',
    Report = 'report',
    Book = 'book',
    Article = 'article',
    Other = 'other',
}

export enum ResearchQuestionType {
    Primary = 'primary',
    Secondary = 'secondary',
    Hypothesis = 'hypothesis',
}

export enum ResearchFindingType {
    Insight = 'insight',
    Result = 'result',
    Observation = 'observation',
    Conclusion = 'conclusion',
    Recommendation = 'recommendation',
}

export enum ResearchEvidenceType {
    Source = 'source',
    Survey = 'survey',
    SurveyQuestion = 'survey_question',
    ResearchQuestion = 'research_question',
    Finding = 'finding',
    Observation = 'observation',
    Dataset = 'dataset',
}

export enum ResearchMemberRole {
    Owner = 'owner',
    LeadResearcher = 'lead_researcher',
    Researcher = 'researcher',
    Contributor = 'contributor',
    Reviewer = 'reviewer',
    Editor = 'editor',
}

export type ResearchInvitationStatus =
    | 'pending'
    | 'accepted'
    | 'expired'

/* =========================================================
 * OPTION TYPES
 * ========================================================= */

export type ResearchTypeOption = FormOption<ResearchType>

export type ResearchStatusOption = FormOption<ResearchStatus>

export type ResearchSourceTypeOption =
    FormOption<ResearchSourceType>

export type ResearchQuestionTypeOption =
    FormOption<ResearchQuestionType>

export type ResearchFindingTypeOption =
    FormOption<ResearchFindingType>

export type ResearchEvidenceTypeOption =
    FormOption<ResearchEvidenceType>

export type ResearchMemberRoleOption =
    FormOption<ResearchMemberRole>

/* =========================================================
 * RESEARCH
 * ========================================================= */

export interface Research {
    id: number
    title: string
    slug: string
    subtitle: string | null
    description: string | null
    summary: string | null

    type: ResearchTypeOption
    status: ResearchStatusOption

    author?: Profile

    featured: boolean
    published_at: string | null

    created_at: string
    updated_at: string
}

/* =========================================================
 * RESEARCH METHODOLOGY
 * ========================================================= */

export interface ResearchMethodology {
    id: number
    research_id: number

    method: string
    description: string | null
    research_design: string | null
    data_collection_method: string | null

    sample_size: number | null
    population: string | null
    geography: string | null

    start_date: string | null
    end_date: string | null

    limitations: string | null

    created_at: string
    updated_at: string
}

/* =========================================================
 * RESEARCH SOURCE
 * ========================================================= */

export interface ResearchSource {
    id: number
    research_id: number

    title: string
    source_type: ResearchSourceTypeOption

    author: string | null
    publisher: string | null
    url: string | null

    published_at: string | null

    citation: string | null
    description: string | null

    position: number

    created_at: string
    updated_at: string
}

/* =========================================================
 * RESEARCH QUESTION
 * ========================================================= */

export interface ResearchQuestion {
    id: number
    research_id: number

    question: string
    description: string | null

    type: ResearchQuestionTypeOption

    position: number

    created_at: string
    updated_at: string
}

/* =========================================================
 * RESEARCH FINDING
 * ========================================================= */

export interface ResearchFinding {
    id: number
    research_id: number

    title: string
    summary: string | null
    description: string | null

    type: ResearchFindingTypeOption

    confidence: number | null
    position: number

    created_at: string
    updated_at: string
}

/* =========================================================
 * RESEARCH EVIDENCE
 * ========================================================= */

export interface ResearchEvidence {
    id: number
    research_id: number
    finding_id: number

    type: ResearchEvidenceTypeOption

    reference_id: number | null

    title: string | null
    description: string | null
    citation: string | null

    position: number

    created_at: string
    updated_at: string
}

/* =========================================================
 * RESEARCH MEMBER
 * ========================================================= */

export interface ResearchMember {
    id: number

    research_id: number
    user_id: number

    user?: Profile

    role: ResearchMemberRoleOption

    joined_at: string | null

    created_at: string
    updated_at: string
}

/* =========================================================
 * RESEARCH INVITATION
 * ========================================================= */

export interface ResearchInvitation {
    id: number

    research_id: number
    invited_by: number

    user_id: number | null
    user?: Profile

    email: string | null

    role: ResearchMemberRoleOption
    status: ResearchInvitationStatus

    expires_at: string
    accepted_at: string | null

    created_at: string
    updated_at: string
}

export interface ResearchEvidence {
    id: number
    research_id: number
    finding_id: number

    type: ResearchEvidenceTypeOption

    reference_id: number | null

    title: string | null
    description: string | null
    citation: string | null

    position: number

    created_at: string
    updated_at: string
}

export interface ResearchMember {
    id: number

    research_id: number
    user_id: number

    user?: Profile

    role: ResearchMemberRoleOption

    joined_at: string | null

    created_at: string
    updated_at: string
}
