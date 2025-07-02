<template>
    <Head title="Permissions" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <!-- <Input /> -->
                <Link :href="route('roles.create')">
                    <Button label="Add Role" />
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
                        <TableRow v-for="(role, index) in roles" :key="index">
                            <TableCell class="font-medium">
                                {{ index + 1 }}
                            </TableCell>
                            <TableCell>{{ role.name }}</TableCell>

                            <TableCell class="text-right">
                                <Link :href="route('roles.edit', role.id)">
                                    <Button class="mr-2" label="Edit" variant="secondary" />
                                </Link>

                                <Button label="Delete" variant="destructive" />
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <Pagination
                    v-model:page="pagination.currentPage"
                    :items-per-page="pagination.perPage"
                    :total="pagination.total"
                    @update:page="fetchRoles"
                />
            </div>
        </div>
        <Toaster />
    </AppLayout>
</template>
<script>
import Button from '@/components/custom/Button.vue';
import { Input } from '@/components/ui/input';
import { Toaster } from '@/components/ui/sonner';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { defineComponent } from 'vue';

import Pagination from '@/components/custom/Pagination.vue';
import 'vue-sonner/style.css';

export default defineComponent({
    components: {
        Table,
        TableBody,
        TableCaption,
        TableCell,
        TableHead,
        TableHeader,
        TableRow,
        AppLayout,
        Head,
        Button,
        Link,
        Toaster,
        Input,
        Pagination,
    },
    data(vm) {
        return {
            breadcrumbs: [{ title: 'Permissions', href: '/permissions' }],
            roles: [],
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
    props: {
        success: Boolean,
        message: String,
    },
    mounted() {
        this.fetchRoles();
        console.log('respose' + this.success);
    },
    methods: {
        async fetchRoles(page = 1, search = null) {
            try {
                const response = await axios.get(route('paginate.roles'), {
                    params: {
                        search: search,
                        page: page,
                    },
                });
                this.roles = response.data.data;
                this.pagination.currentPage = response.data.current_page;
                this.pagination.total = response.data.total;
                this.pagination.perPage = response.data.per_page;
            } catch (error) {
                console.log(error);
            }
        },
        search() {
            this.fetchRoles(1, this.form.search);
        },
    },
});
</script>
