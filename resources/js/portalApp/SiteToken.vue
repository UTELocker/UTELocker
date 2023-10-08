<template>
    <slot></slot>
</template>
<script>
import useSiteToken from "./hooks/useSiteToken";
export default {
    name: "SiteToken",
    setup() {
        const { siteToken } = useSiteToken();
        return {
            siteToken
        }
    }
}
</script>
