<template>
    <div class="flex justify-center items-center bg-gray-200 w-full h-28 md:h-28 text-white border-l-8">
        <div class="flex flex-col items-center justify-center pl-1 w-full h-full">
            <div class="flex sm:text-3xl"><slot name="value"></slot></div>
            <div class="mt-4 sm:mt-4 flex justify-center items-center gap-2">
                <div class="pl-1 flex items-center flex-grow w-8"><slot name="icon"></slot></div>
                <div class=""><slot name="label"></slot></div>
            </div>
        </div>
    </div>
</template>

