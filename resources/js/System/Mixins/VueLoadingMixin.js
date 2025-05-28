export default {
    setup() {
        return {
            fullPage: true,
            fullPageLoader: null,
        }
    },
    methods: {
        loadingShowFullPage() {
            this.fullPageLoader = this.$loading.show({
                canCancel: false,
            });
        },
        loadingHideFullPage() {
            this.fullPageLoader.hide();
        }
    }
}