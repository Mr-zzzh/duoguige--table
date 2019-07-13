<template>
    <div class="page">

        <el-button type="primary" style="margin-bottom: 20px;" @click="btn1">添加新标签</el-button>
        
        <el-input placeholder="请输入内容" v-model="keyword" class="input-with-select" style="width:700px;background: white;float: right;">
            <el-button slot="append" type="primary" @click="ss(keyword)" icon="el-icon-search" style="background:#409EFF;color: white;"></el-button>
        </el-input>


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
            <!-- <el-table-column
            prop=""
            label="显示顺序" align="center"
            >
            </el-table-column> -->
            <el-table-column
            prop="name" align="center"
            label="分类名称">
            </el-table-column>
            <!-- <el-table-column
            prop="address" align="center"
            label="状态">
            </el-table-column> -->
            <el-table-column
            prop="createtime" align="center"
            label="操作时间">
            </el-table-column>
            <el-table-column
            align="center"
            label="操作"
            >
            <template slot-scope="scope">
                <el-button @click="sc(scope.row)" type="text" size="small">删除</el-button>
                <el-button @click="bj(scope.row)" type="text" size="small">编辑</el-button>
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


        <!-- 新增or编辑 -->
        <el-dialog
        title="新增or编辑"
        :visible.sync="dialogVisible"
        width="60%"
        :before-close="handleClose">
        <span>
            <el-form ref="form" :model="form" label-width="100px">
                <el-form-item label="标签名">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <!-- <el-form-item label="活动名称">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="活动名称">
                    <el-radio v-model="radio" label="1">备选项</el-radio>
                    <el-radio v-model="radio" label="2">备选项</el-radio>
                </el-form-item> -->
            </el-form>
        </span>



        <span slot="footer" class="dialog-footer">
            <el-button @click="dialogVisible = false">取 消</el-button>
            <el-button type="primary" @click="xzbtn" v-if="this.bjid == ''">新增</el-button>
            <el-button type="primary" @click="bjbtn" v-if="this.bjid != ''">编辑</el-button>
        </span>
        </el-dialog>


    </div>
</template>

<script>
    export default {
        name: '',
        data() {
            return {
                bjid:'',
                form:{name:''},
                dialogVisible:false,
                page:1,
                limit:15,
                keyword:'',
                total:0,
                currentPage: 1,
                tableData: []
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
                        url:`${this.api}admin/goodslabel/${e.id}`,
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
            // 新增
            btn1(){
                this.dialogVisible = true
                this.form.name = ''
                this.bjid = ''
            },
            xzbtn(){
                this.$axios({
                    method:'post',
                    url:`${this.api}admin/goodslabel`,
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
                    url:`${this.api}admin/goodslabel/${this.bjid}`,
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
                    url:`${this.api}admin/goodslabel/${this.bjid}`,
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
            // 搜索
            ss(){
                this.page = 1
                this.getlist()
            },



            getlist(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/goodslabel`,
                    params:{
                        keyword:this.keyword,
                        limit:this.limit,
                        page:this.page
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