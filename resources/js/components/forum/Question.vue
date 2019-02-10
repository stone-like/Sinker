<template>


  <v-layout>
      <v-card class="width_comp mt-2">
        <v-card-title primary-title>
          <div>
            <h3 class="headline mb-0">
                <router-link :to="data.path">
                    {{data.title}}
                </router-link>
            </h3>
            <div>
                 {{data.created_at}}
            </div>
          </div>
        </v-card-title>
        <v-card-text>
            {{data.body}}
        </v-card-text>


      </v-card>
  </v-layout>

</template>

<script>
export default {
 props:['data']
}//data.pathでそれぞれ個別の質問へ行けるようにしてあげる
</script>

<style>
.width_comp{
    width:100%;
}
</style>
