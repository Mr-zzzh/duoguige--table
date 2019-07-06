<template>
    <div class="header">
        <div class="logo">
            <img height="30px" src="../../../static/img/1_01.png" alt="">
        </div>
        <div>
            <el-menu
            :default-active="index"
            class="el-menu-demo"
            mode="horizontal"
            @select="handleSelect"
            background-color="#1D2B36"
            text-color="#fff"
            active-text-color="#fff"
            router>
            <!-- <el-menu-item index="/index">首页</el-menu-item> -->
            <!-- <el-menu-item index="/storelist">店铺</el-menu-item> -->
            <!-- <el-menu-item :index="'/admin_vip'" >会员</el-menu-item> -->
            <!-- <el-menu-item :index="'/admin_dd'" >订单</el-menu-item> -->
            <!-- <el-menu-item :index="'/admin_caiwu'" >财务</el-menu-item> -->
            <!-- <el-menu-item index="/storelist">门店</el-menu-item> -->
            <!-- <el-menu-item :index="'/admin_yy'" >应用</el-menu-item> -->
            <!-- <el-menu-item index="/storelist">数据</el-menu-item> -->
            <!-- <el-menu-item :index="'/admin_screen'" >设置</el-menu-item> -->
            <!-- <el-menu-item index="/marketingpre">营销</el-menu-item> -->
            <!-- <span style="outline: none;    width: 40px;height: 40px;margin-top: 20px;float: left;margin-left: 31px;"> -->
                <!--<img src="../../../static/img/2.png" alt="" style="width: 100%;height: 100%;">-->
            <!-- </span> -->
            <!-- <span style="outline: none;    color: white;margin-top: 30px;margin-left: 31px;display: inline-block;">{{adminname_a}}，欢迎您进入乐游购淘宝客后台管理系统！</span> -->
            <span style="outline: none;color: white;float: right;margin-right: 250px;margin-top: 30px;">
                {{nn}}年{{yy+1}}月{{rr}}日 &nbsp; {{ss}}:{{ff}}:{{miao}} &nbsp; {{zz}}
            </span>
           </el-menu>
        </div>
        <div class="dropdowm">
            <el-dropdown trigger="click" >
                <span class="el-dropdown-link" style="position: absolute;right: 30px;    top: -6px;">
                    <!-- {{aduser}} -->
                    <!-- admin -->
                    <!-- <i class="el-icon-arrow-down el-icon--right"></i> -->
                    <img src="../../../static/img/1_06.png" alt="">
                </span>
                <el-dropdown-menu slot="dropdown">
                    <!-- <el-dropdown-item @click.native="toqxgl">权限管理</el-dropdown-item> -->
                    <el-dropdown-item @click.native="changeper1">修改信息</el-dropdown-item>
                    <el-dropdown-item @click.native="backlogin">退出登录</el-dropdown-item>
                </el-dropdown-menu>
            </el-dropdown>
        </div>

        <!-- 修改 -->
        <div>
            <el-dialog title="修改信息" :visible.sync="dialogFormVisible">
                <el-form ref="form11" :model="form11" label-width="100px">
                    <el-form-item label="名称">
                        <el-input v-model="form11.name"></el-input>
                    </el-form-item>
                    <el-form-item label="手机号">
                        <el-input v-model="form11.phone"></el-input>
                    </el-form-item>
                    <el-form-item label="密码">
                        <el-input v-model="form11.password" type="password"></el-input>
                    </el-form-item>
                    <el-form-item label="状态">
                        <el-radio v-model="form11.status" label="0">禁用</el-radio>
                        <el-radio v-model="form11.status" label="1">启用</el-radio>
                    </el-form-item>
                    <el-form-item label="账号头像">
                        <el-upload
                        class="avatar-uploader"
                        :action="`${api}upload`"
                        :show-file-list="false"
                        :before-upload="beforeAvatarUpload">
                        <img v-if="form11.avatar" :src="form11.avatar" class="avatar" style="width:100px;height:100px;">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                </el-form>
                 <div slot="footer" class="dialog-footer">
                    <el-button type="primary" @click="submit">确 定</el-button>
                    <el-button @click="dialogFormVisible = false">取 消</el-button>
                </div>
            </el-dialog>
        </div>

    </div>
