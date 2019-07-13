<template>
    <div class="page">

        <el-button type="primary" style="margin-bottom: 20px;" @click="btn1">添加故障信息</el-button>
        
        <el-input placeholder="请输入内容" v-model="keyword" class="input-with-select" style="width:20%;background: white;float: right;">
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

        <el-select v-model="bid" placeholder="请选择" @change="xl" style="float:right;margin-right:10px">
            <el-option
            v-for="item in options"
            :key="item.id"
            :label="item.name"
            :value="item.id">
            </el-option>
        </el-select>


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
            prop="fault_code"
            label="故障代码" align="center"
            >
            </el-table-column>
            <el-table-column
            prop="bname" align="center"
            label="品牌名">
            </el-table-column>
            <el-table-column
            prop="models" align="center"
            label="适用机型">
            </el-table-column>
            <el-table-column
            prop="paraphrase" align="center"
            label="代码释义">
            </el-table-column>
            <el-table-column
            prop="dispose" align="center"
            label="处理办法">
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
                <el-form-item label="故障代码">
                    <el-input v-model="form.fault_code"></el-input>
                </el-form-item>
                <el-form-item label="品牌">
                    <el-select v-model="form.bid" placeholder="请选择">
                        <el-option
                        v-for="item in options1"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="适用机型">
                    <el-input v-model="form.models"></el-input>
                </el-form-item>
                <el-form-item label="代码释义">
                    <el-input v-model="form.paraphrase" type="textarea"></el-input>
                </el-form-item>
                <el-form-item label="处理办法">
                    <el-input v-model="form.dispose" type="textarea"></el-input>
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
                options1:[],
                options:[
                    {
                        id:"",
                        name:'全部品牌'
                    }
                ],
                bid:'',
                bjid:'',
                form:{
                    fault_code:'',
                    bid:'',
                    models:'',
                    paraphrase:'',
                    dispose:'',
                },
                dialogVisible:false,
                page:1,
                limit:15,
                keyword:'',
                total:0,
                currentPage: 1,
                tableData: [],

                timer:'',
                starttime:'',
                endtime:'',
            }
        },
        methods:{
            // 时间
            xzfl(e){
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
            xl(e){
              this.page = 1
              this.getlist()  
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
                        url:`${this.api}admin/fault/${e.id}`,
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
                this.form.fault_code = ''
                this.form.bid = ''
                this.form.models = ''
                this.form.paraphrase = ''
                this.form.dispose = ''
                this.bjid = ''
            },
            xzbtn(){
                this.$axios({
                    method:'post',
                    url:`${this.api}admin/fault`,
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
                    url:`${this.api}admin/fault/${this.bjid}`,
                    params:{

                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        this.form = res.data.data
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
            },
            bjbtn(){
                this.$axios({
                    method:'put',
                    url:`${this.api}admin/fault/${this.bjid}`,
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
                    url:`${this.api}admin/fault`,
                    params:{
                        keyword:this.keyword,
                        limit:this.limit,
                        page:this.page,
                        starttime:this.starttime,
                        endtime:this.endtime,
                        bid:this.bid
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
                        this.options1 = res.data.data.data
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
            this.getlist1()
        }
    }
</script>

<style lang="less" scoped>
    
</style>