<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Table from '@/components/Table.vue';
import { Head } from '@inertiajs/vue3';
import {
    Card, CardHeader, CardDescription, CardTitle, CardContent
} from '@/components/ui/card';
import {
    Breadcrumb, BreadcrumbItem, BreadcrumbLink, BreadcrumbList, BreadcrumbPage, BreadcrumbSeparator
} from '@/components/ui/breadcrumb';
import Heading from '@/components/Heading.vue';
import { ArrowLeftRight, HandCoins, Banknote } from 'lucide-vue-next'
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import RevertTransaction from '@/components/RevertTransaction.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Início',
        href: '/dashboard'
    }
];
defineProps({
    user: {
        type: Object,
        required: true
    },
    balance: {
        type: Number,
        required: true
    },
    depositCount: {
        type: Number,
        required: true
    },
    transferCount: {
        type: Number,
        required: true
    },
    lastDeposits: {
        type: Array,
        required: true
    },
    lastTransfers: {
        type: Array,
        required: true
    }
});

const columnsDeposits = [
    { key: 'formatted_amount', label: 'Valor' },
    { key: 'formatted_created_at', label: 'Data' },
    { key: 'action', label: 'Ação' },
];
const columnsTransfers = [
    { key: 'formatted_amount', label: 'Valor' },
    { key: 'formatted_created_at', label: 'Data' },
    { key: 'action', label: 'Ação' },
];

const form = useForm({});
const revertTransactionModal = ref(null);
const selectedTransaction = ref(null);
const handleRevert = (item) => {
    selectedTransaction.value = item;
    revertTransactionModal.value.openModal();
};

const confirmRevert = () => {
    if (!selectedTransaction.value) return;

    form.post(`/wallet/transaction/reverse/${selectedTransaction.value.id}`, {
        onSuccess: () => {
            selectedTransaction.value = null;
        },
        onError: (errors) => {
        }
    });
};
const cancelRevert = () => {
    selectedTransaction.value = null;
};

</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Heading title="Dashboard" description="Resumo da sua conta" />
        <div class="flex-col md:flex">
            <div class="flex-1 space-y-4">
                <div class="space-y-4">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">
                                    Conta Corrente
                                </CardTitle>
                                <Banknote/>
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">
                                    {{balance}}
                                </div>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">
                                    Depósitos
                                </CardTitle>
                                <HandCoins/>
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">
                                    {{ depositCount }}
                                </div>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium">
                                    Transferências
                                </CardTitle>
                                <ArrowLeftRight/>
                            </CardHeader>
                            <CardContent>
                                <div class="text-2xl font-bold">
                                    {{ transferCount }}
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-8">
                        <Card class="col-span-4">
                            <CardHeader>
                                <CardTitle>Ultimos Depósitos</CardTitle>
                                <CardDescription>
                                    Os 10 ultimos.
                                </CardDescription>
                            </CardHeader>
                            <CardContent class="pl-2">
                                    <Table :columns="columnsDeposits" :data="lastDeposits" @revert="handleRevert" />
                            </CardContent>
                        </Card>
                        <Card class="col-span-4">
                            <CardHeader>
                                <CardTitle>Ultimas Transferências</CardTitle>
                                <CardDescription>
                                    As 10 ultimos.
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <Table :columns="columnsTransfers" :data="lastTransfers"  @revert="handleRevert"/>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </div>
        <RevertTransaction
            ref="revertTransactionModal"
            title="Reverter Transação"
            :message="`Tem certeza que deseja reverter a transação de ${selectedTransaction?.formatted_amount} - ${selectedTransaction?.formatted_created_at} ?`"
            @confirm="confirmRevert"
            @close="cancelRevert"/>
    </AppLayout>
</template>
