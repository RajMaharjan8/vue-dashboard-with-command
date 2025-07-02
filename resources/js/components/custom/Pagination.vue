<template>
    <Pagination v-slot="{ page }" :items-per-page="10" :total="30" :default-page="2">
        <PaginationContent v-slot="{ items }">
            <PaginationPrevious />

            <template v-for="(item, index) in items" :key="index">
                <PaginationItem v-if="item.type === 'page'" :value="item.value" :is-active="item.value === page">
                    {{ item.value }}
                </PaginationItem>
            </template>

            <PaginationEllipsis :index="4" />

            <PaginationNext />
        </PaginationContent>
    </Pagination>
</template>

<script>
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';

import { defineComponent } from 'vue';

export default defineComponent({
    components: {
        Pagination,
        PaginationContent,
        PaginationEllipsis,
        PaginationItem,
        PaginationNext,
        PaginationPrevious,
    },
});
</script>
