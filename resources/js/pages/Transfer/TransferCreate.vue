<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Banknote, LoaderCircle, Check } from 'lucide-vue-next';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
import { useToast } from '@/components/ui/toast';

const { toast } = useToast()

const props = defineProps<{
    error: string | null,
    sucess: string | null,
}>();

const walletFound = ref(false);
const walletData = ref(null);

const formTransfer = useForm({
    amount: '',
    recipient: null,
});

const formWalletSearch = useForm({
    email: ''
});

const searchWallet = async () => {
    formWalletSearch.clearErrors();
    walletFound.value = false;
    try {
        const response = await axios.get('/wallets/by-email', {
            params: {
                email: formWalletSearch.email
            }
        });
        walletFound.value = true;
        walletData.value = response.data;
        formTransfer.recipient = walletData.value.recipient;
    } catch (error: any) {
        walletFound.value = false;
        if (error.response?.status === 404) {
            formWalletSearch.setError('email', 'Carteira não encontrada');
        } else {
            formWalletSearch.setError('email', 'Erro inesperado.');
        }
    }
};
const transfer = () => {
    formTransfer.post(route('transfer.store'), {
        onSuccess: (response) => {
            if (response.props.success) {
                toast({
                    title: "Sucesso!",
                    description: response.props.success,
                    variant: "success",
                    duration: 3000
                })
                formTransfer.reset();
                walletData.value = null;
            }
            if (response.props.error) {
                toast({
                    title: "Erro!",
                    description: response.props.error,
                    variant: "destructive",
                    duration: 5000
                })
            }
            if(!props.error){
                formTransfer.reset();
                walletData.value = null;
            }
        }
    });
};

const breadcrumbs = [
    {
        title: 'Início',
        href: '/dashboard'
    },
    {
        title: 'Transferência',
        href: '/transfer'
    }
];


</script>

<template>
    <Head title="Depósito" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Heading title="Transferência" description="Realize entre contas" />
        <div class="">
            <div class="grid grid-cols-2 gap-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">
                            Buscar Conta
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Label for="email">Email da Conta:</Label>
                        <div class="flex w-full max-w-sm items-center gap-1.5">
                        <div class="relative w-full max-w-sm items-center">
                            <Input
                                id="email"
                                type="email"
                                required autofocus :tabindex="1"
                                autocomplete="email"
                                v-model.number="formWalletSearch.email"
                                placeholder="joao@email.com" />
                            <span v-if="walletData" class="absolute end-0 inset-y-0 flex items-center justify-center px-2">
                            <Check class="size-6 text-green-600" />
                            </span>
                            <InputError :message="formWalletSearch.errors.email" />
                        </div>
                            <Button tabindex="5" :disabled="formWalletSearch.processing"
                                    @click="searchWallet">
                                <LoaderCircle v-if="formWalletSearch.processing" class="h-4 w-4 animate-spin" />
                                Buscar
                            </Button>
                        </div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">
                            Transferência
                        </CardTitle>
                        <Banknote />
                    </CardHeader>
                    <CardContent>
                        <Label for="email">Valor da Transferência:</Label>
                        <Input
                            id="amount"
                            type="number"
                            required autofocus :tabindex="1"
                            autocomplete="amount"
                            v-model.number="formTransfer.amount"
                            step="0.01"
                            placeholder="50"
                            :disabled="!walletData || formTransfer.processing"/>
                        <InputError :message="formTransfer.errors.amount" />
                        <div v-if="props.error" class="text-red-600 mt-2">
                            {{ props.error }}
                        </div>
                        <Button class="mt-2 w-full" tabindex="5" :disabled="!walletData || formTransfer.processing"
                                @click="transfer">
                            <LoaderCircle v-if="formTransfer.processing" class="h-4 w-4 animate-spin" />
                            Transferir
                        </Button>

                        <span v-if="formTransfer">

                        </span>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
