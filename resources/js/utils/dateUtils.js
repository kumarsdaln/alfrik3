export function formatDate(dateString, options = null) {
    const defaultOptions = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    };
    if (!dateString) return 'Present'
    const date = new Date(dateString);
    return date.toLocaleDateString(
        undefined,
        options ?? defaultOptions
    );
}

export function formatDateTime(dateString) {
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    };
    return new Date(dateString).toLocaleString(undefined, options);
}

export function formatPeriod(start, end) {
        const fmt = (d) => d ? new Date(d).toLocaleString('en-US', { month: 'short', year: 'numeric' }) : null
        const s = fmt(start)
        const e = fmt(end) || 'Present'
        return s ? `${s} – ${e}` : ''
}
export function getDuration(start, end) {
        const s = new Date(start)
        const e = end ? new Date(end) : new Date()
        const months = (e.getFullYear() - s.getFullYear()) * 12 + (e.getMonth() - s.getMonth())
        if (months < 12) return `${months}mo`
        const y = Math.floor(months / 12)
        const m = months % 12
        return m > 0 ? `${y}y ${m}mo` : `${y}y`
}