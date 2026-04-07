import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useCompanyInfo() {
    const page = usePage();

    const companyInfo = computed(() => page.props.companyInfo || {});

    // Clean phone number for tel: and wa.me links (remove spaces, dashes, +)
    const phoneClean = computed(() => {
        const raw = companyInfo.value.phone_numbers || '254700000000';
        return raw.replace(/[^0-9]/g, '');
    });

    // Pretty phone for display
    const phoneDisplay = computed(() => {
        const num = phoneClean.value;
        if (num.startsWith('254') && num.length === 12) {
            return `+${num.slice(0, 3)} ${num.slice(3, 6)} ${num.slice(6, 9)} ${num.slice(9)}`;
        }
        return companyInfo.value.phone_numbers || '+254 700 000 000';
    });

    const whatsappUrl = (message = '') => {
        const text = message ? `?text=${encodeURIComponent(message)}` : '';
        return `https://wa.me/${phoneClean.value}${text}`;
    };

    const callUrl = computed(() => `tel:+${phoneClean.value}`);

    const email = computed(() => companyInfo.value.emails || 'info@techmart.ke');
    const address = computed(() => companyInfo.value.address || 'Nairobi, Kenya');
    const companyName = computed(() => companyInfo.value.company_name || 'TechMart Kenya');
    const shortName = computed(() => companyInfo.value.short_name || 'TechMartKE');

    return {
        companyInfo,
        phoneClean,
        phoneDisplay,
        whatsappUrl,
        callUrl,
        email,
        address,
        companyName,
        shortName,
    };
}
