<script setup lang="ts">
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

import { Undo } from 'lucide-vue-next';
defineProps({
    columns: {
        type: Array,
        required: true,
    },
    data: {
        type: Array,
        required: true,
    },
});
</script>
<template>
    <Table>
        <TableHeader>
            <TableRow>
                <TableHead v-for="column in columns" :key="column.key" class="w-[100px]">
                    {{ column.label }}
                </TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="item in data" :key="item.id">
                <TableCell v-for="column in columns" :key="column.id">
                    <template v-if="column.key === 'action'">
                        <Undo
                            v-if="item.reverted !== true"
                            class="w-8 h-8 px-2 py-1 bg-black/80 text-white  rounded hover:bg-gray-500 cursor-pointer"
                            @click="$emit('revert', item)"
                        >
                        </Undo>
                        <span v-else class="text-gray-400">Revertida</span>
                    </template>
                    <template v-else>
                        {{ item[column.key] }}
                    </template>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
