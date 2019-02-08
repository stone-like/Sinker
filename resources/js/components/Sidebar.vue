<template>
    <nav class="sidebar">
        <ul class="side-nav">
            <li class="side-nav__item">
                <router-link class="side-nav__link">
                    <svg class="side-nav__icon">
                         <use xlink:href="../Helpers/img/sprite.svg#icon-brush"></use>
                    </svg>
                     <span>Forum</span>
                    </router-link>
            </li>
            <li class="side-nav__item">
                <router-link class="side-nav__link">
                    <svg class="side-nav__icon">
                         <use xlink:href="../Helpers/img/sprite.svg#icon-chat" v-if="userLoggedIn"></use>
               <use xlink:href="../Helpers/img/sprite.svg#icon-lock" v-else></use>
                    </svg>
                    <span>Ask Question</span>
                </router-link>
            </li>
            <li class="side-nav__item">
                <router-link class="side-nav__link">
                    <svg class="side-nav__icon">
                        <use xlink:href="../Helpers/img/sprite.svg#icon-cog" v-if="userLoggedIn"></use>
               <use xlink:href="../Helpers/img/sprite.svg#icon-lock" v-else></use>
                    </svg>
                    <span>Setting</span>
                </router-link>
            </li>
            <li class="side-nav__item">
                <router-link class="side-nav__link">
                    <svg class="side-nav__icon">
                         <use xlink:href="../Helpers/img/sprite.svg#icon-home"></use>
                    </svg>
                    <span>Home</span>
                </router-link>
            </li>
            <li class="side-nav__item">
                <router-link class="side-nav__link">
                    <svg class="side-nav__icon">
                         <use xlink:href="../Helpers/img/sprite.svg#icon-v-card" v-if="userLoggedIn"></use>
               <use xlink:href="../Helpers/img/sprite.svg#icon-lock" v-else></use>
                    </svg>
                     <span>User Profile</span>
                </router-link>
            </li>
            <li class="side-nav__item">
                <router-link class="side-nav__link">
                    <svg class="side-nav__icon">
                         <use xlink:href="../Helpers/img/sprite.svg#icon-text-document" v-if="userLoggedIn"></use>
               <use xlink:href="../Helpers/img/sprite.svg#icon-lock" v-else></use>
                    </svg>
                     <span>Category</span>
                </router-link>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {

}
</script>

<style lang="scss">
.sidebar{
    background-color: #fff;
    display:flex;
    flex-direction: column;
    justify-content: space-between;
}
.side-nav{
  font-size: 1.4rem;
  list-style: none;
  margin-top:3.5rem;
}
</style>
