<template>
  <div class="mycontainer" ref="entire_forum">
    <div class="message_wrapper" key="message_wrapper">
      <h1 class="route_message">Forum</h1>

      <div class="bottom_line"></div>
    </div>
    <div class="main_wrapper">
      <v-flex xs9 class="rightflex">
        <nav class="page_wrapper">
          <ul class="pagenation">
            <li class="page-item">
              <a @click="first" class="page-link" href="#">&laquo;</a>
            </li>
            <li class="page-item">
              <a @click="prev" class="page-link" href="#">&lt;</a>
            </li>

            <li
              v-for="i in displayPageRange"
              class="page-item"
              :class="{active: i-1 === currentPage}"
              :key="i"
            >
              <a @click="pageSelect(i)" class="page-link" href="#">{{i}}</a>
            </li>

            <li class="page-item">
              <a @click="next" class="page-link" href="#">&gt;</a>
            </li>
            <li class="page-item">
              <a @click="last" class="page-link" href="#">&raquo;</a>
            </li>
          </ul>
        </nav>
        <transition-group tag="ul" class="grid_ul" name="fade" mode="out-in" appear>
          <question
            v-for="(question,index) in displayItems"
            :key="question.path"
            　:throw_question="question"
            :class="'question_items question_items'+ index"
          ></question>
        </transition-group>
      </v-flex>
      <v-flex xs3 class="app-sidebar">
        <app-sidebar></app-sidebar>
      </v-flex>
    </div>
  </div>
</template>

