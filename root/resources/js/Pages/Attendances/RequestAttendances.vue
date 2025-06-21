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
                                    <h1 class="sm:text-3xl text-2xl font-medium text-indigo-500  title-font text-gray-900">各種申請一覧</h1>
                                </div>
                                <div class="flex flex-wrap -m-4">
                                    <div class="p-4 md:w-1/3">
                                        <Link class="flex h-full bg-gray-100 p-8 flex-col rounded-[25px] shadow-[14px_11px_25px_-14px_#777777]
                                            cursor-pointer transition transform hover:scale-[1.03] hover:bg-gray-300">
                                            <div class="flex items-center mb-3">
                                                <div class="w-8 h-8 mr-3 inline-flex items-center justify-center rounded-full text-white flex-shrink-0">
                                                    <img src="/images/time_correction_icon.svg" class="h-10">
                                                </div>
                                                <h2 class="text-gray-900 text-lg title-font font-medium">勤怠時刻修正</h2>
                                            </div>
                                            <div class="flex-grow">
                                                <p class="leading-relaxed text-base">
                                                    ・出勤・退勤時刻や休憩時間に誤りがある場合に、正しい時刻を申請する機能です。
                                                </p>
                                                <p class="leading-relaxed text-base">
                                                    ・打刻忘れ・間違った時間の修正を行う際にご利用ください。
                                                </p>
                                            </div>
                                        </Link>
                                    </div>
                                    <div class="p-4 md:w-1/3">
                                        <Link class="flex rounded-lg h-full bg-gray-100 p-8 flex-col shadow-[14px_11px_25px_-14px_#777777]
                                            cursor-pointer transition transform hover:scale-[1.03] hover:bg-gray-300">
                                            <div class="flex items-center mb-3">
                                                <div class="w-8 h-8 mr-3 inline-flex items-center justify-center rounded-full text-white flex-shrink-0">
                                                    <img src="/images/holiday_icon.svg" class="h-10">
                                                </div>
                                                <h2 class="text-gray-900 text-lg title-font font-medium">休暇申請</h2>
                                            </div>
                                            <div class="flex-grow">
                                                <p class="leading-relaxed text-base">
                                                    ・有給休暇・特別休暇・半日休暇など、事前に申請が必要な休暇の申請に利用します。
                                                </p>
                                                <p class="leading-relaxed text-base">
                                                    ・勤務スケジュールに影響するため、申請はお早めにお願いいたします。
                                                </p>
                                            </div>
                                        </Link>
                                    </div>

                                    <div class="p-4 md:w-1/3">
                                        <Link class="flex rounded-lg h-full bg-gray-100 p-8 flex-col shadow-[14px_11px_25px_-14px_#777777]
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

                                    <div class="p-4 md:w-1/3">
                                        <Link class="flex rounded-lg h-full bg-gray-100 p-8 flex-col shadow-[14px_11px_25px_-14px_#777777]
                                            cursor-pointer transition transform hover:scale-[1.03] hover:bg-gray-300">
                                            <div class="flex items-center mb-3">
                                                <div class="w-8 h-8 mr-3 inline-flex items-center justify-center rounded-full bg-indigo-500 text-white flex-shrink-0">
                                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                                    <circle cx="6" cy="6" r="3"></circle>
                                                    <circle cx="6" cy="18" r="3"></circle>
                                                    <path d="M20 4L8.12 15.88M14.47 14.48L20 20M8.12 8.12L12 12"></path>
                                                </svg>
                                                </div>
                                                <h2 class="text-gray-900 text-lg title-font font-medium">その他申請</h2>
                                            </div>
                                            <div class="flex-grow">
                                                <p class="leading-relaxed text-base">
                                                    ・上記に該当しない勤務関連の特別な申請を行うための機能です。
                                                </p>
                                                <p class="leading-relaxed text-base">
                                                    ・例：研修参加申請、在宅勤務許可申請など。詳細は備考欄にご記入ください。
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
