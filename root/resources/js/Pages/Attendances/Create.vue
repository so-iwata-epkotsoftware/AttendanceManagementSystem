<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { onMounted, reactive, ref } from 'vue';

import InputError from '@/Components/InputError.vue';

const props = defineProps({
    today : String,
    user : Object,
    stampData : Object,
    errors : Object,
});

const stamp = ref('');

if (props.stampData && props.stampData.clock_in && props.stampData.clock_out)
{
    stamp.value = '本日の打刻完了';
}
else if(props.stampData?.clock_in && !props.stampData?.clock_out)
{
    stamp.value = `本日の出勤打刻は ${props.stampData.clock_in} に完了`;
}
else
{
    stamp.value = '本日の出勤打刻はまだしていません';
}

const getDate = () => {
    const nowDate = new Date();
    const hours = String(nowDate.getHours()).padStart(2, '0');
    const minutes = String(nowDate.getMinutes()).padStart(2, '0');
    return `${hours}:${minutes}`;
};

const form = reactive({
    id : props.stampData?.id ?? null,
    date : props.today,
    user_id : props.user.id,
    company_id : props.user.company_id,
    clock_in : props.stampData?.clock_in ?? null,
    clock_out : props.stampData?.clock_out ?? null,
    break_minutes : props.stampData?.break_minutes ?? 60,
    vacation_type : props.stampData?.vacation.vacation_type ?? null,
    reason : props.stampData?.attendance_status.reason ?? null,
});


const date = ref('');
onMounted(() => {
    date.value = getDate();
});

const clockInStamp = () => {
    form.clock_in = getDate();

    router.post(route('attendances.store'), form, {
        onError: () => {
            console.warn('バリデーションエラーが発生しました');
        }
    });
};

const clockOutStamp = () => {
    form.clock_out = getDate();

    router.post(route('attendances.update', form.id), form, {
        onError: () => {
            console.warn('バリデーションエラーが発生しました');
        }
    });
};

</script>

<template>
    <Head title="勤怠打刻" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                勤怠打刻
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg w-1/2 mx-auto" >
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font relative">
                            <div class="container px-5 mx-auto pb-10">
                                <div class="flex flex-col text-center w-full mb-12">
                                    <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">{{ props.today }}勤怠打刻</h1>
                                    <p class="sm:text-2xl text-2xl font-medium title-font text-gray-900">{{ stamp }}</p>
                                </div>

                                <div class="lg:w-1/2 md:w-2/3 mx-auto flex flex-wrap m-4">
                                    <div class="p-2 lg:w-1/2">
                                        <div class="relative">
                                            <label for="vacation_type" class="leading-7 text-sm text-gray-600">出勤区分</label>
                                            <select name="vacation_type" id="vacation_type" v-model="form.vacation_type"
                                                class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                <option value="">-</option>
                                                <option value="RegularAttendance">通常出勤</option>
                                                <option value="LateArrival">遅刻</option>
                                                <option value="EarlyLeaving">早退</option>
                                                <option value="HolidayWork">休日出勤</option>
                                                <option value="CompensatedLeave">振替出勤</option>
                                                <option value="StaggeredWork">時差出勤</option>
                                                <option value="Telecommuting">在宅勤務</option>
                                                <option value="BusinessTravel">出張</option>
                                            </select>
                                            <InputError :message="props.errors?.vacation_type"/>
                                        </div>
                                    </div>
                                    <div class="p-2 lg:w-1/2">
                                        <div class="relative">
                                            <label for="break_minutes" class="leading-7 text-sm text-gray-600" >休憩時間（分）</label>
                                            <input type="number" id="break_minutes" name="break_minutes" v-model="form.break_minutes"
                                                class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                            <InputError :message="props.errors?.break_minutes"/>
                                        </div>
                                    </div>

                                </div>

                                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                    <div class="flex flex-wrap -m-2">
                                        <div class="p-2 w-1/2">
                                            <div class="relative">
                                                <button :disabled="props.stampData?.clock_in" @click="clockInStamp"
                                                    class="flex mx-auto text-white bg-gray-500 border-0 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg
                                                        disabled:opacity-40 disabled:cursor-not-allowed">
                                                    出勤
                                                </button>
                                            </div>
                                        </div>
                                        <div class="p-2 w-1/2">
                                            <div class="relative">
                                                <button :disabled="props.stampData?.clock_out" @click="clockOutStamp"
                                                    class="flex mx-auto text-white bg-gray-500 border-0 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg
                                                        disabled:opacity-40 disabled:cursor-not-allowed">
                                                    退勤
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="lg:w-3/4 md:w-2/3 mx-auto">
                                    <div class="p-2 w-full">
                                        <div class="relative">
                                            <label for="reason" class="leading-7 text-sm text-gray-600">備考</label>
                                            <textarea id="reason" name="reason" v-model="form.reason"
                                                class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                                            <InputError :message="props.errors?.reason"/>
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
