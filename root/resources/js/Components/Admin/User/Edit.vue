<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { computed, reactive } from 'vue';

const props = defineProps({
    staffUserData : Object,
    errors : Object,
});


const form = reactive({
    id : props.staffUserData.id,
    name: props.staffUserData.name,
    email: props.staffUserData.email,
    password: '',
    password_confirmation: '',
    company_name: props.staffUserData.company.name,
    address: props.staffUserData.company.address,
    start_time: props.staffUserData.company.start_time,
    end_time: props.staffUserData.company.end_time,
    break_time: props.staffUserData.company.break_hi,
    work_hours: props.staffUserData.company.work_hours_hi,
});

const emit = defineEmits(['close']);
const close = () => {
    return emit('close'); // 親に「閉じて」と伝える
};


const updateUser = () => {
    router.post(route('admin.user.update', form.id), form, {
        onSuccess: () => {
            emit('close');
        },
        onError: (e) => {
            console.log('Error', e);
        },
    });
};
</script>

<template>
    <Head title="ユーザー新規登録" />
    <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <section class="text-gray-600 body-font relative">
                    <div class="container px-5 py-4 mx-auto">
                        <div class="modal relative overflow-visible z-50 bg-white sm:rounded-lg mb-10">
                            <div class="fixed top-0 left-0 m-3">
                                <h1 class="sm:text-xl text-xl font-medium title-font mb-2 text-gray-900">ユーザー情報更新</h1>
                            </div>
                            <div class="flex justify-between fixed right-0 top-0 mx-1 my-2">
                                <span @click="close" class="cursor-pointer transition transform hover:scale-[1.5]">
                                    <img src="/images/close_icon.svg" class="h-7 w-10" alt="閉じる">
                                </span>
                            </div>
                        </div>

                        <div class="lg:w-5/6 md:w-2/3 mx-auto">
                            <form @submit.prevent="updateUser" class="flex flex-wrap -m-2" >
                                <div class="p-2 w-1/2">
                                    <InputLabel for="name" value="氏名" />
                                    <TextInput id="name" type="text" v-model="form.name" class="mt-1 block w-full" required autocomplete="new-name"/>
                                    <InputError class="mt-2" :message="props.errors?.name"/>
                                </div>

                                <div class="p-2 w-1/2">
                                        <InputLabel for="email" value="メールアドレス" />
                                        <TextInput id="email" type="email" v-model="form.email" class="mt-1 block w-full" required autocomplete="new-email"/>
                                        <InputError class="mt-2" :message="props.errors?.email"/>
                                </div>

                                <div class="p-2 w-1/2">
                                    <InputLabel for="password" value="パスワード" />
                                    <TextInput id="password" type="password" v-model="form.password" class="mt-1 block w-full" required autocomplete="new-password"/>
                                    <InputError class="mt-2" :message="props.errors?.password" />
                                </div>

                                <div class="p-2 w-1/2">
                                    <InputLabel for="password_confirmation" value="パスワード（確認用）"/>
                                    <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" required autocomplete="new-password"/>
                                    <InputError class="mt-2" :message="props.errors?.password_confirmation"/>
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
                                    <button
                                        class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                                        更新
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

</template>
