<template>
    <transition
      name="expand"
      @enter="enter"
      @after-enter="afterEnter"
      @leave="leave"
    mode="out-in">
    <slot></slot>

    </transition>
</template>

<script>
export default {
  methods:{
      enter(element){
          //elementは今enterが起こった場所つまり、transitionの所
          const width = getComputedStyle(element).width;//transitionのwidthを計算,autoじゃtransitionが効かないので実際の高さが必要

          console.log(width)//ここだときちんとtransitionの大きさ1422pxになっているが、終了時にはbodyの大きさ1600近くになってしまっている

          element.style.width = width;
          element.style.position = 'absolute';
          element.style.visibility = 'hidden';
          element.style.height = 'auto';//一旦autoにしておいて

          const height = getComputedStyle(element).height;//動的に再計算

          element.style.width = null;
          element.style.position = null;
          element.style.visibility = null;
          element.style.height = 0;
          //今やりたいアニメーションはheight　0からの出現なため

          //animationをきちんと動かすために直前に設定した0からの変化を再計算する
          getComputedStyle(element).height;
          //height:0から

          //なぜsetTimeoutを使うかというとレンダリングの問題でもしheightがautoに設定し終わってない中レンダリングが終わってしまうとheightの値が分からないままなのでerrorになる、なのでheightがautoに設定される→レンダリングとするため、今までは例えばsliceとかするときはそもそも条件に合わないならsliceしないというのでよかったが、ここは絶対描写しなくてはいけないためこの方法
          setTimeout(() => {
              element.style.height=height;//const heightからとってくる
              //height:autoへ
              element.style.width=width;
          })
      },
      afterEnter(element){
          element.style.height='auto';
      },
      leave(element){
          const height = getComputedStyle(element).height;

          element.style.height = height;

          getComputedStyle(element).height;

          setTimeout(() => {
              element.style.height = 0;
          })
      }
  }
}
</script>

<style lang="scss" scoped>
* {
    // for performance issue
  will-change: height;
  transform: translateZ(0);
  backface-visibility: hidden;
  perspective: 1000px;
}

.expand-enter-active,
.expand-leave-active {
  transition: height .4s ease;
  overflow: hidden;
}

.expand-enter,
.expand-leave-to {

  height:0;
//   opacity:0;
}

</style>