</template>
<script>
// import md5 from 'js-md5';
// import { Toast } from 'mint-ui';
export default {
    data () {
        return {
            productarr:['productlist','productover','warehouse','recovery','classification'],
            memberarr:['memberlist','memberlevel','memtoplist'],
            cashierarr:['outlog','moneycount','numlog','restlog'],
            indexarr:['index','changeper','power'],
            orderarr:['toSendgoods','toTakegoods','obligation','toCompleted','closeorder','allorders','legalapply','legalcomplete'],
            setlistarr:['paysetlist','advertlist','noticelist','pptlist','allset','couponlist','numset','personset','dailyset','sharelist','userlist'],
            index:'',
            dialogFormVisible:false,
            // 顶部权限
            productheader:false,
            memberheader:false,
            cashierheader:false,
            orderheader:false,
            setlistheader:false,
            togoodsurl:'',
            tomemberurl:'',
            tocashierurl:'',
            toorderurl:'',
            toseturl:'',
            adminname:'',
            adminmima:'',
            form: {name:''},
            formLabelWidth:'120px',
            imageUrl:'',
            baseurl:this.api,
            ms_id:localStorage.getItem('ms_id'),
            aduser:'admin',
            ms_level:sessionStorage.getItem("ms_level"),
            usequnxian:JSON.parse(sessionStorage.getItem("usequnxian")),
            adminuserneme:'',//登录账号
            adminquemima:'',//确认密码
            adminname_a:"",
            password:'',//
            nn:'',
            yy:'',
            rr:'',
            ss:'',
            ff:'',
            zz:'',
            miao:'',

            form11:{
                name:'',
                phone:'',
                password:'',
                avatar:'',
                status:'',
            },
        }
    },
    created(){
        this.getsj()
                let a = new Date()
                this.nn = a.getFullYear()
                this.yy = a.getMonth()
                this.rr = a.getDate()
                this.ss = a.getHours()
                this.ff = a.getMinutes()
                this.zz = a.getDay()
                this.miao = a.getSeconds()
                // console.log(this.miao)

                if(this.zz == 0){
                    this.zz = '星期天'
                }else if(this.zz == 1){
                    this.zz = '星期一'
                }else if(this.zz == 2){
                    this.zz = '星期二'
                }else if(this.zz == 3){
                    this.zz = '星期三'
                }else if(this.zz == 4){
                    this.zz = '星期四'
                }else if(this.zz == 5){
                    this.zz = '星期五'
                }else if(this.zz == 6){
                    this.zz = '星期六'
                }
      let c = JSON.parse(localStorage.getItem('admin_info'))
      // console.log(c.nickname)
      this.adminname_a = c.nickname
        // console.log(a.getFullYear())
        // console.log(a.getMonth())
        // console.log(a.getDate())
        // console.log(a.getHours())
        // console.log(a.getMinutes())
        // console.log(a.getDay())


        // this.aduser = JSON.parse(localStorage.getItem('admin_info')).name
        // console.log(this.aduser)

        this.index = `/${sessionStorage.getItem('openindex')}`
        if(this.index=='/'){
            this.index = '/index'
        }else if(this.index=='/numlog'){
            this.index = '/outlog'
        }else if(this.index=='/legalapply'){
            this.index = '/toSendgoods'
        }
        // this.getlist()
    },
    mounted(){
        // console.log('usequnxian',this.usequnxian)
        // this.usequnxian.forEach(item => {
        //     if(item.title=='商品'){
        //         console.log('商品',item.showarr[0].tzurl)
        //         this.productheader = item.active
        //         this.togoodsurl =item.showarr[0].tzurl?item.showarr[0].tzurl:''
        //     }else if(item.title=='会员'){
        //         // console.log('会员',item.active)
        //         this.memberheader = item.active
        //         this.tomemberurl =item.showarr[0].tzurl?item.showarr[0].tzurl:''
        //         // this.memberheader = 0
        //     }else if(item.title=='财务'){
        //         // console.log('财务',item.active)
        //         this.cashierheader = item.active
        //         this.tocashierurl =item.showarr[0].tzurl?item.showarr[0].tzurl:''
        //         // this.cashierheader = 0
        //     }else if(item.title=='订单'){
        //         // console.log('订单',item.active)
        //         this.orderheader = item.active
        //         this.toorderurl =item.showarr[0].tzurl?item.showarr[0].tzurl:''
        //     }else if(item.title=='设置'){
        //         // console.log('设置',item.active)
        //         this.setlistheader = item.active
        //         this.toseturl =item.showarr[0].tzurl?item.showarr[0].tzurl:''
        //     }
        // });
    },
    watch:{
        $route(to,from){
            if(this.indexarr.includes(to.name)){
                this.index = '/index'
            }else if(this.productarr.includes(to.name)){
                this.index="/productlist"
            }else if(this.memberarr.includes(to.name)){
                this.index="/memberlist"
            }else if(this.orderarr.includes(to.name)){
                this.index="/toSendgoods"
            }else if(this.cashierarr.includes(to.name)){
                this.index="/outlog"
            }else if(this.setlistarr.includes(to.name)){
                this.index="/paysetlist"
            }
        }
    },
    methods: {
        getsj(){
            this.sj1 = setInterval(()=>{
                let a = new Date()
                // console.log(a)
                this.nn = a.getFullYear()
                this.yy = a.getMonth()
                this.rr = a.getDate()
                this.ss = a.getHours()
                this.ff = a.getMinutes()
                this.zz = a.getDay()
                this.miao = a.getSeconds()
                

                if(this.zz == 0){
                    this.zz = '星期天'
                }else if(this.zz == 1){
                    this.zz = '星期一'
                }else if(this.zz == 2){
                    this.zz = '星期二'
                }else if(this.zz == 3){
                    this.zz = '星期三'
                }else if(this.zz == 4){
                    this.zz = '星期四'
                }else if(this.zz == 5){
                    this.zz = '星期五'
                }else if(this.zz == 6){
                    this.zz = '星期六'
                }
            },1000)
        },
      handleSelect(key, keyPath) {
        // console.log(key, keyPath);
      },
    //   toqxgl(){
    //     this.$router.push('/power')
    //   },
      changeper1(){
        // this.$router.push('/changeper')
        this.dialogFormVisible=true
        let a = JSON.parse(localStorage.getItem('admin_info'))
        console.log(a)
        this.adminname = a.nickname
        this.adminuserneme = a.username
        // this.getlist()
      },
    //   getlist(){
    //       this.$http.post(this.api+'admin/admin/info',{
    //           id:this.ms_id
    //       }).then(res=>{
    //         //   console.log(res)
    //           if(res.data.status==1){
    //               this.form=res.data.result
    //               this.imageUrl = this.form.admin_img
    //               this.form.password=''
    //           }
    //       })
    //   },
    //   handleAvatarSuccess(res, file) {
    //         this.imageUrl = URL.createObjectURL(file.raw);
    //         // console.log(file)
    //     },

        // beforeAvatarUpload(file) {
        //     let image_base64;
        //     let that = this
        //     if (!/image\/\w+/.test(file.type)) {
        //         that.$message("请确保文件为图像类型");
        //         return false;
        //     } else {
        //         let reader = new FileReader();
        //         reader.readAsDataURL(file);
        //         reader.onload = function(e) {
        //             image_base64 = this.result.split(",")[1];
        //             var params = {
        //                 imgdata: image_base64
        //             };
        //             that.$http
        //                 .post(that.api + "index/index/uploadimg", params)
        //                 .then(res => {
        //                        console.log(res)
        //                     if (res.data.status == 1) {
        //                         that.imageUrl = res.data.result.url
        //                         that.$message.success(res.data.message);
        //                     } else if (res.data.status == 0) {
        //                         this.$message.error(res.data.message);
        //                     }
        //                 });
        //         };
        //     }
        // },
      // 上传缩略图
            beforeAvatarUpload(file) {
              var that = this;
              // 判断类型是不是图片
              if (!/image\/\w+/.test(file.type)) {
                  that.$message("请确保文件为图像类型");
                  return false;
              } else {
                  let fd = new FormData();
                  fd.append("media", file); //传文件
                  that.$axios({
                      method: "post",
                      url: that.api + "upload",
                      data: fd
                  }).then(res => {
                      // console.log(res);
                      if(res.data.status == 1){
                          that.form11.avatar = res.data.data.url;
                          console.log(that.form)
                      }
                  });
              }
          },
      submit(){
        let a = JSON.parse(localStorage.getItem('admin_info'))
        console.log(a)
         if(!(/^1[3456789]\d{9}$/.test(this.form11.phone))){ 
              this.$message.error('手机号码有误，请重填') 
              return; 
          } 
          this.$axios({
              method:'put',
              url:`${this.api}admin/admin/${a.id}`,
              data:this.form11
          }).then(res=>{
              if(res.data.status == 1){
                  console.log(res.data.data)
                  this.$message.success(res.data.message)
                  this.dialogFormVisible = false
              }else{
                  this.$message.error(res.data.message)
              }
          })
      },
      backlogin(){
        // let c= JSON.parse(localStorage.getItem('admin_info')).token
          this.$axios({
           method:'post',
           url:this.api+'admin/Users/logout',
           data:{}
          }).then(res=>{
           if (res.data.status==1){
             this.$message.success(res.data.message)
             // localStorage.removeItem('admin_info')
             this.$router.push('/')
           } else {
             this.$message.error(res.data.message)
           }
          })

      },//用户注销
    }
}
</script>
<style lang="less" scoped>
 /deep/ .el-popper .popper__arrow{
  display:none
}
.el-popper{
    margin-top: 24px !important;
}
 .header {
        .el-menu-item{
            font-size: 15px;
        }
        .el-menu--horizontal{
            border-bottom: none;
        }
        position: relative;
        box-sizing: border-box;
        width: 100%;
        height: 80px;
        // font-size: 22px;
        color: #696969;
        background: #fff; /* 头部背景色 */
        .logo{
            width: 200px;
            height: 80px;
            float: left;
            // color: white;
            background-color: #0177D5;
            text-align: center;
            img{
                width: 100%;
                height: 100%;
                // margin-top: 10px;
            }
        }
        ul{
            height: 80px;
            margin-left: 200px;
            li{
                height: 100%;
                line-height: 80px;
            }
        }
        .dropdowm{
            position: absolute;
            right: 100px;
            cursor: pointer;
            top: 20px;
            .el-dropdown-link{
                color: white;
            }
        }
/deep/ .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
 /deep/ .avatar-uploader .el-upload:hover {
    border-color: #409EFF!important;
  }
 /deep/ .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
 /deep/ .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }
    }
 .header /deep/ .el-menu--horizontal>.el-menu-item.is-active:hover{
    background:rgba(255,255,255,.3)!important;
    color: white!important;
   /*border-bottom-color: rgba(255,255,255,0)!important;*/
 }
 .header ul li:hover{
   /*background:rgba(255,255,255,.3)!important;*/
   color: white!important;
   /*border-bottom-color: rgba(255,255,255,0)!important;*/
 }
 .header .el-menu-item{
   border: none!important;
   padding: 0 80px!important;

 }
 .header  /deep/ .el-menu--horizontal>.el-menu-item.is-active{
        background:rgba(255,255,255,.3)!important;
//    background: url("../../../static/images/page/shanjiao.png") no-repeat;
   background-size: 100% 100%;
 }

</style>


