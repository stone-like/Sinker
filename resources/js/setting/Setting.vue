<template>
<transition-group tag="div" :class="comp_setting" name="list">
 <h1 class="items setting_header" key="header">
     <span v-if="resultflag">
         <div class="small_message">
            Current
         </div>
         <div>
            Setting
         </div>
    </span>
    <span v-else>
        Setting
    </span>
</h1>

<div class="form_container" key="container">
 <div class="form_wrapper" key="wrapper" v-if="!preview">

  <div class="form_items">
   <span class="form_message username">Username:</span>
  <input type="text"  v-model="form.name" placeholder="change Username" required class="setting_input">
  <span class="error_message" v-if="errors.name">{{errors.name[0]}}</span>
  </div>

 <div class="form_items">
 <span class="form_message email">Email:</span>
 <input type="email"  v-model="form.email" placeholder="change email"  required class="setting_input">
 <span class="error_message" v-if="errors.email">{{errors.email[0]}}</span>

 </div>



<div class="form_items">
 <span class="form_message password">Password:</span>
 <input type="password"  v-model="form.password" placeholder="change password"  required class="setting_input" ref="password">
   <span class="error_message" v-if="errors.password">{{errors.password[0]}}</span>
</div>

<div class="form_items" v-if="!resultflag">
  <span class="form_message">ConfirmPassword:</span>
  <input type="password"  v-model="form.password_confirmation" placeholder="confirm password"  required class="setting_input" v-if="!resultflag">
</div>

<div class="form_items" v-if="!resultflag">
 <span class="form_message">Previous_Password:</span>
 <input type="password"  v-model="form.password_previous" placeholder="enter_previous_password"  required class="setting_input" v-if="!resultflag">
  <span class="error_message" v-if="errors.previous">{{errors.previous}}</span>
</div>


  <button class="setting_button" @click.prevent="InputOrDisplay" key="button">
      {{resultflag ? "ToInput":"Update"}}
 </button>
</div>

<div :class="comp_wrapper2" key="wrapper2">
  <div class="form_items2" key=outer_wrapper>
    <span class="form_message">User_Img:</span>
  <input type="file" @change="change" placeholder="Input_your_img"  class="input_img" v-if="!resultflag" ref="fileElem" name="foo">

  <div class="button_wrapper">
    <button class="fileSelect" @click.prevent="fileSelect">Input_your_img</button>
    <button class="cancelSeclect" @click.prevent="cancelSelect">cancel</button>
  </div>
  <span class="error_message" v-if="errors.previous">{{errors.previous}}</span>
  <div v-if="preview">
        <img :src="preview">
  </div>
</div>
<button class="setting_button2" @click.prevent="InputOrDisplay2" key="button">
      {{resultflag ? "ToInput":"Upload"}}
 </button>


</div>

</div>



</transition-group>


</template>

<script>
export default {
   data(){
       return{
           form:{
              name:null,
              email:null,
              password_previous:null,
              password:null,
              password_confirmation:null,//ここはlaravelのvalidationでconfirmを使うために_confirmationとしなければだめ
          },

          user_img:null,
          errors:[],
          preview:"",
          changed_name:"",
          changed_email:"",
          changed_password:"",
          resultflag:false,
          changed_image:""
       }
   },
   methods:{
       fileSelect(e){
          var fileElem = this.$refs.fileElem
          if(fileElem){
              fileElem.click()
          }
       },
       cancelSelect(){
              this.preview = ""//これだけじゃfileElemがクリックというか選択された状態が解除されていないから同じのが選択できない？
              //ということはinputのvalueをクリアしなくてはいけない
              var fileElem = this.$refs.fileElem//inputのvalueをクリア
              fileElem.value = ""
       },
       change(e){
          var file = e.target.files[0]
          this.user_img = file


          if(file && file.type.match(/^image\/(png|jpeg|jpg)$/)){
              this.preview = URL.createObjectURL(file);
          }
       },
       InputOrDisplay(){
          if(this.resultflag){
             this.returnInput()
          }else{
              this.updateSetting()
          }
       },
       InputOrDisplay2(){
        if(this.resultflag){
             this.returnInput2()
          }else{
              this.updateSetting2()
          }
       },
       returnInput(){
               this.form.name = null
               this.form.email = null
               this.form.password = null
               this.$refs.password.type = "password"

               this.resultflag = false
       },
       updateSetting(){
           var self = this
           axios.patch("/api/user",self.form)
           .then(res => {
               //updateした分vuexの情報を更新
               self.$store.dispatch('storeUser',res.data.name)
               //Tokenとidは変えていないので大丈夫

               self.changed_name = res.data.name
               self.changed_email = res.data.email


               self.$refs.password.type = "text"
               self.form.name = self.changed_name
               self.form.email = self.changed_email
               self.form.password_previous = null
               self.form.password_confirmation = null

               self.resultflag = true

           })
           .catch(error => {
               self.errors = error.response.data.errors
               console.log(error.response.data.errors)
              })
       },
       updateSetting2(){
           var self = this
        //    console.log(self.user_img)
            const formData = new FormData()
            formData.append('image',self.user_img)
            // let config = {
            //         headers: {
            //             'content-type': 'multipart/form-data'
            //         }
            //     };
            // console.log(formData.entries())
           axios.post("/api/image",formData)
           .then(res => {


              self.changed_image = res.data

               self.resultflag = true

           })
           .catch(error => {
               self.errors = error.response.data.errors
               console.log(error.response.data.errors)
              })
       }
    //    dummy(){
    //        this.$refs.password.type="text"
    //        console.log(this.$refs.password.type)
    //    }
   },
   computed:{
       comp_setting(){
           return this.resultflag ? "setting_form result" : "setting_form";
       },
       comp_wrapper2(){
           return this.preview ? "form_wrapper2 image_mode" : "form_wrapper2";
       }
   }
}
</script>

