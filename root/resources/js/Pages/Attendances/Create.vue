<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { onMounted, reactive, ref } from 'vue';

const props = defineProps({
    stampData : Object,
});

console.log(props.stampData.clock_in);

const stamp = ref('');

if (props.stampData && props.stampData.clock_in && props.stampData.clock_out)
{
    stamp.value = '本日の打刻完了';
}
else if(props.stampData.clock_in && !props.stampData.clock_out)
{
    stamp.value = `本日の出勤打刻は ${props.stampData.clock_in} に完了`;
}
else
{
    stamp.value = '本日の出勤打刻はまだありません';
}

const getDate = () => {
    const nowDate = new Date();
    const year = nowDate.getFullYear();
    const month = String(nowDate.getMonth() + 1).padStart(2, '0');
    const day = String(nowDate.getDate()).padStart(2, '0');

    const hours = String(nowDate.getHours()).padStart(2, '0');
    const minutes = String(nowDate.getMinutes()).padStart(2, '0');
    const seconds = String(nowDate.getSeconds()).padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};

const date = ref('');
onMounted(() => {
    date.value = getDate();
});

const clockInStamp = () => {
    router.post(route('attendances.store'), { 'clock_in' :getDate()}, {
        onSuccess: () => {
            emit('close');
        },
        onError: () => {
            console.warn('バリデーションエラーが発生しました');
        }
    });
};

const clockOutStamp = () => {
    router.post(route('attendances.store'), { 'clock_out' :getDate()}, {
        onSuccess: () => {
            emit('close');
        },
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
                                    <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">勤怠打刻</h1>
                                    <p class="sm:text-2xl text-2xl font-medium title-font text-gray-900">{{ stamp }}</p>
                                </div>
                                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                    <div class="flex flex-wrap -m-2">
                                        <div class="p-2 w-1/2">
                                            <div class="relative">
                                                <button :disabled="props.stampData.clock_in" @click="clockInStamp"
                                                    class="flex mx-auto text-white bg-gray-500 border-0 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg
                                                        disabled:opacity-40 disabled:cursor-not-allowed">
                                                    出勤
                                                </button>
                                            </div>
                                        </div>
                                        <div class="p-2 w-1/2">
                                            <div class="relative">
                                                <button :disabled="props.stampData.clock_in" @click="clockOutStamp"
                                                    class="flex mx-auto text-white bg-gray-500 border-0 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg
                                                        disabled:opacity-40 disabled:cursor-not-allowed">
                                                    退勤
                                                </button>
                                            </div>
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
