<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next';

import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';


defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthLayout title="Log in" description="Enter your email below to log into your account">
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        v-model="form.email"
                        autocomplete="email"
                        placeholder="email@example.com"
                        autofocus
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Password</Label>
                        <TextLink v-if="canResetPassword" href="/forgot-password" class="text-sm">
                            Forgot your password?
                        </TextLink>
                    </div>
                    <Input
                        id="password"
                        type="password"
                        name="password"
                        v-model="form.password"
                        autocomplete="current-password"
                        placeholder="Password"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex items-center space-x-2">
                    <Checkbox id="remember" :checked="form.remember" @update:checked="(v) => form.remember = v" />
                    <label for="remember" class="text-sm font-medium leading-none">
                        Remember me
                    </label>
                </div>

                <Button type="submit" class="w-full" :disabled="form.processing">
                    <Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                    Log in
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Don't have an account?
                <TextLink href="/register" class="underline underline-offset-4">
                    Sign up
                </TextLink>
            </div>
        </form>
    </AuthLayout>
</template>