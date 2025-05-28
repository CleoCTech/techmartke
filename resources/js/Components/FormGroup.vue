<template>
    <div >
        <div class="px-2 py-1">
            <slot name="label"></slot>
        </div>
        <div class="px-2">
            <slot name="value"></slot>
        </div>
    </div>

    <!-- <div>
        <label class="block text-sm font-medium mb-1" for="default">
            <slot name="label"></slot>
        </label>
        <input id="default" class="form-input w-full" type="text" />
    </div> -->
</template>
