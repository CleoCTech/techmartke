<script setup>
import { Link } from '@inertiajs/vue3';
import { List, Plus, Eye, Pencil, Upload } from 'lucide-vue-next';

defineProps({
    active: {
        type: String,
        default: 'list', // list | create | edit | show | bulk
    },
    productUuid: {
        type: String,
        default: null,
    },
});
</script>

<template>
    <div class="relative mb-6">
        <div class="absolute bottom-0 w-full h-px bg-slate-200 dark:bg-slate-700" aria-hidden="true"></div>
        <ul class="relative text-sm font-medium flex flex-nowrap px-4 sm:px-6 lg:px-8 overflow-x-auto no-scrollbar border-b border-slate-200 dark:border-slate-700">
            <!-- List -->
            <li class="mr-6">
                <Link
                    href="/admin/products"
                    class="relative py-3 whitespace-nowrap focus:outline-none group inline-flex items-center gap-1.5"
                    :class="active === 'list' ? 'text-indigo-500' : 'text-slate-500 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'"
                >
                    <List class="w-3.5 h-3.5" />
                    <span>List</span>
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 -mb-px transition-all duration-150 ease-in-out"
                        :class="active === 'list' ? 'bg-indigo-500' : 'bg-transparent group-hover:bg-slate-200 dark:group-hover:bg-slate-700'"
                    />
                </Link>
            </li>

            <!-- New -->
            <li class="mr-6">
                <Link
                    href="/admin/products/create"
                    class="relative py-3 whitespace-nowrap focus:outline-none group inline-flex items-center gap-1.5"
                    :class="active === 'create' ? 'text-indigo-500' : 'text-slate-500 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'"
                >
                    <Plus class="w-3.5 h-3.5" />
                    <span>New</span>
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 -mb-px transition-all duration-150 ease-in-out"
                        :class="active === 'create' ? 'bg-indigo-500' : 'bg-transparent group-hover:bg-slate-200 dark:group-hover:bg-slate-700'"
                    />
                </Link>
            </li>

            <!-- View (only when product selected) -->
            <li class="mr-6">
                <Link
                    :href="productUuid ? `/admin/products/show/${productUuid}` : '#'"
                    class="relative py-3 whitespace-nowrap focus:outline-none group inline-flex items-center gap-1.5"
                    :class="[
                        active === 'show' ? 'text-indigo-500' : 'text-slate-500 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300',
                        !productUuid ? 'opacity-40 cursor-not-allowed pointer-events-none' : ''
                    ]"
                >
                    <Eye class="w-3.5 h-3.5" />
                    <span>View</span>
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 -mb-px transition-all duration-150 ease-in-out"
                        :class="active === 'show' ? 'bg-indigo-500' : 'bg-transparent group-hover:bg-slate-200 dark:group-hover:bg-slate-700'"
                    />
                </Link>
            </li>

            <!-- Edit (only when product selected) -->
            <li class="mr-6">
                <Link
                    :href="productUuid ? `/admin/products/edit/${productUuid}` : '#'"
                    class="relative py-3 whitespace-nowrap focus:outline-none group inline-flex items-center gap-1.5"
                    :class="[
                        active === 'edit' ? 'text-indigo-500' : 'text-slate-500 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300',
                        !productUuid ? 'opacity-40 cursor-not-allowed pointer-events-none' : ''
                    ]"
                >
                    <Pencil class="w-3.5 h-3.5" />
                    <span>Edit</span>
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 -mb-px transition-all duration-150 ease-in-out"
                        :class="active === 'edit' ? 'bg-indigo-500' : 'bg-transparent group-hover:bg-slate-200 dark:group-hover:bg-slate-700'"
                    />
                </Link>
            </li>

            <!-- Bulk Upload -->
            <li class="mr-6">
                <Link
                    href="/admin/products/bulk-upload"
                    class="relative py-3 whitespace-nowrap focus:outline-none group inline-flex items-center gap-1.5"
                    :class="active === 'bulk' ? 'text-indigo-500' : 'text-slate-500 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-300'"
                >
                    <Upload class="w-3.5 h-3.5" />
                    <span>Bulk Upload</span>
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 -mb-px transition-all duration-150 ease-in-out"
                        :class="active === 'bulk' ? 'bg-indigo-500' : 'bg-transparent group-hover:bg-slate-200 dark:group-hover:bg-slate-700'"
                    />
                </Link>
            </li>
        </ul>
    </div>
</template>
