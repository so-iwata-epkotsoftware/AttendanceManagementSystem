<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const props = defineProps({
    user : Object,
    staff_user : Object,
    expenses : Object,
});

console.log(props.expenses);

</script>

<template>
    <Head title="申請一覧" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                申請一覧
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font">
                            <div class="container px-5 py-10 mx-auto">
                                <div class="flex flex-col text-center w-full mb-10">
                                    <h1 class="sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">申請一覧</h1>
                                </div>
                                <div class="lg:w-4/5 w-full mx-auto overflow-auto">
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">経費種類</th>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">日付</th>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">利用区間</th>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">金額</th>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ステータス</th>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">備考</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="expense in props.expenses" :key="expense.id" class="border-t-2 border-gray-200">
                                                <td class="px-4 py-3">{{ expense.expense_type_jp }}</td>
                                                <td class="px-4 py-3">{{ dayjs(expense.date).format('YYYY/MM/DD') }}</td>
                                                <td class="px-4 py-3">{{ expense.section }}</td>
                                                <td class="px-4 py-3">{{ expense.amount }}</td>
                                                <td class="px-4 py-3">{{ expense.status_jp }}</td>
                                                <td class="px-4 py-3">{{ expense.note ?? '-' }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
