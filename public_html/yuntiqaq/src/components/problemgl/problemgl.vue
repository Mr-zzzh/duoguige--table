<template>
    <div class="page">

        <!-- <el-button type="primary" style="margin-bottom: 20px;" @click="btn1">添加电梯</el-button> -->
        
        <el-input placeholder="请输入内容" v-model="keyword" class="input-with-select" style="width:700px;background: white;float: right;margin-bottom: 20px;">
            <el-button slot="append" type="primary" @click="ss(keyword)" icon="el-icon-search" style="background:#409EFF;color: white;"></el-button>
        </el-input>

        <el-date-picker  @change="xzfl(timer)" style="float:right;margin-right:20px"
        v-model="timer"
        type="datetimerange"
        value-format='yyyy-MM-dd hh-mm-ss'
        range-separator="至"
        start-placeholder="开始日期"
        end-placeholder="结束日期">
        </el-date-picker>


        <el-table
            :data="tableData"
            border
            style="width: 100%">
            <el-table-column
            type="index"
            label="序号"
            width="100"
            align="center"
            >
            </el-table-column>
            <el-table-column
            prop="uname"
            label="提问人姓名" align="center"
            >
            </el-table-column>
            <el-table-column
            prop="title" align="center"
            label="标题">
            </el-table-column>
            <el-table-column
            prop="createtime" align="center"
            label="创建时间">
            </el-table-column>
            <el-table-column
            align="center"
            label="操作"
            >
            <template slot-scope="scope">
                <el-button @click="sc(scope.row)" type="text" size="small">删除</el-button>
                <!-- <el-button @click="bj(scope.row)" type="text" size="small">编辑</el-button> -->
                <el-button @click="xq(scope.row)" type="text" size="small">详情</el-button>
            </template>
            </el-table-column>
        </el-table>

        <el-pagination
        style="margin-top:20px"
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        :current-page="currentPage"
        :page-sizes="[15, 20, 30, 40]"
        :page-size="100"
        layout="total, sizes, prev, pager, next, jumper"
        :total="this.total">
        </el-pagination>


    </div>
</template>

<script>
    export default {
        name: '',
        data() {
            return {
                timer: '',
                // bjid:'',
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
            // 弹窗
            handleClose(){
                this.dialogVisible = false
            },
            // 分页-一页多少
            handleSizeChange(val) {
                this.limit = val
                this.page = 1
                this.getlist()
                console.log(`每页 ${val} 条`);
            },
            // 分页
            handleCurrentChange(val) {
                this.page = val
                this.getlist()
                console.log(`当前页: ${val}`);
            },
            // 删除
            sc(e){
                this.$confirm('是否确定删除?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios({
                        method:'delete',
                        url:`${this.api}admin/question/${e.id}`,
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
            // // 新增
            // btn1(){
            //     this.bjid = ''
            //     this.$router.push({
            //         name:'admin_newsadd',
            //         query:{
            //             bjid:this.bjid
            //         }
            //     })
            // },
            // // 编辑
            // bj(e){
            //     this.bjid = e.id
            //     this.$router.push({
            //         name:'admin_newsadd',
            //         query:{
            //             bjid:this.bjid
            //         }
            //     })
            // },
            // 详情
            xq(e){
                this.$router.push({
                    name:'admin_problemglxq',
                    query:{
                        id:e.id
                    }
                })
            },
            // 搜索
            ss(){
                this.page = 1
                this.getlist()
            },
            // 时间
            xzfl(e){
               console.log(e == null)
                if(e == null){
                    this.starttime = ''
                    this.endtime = ''
                }else{
                    this.starttime = e[0]
                    this.endtime = e[1]
                }
              this.page = 1
              this.getlist()  
            },




            getlist(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/question`,
                    params:{
                        keyword:this.keyword,
                        limit:this.limit,
                        page:this.page,
                        starttime:this.starttime,
                        endtime:this.endtime,
                    },
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        this.tableData = res.data.data.data
                        this.total = res.data.data.total
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
            },
        },
        mounted(){

        },
        created(){
            this.getlist()
           
        }
    }
</script>

<style lang="less" scoped>
    
</style>