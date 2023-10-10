<template>
    <h1>
        <a id="logo" href="/">
            <img alt="logo" src="https://next.antdv.com/assets/logo.1ef800a8.svg" />
            UTELocker
        </a>
    </h1>
</template>
<script>
export default {
    name: "Logo",
}
</script>
<style scoped lang="scss">
#logo {
    height: var(--header-height);
    padding-left: 40px;
    overflow: hidden;
    color: var(--site-text-color);
    font-size: 18px;
    line-height: var(--header-height);
    white-space: nowrap;
    text-decoration: none;

    img {
        position: relative;
        top: -1.5px;
        height: 32px;
        margin-right: 16px;
    }
}

@media only screen and (max-width: 767.99px) {
    #logo {
        padding-right: 0;
        padding-left: 0;
    }
}
</style>
