<template>
    <div class="page">

        <el-button type="primary" style="margin-bottom: 20px;" @click="btn1">添加新轮播图</el-button>
        
        <el-select v-model="type" placeholder="请选择" @change="ss" style="float:right">
            <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value">
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
            prop="sort"
            label="排序" align="center"
            >
            </el-table-column>
            <el-table-column
            prop="jumpurl" align="center"
            label="跳转链接地址">
            </el-table-column>
            <el-table-column
            prop="url" align="center"
            label="图片url">
                <template slot-scope="scope">
                    <img :src="scope.row.url" alt="" style="width:50px;height:50px">
                </template>
            </el-table-column>
            <el-table-column
            prop="type_text" align="center"
            label="类型">
            </el-table-column>
            <el-table-column
            prop="status_text" align="center"
            label="状态">
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
                <el-form-item label="排序">
                    <el-input v-model="form.sort"></el-input>
                </el-form-item>
                <el-form-item label="跳转链接地址">
                    <el-input v-model="form.jumpurl"></el-input>
                </el-form-item>
                <el-form-item label="类型">
                    <el-radio v-model="form.type" label="1">首页轮播图</el-radio>
                    <el-radio v-model="form.type" label="2">保险页面图</el-radio>
                    <el-radio v-model="form.type" label="3">新闻页面轮播图</el-radio>
                </el-form-item>
                <el-form-item label="状态">
                    <el-radio v-model="form.status" label="1">显示</el-radio>
                    <el-radio v-model="form.status" label="2">不显示</el-radio>
                </el-form-item>
                <el-form-item label="图片">
                    <el-upload
                    class="avatar-uploader"
                    :action="`${api}upload`"
                    :show-file-list="false"
                    :before-upload="beforeAvatarUpload">
                    <img v-if="form.url" :src="form.url" class="avatar" style="width:100px;height:100px;">
                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item label="新闻" v-if='form.type == "3"'>
                    <el-select v-model="form.newsid" placeholder="请选择">
                        <el-option
                        v-for="item in options1"
                        :key="item.id"
                        :label="item.title"
                        :value="item.id">
                        </el-option>
                    </el-select>
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
                    url:'',
                    jumpurl:'',
                    sort:'',
                    type:'',
                    url:'',
                    status:'',
                },
                dialogVisible:false,
                page:1,
                limit:15,
                keyword:'',
                total:0,
                currentPage: 1,
                tableData: [],

                options: [{
                value: '',
                label: '全部'
                }, {
                value: '1',
                label: '首页轮播图'
                }, {
                value: '2',
                label: '保险页面图'
                }, {
                value: '3',
                label: '新闻页面轮播图'
                }],
                type: '',
                options1:[],
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
                        url:`${this.api}admin/banner/${e.id}`,
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
                this.form.newsid = ''
                this.form.status = ''
                this.form.type = ''
                this.form.sort = ''
                this.form.jumpurl = ''
                this.form.url = ''
                this.bjid = ''
            },
            xzbtn(){
                this.$axios({
                    method:'post',
                    url:`${this.api}admin/banner`,
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
                    url:`${this.api}admin/banner/${this.bjid}`,
                    params:{

                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        this.form = res.data.data
                        this.form.type = this.form.type.toString()
                        this.form.status = this.form.status.toString()
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
            },
            bjbtn(){
                this.$axios({
                    method:'put',
                    url:`${this.api}admin/banner/${this.bjid}`,
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
                            that.form.url = res.data.data.url;
                            console.log(that.form)
                        }
                    });
                }
            },




            getlist(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/banner`,
                    params:{
                        type:this.type,
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
            getlist1(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/news`,
                    params:{
                        limit:9999999999999999,
                        page:1
                    },
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        this.options1 = res.data.data.data
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