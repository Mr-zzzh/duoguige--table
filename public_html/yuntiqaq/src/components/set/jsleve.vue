<template>
    <div class="page">

        <el-button type="primary" style="margin-bottom: 20px;" @click="btn1">添加技师等级</el-button>
        
  


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
            prop="name"
            label="规则名称" align="center"
            >
            </el-table-column>
            <el-table-column
            prop="score" align="center"
            label="等级名称">
            <template slot-scope="scope">
                <div v-for="ia in scope.row.content" :key="ia.id">
                    {{ia.name}}
                </div>
            </template>
            </el-table-column>
            <el-table-column
            prop="score" align="center"
            label="晋级条件">
            <template slot-scope="scope">
                <div style="display:flex">
                    <div style="flex:1">
                        <div v-for="ia in scope.row.content" :key="ia.id">
                           分数：{{ia.min_score}}--{{ia.max_score}}
                        </div>
                    </div>
                    <div style="flex:1">
                        <div v-for="ia in scope.row.content" :key="ia.id">
                            订单量：{{ia.min_number}}--{{ia.max_number}}
                        </div>
                    </div>
                </div>
            </template>
            </el-table-column>
            <el-table-column
            prop="status_text" align="center"
            label="状态">
            <template slot-scope="scope">
                <el-switch
                v-model="scope.row.status"
                active-color="#13ce66"
                inactive-color="#ff4949"
                active-value="1"
                @change="gbzt(scope.row.id,scope.row.status)"
                inactive-value="2">
                </el-switch>
            </template>
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
                <el-form-item label="名称">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="状态">
                    <el-radio v-model="form.status" label="1">开启</el-radio>
                    <el-radio v-model="form.status" label="2">不开启</el-radio>
                </el-form-item>
                <el-form-item label="晋级条件">
                    <div v-for="(ia,index) in form.content" :key='ia.id' style="margin-bottom:10px;">
                        <el-input v-model="ia.name" style='width: 10%;'></el-input>
                        <span>分数:</span>
                        <el-input v-model="ia.min_score" style='width: 10%;'></el-input>
                        <span>-</span>
                        <el-input v-model="ia.max_score" style='width: 10%;'></el-input>
                        <span>订单量:</span>
                        <el-input v-model="ia.min_number" style='width: 10%;'></el-input>
                        <span>-</span>
                        <el-input v-model="ia.max_number" style='width: 10%;'></el-input>
                        <i class="el-icon-remove-outline" style="font-size:24px" @click="sctj(index)"></i>
                    </div>
                    <i class="el-icon-circle-plus-outline" style="font-size: 30px" @click="xztj"></i>
                </el-form-item>
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
                form:{
                    name:'',
                    status:'',
                    content:[{}],
                },
                dialogVisible:false,
                page:1,
                limit:15,
                total:0,
                currentPage: 1,
                tableData: [],

            }
        },
        methods:{
            gbzt(e,q){
                console.log(q)
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/grade/${e}`,
                    params:{
                        status:q
                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data)
                        this.page = 1
                        this.limit = 15
                        this.getlist()
                        this.$message.success(res.data.message)
                    }else{
                        this.$messages.error(res.data.message)
                    }
                })
            },
            // 新增晋级条件
            xztj(){
                this.form.content.push({})
            },
            sctj(e){
                this.form.content.splice(e,1)
            },
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
                this.limit = 15
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
                        url:`${this.api}admin/grade/${e.id}`,
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
                this.form.score = ''
                this.form.number = ''
                this.form.status = ''
                this.bjid = ''
            },
            xzbtn(){
                this.$axios({
                    method:'post',
                    url:`${this.api}admin/grade`,
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
                console.log(e)
                this.form.name = e.name
                this.form.status = e.status
                this.form.content = e.content
                this.bjid = e.id
                this.dialogVisible = true
                // this.$axios({
                //     method:'get',
                //     url:`${this.api}admin/grade/${this.bjid}`,
                //     params:{

                //     }
                // }).then(res=>{
                //     if(res.data.status == 1){
                //         console.log(res.data.data)
                //         this.form = res.data.data
                //         this.form.status = this.form.status.toString()
                //     }else{
                //         this.$message.error(res.data.message)
                //     }
                // })
            },
            bjbtn(){
                this.$axios({
                    method:'put',
                    url:`${this.api}admin/grade/${this.bjid}`,
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
                this.limit = 15
                this.getlist()
            },


            getlist(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/grade`,
                    params:{
                        limit:this.limit,
                        page:this.page
                    },
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        this.tableData = res.data.data.data
                        this.tableData.forEach(res=>{
                            res.status = res.status.toString()
                        })
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