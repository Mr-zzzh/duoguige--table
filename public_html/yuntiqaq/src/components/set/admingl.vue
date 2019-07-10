<template>
    <div class="page">

        <el-button type="primary" style="margin-bottom: 20px;" @click="btn1">添加操作员</el-button>
        
        <el-input placeholder="请输入内容" v-model="keyword" class="input-with-select" style="width:700px;background: white;float: right;">
            <el-button slot="append" type="primary" @click="ss(keyword)" icon="el-icon-search" style="background:#409EFF;color: white;"></el-button>
        </el-input>

        <el-select v-model="status" placeholder="请选择" @change="ss" style="float:right;margin-right:10px">
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
            prop="phone"
            label="手机号" align="center"
            >
            </el-table-column>
            <el-table-column
            prop="name" align="center"
            label="账号">
            </el-table-column>
            <el-table-column
            prop="status_text" align="center"
            label="状态">
            </el-table-column>
            <el-table-column
            prop="avatar" align="center"
            label="用户头像">
                <template slot-scope="scope">
                    <img :src="scope.row.avatar" alt="" style="width:50px;height:50px">
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
                <el-form-item label="手机号">
                    <el-input v-model="form.phone"></el-input>
                </el-form-item>
                <el-form-item label="密码">
                    <el-input v-model="form.password" type="password"></el-input>
                </el-form-item>
                <el-form-item label="状态">
                    <el-radio v-model="form.status" label="0">禁用</el-radio>
                    <el-radio v-model="form.status" label="1">启用</el-radio>
                </el-form-item>
                <el-form-item label="账号头像">
                    <el-upload
                    class="avatar-uploader"
                    :action="`${api}upload`"
                    :show-file-list="false"
                    :before-upload="beforeAvatarUpload">
                    <img v-if="form.avatar" :src="form.avatar" class="avatar" style="width:100px;height:100px;">
                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
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
                    phone:'',
                    password:'',
                    avatar:'',
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
                label: '全部状态'
                }, {
                value: '0',
                label: '禁用'
                }, {
                value: '1',
                label: '启用'
                }],
                status:'',
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
                        url:`${this.api}admin/admin/${e.id}`,
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
                this.form.phone = ''
                this.form.password = ''
                this.form.avatar = ''
                this.form.status = ''
                this.bjid = ''
            },
            xzbtn(){
                if(!(/^1[3456789]\d{9}$/.test(this.form.phone))){ 
                    this.$message.error('手机号码有误，请重填') 
                    return; 
                } 
                this.$axios({
                    method:'post',
                    url:`${this.api}admin/admin`,
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
                    url:`${this.api}admin/admin/${this.bjid}`,
                    params:{

                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        this.form = res.data.data
                        this.form.status = this.form.status.toString()
                        this.form.password = ''
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
            },
            bjbtn(){
                if(!(/^1[3456789]\d{9}$/.test(this.form.phone))){ 
                    this.$message.error('手机号码有误，请重填') 
                    return; 
                } 
                this.$axios({
                    method:'put',
                    url:`${this.api}admin/admin/${this.bjid}`,
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
                            that.form.avatar = res.data.data.url;
                            console.log(that.form)
                        }
                    });
                }
            },




            getlist(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/admin`,
                    params:{
                        status:this.status,
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