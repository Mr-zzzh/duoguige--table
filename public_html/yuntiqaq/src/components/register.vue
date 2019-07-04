<template>
    <div style="height: 100%;width: 100%">
      <div class="Login">
         <div style="padding-top: 300px">
             <div class="Login_Forem">
                 <div></div>
                 <div>
                     <p class="welcome">欢迎登录</p>
                      <!--表单-->
                     <div class="welcome_forme">
                       <div class="usernamess" style="margin-top: 50px" :class="names==0?'usernamess':'actives'">
                         <div>
                           账号
                         </div>
                         <div>
                           <!--<el-input v-model="username" placeholder="请输入账号"></el-input>-->
                           <input @keyup.enter="dl()" @input="iction()" type="text" v-model="username" placeholder="请输入账号">
                         </div>
                         <div class="yanjin">

                         </div>
                       </div>
                       <!--密码-->
                         <div class="usernamess" style="margin-top: 50px" :class="namesa==0?'usernamess':'actives'">
                         <div>
                           密码
                         </div>
                         <div>
                           <!--<el-input v-model="username" placeholder="请输入账号"></el-input>-->
                           <input @keyup.enter="dl()" @input="iction_a()" type="password" v-model="Password" placeholder="请输入密码" v-show="show_img">
                           <input @keyup.enter="dl()" @input="iction_a()" type="text" v-model="Password" placeholder="请输入密码" v-show="!show_img">
                         </div>
                         <div class="yanjin">
                           <img src="../../static/img/OA/login_eyes2.png" alt=""  @click="qufan()" v-show="show_img">
                           <img src="../../static/img/OA/login_eyes.png" alt=""  @click="qufan()" v-show="!show_img">
                         </div>
                       </div>
                       <el-checkbox-group text-color="#2FC6AF" fill="2FC6AF" style="margin-top: 20px;display: inline-block;"  v-model="rembervalue" >
                       <el-checkbox  label="记住密码" style="color:#000000;"></el-checkbox>
                       </el-checkbox-group>
                       <button @click="dl()">登录</button>
                     </div>
                 </div>
             </div>
         </div>
      </div>
    </div>
</template>

<script>
    export default {
        name: '',
        data() {
            return {
                zt:0,
              names:0,
              namesa:0,
              username:'',//账号
              Password:'',//密码
              rembervalue:'',//记住密码
              show_img:true,//取反
              user:'',
              admin_header:[],
            }
        },
        methods:{
          qufan(){
            this.show_img = !this.show_img
          },//取反
            dl(){
                
                this.$axios({
                    method:'post',
                    url:this.api+'admin/login',
                    data:{
                      phone:this.username,
                      password:this.Password,
                    }
                }).then(res=>{
                    console.log(res)
                    if(res.data.status == 1){
                      console.log(res.data.data.info)
                        this.$message.success(res.data.message)
                        let a = JSON.stringify(res.data.data.info)
                       localStorage.setItem('admin_info', a)
                        // this.$router.push({name:'admin_index'})
                      this.user = JSON.parse(localStorage.getItem('admin_info'))
                        this.$router.push({name:'admin_index'})
                      if (this.rembervalue.length>0) {
                        localStorage.setItem('ms_username', this.username);
                        localStorage.setItem('ms_password', this.Password);
                        localStorage.setItem("rembervalue", "1");
                      } else {
                         localStorage.setItem('ms_username', '');
                         localStorage.setItem('ms_password', '');
                         localStorage.setItem("rembervalue", "0");
                       }
                     }else{
                      this.$message.error(res.data.message)
                    }
                })
            },
          iction(){
            this.names = 1
            if (this.username ==''){
              this.names = 0
            }
          },
          iction_a(){
            this.namesa = 1
            if (this.Password ==''){
              this.namesa = 0
            }
          },
        },
        created(){
          this.user = JSON.parse(localStorage.getItem('admin_info'))
          console.log(this.user.token)
        },
        mounted() {
            this.rembervalue = localStorage.getItem("rembervalue");
            console.log(this.rembervalue)
            if (this.rembervalue==1){
            this.rembervalue = ['记住密码']
            this.username = localStorage.getItem("ms_username");
            this.Password = localStorage.getItem("ms_password");
            }else {
            this.rembervalue = [];
            }
        }
    }
</script>

