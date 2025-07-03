<template>
    <Head title="Pages" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <Link :href="route('pages.create')">
                    <Button label="Add Page" />
                </Link>
                <div class="w-64">
                    <form @submit.prevent="search()" class="flex gap-2">
                        <Input v-model="form.search" />
                        <Button label="Search" />
                    </form>
                </div>
            </div>

            <div class="relative overflow-x-auto">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead> S.N. </TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead class="text-right"> Action </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(page, index) in pages" :key="index">
                            <TableCell class="font-medium">
                                {{ index + 1 }}
                            </TableCell>
                            <TableCell>{{ page.name }}</TableCell>

                            <TableCell class="text-right">
                                <Link :href="route('pages.edit', page.id)">
                                    <Button class="mr-2" label="Edit" variant="secondary" />
                                </Link>

                                <Button label="Delete" variant="destructive" />
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <Pagination
                    v-if ="pages.length > 0"
                    v-model:page="pagination.currentPage"
                    :items-per-page="pagination.perPage"
                    :total="pagination.total"
                    @update:page="fetchRoles"
                />
            </div>
        </div>
    </AppLayout>
</template>

<script>
import Button from '@/components/custom/Button.vue';
import Pagination from '@/components/custom/Pagination.vue';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { defineComponent } from 'vue';

export default defineComponent({
    components: {
        AppLayout,
        Table,
        TableBody,
        TableCaption,
        TableCell,
        TableHead,
        TableHeader,
        TableRow,
        Input,
        Pagination,
        Head,
        Button,
        Link,
    },
    data() {
        return {
            breadcrumbs: [{ title: 'Pages', href: '/pages' }],
            pages: [],
            form: this.$inertia.form({
                search: '',
            }),
            pagination: {
                currentPage: 1,
                total: 0,
                perPage: 10,
            },
        };
    },
    mounted() {
        this.fetchPages();
    },
    methods: {
        async fetchPages(page = 1, search = null) {
            const response = await axios.get(route('paginate.pages'), {
                params: {
                    search: search,
                    page: page,
                },
            });
            this.pages = response.data.data;
            this.pagination.currentPage = response.data.current_page;
            this.pagination.total = response.data.total;
            this.pagination.perPage = response.data.per_page;
        },
        search() {
            this.fetchPages(1, this.form.search);
        },
    },
});
</script>
