<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { reactive } from 'vue';


const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    company_name: '',
    address: '',
    start_time: '09:00',
    end_time: '18:00',
    break_time: '01:00',
    work_hours: '08:00',
});

const storeUser = () => {
    router.post(route('admin.user.store'), form);
};
</script>

<template>
    <Head title="ユーザー新規登録" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                ユーザー新規登録
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font relative">
                            <div class="container px-5 py-4 mx-auto">
                                <div class="flex flex-col text-center w-full mb-12">
                                    <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">ユーザー登録</h1>
                                </div>
                                <div class="lg:w-3/4 md:w-2/3 mx-auto">
                                    <div class="flex flex-wrap -m-2">
                                        <div class="p-2 w-1/2">
                                                <InputLabel for="name" value="氏名" />
                                                <TextInput id="name" type="text" v-model="form.name" class="mt-1 block w-full" required autocomplete="new-name"/>
                                        </div>

                                        <div class="p-2 w-1/2">
                                                <InputLabel for="email" value="メールアドレス" />
                                                <TextInput id="email" type="email" v-model="form.email" class="mt-1 block w-full" required autocomplete="new-email"/>
                                        </div>

                                        <div class="p-2 w-1/2">
                                            <InputLabel for="password" value="パスワード" />
                                            <TextInput id="password" type="password" v-model="form.password" class="mt-1 block w-full" required autocomplete="new-password"/>
                                            <!-- <InputError class="mt-2" :message="form.errors.password" /> -->
                                        </div>

                                        <div class="p-2 w-1/2">
                                            <InputLabel for="password_confirmation" value="パスワード（確認用）"/>
                                            <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" required autocomplete="new-password"/>
                                            <!-- <InputError class="mt-2" :message="form.errors.password_confirmation"/> -->
                                        </div>

                                        <div class="p-2 w-1/2">
                                                <InputLabel for="company_name" value="会社名" />
                                                <TextInput id="company_name" type="text" v-model="form.company_name" class="mt-1 block w-full" required autocomplete="new-company_name"/>
                                        </div>

                                        <div class="p-2 w-1/2">
                                                <InputLabel for="address" value="会社アドレス" />
                                                <TextInput id="address" type="text" v-model="form.address" class="mt-1 block w-full" required autocomplete="new-address"/>
                                        </div>
                                        <div class="p-2 w-1/2">
                                                <InputLabel for="start_time" value="所定出社時間" />
                                                <TextInput id="start_time" type="time" v-model="form.start_time" class="mt-1 block w-full" required autocomplete="new-start_time"/>
                                        </div>

                                        <div class="p-2 w-1/2">
                                                <InputLabel for="end_time" value="所定退社時間" />
                                                <TextInput id="end_time" type="time" v-model="form.end_time" class="mt-1 block w-full" required autocomplete="new-end_time"/>
                                        </div>

                                        <div class="p-2 w-1/2">
                                                <InputLabel for="break_time" value="所定休憩時間" />
                                                <TextInput id="break_time" type="time" v-model="form.break_time" class="mt-1 block w-full" required autocomplete="new-break_time"/>
                                        </div>

                                        <div class="p-2 w-1/2">
                                                <InputLabel for="work_hours" value="所定労働時間" />
                                                <TextInput id="work_hours" type="time" v-model="form.work_hours" class="mt-1 block w-full" required autocomplete="new-work_hours"/>
                                        </div>

                                        <div class="p-2 w-full">
                                            <button @click="storeUser"
                                                class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録</button>
                                        </div>

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
