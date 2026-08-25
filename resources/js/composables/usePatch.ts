import axios, { AxiosError } from 'axios'
import { toast } from 'vue-sonner'

/**
 * Fire a PATCH for a single field (status toggles, etc.) and surface the
 * server's JSON message as a toast.
 *
 * Call convention used across the app: `patch(url, key, value, onSuccess?)`.
 * The body is sent as `{ [key]: value }` so endpoints that read `$request->{key}`
 * (e.g. course status) work, while endpoints that just flip a flag ignore it.
 * The toast text comes from the JSON response (`message` or `success`).
 */
export function usePatch() {
  const patch = async (
    url: string,
    key: string,
    value: unknown,
    onSuccessCallback?: () => void,
  ): Promise<void> => {
    try {
      const csrfToken = document
        .querySelector<HTMLMetaElement>('meta[name="csrf-token"]')
        ?.getAttribute('content')

      const { data } = await axios.patch(
        url,
        { [key]: value },
        { headers: { 'X-CSRF-TOKEN': csrfToken ?? '' } },
      )

      toast.success(data?.message ?? data?.success ?? 'Updated successfully.')
      onSuccessCallback?.()
    } catch (err) {
      const error = err as AxiosError<{ message?: string; success?: string }>

      toast.error(
        error.response?.data?.message ??
        error.response?.data?.success ??
        error.message ??
        'Something went wrong.',
      )
    }
  }

  return { patch }
}
