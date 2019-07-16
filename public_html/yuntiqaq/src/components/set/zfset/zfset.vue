<template>
    <div class="ccia page">
        <div class="herder_biaoti">
           <div class="herder_L">
           </div>
           <div class="herder_R">

            <el-button  type="primary"  @click="toaddlevel" style="margin-bottom:10px;" icon="el-icon-plus">新增</el-button>
           </div>
         </div>

        <div class="ccia_top">
            
        </div>
        <div class="ccia_contentbox" style="margin-bottom:20px">
            <div class="ccia_content clearfix">
                <el-table
                    border
                    ref="multipleTable"
                    :data="tableData"
                    tooltip-effect="dark"
                    style="width: 100%"
                    >
                    <el-table-column
                        label="序号"
                        width="150"
                        align="center"
                        type="index">
                    </el-table-column>

                     <el-table-column
                     align="center"
                        label="支付方式">
                        <template slot-scope="scope">
                            <span v-if="scope.row.paytype==1">支付宝支付</span>
                            <span v-if="scope.row.paytype==2">微信app支付</span>
                            <span v-if="scope.row.paytype==3">微信H5公众号支付</span>
                            <span v-if="scope.row.paytype==4">微信小程序支付</span>
                        </template>
                    </el-table-column>
                    <el-table-column
                    align="center"
                        prop="createtime"
                        label="创建时间">
                    </el-table-column>
                   
                    <el-table-column
                        align="center"
                        label="操作">
                        <template slot-scope="scope">
                            <el-button @click="toedit(scope.row)" type="text" size="small" style="color: #2FC6AF">编辑</el-button>
                            <el-button @click="deletelevel(scope.row)" style="color: #2FC6AF" type="text" size="small">删除</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>

        <el-dialog
        title="添加"
        :visible.sync="dialogVisible"
        width="60%"
        :before-close="handleClose">
        <span>
            <div class="ccia1">
        <div class="ccia_top">
            <button style="">
                {{edit?'编辑支付设置':'添加支付设置'}}
            </button>
        </div>
        
        <div class="ccia_contentbox">
            <!-- <div class="ccia_contenttop"></div> -->
            <div class="ccia_content">
            
               <!-- <div class="content_top">
                   <span>标题</span>
                   <el-input v-model="title" placeholder="请输入内容"></el-input>
               </div> -->
               <div class="content_top">
                   <span>支付方式类型</span>
                        <el-radio v-model="paytype" label='1'>支付宝</el-radio>
                        <el-radio v-model="paytype" label='2'>微信app支付</el-radio>
                        <el-radio v-model="paytype" label='3'>微信H5公众号支付</el-radio>
                        <el-radio v-model="paytype" label='4'>微信小程序支付</el-radio>
               </div>
               <div v-if="paytype==2 || paytype==3 || paytype==4">
                    <div class="content_top">
                        <span>微信支付appid</span>
                        <div>
                            <el-input v-model="wxpay_APPID" placeholder="请输入内容"></el-input>
                        </div>
                    </div>
                    <div class="content_top">
                        <span>微信支付商户号</span>
                        <div>
                            <el-input v-model="wxpay_MCHID" placeholder="请输入内容"></el-input>
                        </div>
                    </div>
                    <div class="content_top">
                        <span>微信支付key</span>
                        <div>
                            <el-input v-model="wxpay_KEY" placeholder="请输入内容"></el-input>
                        </div>
                    </div>
                    <div class="content_top">
                        <span>微信支付应用密钥</span>
                        <div>
                            <el-input v-model="wxpay_APPSECRET" placeholder="请输入内容"></el-input>
                        </div>
                    </div>
                    <div class="content_top">
                        <span>微信cert</span>
                        <div>
                            <el-input @input="cert" v-model="wxpay_apiclient_cert" placeholder="请输入内容"></el-input>
                        </div>
                    </div>
                    <div class="content_top">
                        <span>微信key证书</span>
                        <div>
                            <el-input @input="keyy" v-model="wxpay_apiclient_key" placeholder="请输入内容"></el-input>
                        </div>
                    </div>
               </div>
               <div v-if="paytype==1">
                    <div class="content_top">
                        <span>阿里支付appid</span>
                        <div>
                            <el-input v-model="alipay_appId" placeholder="请输入内容"></el-input>
                        </div>
                    </div>
                    <div class="content_top">
                        <span>阿里支付网关</span>
                        <div>
                            <el-input v-model="alipay_gatewayUrl" placeholder="请输入内容"></el-input>
                        </div>
                    </div>
                    <div class="content_top">
                        <span>阿里支付私钥</span>
                        <div>
                            <el-input v-model="alipay_rsaPrivateKey" placeholder="请输入内容"></el-input>
                        </div>
                    </div>
                    <div class="content_top">
                        <span>阿里支付公钥</span>
                        <div>
                            <el-input v-model="alipay_alipayrsaPublicKey" placeholder="请输入内容"></el-input>
                        </div>
                    </div>
               </div>
                <div class="submitbox">  
                    <button @click="submit" style="">提交</button>
                    <!-- <button @click="goback">返回列表</button> -->
                    <p>(如已有该支付方式,提交会覆盖原支付设置)</p>
                </div>
            </div>
        </div>
    </div>
        </span>
        <!-- <span slot="footer" class="dialog-footer">
            <el-button @click="dialogVisible = false">取 消</el-button>
            <el-button type="primary" @click="dialogVisible = false">确 定</el-button>
        </span> -->
        </el-dialog>



    </div>
