<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const fileInput = ref(null);

// useFormでフォームデータとエラー管理
const form = useForm({
    expense_type: '',
    date: '',
    section: '',
    amount: '',
    receipt: [],
    note: '',
});


// ファイル選択時のハンドラ
const handleFileChange = (e) => {
    console.log(e.target.files);
    form.receipt = Array.from(e.target.files);
    console.log(form.receipt);
};

// 送信処理
const submit = () => {
    form.post(route('attendances.expenses.store'), {
        onSuccess: () => {
            // 成功時の処理（例：メッセージ表示やリダイレクト）
            form.reset();
            fileInput.value.value = null;
        },
        onError: (errors) => {
            console.log(errors); // ここでエラー内容を確認
        }
    });
};
</script>

<template>
    <Head title="経費申請" />

    <AuthenticatedLayout>
        <template #header>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            経費申請
        </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font relative">
                            <div class="container px-5 py-12 mx-auto">
                                <div class="flex flex-col text-center w-full mb-12">
                                    <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">経費申請</h1>
                                </div>
                                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                    <form @submit.prevent="submit" enctype="multipart/form-data">
                                        <div class="flex flex-wrap -m-2">
                                            <!-- 経費の種類 -->
                                            <div class="p-2 w-1/2">
                                                <div class="relative">
                                                    <label for="expense_type" class="leading-7 text-sm text-gray-600">経費の種類</label>
                                                    <select id="expense_type" v-model="form.expense_type"
                                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                        <option value="">-</option>
                                                        <option value="transportation">交通費</option>
                                                        <option value="travel">出張費</option>
                                                        <option value="other">その他</option>
                                                    </select>
                                                    <div v-if="form.errors.expense_type" class="text-red-500 text-xs">
                                                        <ul>
                                                            <li v-for="expense_type, index in form.errors.expense_type" :key="index">
                                                                {{ expense_type }}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- 日付 -->
                                            <div class="p-2 w-1/2">
                                                <div class="relative">
                                                    <label for="date" class="leading-7 text-sm text-gray-600">日付</label>
                                                    <input type="date" id="date" v-model="form.date"
                                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                    <div v-if="form.errors.date" class="text-red-500 text-xs">
                                                        <ul>
                                                            <li v-for="date, index in form.errors.date" :key="index">
                                                                {{ date }}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- 利用区間 -->
                                            <div class="p-2 w-1/2">
                                                <div class="relative">
                                                    <label for="section" class="leading-7 text-sm text-gray-600">利用区間</label>
                                                    <input type="text" id="section" v-model="form.section"
                                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                    <div v-if="form.errors.section" class="text-red-500 text-xs">
                                                        <ul>
                                                            <li v-for="section, index in form.errors.section" :key="index">
                                                                {{ section }}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- 金額 -->
                                            <div class="p-2 w-1/2">
                                                <div class="relative">
                                                    <label for="amount" class="leading-7 text-sm text-gray-600">金額入力</label>
                                                    <input type="number" id="amount" v-model="form.amount" min="0"
                                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                    <div v-if="form.errors.amount" class="text-red-500 text-xs">
                                                        <ul>
                                                            <li v-for="amount, index in form.errors.amount" :key="index">
                                                                {{ amount }}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- 領収書画像 -->
                                            <div class="p-2 ">
                                                <div class="relative">
                                                    <label for="receipt" class="leading-7 text-sm text-gray-600">画像（領収書等）</label>
                                                    <input type="file" id="receipt"  name="receipt[]" @change="handleFileChange" accept="image/*,application/pdf" multiple ref="fileInput"
                                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                    <div v-if="form.errors.receipt" class="text-red-500 text-xs">
                                                        <ul>
                                                            <li v-for="receipt, index in form.errors.receipt" :key="index">
                                                                {{ receipt }}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- 備考 -->
                                            <div class="p-2 w-full">
                                                <div class="relative">
                                                    <label for="note" class="leading-7 text-sm text-gray-600">備考（申請理由等）</label>
                                                    <textarea id="note" v-model="form.note"
                                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                                                    <div v-if="form.errors.note" class="text-red-500 text-xs">
                                                        <ul>
                                                            <li v-for="note, index in form.errors.note" :key="index">
                                                                {{ note }}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- 送信ボタン -->
                                            <div class="p-2 w-full">
                                                <button type="submit"
                                                    class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg"
                                                    :disabled="form.processing">
                                                    申請
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
