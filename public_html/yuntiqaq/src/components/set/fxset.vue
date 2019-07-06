<template>
    <div class="page">
        <el-form ref="form" :model="form" label-width="140px">
            <el-form-item label="分享标题">
                <el-input v-model="form.title"></el-input>
            </el-form-item>
            <el-form-item label="分享图标">
                <el-upload
                class="avatar-uploader"
                :action="`${api}upload`"
                :show-file-list="false"
                :before-upload="beforeAvatarUpload">
                <img v-if="form.icon" :src="form.icon" class="avatar" style="width:100px;height:100px;">
                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                </el-upload>
            </el-form-item>
            <el-form-item label="分享描述">
                <el-input v-model="form.intro"></el-input>
            </el-form-item>
            <el-form-item label="分享链接">
                <el-input v-model="form.share_link"></el-input>
            </el-form-item>
        </el-form>

        <el-button style="margin-left: 40%;" type="primary" @click="onSubmit">保存</el-button>
    </div>
</template>

<script>
    export default {
        name: '',
        data() {
            return {
                form:{
                    icon:'',
                },
                
            }
        },
        methods:{
            onSubmit(){
                this.$axios({
                    method:'put',
                    url:`${this.api}admin/share/1`,
                    data:this.form
                }).then(res=>{
                    if(res.data.status == 1){
                        this.$message.success(res.data.message)
                        console.log(res.data.data)
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
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
                            that.form.icon = res.data.data.url;
                            console.log(that.form)
                        }
                    });
                }
            },

            getlist(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/share/1`,
                    params:{

                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        if(res.data.data != null){
                            this.form = res.data.data
                        }
                        console.log(res.data.data)
                    }
                })
            }
        },
        created(){
            this.getlist()
        }
    }
</script>

<style lang="less" scoped>
    
</style>