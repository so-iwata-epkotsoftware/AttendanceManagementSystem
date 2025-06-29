<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

import Pagination from '@/Components/Pagination.vue';
import { computed, reactive, ref } from 'vue';

import Modal from '@/Components/Modal.vue';
import Create from '@/Components/Admin/User/Create.vue';
import Edit from '@/Components/Admin/User/Edit.vue';

const props = defineProps({
    staff_users : Object,
    companies : Array,
    errors : Object,
});

const form = reactive({
    searchText : null,
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
const isCompanySelected = company => {
    return form.selectedCompanies.includes(company);
};
const searchUser = () => {
    router.get(route('admin.user.index'), form);
};

const selectedUsers = ref([]);
const formUser = reactive({
    users : [],
});
const toggleUser= user => {
    if (!formUser.users.includes(user)) {
        formUser.users.push(user);
    } else {
        const index = formUser.users.indexOf(user);
        formUser.users.splice(index, 1);
    }
};
const isUserSelected = user => {
    return formUser.users.includes(user);
};

const deleteUser = () => {
    if (!confirm('本当に削除しますか？')) return;

    router.post(route('admin.user.destroy'), formUser);
};

const showAttendances = id => {
    router.get(route('admin.attendances.index', id));
};

const toggleEditUser = ref(false);
const staffUserData = ref([]);
const editUser = user => {
    staffUserData.value = user;
    toggleEditUser.value = true;
};

const toggleCreateUser = ref(false);
const createUser = () => {
    toggleCreateUser.value = true;
};
</script>

<style scoped>
.clipped-card {
    clip-path: polygon(0 0, 100% 0, 100% 70%, 60% 100%, 0 100%);
}


.clipped-left-end-card1 {
    clip-path: polygon(0 100%, 100% 70%, 100% 80%);
}

.clipped-left-end-card2{
    clip-path: polygon(0 100%, 100% 70%, 100% 100%);
}
</style>

<template>
    <Head title="ユーザー一覧" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800" >
                ユーザー一覧
            </h2>
        </template>

        <Modal @close="toggleCreateUser=false" :show="toggleCreateUser">
            <Create :errors="props.errors" @close="toggleCreateUser=false"/>
        </Modal>

        <Modal @close="toggleEditUser=false" :show="toggleEditUser">
            <Edit :errors="props.errors" @close="toggleEditUser=false" :staffUserData="staffUserData"/>
        </Modal>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8 max-w-7xl">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 mx-auto">
                        <section @focus.prevent="searchUser" class="text-gray-600 body-font">
                            <div class="container px-5 py-5 mx-auto flex flex-wrap">
                                <div class="lg:w-5/6 md:w-5/6 mx-auto shadow-[14px_11px_25px_-14px_#777777]
                                        border-2 rounded-lg border-gray-200 p-10 mb-10 relative">
                                        <div class=" absolute top-4 right-12 flex">
                                            <span @click="createUser" class="cursor-pointer transition transform hover:scale-[1.5] mr-5">
                                                <img src="/images/add_user_icon.svg" class="h-10" alt="ユーザー登録">
                                            </span>

                                            <form @submit.prevent="deleteUser" class="cursor-pointer transition transform hover:scale-[1.5] ">
                                                <button type="submit">
                                                    <img src="/images/trash_icon.svg" class="h-10" alt="ゴミ箱">
                                                </button>
                                            </form>
                                        </div>

                                    <div class="flex w-1/2 items-end mx-auto">
                                        <div class="relative mr-4 w-full">
                                            <label for="search-user" class="leading-7 text-lg text-gray-600">ユーザー検索</label>
                                            <input type="text" id="search-user" name="search-user" v-model="form.searchText"
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
                                                :class="isCompanySelected(company) ? 'bg-indigo-100 border-indigo-500' : 'border-gray-200'"
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

                                <div class="flex flex-wrap mx-auto w-5/6">
                                    <div class="m-4 max-w-md border-none mx-auto relative rounded-lg " v-for="user in props.staff_users.data" :key="user.id">
                                        <div @click="editUser(user)"
                                            class="flex items-center justify-center bg-green-300 border border-green-300 border-opacity-70
                                                    min-h-[40px] min-w-[90px] px-6 py-2 cursor-pointer transition transform hover:scale-[1.08] hover:bg-green-500
                                                    absolute top-2 right-2 rounded-full z-50 shadow-md">
                                            <span class="text-lg text-white">更新</span>
                                        </div>

                                        <div class="flex shadow-[13px_18px_26px_-4px_#777777] border-2 rounded-lg transition transform hover:scale-[1.03]
                                                border-none m-0 p-0 border-opacity-50  sm:flex-row flex-col  min-h-[400px] max-w-[400px] bg-white">
                                            <div @click="toggleUser(user)"
                                                class="flex border-2 rounded-lg border-gray-200 border-opacity-50 p-8 sm:flex-row flex-col border-b-2 min-h-[400px] max-w-[400px]
                                                    cursor-pointer transition transform hover:scale-[1.03] hover:bg-gray-300
                                                    bg-white clipped-card z-40">
                                                <input type="checkbox" disabled :checked="isUserSelected(user)"
                                                    class="absolute top-4 left-4 rounded-full"/>
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
                                                    <p>所定出社時間： {{ user.company.start_in_time }}</p>
                                                    <p>所定退社時間： {{ user.company.end_out_time }}</p>
                                                    <p>所定休憩時間： {{ user.company.break }}</p>
                                                    <p>所定労働時間： {{ user.company.work_hours_time }}</p>
                                                    <p>未承認数：{{ user.pending_count }}件</p>
                                                    <p>否認数：{{ user.rejected_count }}件</p>
                                                </div>
                                            </div>

                                            <div @click="showAttendances(user.id)"
                                                class="flex border-4 bg-blue-300 border-blue-300 sm:flex-row flex-col border-b-2 min-h-[100%] min-w-[40%]
                                                cursor-pointer transition transform hover:scale-[1.01] hover:bg-blue-500
                                                clipped-left-end-card2 absolute bottom-0 right-0 z-10">
                                                <span class="absolute bottom-1 right-3 text-lg text-white">勤怠一覧</span>
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
