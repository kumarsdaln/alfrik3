export type DateInput = string | number | Date | null | undefined

function toDate(value: DateInput): Date | null {
    if (value === null || value === undefined || value === '') {
        return null
    }

    const date = value instanceof Date ? value : new Date(value)

    return Number.isNaN(date.getTime()) ? null : date
}

export function formatDate(
    dateString: DateInput,
    options: Intl.DateTimeFormatOptions | null = null,
): string {
    const date = toDate(dateString)

    if (!date) {
        return 'Present'
    }

    const defaultOptions: Intl.DateTimeFormatOptions = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    }

    return date.toLocaleDateString(
        undefined,
        options ?? defaultOptions,
    )
}

export function formatDateTime(dateString: DateInput): string {
    const date = toDate(dateString)

    if (!date) {
        return ''
    }

    return date.toLocaleString(undefined, {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
    })
}

export function formatDateTimeLocal(dateString: DateInput): string {
    const date = toDate(dateString)

    if (!date) {
        return ''
    }

    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')
    const hours = String(date.getHours()).padStart(2, '0')
    const minutes = String(date.getMinutes()).padStart(2, '0')

    return `${year}-${month}-${day}T${hours}:${minutes}`
}

export function formatPeriod(
    start: DateInput,
    end: DateInput,
): string {
    const startDate = toDate(start)
    const endDate = toDate(end)

    if (!startDate) {
        return ''
    }

    const formattedStart = startDate.toLocaleDateString('en-US', {
        month: 'short',
        year: 'numeric',
    })

    const formattedEnd = endDate
        ? endDate.toLocaleDateString('en-US', {
              month: 'short',
              year: 'numeric',
          })
        : 'Present'

    return `${formattedStart} – ${formattedEnd}`
}

export function getDuration(
    start: DateInput,
    end: DateInput = new Date(),
): string {
    const startDate = toDate(start)
    const endDate = toDate(end) ?? new Date()

    if (!startDate) {
        return ''
    }

    const months =
        (endDate.getFullYear() - startDate.getFullYear()) * 12 +
        (endDate.getMonth() - startDate.getMonth())

    if (months < 12) {
        return `${months}mo`
    }

    const years = Math.floor(months / 12)
    const remainingMonths = months % 12

    return remainingMonths > 0
        ? `${years}y ${remainingMonths}mo`
        : `${years}y`
}