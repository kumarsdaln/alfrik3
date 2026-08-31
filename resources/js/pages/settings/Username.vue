<script setup lang="ts">
import { ref } from 'vue'
import { Form, Head } from '@inertiajs/vue3'

import UsernameController from '@/actions/App/Http/Controllers/Settings/UsernameController'

import Heading from '@/components/Heading.vue'
import AppFormControl from '@/components/form/AppFormControl.vue'
import AppInput from '@/components/form/AppInput.vue'

import { Button } from '@/components/ui/button'

interface Props {
    username: string
}

const props = defineProps<Props>()

const username = ref(props.username)
</script>

<template>
    <Head title="Username settings" />

    <h1 class="sr-only">
        Username settings
    </h1>

    <div class="flex flex-col space-y-6">

        <Heading
            variant="small"
            title="Username"
            description="Choose the username people will use to find your profile"
        />

        <Form
            v-bind="UsernameController.update.form()"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <AppFormControl :error="errors.username">
                <AppInput
                    v-model="username"
                    name="username"
                    placeholder="Enter your username"
                    autocomplete="username"
                    required
                />
            </AppFormControl>

            <p class="-mt-4 text-sm text-muted-foreground">
                Your username is used for your public profile.
            </p>

            <div class="flex items-center gap-4">
                <Button
                    type="submit"
                    :disabled="processing"
                >
                    Save
                </Button>
            </div>
        </Form>
    </div>
</template>