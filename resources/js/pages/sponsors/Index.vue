<script setup lang="ts">
    import { computed } from 'vue'
    import { Head, useForm } from '@inertiajs/vue3'
    import { Handshake, ExternalLink, Send } from '@lucide/vue'
    import type { Sponsor } from '@/types/sponsor'

    const props = withDefaults(defineProps<{
        sponsors?: Sponsor[]
        tiers?: string[]
    }>(), {
        sponsors: () => [],
        tiers: () => [],
    })

    const label = (t: string) => t.charAt(0).toUpperCase() + t.slice(1)

    // Group active sponsors by tier, preserving the tier order from the server.
    const grouped = computed(() =>
        props.tiers
            .map((tier) => ({ tier, items: props.sponsors.filter((s) => s.tier === tier) }))
            .filter((g) => g.items.length),
    )

    const scrollTo = (id: string) => document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' })

    // "Become a sponsor" application → lands in the Leads CRM.
    const form = useForm({
        name: '',
        organization: '',
        email: '',
        phone: '',
        tier: '',
        message: '',
    })

    const submitApplication = () => {
        form.post(route('sponsors.apply'), {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        })
    }
</script>

<template>

    <Head title="Our Sponsors & Partners | Alfrik">
        <meta name="description" content="The organisations and partners that support Alfrik." />
    </Head>
    <!-- HERO -->
    <section class="relative overflow-hidden bg-white dark:bg-black">
        <div
            class="pointer-events-none absolute -top-40 left-1/2 -z-0 h-[34rem] w-[34rem] -translate-x-1/2 rounded-full bg-primary/10 opacity-60 blur-3xl" />
        <div class="container relative mx-auto px-4 pb-10 pt-16 text-center sm:pt-24">
            <span
                class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/5 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.16em] text-primary">
                <Handshake class="h-3.5 w-3.5" /> Sponsors &amp; Partners
            </span>
            <h1 class="mt-6 font-prata text-4xl leading-[1.1] text-zinc-900 dark:text-white sm:text-6xl">
                The partners behind Alfrik
            </h1>
            <p class="mx-auto mt-6 max-w-2xl font-lora text-lg leading-relaxed text-zinc-600 dark:text-zinc-300">
                We're proud to work alongside the organisations that support our programmes, events and community.
            </p>
            <div class="mt-8 flex justify-center">
                <button
                    class="inline-flex items-center gap-2 rounded-full bg-primary px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-primary/20 transition hover:bg-primary/90"
                    @click="scrollTo('become')">
                    <Handshake class="h-4 w-4" /> Become a sponsor
                </button>
            </div>
        </div>
    </section>

    <!-- TIERS -->
    <section class="bg-zinc-50/60 py-16 dark:bg-zinc-950/40 lg:py-24">
        <div class="container mx-auto px-4">
            <div v-if="grouped.length" class="space-y-16">
                <div v-for="group in grouped" :key="group.tier">
                    <div class="mb-8 flex items-center gap-4">
                        <h2 class="font-prata text-2xl text-zinc-900 dark:text-white sm:text-3xl">{{ label(group.tier)
                            }}</h2>
                        <span class="h-px flex-1 bg-black/10 dark:bg-white/10" />
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <component :is="s.website_url ? 'a' : 'div'" v-for="s in group.items" :key="s.id"
                            :href="s.website_url || undefined" :target="s.website_url ? '_blank' : undefined"
                            :rel="s.website_url ? 'noopener' : undefined"
                            class="group relative flex flex-col rounded-3xl border border-black/5 bg-white p-6 transition-all duration-300 hover:-translate-y-1 hover:border-primary/30 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.12)] dark:border-white/10 dark:bg-zinc-900">
                            <div
                                class="flex h-24 items-center justify-center rounded-2xl bg-zinc-50 p-4 dark:bg-white/[0.03]">
                                <img v-if="s.logo_url" :src="s.logo_url" :alt="s.name"
                                    class="max-h-full max-w-full object-contain" />
                                <span v-else class="font-prata text-3xl text-zinc-300">{{ s.name?.charAt(0) }}</span>
                            </div>
                            <div class="mt-5 flex items-center justify-between gap-2">
                                <h3 class="font-prata text-lg text-zinc-900 dark:text-white">{{ s.name }}</h3>
                                <ExternalLink v-if="s.website_url"
                                    class="h-4 w-4 shrink-0 text-zinc-300 transition group-hover:text-primary" />
                            </div>
                            <p v-if="s.description"
                                class="mt-2 font-lora text-sm leading-relaxed text-zinc-600 line-clamp-3 dark:text-zinc-300">
                                {{ s.description }}
                            </p>
                        </component>
                    </div>
                </div>
            </div>

            <!-- EMPTY -->
            <div v-else
                class="mx-auto max-w-md rounded-3xl border border-dashed border-black/10 bg-white py-16 text-center dark:border-white/10 dark:bg-zinc-900">
                <Handshake class="mx-auto h-8 w-8 text-zinc-300" />
                <p class="mt-4 font-lora text-zinc-500 dark:text-zinc-400">Our sponsors will be announced here soon.</p>
            </div>
        </div>
    </section>

    <!-- ===== BECOME A SPONSOR ===== -->
    <section id="become" class="py-16 lg:py-24">
        <div class="container mx-auto px-4">
            <div class="grid items-start gap-10 lg:grid-cols-2 lg:gap-16">
                <!-- pitch -->
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-primary">Partner with us</p>
                    <h2 class="mt-2 font-prata text-3xl text-zinc-900 dark:text-white sm:text-4xl">How to become a
                        sponsor</h2>
                    <p class="mt-4 font-lora text-zinc-600 dark:text-zinc-300">
                        Sponsoring Alfrik puts your primary in front of an engaged community of founders, creators
                        and technology leaders across our events, awards and content. Choose a tier that fits your
                        goals — we'll tailor the benefits with you.
                    </p>

                    <ul class="mt-8 space-y-4">
                        <li v-for="(t, i) in tiers" :key="t" class="flex items-center gap-4">
                            <span
                                class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-primary/10 font-redhat text-sm font-bold text-primary">{{
                                i + 1 }}</span>
                            <div>
                                <span class="font-redhat font-semibold capitalize text-zinc-900 dark:text-white">{{
                                    label(t) }}</span>
                                <span class="font-lora text-sm text-zinc-500 dark:text-zinc-400"> — 
                                    {{ i === 0 ?'headline visibility & top billing' 
                                      : i === tiers.length - 1 ? 'community support & recognition' : 'prominent placement & benefits' }}</span>
                            </div>
                        </li>
                    </ul>

                    <p class="mt-8 font-lora text-sm text-zinc-500 dark:text-zinc-400">
                        Prefer email? Reach us any time and mention "Sponsorship" — the form is the fastest route.
                    </p>
                </div>

                <!-- form -->
                <div
                    class="rounded-3xl border border-black/5 bg-white p-6 shadow-[0_24px_50px_-24px_rgba(0,0,0,0.18)] dark:border-white/10 dark:bg-zinc-900 sm:p-8">
                    <h3 class="font-prata text-xl text-zinc-900 dark:text-white">Sponsorship enquiry</h3>
                    <p class="mt-1 font-lora text-sm text-zinc-500 dark:text-zinc-400">Tell us a little about you —
                        we'll be in touch.</p>

                    <form class="mt-6 space-y-4" @submit.prevent="submitApplication">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Your
                                    name</label>
                                <input v-model="form.name" type="text" required
                                    class="w-full rounded-xl border border-black/10 bg-white px-4 py-2.5 text-sm text-zinc-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-white/10 dark:bg-zinc-800 dark:text-white" />
                                <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label
                                    class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Organisation</label>
                                <input v-model="form.organization" type="text" required
                                    class="w-full rounded-xl border border-black/10 bg-white px-4 py-2.5 text-sm text-zinc-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-white/10 dark:bg-zinc-800 dark:text-white" />
                                <p v-if="form.errors.organization" class="mt-1 text-xs text-red-600">{{
                                    form.errors.organization }}</p>
                            </div>
                            <div>
                                <label
                                    class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Email</label>
                                <input v-model="form.email" type="email" required
                                    class="w-full rounded-xl border border-black/10 bg-white px-4 py-2.5 text-sm text-zinc-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-white/10 dark:bg-zinc-800 dark:text-white" />
                                <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">{{ form.errors.email }}
                                </p>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Phone
                                    <span class="text-zinc-400">(optional)</span></label>
                                <input v-model="form.phone" type="tel"
                                    class="w-full rounded-xl border border-black/10 bg-white px-4 py-2.5 text-sm text-zinc-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-white/10 dark:bg-zinc-800 dark:text-white" />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Preferred
                                tier</label>
                            <select v-model="form.tier"
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-2.5 text-sm text-zinc-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-white/10 dark:bg-zinc-800 dark:text-white">
                                <option value="">Not sure yet — advise me</option>
                                <option v-for="t in tiers" :key="t" :value="t">{{ label(t) }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Message
                                <span class="text-zinc-400">(optional)</span></label>
                            <textarea v-model="form.message" rows="4" placeholder="What are you hoping to achieve?"
                                class="w-full rounded-xl border border-black/10 bg-white px-4 py-2.5 text-sm text-zinc-900 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-white/10 dark:bg-zinc-800 dark:text-white"></textarea>
                        </div>

                        <button type="submit" :disabled="form.processing"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-primary px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-primary/20 transition hover:bg-primary/90 disabled:opacity-60">
                            <Send class="h-4 w-4" /> {{ form.processing ? 'Sending…' : 'Send sponsorship enquiry' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>
