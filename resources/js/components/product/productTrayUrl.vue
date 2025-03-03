<template>
    <div class="product-main__config-select-item-col product-main__config-select-item-col--btn">
        <div class="product-main__config-look-product">
            <a :href="tray.urlSelect" target="_blank">
                <span class="product-main__d product-main__d--none product-main__d--2xl-block text-nowrap">Bekijk dit product</span>
                <span class="product-main__d product-main__d--2xl-none">
                    <svg width="20" height="20" viewBox="0 0 488 488" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_5_2)">
                            <path d="M243.925 98.5249C150.725 98.5249 66.225 149.425 3.825 232.325C-1.275 239.125 -1.275 248.525 3.825 255.325C66.225 338.325 150.725 389.325 243.925 389.325C337.125 389.325 421.625 338.425 484.025 255.525C489.125 248.725 489.125 239.325 484.025 232.525C421.625 149.525 337.125 98.5249 243.925 98.5249ZM250.625 346.325C188.725 350.225 137.725 299.225 141.625 237.325C144.825 186.325 186.225 144.825 237.325 141.625C299.225 137.725 350.225 188.725 346.325 250.625C343.025 301.625 301.625 343.025 250.625 346.325Z"/>
                            <path d="M247.525 299.025C214.225 301.125 186.725 273.625 188.825 240.325C190.525 212.825 212.825 190.525 240.325 188.825C273.625 186.725 301.125 214.225 299.025 247.525C297.325 275.025 275.025 297.325 247.525 299.025Z"/>                                            </g>
                        <defs>
                            <clipPath id="clip0_5_2">
                                <rect width="487.85" height="487.85" fill="white"/>
                            </clipPath>
                        </defs>
                    </svg>
                </span>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    name: "productTrayUrl",
    props: {
        tray: {},
    },
}
</script>

<style scoped>

</style>