<style lang="less" scoped>
  .Login{
    width: 100%;
    position: relative;
    height: 100%;
    /*min-height: 950px;*/
    background-size:cover;
    background: url("../../static/img/OA/login_background.png") no-repeat;
    background-size: 100% 100%;
  }
  .From{
      position: absolute;
    right: 5%;
    top: 20%;

     width: 400px;
    padding-bottom: 30px;
     background: white;
    border-radius: 10px;
    // margin: 0 auto 0;
    // padding-top: 70px;
  }
  .Form_img{
    width: 100%;
    height: 77px;
    margin: 33px auto 0;
  }
  .Form_img>img{
    width: 100%;
    height: 100%;
  }
  .Fomre_r{
    padding: 0 25px;
    margin-top: 50px;
  }
  .Fomre_r /deep/ .el-input__inner{
    background: rgb(234,234,236);
  }
  .welcome_forme>button{
    width: 100%;
    height: 45px;
    line-height: 45px;
    margin-top: 60px;
    text-align: center;
    background: #47C3CF;
    border: none;
    color: white;
    font-size: 18px;
    border-radius: 5px;
  }
  .welcome_forme /deep/ .el-checkbox__input.is-checked+.el-checkbox__label{
    color:#47C3CF!important;
  }
  .welcome_forme /deep/ .el-checkbox__input.is-checked .el-checkbox__inner, .el-checkbox__input.is-indeterminate .el-checkbox__inner{
    background: #47C3CF!important;
    color:#47C3CF!important;
    border-color: #47C3CF!important;
  }
  .welcome_forme /deep/ .el-checkbox__inner:hover{
    border-color: #47C3CF!important;
  }
  .copy{
        width:100%;
        height: 80px;
        background-color: aqua;
        color: #fff;
        font-size:16px;
        text-align: center;
        position: absolute;
        bottom: 0px;
        background:rgba(0,0,0,0.12);
        
        
        p{
            line-height: 30px;
            font-size:16px;
            // font-family:PingFang-SC-Medium;
            font-weight:500;
            opacity:0.88;
        }
        p:first-child{
            margin-top:10px;
        }
    }
   .Fomre_r .codebtn{
            margin-left: -20px;
    width: 40%;
    height: 40px;
    line-height: 20px;
    display: inline-block;
    margin: 0;
    background: #2FC6AF;
    border: none;
    color: white;
    font-size: 18px;
    border-radius: 5px;
    text-align: center;
    margin-top: 10px;
        margin-left: 10px;
    }
  .Login_Forem{
     width: 1050px;
    height: 450px;
    background: white;
    margin: 0 auto;
    border-radius:5px;
    overflow: hidden;
    display: flex;
  }
  .Login_Forem>div:nth-child(1){
    flex: 2;
    background: url("../../static/img/OA/login_background2.png") no-repeat;
    background-size: 100% 100%;
  }
  .Login_Forem>div:nth-child(2){
    flex: 1.5;
  }
  .welcome{
    text-align: center;
    padding-top: 25px;
    font-family:MicrosoftYaHei-Bold;
    font-weight:bold;
    color:rgba(51,51,51,1);
    font-size: 20px;
  }
  .welcome_forme{
    padding: 0 66px;
    margin-top: 68px;
  }
  .usernamess{
    display: flex;
    width: 100%;
    padding-bottom: 14px;
    border-bottom: 1px solid #DDDDDD;
  }
  .usernamess>div:nth-child(1){
    flex: 2;
    font-size:16px;
    font-family:MicrosoftYaHei;
    font-weight:400;
    color:rgba(102,102,102,1);
  }
  .usernamess>div:nth-child(2) {
    flex: 8;
    padding-top: 2px;
  }
    .usernamess>div:nth-child(2)>input{
       border: none;
       background: none;
      outline: none;
      font-size:14px;
      font-family:MicrosoftYaHei;
      font-weight:400;
    }
  .usernamess>div:nth-child(2)>input::-webkit-input-placeholder{
    font-size:14px;
    font-family:MicrosoftYaHei;
    font-weight:400;
    color:rgba(153,153,153,1);
  }
  .yanjin{
    flex: 2;
    text-align: right;
    padding-top: 3px;
  }
  .actives{
    display: flex;
    width: 100%;
    padding-bottom: 14px;
    border-bottom: 1px solid #47C3CF;
  }
  .activesa{
    display: flex;
    width: 100%;
    padding-bottom: 14px;
    border-bottom: 1px solid #47C3CF;
  }
</style>
