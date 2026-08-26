export type SponsorTier = 'platinum' | 'gold' | 'silver' | 'bronze' | 'partner'

export interface Sponsor {
    id: number
    name: string
    slug?: string
    tier: SponsorTier | string
    website_url?: string | null
    description?: string | null
    logo_url?: string | null
    sort_order?: number
    is_active?: boolean
    // Satisfies the table's TableRow shape so a Sponsor can be a table row.
    [key: string]: unknown
}
