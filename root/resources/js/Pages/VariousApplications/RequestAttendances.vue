<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="各種申請" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                各種申請
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font">
                            <div class="container px-5 py-10 mx-auto">
                                <div class="flex flex-col text-center w-full mb-20">
                                    <h1 class="sm:text-3xl text-2xl font-medium text-indigo-500  title-font">各種申請一覧</h1>
                                </div>
                                <div class="flex flex-wrap -m-4">
                                    <div class="p-4 md:w-1/3 mx-auto">
                                        <Link as="button" :href="route('attendances.variousApplications.list')" class="flex h-full bg-gray-100 p-8 flex-col rounded-[25px] shadow-[14px_11px_25px_-14px_#777777]
                                            cursor-pointer transition transform hover:scale-[1.03] hover:bg-gray-300">
                                            <div class="flex items-center mb-3">
                                                <div class="w-8 h-8 mr-3 inline-flex items-center justify-center rounded-full text-white flex-shrink-0">
                                                    <img src="/images/cost_icon.svg" class="h-10">
                                                </div>
                                                <h2 class="text-gray-900 text-lg title-font font-medium">申請一覧</h2>
                                            </div>
                                            <div class="flex-grow">
                                                <p class="leading-relaxed text-base">
                                                    ・過去に申請した経費の履歴や現在の申請状況を一覧で確認できます。
                                                </p>
                                                <p class="leading-relaxed text-base">
                                                    ・各申請の承認状況（未承認・承認済み・却下）や詳細内容を確認できます。
                                                </p>
                                            </div>
                                        </Link>
                                    </div>
                                    <div class="p-4 md:w-1/3 mx-auto">
                                        <Link as="button" :href="route('attendances.expenses.create')" class="flex h-full bg-gray-100 p-8 flex-col rounded-[25px] shadow-[14px_11px_25px_-14px_#777777]
                                            cursor-pointer transition transform hover:scale-[1.03] hover:bg-gray-300">
                                            <div class="flex items-center mb-3">
                                                <div class="w-8 h-8 mr-3 inline-flex items-center justify-center rounded-full text-white flex-shrink-0">
                                                    <img src="/images/cost_icon.svg" class="h-10">
                                                </div>
                                                <h2 class="text-gray-900 text-lg title-font font-medium">経費申請</h2>
                                            </div>
                                            <div class="flex-grow">
                                                <p class="leading-relaxed text-base">
                                                    ・業務上の出張費・備品購入などの経費を会社に申請・精算するための機能です。
                                                </p>
                                                <p class="leading-relaxed text-base">
                                                    ・必要に応じてレシート画像の添付や使用目的の入力が求められます。
                                                </p>
                                            </div>
                                        </Link>
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