<script>
import Question from "./Question";
import AppSidebar from "./AppSidebar";
import { TweenMax, bezier, DirectionalRotationPlugin, CSSPlugin } from "gsap";
export default {
  data() {
    return {
      questions: {}, //表示データ
      currentPage: 0,
      size: 12, //1ページ当たりの個数
      pageRange: 10 //一回に表示されるのはのは10個まで(1,2,...10)とか(2,3,....11)とか
    };
  },
  components: { Question, AppSidebar },
  created() {
    axios
      .get("api/question")
      .then(res => (this.questions = res.data.data))
      .catch(error => console.log(error.response.data));

    this.listen();
  },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      var self = vm;
      self.$eventBus.$emit("changeGridUser", true);
      //sidebarのactiveを外したりつけたり処理するここはforumなのでactiveをforumにつけてfrom.pathの所を外す
      if (
        from.path == "/ask" ||
        from.path == "/category" ||
        from.path == "/setting" ||
        from.path == "/userprofile" ||
        from.path == "/bookmark" ||
        from.path == "/search" ||
        from.name == "read"
      ) {
        self.$store.dispatch("changeTransition_Router", "ReadToRead_enter");
        self.$store.dispatch("changeTransition_Tool", "wipe");
        //ReadToReadだからsidebarのchangeはいらない
        self.$store.dispatch("changeToolRead", true);
        var tm0 = new TimelineMax();

        tm0
          .to("html", 0.0001, {
            onStart: function() {
              //時系列を作る代わりの者があればそっちを使ったほうがいいはず
              //こっちは覆いを外す側でまず外すのが最初
              self.$eventBus.$emit("wipeEffectRemove");
              //  self.$eventBus.$emit("entireFade","visible")
            }
          })
          .add("scene1")
          .to(
            "html",
            0.0001,
            {
              onStart: function() {
                //覆いを外したらsidebarをだす

                self.$eventBus.$emit("changeSidebarMode", "/forum");
              }
            },
            "scene1+=0.00001"
          );
      } else if (from.path == "/welcome") {
        self.$store.dispatch("changeTransition_Router", "HomeToRead");
        self.$store.dispatch("changeTransition_Tool", "fade-side");

        self.$store.dispatch("changeToolRead", true);
        var tm0 = new TimelineMax();
        tm0
          .to(self.$refs.entire_forum, 0.0001, {
            onStart: function() {
              self.$eventBus.$emit("changeSidebarMode", "/forum");
              self.$eventBus.$emit("entireFade", "visible");
            }
          })
          .add("scene1");
      }
    });
  },
  beforeRouteLeave(to, from, next) {
    var self = this;
    //   self.$eventBus.$emit("changeGridUser",false)
    if (
      to.path == "/ask" ||
      to.path == "/category" ||
      to.path == "/setting" ||
      to.path == "/userprofile" ||
      to.path == "/bookmark"
    ) {
      self.$eventBus.$emit("changeGridUser", false);
      self.$store.dispatch("changeTransition_Router", "ReadToRead_leave");
      self.$store.dispatch("changeTransition_Tool", "wipe");

      var tm0 = new TimelineMax();

      tm0
        .to(self.$refs.entire_forum, 0.00000001, {
          onStart: function() {
            self.$eventBus.$emit("changeSidebarMode", to.path);
          }
        })
        .add("scene1")
        .to(
          self.$refs.entire_forum,
          0.000001,
          {
            onStart: function() {
              //ここでwipeするときの色と文字を送る
              if (to.path == "/category") {
                var wipe_array = { name: "CATEGORY", color: "#f39c12" };
              } else if (to.path == "/ask") {
                var wipe_array = { name: "QUESTION", color: "#c0392b" };
              } else if (to.path == "/setting") {
                var wipe_array = { name: "SETTING", color: "#FDA7DF" };
              } else if (to.path == "/userprofile") {
                var wipe_array = { name: "USERPROFILE", color: "#8e44ad" };
              } else if (to.path == "/bookmark") {
                var wipe_array = { name: "BOOKMARK", color: "#2ecc71" };
              }
              self.$eventBus.$emit("wipeEffectStart", wipe_array);
              //sidebarが引っ込んだらこれをapphomeに送って一面を覆う
            }
          },
          "scene1+=0.000001"
        )
        .add("scene2")
        .to(
          self.$refs.entire_forum,
          0.00000001,
          {
            onStart: function() {
              //完全にアニメーションが終わったらv-ifでsidebarを消す(ただし今回はReadToReadなのでtoolbarに切り替える必要はないので消さなくていい)
              self.$store.dispatch("changeToolRead", false);
              next();
            }
          },
          "scene2+=1"
        );
    } else if (to.path == "/welcome") {
      self.$store.dispatch("changeTransition_Router", "ReadToHome");
      self.$store.dispatch("changeTransition_Tool", "fade-side");

      var tm0 = new TimelineMax();

      tm0
        .to(self.$refs.entire_forum, 0.00000001, {
          onStart: function() {
            self.$eventBus.$emit("changeSidebarMode");
            self.$eventBus.$emit("entireFade", "invisible");
          }
        })
        .add("scene1")
        .to(
          self.$refs.entire_forum,
          0.00000001,
          {
            onStart: function() {
              self.$store.dispatch("changeToolRead", false);
              next();
            }
          },
          "scene1+=1"
        );
    } else if (to.name == "read") {
      //doneCardEventを使うのはforumだけ
      self.$eventBus.$on("doneCardEvent", () => {
        next();
      });
    } else {
      next();
    }

    next(false);
  },

  computed: {
    pages() {
      //何ページになるか取得
      return Math.ceil(this.questions.length / this.size);
    },
    displayPageRange() {
      //currentpageをaタグによるクリックから変えることによりそれに合わせてcomputedで再計算され描画も自動で変わる
      //表示するページ番号の配列(1~10までとか3~12までとか)
      const half = Math.ceil(this.pageRange / 2);
      let start, end;

      if (this.pages < this.pageRange) {
        //もしpaginationのrangeよりページ数が少なかったら
        start = 1;
        end = this.pages;
      } else if (this.currentPage < half) {
        //10ページの半分5までは普通に進み、一回当たりの表示限界の半分を超えたら常にそのページが中央に来るようにする、つまりstartがどんどん2,3...とずれていく、最後の5個もそのまま中央には来ない,最初と最後の5個以外は常に中央

        //最初の5個はstartが１
        start = 1;
        end = start + this.pageRange - 1;
      } else if (this.pages - half < this.currentPage) {
        //last5ページ

        end = this.pages;
        start = end - this.pageRange + 1;
      } else {
        //それ以外はcurrentpageが中心でstratとendはそれに合わせる
        start = this.currentPage - half + 1;
        end = this.currentPage + half;
      }

      let indexes = [];
      for (let i = start; i <= end; i++) {
        indexes.push(i);
      }

      return indexes;
    },
    displayItems() {
      //currentpageをaタグによるクリックから変えることによりそれに合わせてcomputedで再計算され描画も自動で変わる
      //1ページ当たりで表示する質問を取得,最初はcurrentPage0だからquestion全体を0~11まで切り取り、次はcurrentPage1で12~23まで切り取る
      const head = this.currentPage * this.size;
      return this.questions.slice(head, head + this.size); //(0,12)だと0から12個目の前、つまり11個なので0,1,2~11までを切り取る
    },
    isSelected(page) {
      //今いるページか判定
      return page - 1 === this.currentPage;
    }
  },
  methods: {
    first() {
      //pageの先頭へ
      this.currentPage = 0;
      this.selectHandler();
    },
    last() {
      //最後尾へ
      this.currentPage = this.pages - 1; //全体のpageが1~10としたらcurtrentPageは0から始まるのでpageとcurrentPageのずれをなくす
      this.selectHandler();
    },
    prev() {
      if (0 < this.currentPage) {
        //最初のページでなければ
        this.currentPage--;
        this.selectHandler();
      }
    },
    next() {
      if (this.currentPage < this.pages - 1) {
        //次のページがあるなら
        this.currentPage++;
        this.selectHandler();
      }
    },
    pageSelect(index) {
      //指定したページへ
      this.currentPage = index - 1;
      this.selectHandler();
    },
    selectHandler() {
      //ページを移動したときの処理
    },

    listen() {
      //changeSidebarにどうせだからとsidebarのactiveせットの役割を持たせてしまったけどリロードに対応してないのでこのページに来たときはなんでもactiveとするならcreated()のlisten()で一律処理してしまえばよさそう
      this.$eventBus.$emit("setSidebarMark", "/forum");
      Echo.channel("addQuestionChannel").listen("AddQuestionEvent", e => {
        this.questions.unshift(e.question);
      });

      Echo.channel("deleteQuestionChannel").listen("DeleteQuestionEvent", e => {
        for (let index = 0; index < this.questions.length; index++) {
          if (this.questions[index].question_id == e.question.question_id) {
            this.questions.splice(index, 1);
          }
        }
      });
    }
  }
};
</script>

