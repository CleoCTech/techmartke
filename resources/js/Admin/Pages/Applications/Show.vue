<template>
    <div>
        <Head :title="cardData.first_name" />
        <x-show-template :setup="setup" :selected="selected">
            <x-grid>
                <!-- Personal Information -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>First Name</template>
                        <template #value>{{cardData.first_name}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Last Name</template>
                        <template #value>{{cardData.last_name}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Email</template>
                        <template #value>{{cardData.email}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Phone</template>
                        <template #value>{{cardData.phone}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Date of Birth</template>
                        <template #value>{{cardData.date_of_birth}}</template>
                    </x-show-group>
                </x-grid-col>
                
                <!-- Program Information -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Program</template>
                        <template #value>{{cardData.program}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Start Date</template>
                        <template #value>{{cardData.start_date}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Study Format</template>
                        <template #value>{{cardData.study_format}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Career Goals</template>
                        <template #value>{{cardData.career_goals}}</template>
                    </x-show-group>
                </x-grid-col>
                
                <!-- Additional Information -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>How did you hear about us?</template>
                        <template #value>{{cardData.hear_about_us || 'Not specified'}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Additional Information</template>
                        <template #value>{{cardData.additional_info || 'None provided'}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Terms Accepted</template>
                        <template #value>{{cardData.terms_accepted ? 'Yes' : 'No'}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Marketing Consent</template>
                        <template #value>{{cardData.marketing_consent ? 'Yes' : 'No'}}</template>
                    </x-show-group>
                </x-grid-col>
                
                <!-- Status and Dates -->
                <x-grid-col>
                    <x-show-group>
                        <template #label>Status</template>
                        <template #value>
                            <x-badge :class="setup.statuses[setup.statuses.findIndex(x => x.id ===cardData.status)].color">{{setup.statuses[setup.statuses.findIndex(x => x.id ===cardData.status)].caption}}</x-badge>
                        </template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Date Created</template>
                        <template #value>{{cardData.created_at}}</template>
                    </x-show-group>
                </x-grid-col>
                <x-grid-col>
                    <x-show-group>
                        <template #label>Last Updated</template>
                        <template #value>{{cardData.updated_at}}</template>
                    </x-show-group>
                </x-grid-col>
            </x-grid>
        </x-show-template>
    </div>
</template>
<script setup>
    import { showProps, useShow } from '@/Composables/useShow.js'
    const props = defineProps(showProps)
    const {    
        xGrid,
        xGridCol,
        xLoading,
        xPanel,
        xShowGroup,
        xBadge,
        xShowTemplate
    } = useShow(props)
</script>