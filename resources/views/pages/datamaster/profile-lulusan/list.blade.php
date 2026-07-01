@extends('pages.datamaster.index')

@section('content')
    <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Profile Lulusan</h3>
    <div class="flex justify-between mb-3">
        <div class="relative flex w-full max-w-4xl flex-col gap-1 text-neutral-600 dark:text-neutral-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                aria-hidden="true"
                class="absolute left-2.5 top-1/2 size-5 -translate-y-1/2 text-neutral-600/50 dark:text-neutral-300/50">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="search"
                class="w-full rounded-sm border border-neutral-300 bg-neutral-50 py-2 pl-10 pr-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black disabled:cursor-not-allowed disabled:opacity-75 dark:border-neutral-700 dark:bg-neutral-900/50 dark:focus-visible:outline-white"
                name="search" placeholder="Search" aria-label="search" />
        </div>
        <button type="button"
            class="inline-flex justify-center items-center gap-2 whitespace-nowrap rounded-sm bg-sky-500 border border-sky-500 dark:border-sky-500 px-4 py-2 text-sm font-medium tracking-wide text-white transition hover:opacity-75 text-center focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500 active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-sky-500 dark:text-white dark:focus-visible:outline-sky-500">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                class="size-5 fill-white dark:fill-white" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z"
                    clip-rule="evenodd" />
            </svg>
            Create
        </button>
    </div>
    <div class="relative overflow-visible w-full rounded-sm border border-neutral-300 dark:border-neutral-700">
        <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300 overflow-visible">
            <thead
                class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                <tr>
                    <th scope="col" class="p-4">No</th>
                    <th scope="col" class="p-4">Program Studi</th>
                    <th scope="col" class="p-4">Kode PL</th>
                    <th scope="col" class="p-4">Profile Lulusan</th>
                    <th scope="col" class="p-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                <tr>
                    <td class="p-4">2335</td>
                    <td class="p-4">Alice Brown</td>
                    <td class="p-4">alice.brown@gmail.com</td>
                    <td class="p-4">alice.brown@gmail.com</td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            <button aria-label="create something epic" type="button"
                                class="inline-flex justify-center items-center aspect-square whitespace-nowrap rounded bg-black p-2 text-xs font-medium tracking-wide text-neutral-100 transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-white dark:text-black dark:focus-visible:outline-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>

                            </button>
                            <button aria-label="create something epic" type="button"
                                class="inline-flex justify-center items-center aspect-square whitespace-nowrap rounded bg-red-500 p-2 text-xs font-medium tracking-wide text-white transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-red-500 dark:text-white dark:focus-visible:outline-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>

                            </button>
                        </div>
                    </td>

                </tr>
            </tbody>
        </table>

    </div>
    <div class="w-full my-5 flex justify-center">
        <nav aria-label="pagination">
            <ul class="flex shrink-0 items-center gap-2 text-sm font-medium">
                <li>
                    <a href="#"
                        class="flex items-center rounded-sm p-1 text-neutral-600 hover:text-black dark:text-neutral-300 dark:hover:text-white"
                        aria-label="previous page">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                            class="size-6">
                            <path fill-rule="evenodd"
                                d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                clip-rule="evenodd" />
                        </svg>
                        Previous
                    </a>
                </li>
                <li><a href="#"
                        class="flex size-6 items-center justify-center rounded-sm p-1 text-neutral-600 hover:text-black dark:text-neutral-300 dark:hover:text-white"
                        aria-label="page 1">1</a></li>
                <li><a href="#"
                        class="flex size-6 items-center justify-center rounded-sm bg-black p-1 font-bold text-neutral-100 dark:bg-white dark:text-black"
                        aria-current="page" aria-label="page 2">2</a></li>
                <li><a href="#"
                        class="flex size-6 items-center justify-center rounded-sm p-1 text-neutral-600 hover:text-black dark:text-neutral-300 dark:hover:text-white"
                        aria-label="page 3">3</a></li>
                <li><a href="#"
                        class="flex size-6 items-center justify-center rounded-sm p-1 text-neutral-600 hover:text-black dark:text-neutral-300 dark:hover:text-white"
                        aria-label="page 4">4</a></li>
                <li>
                    <a href="#"
                        class="flex items-center rounded-sm p-1 text-neutral-600 hover:text-black dark:text-neutral-300 dark:hover:text-white"
                        aria-label="next page">
                        Next
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                            class="size-6">
                            <path fill-rule="evenodd"
                                d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
@endsection
