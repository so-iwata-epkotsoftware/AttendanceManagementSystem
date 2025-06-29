<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { computed, onMounted, reactive, ref } from 'vue';

const props = defineProps({
    editExpenseData : Object,
});

const fileInput = ref(null);
console.log(props.editExpenseData);

// useFormでフォームデータとエラー管理
const form = useForm({
    expense_type: props.editExpenseData.expense_type,
    date: props.editExpenseData.date,
    section: props.editExpenseData.section,
    amount: props.editExpenseData.amount,
    receipt: [],
    note: props.editExpenseData.note,
    expense_receipts : props.editExpenseData.expense_receipts,
    status : null,
});

const emit = defineEmits(['close']);
const close = () => {
    return emit('close'); // 親に「閉じて」と伝える
};

const previewFiles = ref([]);

onMounted(() => {
    previewFiles.value = Array.from(props.editExpenseData.expense_receipts) ;
});

// const handleFileChange = (e) => {
//     previewFiles.value = [];
//     const files = Array.from(e.target.files);
//     files.forEach(file => {
//         if (file.type.startsWith('image/')) {
//             const url = URL.createObjectURL(file);
//             previewFiles.value.push({ url, type: file.type });
//         } else if (file.type === 'application/pdf') {
//             previewFiles.value.push({ url: null, type: file.type });
//         }
//     });
// };

const submit = status => {
    console.log(status);
    form.status = status;
    form.post(route('admin.variousApplications.update', props.editExpenseData.id), {
        onSuccess : () => emit('close'),
    });
};

</script>

<template>
    <Head title="ユーザー新規登録" />
    <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <section class="text-gray-600 body-font relative">
                    <div class="container px-5 py-12 mx-auto">
                        <div class="flex justify-between fixed right-0 top-0 mx-1 my-2">
                            <span @click="close" class="cursor-pointer transition transform hover:scale-[1.5]">
                                <img src="/images/close_icon.svg" class="h-7 w-10" alt="閉じる">
                            </span>
                        </div>
                        <div class="flex flex-col text-center w-full mb-6">
                            <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">申請内容</h1>
                        </div>
                        <div class="lg:w-5/6 md:w-3/4 mx-auto">
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
                                <!-- ファイル選択 -->
                                <!-- <input type="file" id="receipt" name="receipt[]" @change="handleFileChange" accept="image/*,application/pdf" multiple ref="fileInput"> -->

                                <!-- プレビュー表示 -->
                                <div class="flex space-x-2 my-2" v-if="form.expense_receipts.length">
                                    <div v-for="(file, idx) in form.expense_receipts" :key="idx">
                                        <template v-if="file.file_type === 'application/pdf'">
                                            <span class="text-gray-500">PDFファイル（プレビューなし）</span>
                                        </template>
                                        <template v-else>
                                            <img :src="`/storage/${file.file_path}`" alt="プレビュー画像" class="w-32 h-32 object-cover rounded border" />
                                        </template>
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
                                <div class="p-2 w-full flex">
                                    <button type="submit"
                                        class="flex mx-auto text-white bg-green-500 border-0 py-2 px-8 focus:outline-none hover:bg-green-600 rounded text-lg"
                                        :disabled="form.processing" @click="submit('approved')">
                                        承認申請
                                    </button>
                                    <button type="submit"
                                        class="flex mx-auto text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg"
                                        :disabled="form.processing"  @click="submit('rejected')">
                                        否認申請
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

</template>