</template>
<script>
export default {
    data(){
        return{
            typelist: [{
                value: '1',
                label: '启用'
                }, {
                value: '0',
                label: '禁用'
                }],
            type: '',
            keyword:'',
            //表格
            tableData: [],
            multipleSelection: [],
            // 分页
            // currentPage:1,
            // limit:10,
            // total:0
            edit:'',
            uid:'',

            paytype:'',

            wxpay_APPID:"",
            wxpay_MCHID:"",
            wxpay_KEY:"",
            wxpay_APPSECRET:"",
            wxpay_apiclient_cert:"",
            wxpay_apiclient_key:"",


            alipay_appId:"",
            alipay_gatewayUrl:"",
            alipay_rsaPrivateKey:"",
            alipay_alipayrsaPublicKey:"",

            dialogVisible:false,
            q:''
        }
    },
    //haolenhaolenhaolenhaolenhaolenhaolenhaolen
    methods:{
        handleClose(done) {
            this.dialogVisible = false
        },

        // 获取列表
        getlist(){
       

            this.$axios({
                method:'get',
                url:this.api+'admin/payset/index',
                params:{
                    
                }
            }).then(res=>{
                // console.log('支付',res)
                if(res.data.status==1){
                    this.tableData = res.data.data
                    // this.total = Number(res.data.result.list.length)
                    console.log(this.tableData)
                }else{
                    this.tableData = []
                    // this.total = 0
                }
            })
        },
        // search(){
        //     this.currentPage = 1
        //     this.getlist()
        // },
        // 跳转添加页面
        toaddlevel(){
            // this.$router.push('/admin_addzfset')
            this.dialogVisible = true
        },
        toedit(row){
            // this.$router.push({
            //     name:'admin_addzfset',
            //     query:{
            //         paytype:row.paytype
            //     }
            // })
            this.dialogVisible = true
            this.q = row.paytype
            this.getinfo()
        },
        // 选中事件
        handleSelectionChange(val) {
                console.log(val)
                if(val == ''){
                    this.multipleSelection = []
                }else{
                    this.multipleSelection = []
                    val.forEach(res => {
                        this.multipleSelection.push(res.id)
                    });
                }
                console.log(this.multipleSelection)
        },
        // 删除按钮
        deletelevel(row){
            console.log(row)
            this.$confirm('此操作将永久删除, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                this.$axios({
                    method:'post',
                    url:this.api+'admin/payset/delete',
                    data:{
                        id:row.id
                    }
                }).then(res=>{
                    // console.log('删除',res)
                    if(res.data.status==1){
                        this.$message.success('删除成功!');
                        this.getlist()
                    }else{
                        this.$message.error(res.data.message);
                    }
                })
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: '已取消删除'
                });          
            });
            // console.log(all)
            // console.log(row)
            
        },
         cert(){
            console.log(this.wxpay_apiclient_cert.length)
        },
        keyy(){
            console.log(this.wxpay_apiclient_key.length)
        },

        
        goback(){
            this.$router.go(-1)
        },
        // 提交
        submit(){
            


            var params={}
            if(this.paytype==1){
                this.$axios({
                    method:'post',
                    url:this.api+'admin/payset/add',
                    data:{
                        paytype:1,
                        alipay_appId:this.alipay_appId,
                        alipay_gatewayUrl:this.alipay_gatewayUrl,
                        alipay_rsaPrivateKey:this.alipay_rsaPrivateKey,
                        alipay_alipayrsaPublicKey:this.alipay_alipayrsaPublicKey,
                    }
                }).then(res=>{
                    // console.log('提交',res)
                    if(res.data.status==1){
                        this.$message.success(res.data.message);
                        this.dialogVisible = false
                        this.getlist()
                        this.paytype = ''
                        this.wxpay_APPID='',
                        this.wxpay_MCHID="",
                        this.wxpay_KEY="",
                        this.wxpay_APPSECRET="",
                        this.wxpay_apiclient_cert="",
                        this.wxpay_apiclient_key="",
                        this.alipay_appId="",
                        this.alipay_gatewayUrl="",
                        this.alipay_rsaPrivateKey="",
                        this.alipay_alipayrsaPublicKey=""
                    }else{
                        this.$message.error(res.data.message);
                    }
                })
            }else if(this.paytype==2){
                if(this.wxpay_apiclient_cert.length<50){
                alert('微信cert最少50个字符')
                return
            }else if(this.wxpay_apiclient_key.length<50){
                alert('微信key最少50个字符')
                return
            }
                this.$axios({
                    method:'post',
                    url:this.api+'admin/payset/add',
                    data:{
                        
                        paytype:2,
                        wxpay_APPID:this.wxpay_APPID,
                        wxpay_MCHID:this.wxpay_MCHID,
                        wxpay_KEY:this.wxpay_KEY,
                        wxpay_APPSECRET:this.wxpay_APPSECRET,
                        wxpay_apiclient_cert:this.wxpay_apiclient_cert,
                        wxpay_apiclient_key:this.wxpay_apiclient_key
                    }
                }).then(res=>{
                    // console.log('提交',res)
                    if(res.data.status==1){
                        this.$message.success(res.data.message);
                        this.dialogVisible = false
                        this.getlist()
                        this.paytype = ''
                        this.wxpay_APPID='',
                        this.wxpay_MCHID="",
                        this.wxpay_KEY="",
                        this.wxpay_APPSECRET="",
                        this.wxpay_apiclient_cert="",
                        this.wxpay_apiclient_key="",
                        this.alipay_appId="",
                        this.alipay_gatewayUrl="",
                        this.alipay_rsaPrivateKey="",
                        this.alipay_alipayrsaPublicKey=""
                    }else{
                        this.$message.error(res.data.message);
                    }
                })
            }else if(this.paytype==3){
                if(this.wxpay_apiclient_cert.length<50){
                alert('微信cert最少50个字符')
                return
            }else if(this.wxpay_apiclient_key.length<50){
                alert('微信key最少50个字符')
                return
            }
                this.$axios({
                    method:'post',
                    url:this.api+'admin/payset/add',
                    data:{
                        
                        paytype:3,
                        wxpay_APPID:this.wxpay_APPID,
                        wxpay_MCHID:this.wxpay_MCHID,
                        wxpay_KEY:this.wxpay_KEY,
                        wxpay_APPSECRET:this.wxpay_APPSECRET,
                        wxpay_apiclient_cert:this.wxpay_apiclient_cert,
                        wxpay_apiclient_key:this.wxpay_apiclient_key
                    }
                }).then(res=>{
                    // console.log('提交',res)
                    if(res.data.status==1){
                        this.$message.success(res.data.message);
                        this.dialogVisible = false
                        this.getlist()
                        this.paytype = ''
                        this.wxpay_APPID='',
                        this.wxpay_MCHID="",
                        this.wxpay_KEY="",
                        this.wxpay_APPSECRET="",
                        this.wxpay_apiclient_cert="",
                        this.wxpay_apiclient_key="",
                        this.alipay_appId="",
                        this.alipay_gatewayUrl="",
                        this.alipay_rsaPrivateKey="",
                        this.alipay_alipayrsaPublicKey=""
                    }else{
                        this.$message.error(res.data.message);
                    }
                })
            }else if(this.paytype==4){
                if(this.wxpay_apiclient_cert.length<50){
                alert('微信cert最少50个字符')
                return
            }else if(this.wxpay_apiclient_key.length<50){
                alert('微信key最少50个字符')
                return
            }
                this.$axios({
                    method:'post',
                    url:this.api+'admin/payset/add',
                    data:{
                        
                        paytype:4,
                        wxpay_APPID:this.wxpay_APPID,
                        wxpay_MCHID:this.wxpay_MCHID,
                        wxpay_KEY:this.wxpay_KEY,
                        wxpay_APPSECRET:this.wxpay_APPSECRET,
                        wxpay_apiclient_cert:this.wxpay_apiclient_cert,
                        wxpay_apiclient_key:this.wxpay_apiclient_key
                    }
                }).then(res=>{
                    // console.log('提交',res)
                    if(res.data.status==1){
                        this.$message.success(res.data.message);
                        this.dialogVisible = false
                        this.getlist()
                        this.paytype = ''
                        this.wxpay_APPID='',
                        this.wxpay_MCHID="",
                        this.wxpay_KEY="",
                        this.wxpay_APPSECRET="",
                        this.wxpay_apiclient_cert="",
                        this.wxpay_apiclient_key="",
                        this.alipay_appId="",
                        this.alipay_gatewayUrl="",
                        this.alipay_rsaPrivateKey="",
                        this.alipay_alipayrsaPublicKey=""
                    }else{
                        this.$message.error(res.data.message);
                    }
                })
            }
            
        },
        // 获取数据
        getinfo(){

            this.$axios({
                method:'post',
                url:this.api+'admin/payset/payget',
                data:{
                    
                    paytype:this.q,
                }
            }).then(res=>{
                console.log(res.data.data.result)
                if(res.data.status==1){
                    if(this.q==1){
                        this.paytype = '1'
                        this.alipay_appId=res.data.data.result.alipay_appId,
                        this.alipay_gatewayUrl=res.data.data.result.alipay_gatewayUrl,
                        this.alipay_rsaPrivateKey=res.data.data.result.alipay_rsaPrivateKey,
                        this.alipay_alipayrsaPublicKey=res.data.data.result.alipay_alipayrsaPublicKey
                    }else if(this.q==2){
                        this.paytype = '2'
                        this.wxpay_APPID=res.data.data.result.wxpay_APPID,
                        this.wxpay_MCHID=res.data.data.result.wxpay_MCHID,
                        this.wxpay_KEY=res.data.data.result.wxpay_KEY,
                        this.wxpay_APPSECRET=res.data.data.result.wxpay_APPSECRET,
                        // this.wxpay_apiclient_cert=res.data.data.result.wxpay_apiclient_cert,
                        // this.wxpay_apiclient_key=res.data.data.result.wxpay_apiclient_key
                        this.wxpay_apiclient_cert='',
                        this.wxpay_apiclient_key=''
                    }else if(this.q==3){
                        this.paytype = '3'
                        this.wxpay_APPID=res.data.data.result.wxpay_APPID,
                        this.wxpay_MCHID=res.data.data.result.wxpay_MCHID,
                        this.wxpay_KEY=res.data.data.result.wxpay_KEY,
                        this.wxpay_APPSECRET=res.data.data.result.wxpay_APPSECRET,
                        // this.wxpay_apiclient_cert=res.data.data.result.wxpay_apiclient_cert,
                        // this.wxpay_apiclient_key=res.data.data.result.wxpay_apiclient_key
                        this.wxpay_apiclient_cert='',
                        this.wxpay_apiclient_key=''
                    }else if(this.q==4){
                        this.paytype = '4'
                        this.wxpay_APPID=res.data.data.result.wxpay_APPID,
                        this.wxpay_MCHID=res.data.data.result.wxpay_MCHID,
                        this.wxpay_KEY=res.data.data.result.wxpay_KEY,
                        this.wxpay_APPSECRET=res.data.data.result.wxpay_APPSECRET,
                        // this.wxpay_apiclient_cert=res.data.data.result.wxpay_apiclient_cert,
                        // this.wxpay_apiclient_key=res.data.data.result.wxpay_apiclient_key
                        this.wxpay_apiclient_cert='',
                        this.wxpay_apiclient_key=''
                    }
                }else{
                    this.paytype = ''
                    this.wxpay_APPID='',
                    this.wxpay_MCHID="",
                    this.wxpay_KEY="",
                    this.wxpay_APPSECRET="",
                    this.wxpay_apiclient_cert="",
                    this.wxpay_apiclient_key="",
                    this.alipay_appId="",
                    this.alipay_gatewayUrl="",
                    this.alipay_rsaPrivateKey="",
                    this.alipay_alipayrsaPublicKey=""
                }
            })
        }
    },
    mounted(){
        this.getlist()
    }
}
</script>
<style lang="less" scoped>
.ccia{
    .ccia_top{
        button{
            // width: 100px;
            height: 32px;
            line-height: 32px;
            padding: 0 10px;
            color: #ffffff;
            border: 0;
            font-size: 12px;
            border-radius: 2px;
            background-color:rgb(1, 119, 213);
            outline: none;
            cursor: pointer;
            float: left;
        }
        >div{
            float: right;
            width: 60%;
            button{
                border-radius: 0;
                padding: 0 16px;
                float: right;
            }
        }
        .el-select{
            float: right;
            width: 100px;
            border-radius: 0;
        }
        .el-input{
            float: right;
            width: 70%;
            border-radius: 0;
        }
        /deep/ .el-input__inner{
            border-radius: 0;
        }
    }
}
.edit{
    display: inline-block;
    width: 22px;
    height: 22px;
    border-radius: 2px;
    border: 1px solid #EFF0F0;
    // border: 1px solid red;
    text-align: center;
    cursor: pointer;
    i{
        line-height: 22px;
        font-size: 13px;
    }
    
}
.edit:hover{
    border: 1px solid #28B7A3;
    i{
        color: #28B7A3;
    }
}

