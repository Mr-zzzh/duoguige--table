<template>
    <div class="page">
        <header>
            <div>
                标题：{{this.form.title}}
            </div>

            <div>
                <!-- <img src="../../../static/img/news1.png" alt="" style="width: 16px;height: 12px;">
                <span style="margin-right:20px">{{this.form.view_number}}</span>
                <img src="../../../static/img/news3.png" alt="" style="width: 15px;height: 15px;"> -->
                <span style="margin-right:20px">{{this.form.uname}}</span>
                <img src="../../../static/img/news2.png" alt="" style="width: 12px;height: 12px;">
                <span style="margin-right:20px">{{this.form.createtime}}</span>
            </div>

            <img :src="ia" alt="" v-for='ia in this.form.thumb' :key="ia.id" style="width:100px;height:100px;margin:10px">

        </header>


        <section class="section1">
            <div>共{{this.total}}条评论</div>

            <div class="box" v-for="ia in this.form1" :key="ia.id">
                <div>
                    <img :src="ia.avatar" alt="" style="border-radius: 50%;">
                </div>
                <div>
                    <div>
                        <span style="margin-right:10px">{{ia.name}}</span>
                        <span>{{ia.createtime}}</span>
                        <span style="float:right;margin-right:10px">
                            <i class="el-icon-delete" @click="tosc(ia.id)"></i>
                        </span>
                        <span style="float:right">
                            <img src="../../../static/img/news1.png" alt="" style="width: 16px;height: 12px;margin-right:10px" @click="xgzt(ia.id,2)" v-if="ia.status == 1">
                            <img src="../../../static/img/yc.png" alt="" style="width: 16px;height: 12px;margin-right:10px" @click="xgzt1(ia.id,1)" v-if="ia.status == 2">
                        </span>
                    </div>

                    <div style="margin-top:10px;" v-html="ia.answer">
                    </div>
                </div>
            </div>
            <div v-if="this.gdbol" @click="gdinfo()" style="margin-top: 20px;margin-left: 30%;font-size: 15px;color: #666;">点击查看更多评论</div>
        </section>


    </div>
</template>

<script>
    export default {
        name: '',
        data() {
            return {
                id:'',
                limit:15,
                page:1,
                form:{},
                form1:{},
                total:0,
                gdbol:true,
                ycbol:0,
            }
        },
        methods:{
            tosc(e){
                this.$confirm('是否确定删除?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios({
                        method:'post',
                        url:`${this.api}admin/question/delete_answer`,
                        data:{
                            id:e
                        }
                    }).then(res=>{
                        if(res.data.status==1){
                            this.$message.success(res.data.message)
                            this.page = 1
                            this.getinfo1()
                            this.getinfo2()
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

            
            xgzt(e,q){
                this.$axios({
                    method:'post',
                    url:`${this.api}admin/question/edit_status`,
                    data:{
                        id:e,
                        status:q
                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        this.getinfo1()
                        this.$message.success(res.data.message)
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
            },
            xgzt1(e,q){
                this.$axios({
                    method:'post',
                    url:`${this.api}admin/question/edit_status`,
                    data:{
                        id:e,
                        status:q
                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        this.getinfo1()
                        this.$message.success(res.data.message)
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
            },

            // 新闻数据
            getinfo(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/question/${this.id}`,
                    params:{

                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        this.form = res.data.data
                        console.log(res.data.data)
                    }
                })
            },
            // 评论数据
            getinfo1(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/question/answer`,
                    params:{
                        id:this.id,
                        limit:this.limit,
                        page:this.page
                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        this.form1 = res.data.data.data
                        this.total = res.data.data.total
                        console.log(res.data.data)
                    }
                })
            },
            // 点击加载更多
            gdinfo(){
                this.page++
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/question/answer`,
                    params:{
                        id:this.id,
                        limit:this.limit,
                        page:this.page
                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        res.data.data.data.forEach(res => {
                            this.form1.push(res)
                        });
                        this.getinfo2()
                        console.log(res.data.data.data == '')
                    }
                })
            },
            // 判断下一页还有没有数据
            getinfo2(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/question/answer`,
                    params:{
                        id:this.id,
                        limit:this.limit,
                        page:this.page+1
                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        if(res.data.data.data == ''){
                            this.gdbol = false
                        }else{
                            this.gdbol = true
                        }
                        console.log(res.data.data.data == '')
                    }
                })
            },
        },
        created(){
            this.id = this.$route.query.id
            this.getinfo()
            this.getinfo1()
            this.getinfo2()
        }
    }
</script>

<style lang="less" scoped>
    header{
        >div:nth-child(1){
            font-size: 24px;
            font-weight: bold;
        }
        >div:nth-child(2){
            margin: 20px 0 20px 0;
            font-size: 14px;
            color: #666;
        }
    }

    .section1{
        margin-top: 40px;
        >div:nth-child(1){
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .box{
            display: flex;
            margin-top: 40px;
            width: 60%;
            >div:nth-child(1){
                >img{
                    width: 40px;
                    height: 40px;
                    margin-right: 20px;
                }
            }
            >div:nth-child(2){
                flex: 1;
                >div:nth-child(1){
                    font-size: 16px;
                    color: #666;
                }
            }
        }
    }
</style>