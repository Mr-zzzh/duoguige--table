<template>
    <div class="page">

        <el-button type="primary" style="margin-bottom: 20px;" @click="btn1">添加商品</el-button>
        
        <el-input placeholder="请输入内容" v-model="keyword" class="input-with-select" style="width:700px;background: white;float: right;">
            <el-button slot="append" type="primary" @click="ss(keyword)" icon="el-icon-search" style="background:#409EFF;color: white;"></el-button>
        </el-input>

        <el-select v-model="cid" placeholder="请选择" @change="xzfl" style="float:right;margin-right:20px">
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
            <!-- <el-table-column
            type="index"
            label="序号"
            width="100"
            align="center"
            >
            </el-table-column> -->
            <el-table-column
            prop="sort"
            label="排序" align="center"
            >
            </el-table-column>
            <el-table-column
            prop="name" align="center"
            label="商品名">
            </el-table-column>
            <el-table-column
            prop="subhead" align="center"
            label="副标题">
            </el-table-column>
            <el-table-column
            prop="bnam" align="center"
            label="品牌名">
            </el-table-column>
            <el-table-column
            prop="thumbnail" align="center"
            label="缩略图">
                <template slot-scope="scope">
                    <img :src="scope.row.thumbnail" alt="" style="width:50px;height:50px">
                </template>
            </el-table-column>
            <el-table-column
            prop="manufacturers" align="center"
            label="厂家名称">
            </el-table-column>
            <el-table-column
            prop="phone" align="center"
            label="销售电话">
            </el-table-column>
            <el-table-column
            prop="price" align="center"
            label="价格">
            </el-table-column>
            <el-table-column
            prop="sale_number" align="center"
            label="销量">
            </el-table-column>
            <el-table-column
            prop="label" align="center"
            label="标签">
            </el-table-column>
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


    </div>
</template>

<script>
    export default {
        name: '',
        data() {
            return {
                options: [
                    {
                        id:'',
                        name:'全部分类'
                    }
                ],
                cid: '',
                bjid:'',
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
                        url:`${this.api}admin/goods/${e.id}`,
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
                this.bjid = ''
                this.$router.push({
                    name:'admin_dtgladd',
                    query:{
                        bjid:this.bjid
                    }
                })
            },
            // 编辑
            bj(e){
                this.bjid = e.id
                this.$router.push({
                    name:'admin_dtgladd',
                    query:{
                        bjid:this.bjid
                    }
                })
            },
            // 搜索
            ss(){
                this.page = 1
                this.limit = 15
                this.getlist()
            },
            // 分类
            xzfl(){
              this.page = 1
              this.limit = 15
              this.getlist()  
            },




            getlist(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/goods`,
                    params:{
                        keyword:this.keyword,
                        limit:this.limit,
                        page:this.page,
                        cid:this.cid
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
            // 获取分类
            getlist1(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/goodscate`,
                    params:{
                        limit:9999999,
                        page:1
                    },
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        res.data.data.data.forEach(res => {
                            this.options.push(res)
                        });
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