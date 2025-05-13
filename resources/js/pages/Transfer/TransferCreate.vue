<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { useForm } from '@inertiajs/vue3';

const walletInfo = ref(null);
const error = ref(null);

const form = useForm({
    email: '',
    amount: 0,
});

const searchWallet = async () => {
    try {
        const response = await form.get(`/wallets/by-email/${form.email}`, {
            preserveScroll: true,
            onSuccess: (data) => {
                walletInfo.value = data;
                error.value = null;
            },
            onError: (errors) => {
                walletInfo.value = null;
                error.value = errors.message || 'Erro ao buscar carteira';
            },
        });
    } catch (e) {
        walletInfo.value = null;
        error.value = e.response.data.message || 'Erro ao buscar carteira';
    }
};

const transfer = async () => {
    try {
        form.post('/transfer', {
            wallet_id: walletInfo.value.wallet_id,
            amount: form.amount,
        }, {
            onSuccess: () => {
                // Lógica para lidar com o sucesso da transferência
                console.log('Transferência realizada com sucesso!');
            },
            onError: (errors) => {
                // Lógica para lidar com os erros de validação
                console.error(errors);
            },
        });
    } catch (e) {
        error.value = 'Erro ao transferir';
    }
};
</script>

<template>
    <Head title="Depósito" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Heading title="Depósito" description="Realize o depósito na sua conta" />
        <div>
        <label for="email">Email do Destinatário:</label>
        <input type="email" id="email" v-model="form.email" @blur="searchWallet" />
        <button @click="searchWallet" :disabled="form.processing">Buscar Carteira</button>

        <div v-if="walletInfo">
            <p>Nome: {{ walletInfo.user_name }}</p>
            <label for="amount">Valor da Transferência:</label>
            <input type="number" id="amount" v-model="form.amount" />
            <button @click="transfer" :disabled="form.processing">Transferir</button>
        </div>

        <div v-if="form.errors.email">
            <p>{{ form.errors.email }}</p>
        </div>
        <div v-if="form.errors.amount">
            <p>{{ form.errors.amount }}</p>
        </div>
        <div v-if="error">
            <p>{{ error }}</p>
        </div>
    </div>
    </AppLayout>
</template>

<style scoped>

</style>