<style lang="scss" scoped>
.fade-enter {
  // opacity:0;
}

@for $i from 0 through 11 {
  $enter-delay: 0.2s;
  .fade-enter-active {
    opacity: 0; //ちゃんとこれを入れておかないと入ったときに質問が表示されている状態になってしまう
    animation: fade-in 1s;

    &.question_items#{$i} {
      //こうすることで全部にtransition-groupのすべてのforで回した要素にfade-enter-activeがかかっているが、それ+delayを個別出かけてあげる
      animation-delay: #{0.2s * $i + $enter-delay};
    }
  }
}
.fade-leave {
}
//positionをつけないとremoveできない
.fade-leave-active {
  transition: all 0.3s;
  transform: translateX(-100px);
  opacity: 0;
  position: absolute;
}

.fade-move {
  transition: all 0.5s cubic-bezier(0.77, 0, 0.175, 1); //leaveするやつとenterするやつ以外の動き、これはvueが自動でやってくれるのか・・？
}

@keyframes fade-in {
  0% {
    opacity: 0;
    transform: translateY(-15px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}
.question_items {
  //  //入るときにこれが必要？
  //  transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  //  transform: translateY(0px);//fade-enter-activeでtransformY(0px)の位置まで動く
}

.message_wrapper {
  margin-left: 3rem;
  margin-top: 3rem;
  display: flex;
  flex-direction: column;
}

.route_message {
  font-size: 4rem;
  font-family: "Geostar", cursive;
}

.bottom_line {
  height: 1px;
  background-color: rgb(39, 67, 83);
  margin-top: 1rem;
  width: 40rem;
}

.mycontainer {
  height: 100%;
  // padding: 24px;
  width: 100%;
  display: flex;
  flex-direction: column;
}

.main_wrapper {
  display: flex;
  padding: 6.4rem 0rem 6.4rem 20rem;
  height: 100%;
}

.rightflex {
  margin-right: 2rem;
}
.app-sidebar {
  margin-top: 2.4rem;
}
.grid_ul {
  width: 100%;
  height: 100%;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-template-rows: repeat(4, minmax(1fr, min-content));
  grid-template-areas:
    "card01 card02 card03"
    "card04 card05 card06"
    "card07 card08 card09"
    "card10 card11 card12";
  grid-gap: 10px; //gridgapを後に
  justify-items: stretch;
  align-items: stretch;
}

.pagenation {
  display: flex;
  justify-content: center;
  // flex-grow: 1;
  // justify-content: space-around;
}
.page-item {
  list-style: none;
  height: 2.5rem;
  width: 2.5rem;

  &:not(:last-child) {
    margin-right: 1.8rem;
  }
}

.page-link {
  outline: none;
  display: inline-flex;
  text-decoration: none;
  color: #4c4940;
  width: 2.5rem;
  height: 2.5rem;
  line-height: 2.5rem;
  border-radius: 50%;
  border: double 4px #4c4940;
  //   text-align: center;
  //   vertical-align: middle;
  justify-content: center;
  align-items: center;
  overflow: hidden;

  font-size: 1.3rem;
}
// .pagenation{
//     display: flex;
//     flex-grow: 1;
//     justify-content: space-around;
// }
// .page-item{
//   list-style: none;
//   height:2.5rem;
//   width:2.5rem;

// }

// .page-link{
//   text-decoration: none;
//   height:2.5rem;
//   width:2.5rem;
//   display: flex;
//   justify-content: center;
//   align-items: center;
//   font-size: 2rem;
// }
</style>
