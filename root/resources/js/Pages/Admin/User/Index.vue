<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

import Pagination from '@/Components/Pagination.vue';
import { computed, reactive, ref } from 'vue';

const props = defineProps({
    staff_users : Object,
    companies : Array,
});

console.log(props.staff_users);

const showUser = id => {
    router.get(route('admin.user.show', id));
};

const form = reactive({
    searchUser : null,
    selectedCompanies : [],
});

const toggleCompany = company => {
    if (!form.selectedCompanies.includes(company)) {
        form.selectedCompanies.push(company);
    } else {
        const index = form.selectedCompanies.indexOf(company);
        form.selectedCompanies.splice(index, 1);
    }
};

const isSelected = company => {
    return form.selectedCompanies.includes(company);
};

const searchUser = () => {
    console.log(form);
    router.get(route('admin.user.index'), form);
};
</script>

<template>
    <Head title="ユーザー一覧" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800" >
                ユーザー一覧
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8 max-w-7xl">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 mx-auto">
                        <section @focus.prevent="searchUser" class="text-gray-600 body-font">
                            <div class="container px-5 py-5 mx-auto flex flex-wrap">
                                <div class="lg:w-5/6 md:w-1/2 mx-auto shadow-[14px_11px_25px_-14px_#777777]
                                        border-2 rounded-lg border-gray-200 p-10 mb-10">
                                    <div class="flex w-1/2 items-end mx-auto">
                                        <div class="relative mr-4 w-full">
                                            <label for="search-user" class="leading-7 text-lg text-gray-600">ユーザー検索</label>
                                            <input type="text" id="search-user" name="search-user" v-model="form.searchUser"
                                                class="w-full bg-gray-100 rounded border bg-opacity-50 border-gray-300 focus:ring-2 focus:ring-indigo-200 focus:bg-transparent focus:border-indigo-500 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        </div>
                                        <button @click="searchUser" class="cursor-pointer transition transform hover:scale-[1.13]">
                                            <img src="/images/search_icon.svg" alt="検索" class="h-10">
                                        </button>
                                    </div>

                                    <div class="mt-8">
                                        <h1 class="text-lg">所属会社一覧：</h1>
                                    </div>

                                    <div class="flex flex-wrap overflow-y-scroll h-60">
                                        <div v-for="(company, index) in props.companies" :key="index"
                                            class="p-2 lg:w-1/3 md:w-1/2 w-full cursor-pointer">
                                            <div class="h-full flex items-center border p-4 rounded-lg transition duration-200"
                                                :class="isSelected(company) ? 'bg-indigo-100 border-indigo-500' : 'border-gray-200'"
                                                @click="toggleCompany(company)">
                                                <div class="flex-grow">
                                                    <h2 class="text-gray-900 title-font font-medium">
                                                        {{ company }}
                                                    </h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="flex flex-wrap mx-auto w-4/5">
                                    <div class="p-4 max-w-md mx-auto" v-for="user in props.staff_users.data" :key="user.id">
                                        <div @click="showUser(user.id)"
                                            class="flex border-2 rounded-lg border-gray-200 border-opacity-50 p-8 sm:flex-row flex-col border-b-2
                                                cursor-pointer transition transform hover:scale-[1.03] hover:bg-gray-300 shadow-[14px_11px_25px_-14px_#777777]">
                                            <div class="w-16 h-16 sm:mr-8 sm:mb-0 mb-4 inline-flex items-center justify-center rounded-full text-indigo-500 flex-shrink-0">
                                                <img src="/images/human_icon.svg" class="h-12" alt="">
                                            </div>
                                            <div class="flex-grow">
                                                <p>ID： {{ user.id }}</p>
                                                <h2 class="text-gray-900 text-lg title-font font-medium mb-3">
                                                    従業員名： {{ user.name }}
                                                </h2>
                                                <p>所属会社： {{ user.company.name }}</p>
                                                <p>所属会社： {{ user.company.address }}</p>
                                                <p>所定労働時間： {{ user.company.work_hours_time }}</p>
                                                <p>所定出社時間： {{ user.company.start_in_time }}</p>
                                                <p>所定退社時間： {{ user.company.end_out_time }}</p>
                                                <p>所定休憩時間： {{ user.company.break }}</p>
                                                <p>未承認数：{{ user.pending_count }}件</p>
                                                <p>否認数：{{ user.rejected_count }}件</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-6 w-full flex self-center" v-show="props.staff_users.data.length">
                                        <Pagination class="mx-auto"  :links="props.staff_users.links" />
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
