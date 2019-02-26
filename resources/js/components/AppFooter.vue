<template>
 <div class="footer_wrapper">
    <div class="enterprise">&copy; {{ new Date().getFullYear() }} sinker</div>
 </div>
</template>

<script>
export default {

}
</script>

<style lang="scss" scoped>
.footer_wrapper{
    width: 100%;
    height: 100%;
    background-color: #363636;
    display: flex;
    position: relative;
}

.enterprise{
    color: azure;
    font-size: 1.6rem;
    position: absolute;
    top: 50%;
    right: 0%;
    transform: translate(-50%,-50%);
}
</style>
