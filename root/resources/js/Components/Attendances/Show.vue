<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link, usePage, useForm } from '@inertiajs/vue3';
import { computed, reactive, watch  } from 'vue';
import dayjs from 'dayjs';

import InputError from '@/Components/InputError.vue';

const props = defineProps({
    stampData : Object,
});

const attendanceForm = useForm({
    date : props.stampData.day,
    id: props.stampData.data[0]?.id ?? null,
    user_id : props.stampData.data[0]?.user_id ?? props.stampData.data.user_id,
    company_id : props.stampData.data[0]?.company_id ?? props.stampData.data.company_id,
    clock_in : dayjs(props.stampData.data[0]?.clock_in).isValid()
                ? dayjs(props.stampData.data[0]?.clock_in).format('HH:mm')
                : null,
    clock_out : dayjs(props.stampData.data[0]?.clock_out).isValid()
                ? dayjs(props.stampData.data[0]?.clock_out).format('HH:mm')
                : null,
    break_minutes : props.stampData.data[0]?.break_minutes ?? '',
    vacation_type : props.stampData.data[0]?.vacation.vacation_type ?? '',
    reason : props.stampData.data[0]?.attendance_status?.reason ?? '',
});

const vacationTypesOnly = ['Absence', 'PaidLeave', 'SpecialLeave'];

watch(() => attendanceForm.vacation_type, (newVal) => {
    if (vacationTypesOnly.includes(newVal)) {
        attendanceForm.clock_in = null;
        attendanceForm.clock_out = null;
        attendanceForm.break_minutes = null;
    }
});

const emit = defineEmits(['close']);
const close = () => {
    emit('close'); // 親に「閉じて」と伝える
};

const stamp = id => {
    if (id) {
        attendanceForm.post(route('attendances.update', id), {
            onSuccess: () => {
                emit('close');
            },
            onError: () => {
                console.warn('バリデーションエラーが発生しました');
            }
        });
    } else {
        attendanceForm.post(route('attendances.store'), {
            onSuccess: () => {
                emit('close');
            },
            onError: () => {
                console.warn('バリデーションエラーが発生しました');
            }
        });
    }
};
</script>

<template>
    <Head title="勤怠詳細" />

    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <section class="text-gray-600 body-font relative flex flex-col">
                    <div class="flex justify-between items-center">
                        <h1>勤怠詳細</h1>
                        <div class="flex items-center fixed right-0 top-0 mx-1 my-2">
                            <span @click="close" class="cursor-pointer transition transform hover:scale-[1.5]">
                                <img src="/images/close_icon.svg" class="h-7 w-10" alt="閉じる">
                            </span>
                        </div>
                    </div>
                    <div class="lg:w-4/5 md:w-3/4 mx-auto">
                        <div class="flex flex-wrap -m-2">
                            <div class="p-2 w-1/3">
                                <div class="relative">
                                    <label for="name" class="leading-7 text-sm text-gray-600">日付</label>
                                    <input type="text" id="name" name="name" :value="props.stampData.day" disabled
                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                            </div>
                            <div class="p-2 w-1/3">
                                <div class="relative">
                                    <label for="vacation_type" class="leading-7 text-sm text-gray-600">出勤区分</label>
                                    <select name="vacation_type" id="vacation_type" v-model="attendanceForm.vacation_type"
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        <option value="">-</option>
                                        <option value="RegularAttendance">通常出勤</option>
                                        <option value="LateArrival">遅刻</option>
                                        <option value="EarlyLeaving">早退</option>
                                        <option value="Absence">欠勤</option>
                                        <option value="PaidLeave">有給休暇</option>
                                        <option value="SpecialLeave">特別休暇</option>
                                        <option value="HolidayWork">休日出勤</option>
                                        <option value="CompensatedLeave">振替出勤</option>
                                        <option value="StaggeredWork">時差出勤</option>
                                        <option value="Telecommuting">在宅勤務</option>
                                        <option value="BusinessTravel">出張</option>
                                        <option value="HalfDayPaidMorning">半日有給（午前）</option>
                                        <option value="HalfDayPaidAfternoon">半日有給（午後）</option>
                                    </select>
                                    <div v-for="vacation_type in attendanceForm.errors.vacation_type" :key="vacation_type.id">
                                        <InputError :message="vacation_type"/>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 w-1/3">
                                <div class="relative">
                                    <label for="break_minutes" class="leading-7 text-sm text-gray-600">休憩時間（分）</label>
                                    <input type="number" id="break_minutes" name="break_minutes" v-model="attendanceForm.break_minutes"
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    <div v-for="break_minutes in attendanceForm.errors.break_minutes" :key="break_minutes.id">
                                        <InputError :message="break_minutes"/>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 w-1/2">
                                <div class="relative">
                                    <label for="clock_in" class="leading-7 text-sm text-gray-600">出勤時間</label>
                                    <input type="time" id="clock_in" name="clock_in"  v-model="attendanceForm.clock_in"
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    <div v-for="clock_in in attendanceForm.errors.clock_in" :key="clock_in.id">
                                        <InputError :message="clock_in"/>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 w-1/2">
                                <div class="relative">
                                    <label for="clock_out" class="leading-7 text-sm text-gray-600">退勤時間</label>
                                    <input type="time" id="clock_out" name="clock_out" v-model="attendanceForm.clock_out"
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                    <div v-for="clock_out in attendanceForm.errors.clock_out" :key="clock_out.id">
                                        <InputError :message="clock_out"/>
                                    </div>
                                </div>
                            </div>

                            <div class="p-2 w-full">
                                <div class="relative">
                                    <label for="reason" class="leading-7 text-sm text-gray-600">備考</label>
                                    <textarea id="reason" name="reason"  v-model="attendanceForm.reason"
                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                                    <div v-for="reason in attendanceForm.errors.reason" :key="reason.id">
                                        <InputError :message="reason"/>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 w-full">
                                <button @click="stamp(props.stampData.data[0]?.id ?? null)"
                                    class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                                    承認申請
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>
