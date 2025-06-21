<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

import Modal from '@/Components/Modal.vue';
import Show from '@/Components/Admin/Attendances/Show.vue';

const props = defineProps({
    user : Object,
    data : Object, // データ取得
    date : Object, // 日付年と月取得
    errors : Object,
});

const date = reactive({
    user_id : props.user.id,
    year : props.date.year,
    month : props.date.month,
});

const changeDate = () => {
    router.get(route('admin.user.show', date.user_id), date );
};

const showAttendance = ref(false);

const stampData = reactive({
    'day' : '',
    'data' : '',
});
const stampDay = (day, data) => {
    stampData.day = day;
    stampData.data = data;
    showAttendance.value = true;
};
</script>

<template>
    <Head title="勤怠一覧" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                勤怠一覧
            </h2>
        </template>

        <Modal :show='showAttendance' @close="showAttendance = false">
            <Show :stampData="stampData" @close="showAttendance = false" :errors="props.errors"/>
        </Modal>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg" >
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font">
                            <div class="container px-5 mx-auto">
                                <div class="flex flex-col text-center w-full mb-10">
                                    <h1 class="sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">
                                        勤怠一覧
                                    </h1>
                                    <div class="flex text-center justify-center w-full">
                                        <select v-model="date.year"
                                            class="border-none text-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded mr-2"
                                            @change="changeDate">
                                            <option value="2020">2020年</option>
                                            <option value="2021">2021年</option>
                                            <option value="2022">2022年</option>
                                            <option value="2023">2023年</option>
                                            <option value="2024">2024年</option>
                                            <option value="2025">2025年</option>
                                        </select>
                                        <select v-model="date.month"
                                            class="border-none text-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded"
                                        @change="changeDate">
                                            <option value="01" >1月</option>
                                            <option value="02" >2月</option>
                                            <option value="03" >3月</option>
                                            <option value="04" >4月</option>
                                            <option value="05" >5月</option>
                                            <option value="06" >6月</option>
                                            <option value="07" >7月</option>
                                            <option value="08" >8月</option>
                                            <option value="09" >9月</option>
                                            <option value="10" >10月</option>
                                            <option value="11" >11月</option>
                                            <option value="12" >12月</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="lg:w-5/6 w-full mx-auto">
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl ">日付</th>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">出勤区分</th>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">出勤時間</th>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">退勤時間</th>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">休憩</th>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">実働時間</th>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">残業時間</th>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ステータス</th>
                                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">備考</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="datum, day in props.data" :key="day" @click="stampDay(day, datum)"
                                                class="cursor-pointer transition transform hover:scale-[1.01] hover:shadow-md hover:bg-gray-100 border-t-2 border-gray-200">
                                                <td class="px-4 py-3 text-sm text-center">{{ day }}</td>
                                                <td class="px-4 py-3 text-sm text-center">{{ datum[0]?.vacation?.vacation_type_jp ?? '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-center">{{ datum[0]?.clock_in ? datum[0]?.clock_in_time : '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-center">{{ datum[0]?.clock_out ? datum[0]?.clock_out_time : '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-center">{{ datum[0]?.break_minutes ? `${datum[0]?.break_minutes}分` : '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-center">{{ datum[0]?.work_hours ? datum[0]?.work_hours_time : '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-center">{{ datum[0]?.overtime_hours ? datum[0]?.overtime_hours_time : '-' }}</td>
                                                <td class="px-4 py-3 text-center">{{ datum[0]?.attendance_status.status_jp ?? '-' }}</td>
                                                <td class="px-4 py-3 text-xs w-40 break-words">{{ datum[0]?.attendance_status.reason ?? '-' }}</td>
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
