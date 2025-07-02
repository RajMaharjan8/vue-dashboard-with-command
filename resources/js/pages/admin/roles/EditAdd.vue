<template>
    <Head title="Permissions" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <form @submit.prevent="form.id ? update() : submit()">
            <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Input type="text" placeholder="Role" v-model="form.role" />

                <Button label="Save" type="submit" />

                <div class="mt-10">
                    <h2 class="mb-4 text-xl font-semibold text-gray-800">Permissions</h2>

                    <div v-if="permissions" v-for="(permissionGroup, groupName) in permissions" :key="groupName" class="mb-6">
                        <h3 class="mb-2 text-lg font-medium text-gray-700 capitalize">
                            {{ groupName }}
                        </h3>

                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3">
                            <div v-for="(permission, index) in permissionGroup" :key="index" class="flex items-center space-x-2">
                                <div class="flex items-center space-x-2">
                                    <input
                                        type="checkbox"
                                        class="accent-black focus:ring-black"
                                        :id="permission.id"
                                        :value="permission.id"
                                        v-model="form.permissions"
                                    />
                                    <label :for="permission.id" class="text-sm font-medium text-gray-600">
                                        {{ permission.name }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </AppLayout>
</template>

<script>
import Button from '@/components/custom/Button.vue';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineComponent } from 'vue';

export default defineComponent({
    components: {
        AppLayout,
        Head,
        Input,
        Checkbox,
        Button,
    },
    props: {
        role: Object,
        permissions: Object,
    },
    data() {
        return {
            breadcrumbs: [{ title: 'Roles', href: '/roles' }],
            form: this.$inertia.form({
                id: this.role?.id ?? null,
                role: this.role?.name ?? '',
                permissions: this.role?.permissions?.map((p) => p.id) ?? [],
                _method: this.role?.id ? 'put' : 'post',
            }),
        };
    },
    mounted() {
        console.log('roles: ' + JSON.stringify(this.permissions));
    },
    methods: {
        submit() {
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(this.route('roles.store'), {
                    onSuccess: (response) => {
                        console.log(response);
                    },
                    onError: (error) => {
                        console.log('err: ' + error);
                    },
                });
        },
        update() {
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(this.route('roles.update', this.form.id), {
                    onSuccess: (response) => {
                        console.log(response);
                    },
                    onError: (error) => {
                        console.log('err: ' + error);
                    },
                });
        },
    },
});
</script>
