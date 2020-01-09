<template>
  <div class="description_background">
    <h1 class="header-1">{{title}}</h1>

    <p class="paragraph" v-html="description"></p>

    <span class="btn" :style="{color:color}" @click="clickGoToAnotherPlace">Go</span>
  </div>
</template>

<script>
export default {
  props: ["number"],
  data() {
    return {
      //roadにrouterview用の/forumとか入れてdescriptionにはそこがどんな所かの説明,titleにはそのまま行き先の名前
      description: "",
      road: "",
      title: "",
      color: ""
    };
  },
  created() {
    this.listen(this.number);
  },
  methods: {
    async clickGoToAnotherPlace(path) {
      await this.$eventBus.$emit("closeDescription");
      this.$router.push({ path: this.road });
    },
    listen(number) {
      //numberを一応数字に変換しておく
      switch (parseInt(number, 10)) {
        case 1:
          this.road = "/forum";
          this.title = "Forum";
          this.description = "selected menu is forum";
          this.color = "#00ff27";
          break;
        case 2:
          this.road = "/ask";
          this.title = "Ask Question";
          this.description = "selected menu is ask question";
          this.color = "#ff294d";
          break;
        case 3:
          //後で作る
          this.road = "/setting";
          this.title = "Setting";
          this.description = "selected menu is setting";
          this.color = "#17f1bb";
          break;
        case 4:
          this.road = "/welcome";
          this.title = "Home";
          this.description = "selected menu is home";
          this.color = "#fffb11";
          break;
        case 5:
          //後で作る
          this.road = "/userprofile";
          this.title = "Userprofile";
          this.description = "selected menu is userprofile";
          this.color = "#1234f7";
          break;
        case 6:
          this.road = "/category";
          this.title = "Category";
          this.description = "selected menu is category";
          this.color = "#fd0000";
          break;
        case 7:
          this.goto = "/login";
          this.title = "Lock";
          this.description =
            "&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp                          selected menu is lock<br><br><br><br>" +
            "if you want to unlock various plugin,<br><br>" +
            "please make sure to logged in.<br><br>" +
            "you can login form below navigation.";
          this.color = "#a972cb";
          break;
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.description_background {
  background-color: rgba(0, 0, 0, 0.5);
  width: 100vw;
  height: 100vh;
  padding: 10rem 5rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  clip-path: polygon(25% 0, 100% 0, 100% 100%, 0 100%);

  position: fixed;
}

.header-1 {
  font-size: 5rem;
  color: white;
  margin-top: 3rem;
  margin-left: 9rem;
}

.paragraph {
  font-size: 2.4rem;
  color: white;
  margin-top: 3rem;
  margin-bottom: auto;
  display: inline-block;
  margin-left: 9rem;
}
.btn {
  background: none;
  font-size: 3.5rem;
  border: 2px solid;
  line-height: 1;
  margin: 0.5em 0.5em 4em 9rem;
  padding: 1em 2em;
  display: inline-block;
}
</style>

