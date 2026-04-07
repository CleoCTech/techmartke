<template>
    <div>
        <Head title="Company Information" />
        <x-create-edit-template :setup="setup" :selected="selected">
            <form v-on:submit.prevent="submit" action="#" method="POST" enctype="multipart/form-data" class="space-y-8">

                <!-- ============ BASIC INFORMATION ============ -->
                <section>
                    <div class="border-b border-slate-200 dark:border-slate-700 pb-2 mb-4">
                        <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Basic Information</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Company Name <span class="text-red-500">*</span></label>
                            <input v-model="form.name" type="text" required
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Short Name</label>
                            <input v-model="form.shortName" type="text" placeholder="e.g. TechMartKE"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Motto / Tagline</label>
                            <input v-model="form.motto" type="text" placeholder="e.g. Smart Shopping, Better Decisions"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue" />
                        </div>
                    </div>
                </section>

                <!-- ============ CONTACT INFORMATION ============ -->
                <section>
                    <div class="border-b border-slate-200 dark:border-slate-700 pb-2 mb-4">
                        <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Contact Information</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">This phone number is used for WhatsApp and Call buttons across the entire site</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Phone Number (WhatsApp & Calls) <span class="text-red-500">*</span></label>
                            <input v-model="form.phoneNumbers" type="text" required placeholder="254700000000"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue" />
                            <p class="text-[10px] text-slate-400 mt-1">Format: 254XXXXXXXXX (no spaces or +)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email <span class="text-red-500">*</span></label>
                            <input v-model="form.emails" type="email" required placeholder="info@techmart.ke"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Physical Address</label>
                            <input v-model="form.address" type="text" placeholder="e.g. Moi Avenue, Nairobi CBD"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Location</label>
                            <input v-model="form.location" type="text" placeholder="e.g. Nairobi, Kenya"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue" />
                        </div>
                    </div>
                </section>

                <!-- ============ ABOUT US ============ -->
                <section>
                    <div class="border-b border-slate-200 dark:border-slate-700 pb-2 mb-4">
                        <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">About Us</h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Short Description (1 sentence)</label>
                            <input v-model="form.about_short" type="text" placeholder="Kenya's trusted phone & computer marketplace"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 text-sm focus:ring-ablue focus:border-ablue" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Full About</label>
                            <ckeditor v-cloak :editor="editor" v-model="form.about" @ready="cardData != null ? form.about = cardData.about : ''"></ckeditor>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mission</label>
                                <ckeditor v-cloak :editor="editor" v-model="form.mission" @ready="cardData != null ? form.mission = cardData.mission : ''"></ckeditor>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Vision</label>
                                <ckeditor v-cloak :editor="editor" v-model="form.vision" @ready="cardData != null ? form.vision = cardData.vision : ''"></ckeditor>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Core Values</label>
                            <ckeditor v-cloak :editor="editor" v-model="form.core_values" @ready="cardData != null ? form.core_values = cardData.core_values : ''"></ckeditor>
                        </div>
                    </div>
                </section>

                <!-- ============ BRANDING ============ -->
                <section>
                    <div class="border-b border-slate-200 dark:border-slate-700 pb-2 mb-4">
                        <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">Branding</h3>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Logo</label>
                        <div class="flex items-center gap-4">
                            <img v-if="show_image == '' && form.logo != null"
                                :src="$page.props.storagePaths[setup.settings.storageName].images.readPath + form.logo"
                                class="h-20 w-20 rounded-lg border border-slate-200 dark:border-slate-700 object-contain bg-white" />
                            <img v-if="show_image != ''" :src="show_image" class="h-20 w-20 rounded-lg border border-slate-200 dark:border-slate-700 object-contain bg-white" />
                            <input type="file" @input="onFileChange($event, form.logo, show_image)" ref="input"
                                class="text-sm text-slate-700 dark:text-slate-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-slate-100 dark:file:bg-slate-700 file:text-sm file:font-medium hover:file:bg-slate-200 cursor-pointer" />
                        </div>
                    </div>
                </section>

                <!-- Submit -->
                <div class="flex justify-end pt-4 border-t border-slate-200 dark:border-slate-700">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2.5 bg-ablue border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:ring-2 focus:ring-ablue focus:ring-offset-2 transition">
                        Save Changes
                    </button>
                </div>

            </form>
        </x-create-edit-template>
    </div>
</template>

<script setup>
import { createEditProps, useCreateEdit } from '@/Composables/useCreateEdit'
import { ref, reactive } from 'vue'

const props = defineProps({
    ...createEditProps,
})

const form = reactive({
    uuid: null,
    name: null,
    shortName: null,
    motto: null,
    phoneNumbers: null,
    emails: null,
    address: null,
    location: null,
    about_short: null,
    about: null,
    mission: null,
    vision: null,
    core_values: null,
    logo: null,
})

const show_image = ref('')

function setData() {
    if (props.cardData != null && props.cardData.uuid != null) {
        form.uuid = props.cardData.uuid
        form.name = props.cardData.company_name
        form.shortName = props.cardData.short_name
        form.motto = props.cardData.motto
        form.phoneNumbers = props.cardData.phone_numbers
        form.emails = props.cardData.emails
        form.address = props.cardData.address
        form.location = props.cardData.location
        form.about_short = props.cardData.about_short
        form.about = props.cardData.about
        form.mission = props.cardData.mission
        form.vision = props.cardData.vision
        form.core_values = props.cardData.core_values
        form.logo = props.cardData.logo
    }
}

const { editor, submit, onFileChange, ckeditor, xCreateEditTemplate, TextInput } = useCreateEdit(props, setData, form)
</script>
