<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';
import { LoaderCircle } from 'lucide-vue-next';
import { useToast } from '@/components/ui/toast'

const { toast } = useToast()

const form = useForm({
    amount: null,
});
const breadcrumbs = [
    {
        title: 'Início',
        href: '/dashboard',
    },
    {
        title: 'Depósito',
        href: '/deposit',
    },
];
const submit = () => {
    form.post('/deposit', {
        onSuccess: (response) => {
            if (response.props.success) {
                toast({
                    title: "Depósito realizado!",
                    description: response.props.success,
                    variant: "success",
                    duration: 3000
                })
            }
            if (response.props.error) {
                toast({
                    title: "Erro!",
                    description: response.props.error,
                    variant: "destructive",
                    duration: 5000
                })
            }
        },
        onFinish: () => form.reset('amount'),
        onError: (errors) => {
            if (errors.amount) {
                form.reset('amount');
                if (amountInput.value instanceof HTMLInputElement) {
                    amountInput.value.focus();
                }
                toast({
                    title: "Erro!",
                    description: errors.amount,
                    variant: "destructive",
                    duration: 5000
                })
            }
        },
    });
};
</script>

<template>
    <Head title="Depósito" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Heading title="Depósito" description="Realize o depósito na sua conta" />
        <div class="flex w-80 ">
            <form @submit.prevent="submit" class="flex flex-col gap-6">
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="amount">Valor</Label>
                        <Input
                            id="amount"
                            type="number"
                            required autofocus :tabindex="1"
                            autocomplete="amount"
                            v-model.number="form.amount"
                            step="0.01"
                            placeholder="50" />
                        <InputError :message="form.errors.amount" />
                    </div>
                    <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Fazer Depósito
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
