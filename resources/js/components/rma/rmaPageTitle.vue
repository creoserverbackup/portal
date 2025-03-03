<template>
    <div class="row row-column">
        <div class="col-md-7">
            <div class="rma__header-title">
                <h1 class="rma__heading support-center__heading">
                    {{ $t('DashboardRMATitle') }}
                </h1>
                <span class="rma__subtitle c-sc">{{ $t('DashboardRMASubtitle') }}</span>
                <small class="rma__heading-desc">{{ $t('DashboardRMADescription') }}</small>
                <small class="rma__heading-desc">{{ $t('DashboardRMADescription2') }}</small>
                <small class="rma__heading-desc">Email:
                    <a class="rma__heading-desc-href" href="mailto:info@creoserver.com">
                        <span class="c-sc">{{ $t('CreoServerEmail') }}</span>
                    </a>
                </small>
                <small class="rma__heading-desc">(Tel: 08)
                    <a class="rma__heading-desc-href" href="tel:+0858640777">
                        <span class="c-sc">{{ $t('CreoServerPhone') }}</span>
                    </a>
                </small>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "rmaPageTitle"
}
</script>

<style scoped>

</style>