<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
</script>

<template>
    <Head title="Netping" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Netping (обновление раз в 20 сек)</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <PrimaryButton>
                    Добавить точку
                </PrimaryButton>
                <table class="border-collapse w-full mt-3" id="main_table">
                    <thead>
                    <tr >
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            Имя точки</th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            Питание</th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            Охрана</th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            Дверь</th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            Сирена</th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            IP точки</th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            Действия</th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            Камера</th>
                        <th
                            class="p-3 font-bold uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400 text-gray-600 border border-gray-300 hidden lg:table-cell">
                            T°</th>


                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-500 dark:divide-gray-600">
                    <tr>
                        <td
                            class="w-full lg:w-auto p-3 text-gray-800 dark:text-gray-100 text-left border border-b block lg:table-cell relative lg:static">

                            <a href="#">
                                <svg style="float:right; display:inline-block; width: 10%;"
                                     xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
