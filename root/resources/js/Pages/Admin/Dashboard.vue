<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    request_users : Object,
});

console.log(props.request_users);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font">
                            <div class="container px-5 py-24 mx-auto flex flex-wrap">
                                <div class="flex flex-wrap -m-4">
                                    <div class="p-4 lg:w-1/2 md:w-full" v-for="user in props.request_users.data" :key="user.id">
                                        <div @click="showUser(user.id)"
                                            class="flex border-2 rounded-lg border-gray-200 border-opacity-50 p-8 sm:flex-row flex-col border-b-2 cursor-pointer transition transform hover:scale-[1.03] hover:bg-gray-300">
                                            <div class="w-16 h-16 sm:mr-8 sm:mb-0 mb-4 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 flex-shrink-0">
                                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-8 h-8" viewBox="0 0 24 24">
                                                <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-grow">
                                                <p>ID： {{ user.id }}</p>
                                                <h2 class="text-gray-900 text-lg title-font font-medium mb-3">
                                                    従業員名： {{ user.name }}
                                                </h2>
                                                <p>未承認数：{{ user.pending_count }}件</p>
                                                <p>否認数：{{ user.rejected_count }}件</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-6 w-full flex self-center">
                                        <Pagination class="mx-auto"  :links="props.request_users.links" />
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
