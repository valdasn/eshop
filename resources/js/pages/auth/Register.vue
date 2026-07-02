<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next';

import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';


const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout 
        title="Create an account" 
        description="Enter your details below to create your account"
    >
        <Head title="Register" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        type="text"
                        name="name"
                        v-model="form.name"
                        autocomplete="name"
                        placeholder="Full Name"
                        autofocus
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        v-model="form.email"
                        autocomplete="email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        name="password"
                        v-model="form.password"
                        autocomplete="new-password"
                        placeholder="Password"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm Password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        v-model="form.password_confirmation"
                        autocomplete="new-password"
                        placeholder="Confirm Password"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="w-full" :disabled="form.processing">
                    <Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                    Register
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Already have an account?
                <TextLink href="/login" class="underline underline-offset-4">
                    Log in
                </TextLink>
            </div>
        </form>
    </AuthLayout>
</template>