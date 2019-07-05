<template>
    <div class="page" style="height:600px">

        <!-- <el-button type="primary" style="margin-bottom: 20px;" @click="btn1">添加电梯</el-button>
        
        <el-input placeholder="请输入内容" v-model="keyword" class="input-with-select" style="width:700px;background: white;float: right;">
            <el-button slot="append" type="primary" @click="ss(keyword)" icon="el-icon-search" style="background:#409EFF;color: white;"></el-button>
        </el-input>

        <el-date-picker  @change="xzfl(timer)" style="float:right;margin-right:20px"
        v-model="timer"
        type="datetimerange"
        value-format='yyyy-MM-dd hh-mm-ss'
        range-separator="至"
        start-placeholder="开始日期"
        end-placeholder="结束日期">
        </el-date-picker> -->


        <section style="height:600px">
            <div style="overflow: hidden;">
                <div v-for="(ia,index) in this.options" :key="ia.id" @click="toxz(index,ia.id)" class="box-l" style="">
                    <img :src="ia.logo" alt="" style="width:50px;height:50px;float:left;margin-right:10px">
                    <span class="yc" :class="ind==index?'bs':''">{{ia.name}}</span>
                </div>
            </div>

            <div style="overflow: hidden;"> 
                <div class="box" style="cursor: pointer;float: left;" @click="toxzbtn">
                    <img src="../../../../static/img/sc.png" alt="">
                    <div class="yc" style="font-size:12px"></div>
                    <div style="font-size:12px">上传</div>
                </div>
                <div class="box" v-for="(ia,index) in this.tableData" :key="ia.id" @click.right='scbtn(index,ia.id)' style="position: relative;" :tabindex="index" @blur="gbsc()">
                    <img src="../../../../static/img/pdf.png" alt="">
                    <div class="yc" style="font-size:12px">{{ia.name}}</div>
                    <div style="font-size:12px">{{ia.createtime}}</div>
                    <div class="scys" v-if='scind == index'>
                        <div @click="sc(ia.id)">删除</div>
                    </div>
                </div>
            </div>
        </section>


        <el-dialog
        title="新增or编辑"
        :visible.sync="dialogVisible"
        width="30%"
        :before-close="handleClose">
        <span>
            <el-form ref="form" :model="form" label-width="80px">
                <el-form-item label="资料标题">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="资料上传">
                    <el-upload
                    class="avatar-uploader"
                    :action="`${api}upload`"
                    :show-file-list="false"
                    :before-upload="beforeAvatarUpload">
                    <img v-if="form.datum" :src="form.datum" class="avatar" style="width:100px;height:100px;">
                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>
            </el-form>
        </span>
        <span slot="footer" class="dialog-footer">
            <el-button @click="dialogVisible = false">取 消</el-button>
            <el-button type="primary" @click="dialogVisible = false">确 定</el-button>
        </span>
        </el-dialog>



    </div>
</template>

<script>
    export default {
        name: '',
        data() {
            return {
                dialogVisible:false,
                form:{},


                scind:-12,
                ind:0,
                options:[],
                bid:'',
                timer: '',
                bjid:'',
                page:1,
                limit:15,
                keyword:'',
                total:0,
                currentPage: 1,
                tableData: [],
                starttime:'',
                endtime:'',
            }
        },
        methods:{
            handleClose(){
                this.dialogVisible = false
            },
            toxz(e,q){
                this.ind = e
                this.bid = q
                this.getlist()
            },
            // 弹窗
            handleClose(){
                this.dialogVisible = false
            },
            // 删除
            gbsc(){
                console.log(111)
                this.scind = -1
            },
            scbtn(e,q){
                document.oncontextmenu=function(){/*阻止浏览器默认弹框*/
                    return false;
                };
                this.scind = e
                // this.sc(q)
            },
            sc(e){
                this.$confirm('是否确定删除?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios({
                        method:'delete',
                        url:`${this.api}dmin/branddatum/${e}`,
                        data:{
                        }
                    }).then(res=>{
                        if(res.data.status==1){
                            this.$message.success(res.data.message)
                            this.page = 1
                            this.getlist()
                        }else{
                            this.$message.error(res.data.message)
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            // 搜索
            ss(){
                this.page = 1
                this.limit = 15
                this.getlist()
            },
            // 时间
            xzfl(e){
                this.starttime = [0]
                this.endtime = [1]
              this.page = 1
              this.limit = 15
              this.getlist()  
            },
            // 新增
            btn1(){
                this.dialogVisible = true
                this.form.name = ''
                this.bjid = ''
            },
            xzbtn(){
                this.$axios({
                    method:'post',
                    url:`${this.api}admin/goodscate`,
                    data:this.form
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        this.$message.success(res.data.message)
                        this.dialogVisible = false
                        this.page = 1
                        this.getlist()
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
            },
            // 编辑
            bj(e){
                this.bjid = e.id
                this.dialogVisible = true
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/goodscate/${this.bjid}`,
                    params:{

                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        this.form.name = res.data.data.name
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
            },
            bjbtn(){
                this.$axios({
                    method:'put',
                    url:`${this.api}admin/goodscate/${this.bjid}`,
                    data:this.form
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        this.$message.success(res.data.message)
                        this.dialogVisible = false
                        this.page = 1
                        this.getlist()
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
            },

            toxzbtn(){
                this.dialogVisible = true
            },
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
                            that.form.datum = res.data.data.url;
                            console.log(that.form)
                        }
                    });
                }
            },




            getlist(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/branddatum`,
                    params:{
                        bid:this.bid,
                        limit:9999999999999999999,
                        page:1,
                    },
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data.data)
                        this.tableData = res.data.data.data
                        console.log(this.tableData)
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
            },
            getlist1(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/brand`,
                    params:{
                        limit:99999999,
                        page:1,
                    },
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        res.data.data.data.forEach(res => {
                            this.options.push(res)
                        });
                        this.bid = this.options[0].id
                        this.getlist()
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
            },
        },
        mounted(){

        },
        created(){
            this.getlist1()
        }
    }
</script>

<style lang="less" scoped>
    section{
        // margin-top: 30px;
        display: flex;
        >div:nth-child(1){
            flex: 1;
            border-right: 1px solid #ccc;
            margin-right: 20px;
            padding-right: 20px;
        }
        >div:nth-child(2){
            flex: 5
        }
        .box-l{
            line-height:50px;
            line-height: 50px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            cursor: pointer;
        }
        .box{
            width: 100px;
            display: inline-block;
            text-align: center;
        }
    }


    .yc{
        overflow: hidden;
        text-overflow: ellipsis;
        //判断超出一行隐藏
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }
    .bs{
        color: #409EFF;
    }

    .scys{
        position: absolute;
        top: 14%;
        background: #fff;
        right: 5px;
        border: 1px solid #ccc;
        padding: 5px;
        cursor: pointer;
        font-size: 12px;
    }
</style>