.ccia1{
    .ccia_top{
        button{
             // width: 100px;
            height: 32px;
            line-height: 32px;
            padding: 0 10px;
            color: #ffffff;
            border: 0;
            font-size: 12px;
            border-radius: 2px;
            background-color:rgb(1, 119, 213);
            outline: none;
            // cursor: pointer;
            float: left;
        }
    }
    .ccia_contentbox{
        border-top: 1px solid #EDECEC;
        .ccia_content{
            overflow: hidden;
            .el-input{
                width: 50%;
            }
            .content_top{
                margin-top:30px;
                >span:nth-of-type(1){
                    width: 150px;
                    display: inline-block;
                    text-align: right;
                    margin-right:20px;
                    height: 32px;
                    line-height: 32px;
                    font-size: 13px;
                }
                >span:nth-of-type(2){
                    position:absolute;
                    left: 175px;
                    top:40px;
                    font-size: 12px;
                    color :#999999;
                    font-family:PingFang-SC-Medium;
                    font-weight:500;
                }
                .el-select{
                    width: 100px;
                    height: 32px;;
                }
                >div{
                    display: inline;
                }
            }
       
       
            div:last-child{
                button{
                    // width: 100px;
                    min-width: 58px;
                    height: 32px;
                    line-height: 32px;
                    padding: 0 15px;
                    color: #ffffff;
                    border: 0;
                    font-size: 12px;
                    border-radius: 2px;
                    background-color:rgb(1, 119, 213);
                    outline: none;
                    cursor: pointer;
                    // float: left;
                }
                button:nth-of-type(1){
                    margin-left: 170px;
                    margin-right:10px;
                }
                button:nth-of-type(2){
                    color: #000;
                    padding: 0 18px;
                    background-color: #fff;
                    font-size: 13px;
                    border: 1px solid #EFEFEF;
                    
                }
            }
        }
    }
    .submitbox{
        margin-top: 25px;
        margin-left: 5px;
        >p{
            font-size: 14px;
            color: #999;
            padding-left: 169px;
            margin-top: 10px;
        }
    }
}
</style>