<style lang="scss" scoped>
.list-move,
.form_message,
.setting_header,
.setting_button,
.setting_input,
.form_wrapper2,
.setting_button2,
.input_img,
.fileSelect,
.cancelSeclect
{
    transition: all .6s ease-in-out;
}
.setting_form{
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
    padding:7rem 0 7rem 0;
    align-items: flex-start;

}
.setting_header{
    font-size: 4rem;
    background-color:#4C4940;
    color: rgb(253, 247, 231);
    padding: 2rem 1rem 2rem 3rem;
    display: inline-block;
    width: 140rem;

}

.form_container{
    display: flex;
}

.form_wrapper{
    display: flex;
    flex-direction: column;
    margin-top: 10rem;
}

.form_wrapper2{
     display: flex;

    margin-top: 40rem;
    margin-left: 10rem;

     .form_items2{
         display: flex;
    flex-direction: column;
     }

}

.image_mode{
    margin-top: 10rem;
}

.form_items{
    width: 70rem;
    display: flex;
    flex-direction: column;
    margin-bottom: 2rem;
}

.form_message{
    font-size: 3rem;
     background-color:rgb(122, 120, 102);
    color: rgb(223, 219, 210);
    align-self: flex-start;
    padding: 1rem;
    margin-bottom: 2rem;

}

.setting_input{
    width: 70rem;
    font-size: 2rem;
    padding: 1rem;
    border: none;
    border-bottom: 1px solid rgb(122, 120, 102);
    outline: none;
}

.setting_button{
  margin-left: 30rem;

  outline: none;
  display: inline-block;
  text-decoration: none;
  color: #4C4940;
  width: 10rem;
  height: 10rem;
  line-height: 10rem;
  border-radius: 50%;
  border: double 4px #4C4940;
  text-align: center;
  vertical-align: middle;
  overflow: hidden;

  font-size: 2.3rem;
}

.setting_button2{
  margin-left: 10rem;

  outline: none;
  display: inline-block;
  text-decoration: none;
  color: #4C4940;
  width: 10rem;
  height: 10rem;
  line-height: 10rem;
  border-radius: 50%;
  border: double 4px #4C4940;
  text-align: center;
  vertical-align: middle;
  overflow: hidden;

  font-size: 2.3rem;
}

.error_message{
    margin-top: .6rem;
    color: #CD664D;
    font-size: 1.3rem;
}

.input_img{
    display: none;
}

.button_wrapper{
    display: flex;
}

.fileSelect{
    font-size: 1.5rem;
    border: 1px solid black;
    display: inline-block;
    padding: .5rem .5rem .5rem .5rem;
    align-self: flex-start;
    outline: none;
    transition: all .3s ease-in-out;
    margin-right: 1rem;

    &:hover{
        outline: none;
        transform: translateY(-5px);
    }
}

.cancelSeclect{
      font-size: 1.5rem;
    border: 1px solid black;
    display: inline-block;
    padding: .5rem .5rem .5rem .5rem;
    align-self: flex-start;
    outline: none;
    transition: all .3s ease-in-out;

    &:hover{
        outline: none;
        transform: translateY(-5px);
    }
}

.result{

  .setting_header{
      transform:translate3d(9rem,11rem,0);
      height: 10rem;
      width: 10rem;
       padding: 1rem;
       font-size: 2rem;
       display: flex;
       justify-content: center;
       align-items: center;
  }

  .small_message{
      margin-bottom: .8rem;
  }

  .username{
      transform: translate3d(35rem,-10rem,0);
  }

  .email{
       transform: translate3d(35rem,-10rem,0);
  }

  .password{
       transform: translate3d(35rem,-10rem,0);
  }

  .setting_button{
      transform: translate3d(-55rem,-10rem,0);
  }
  .setting_input{
      transform: translate3d(35rem,-10rem,0);
  }




}


</style>
