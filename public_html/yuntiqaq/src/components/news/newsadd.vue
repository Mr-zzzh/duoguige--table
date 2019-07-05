<template>
    <div class="page">
        <el-form ref="form" :model="form" label-width="80px">
            <el-form-item label="标题">
                <el-input v-model="form.title"></el-input>
            </el-form-item>
            <el-form-item label="图片">
                <el-upload
                class="avatar-uploader"
                :action="`${api}upload`"
                :show-file-list="false"
                :before-upload="beforeAvatarUpload">
                <img v-if="form.thumb" :src="form.thumb" class="avatar" style="width:100px;height:100px">
                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                </el-upload>
            </el-form-item>
            <el-form-item label="类型">
                <el-radio v-model="form.type" label="1">图文</el-radio>
                <el-radio v-model="form.type" label="2">视频</el-radio>
            </el-form-item>
            <el-form-item label="视频" v-if='this.form.type == "2"'>
                <el-upload class="avatar-uploader el-upload--text" accept=".mp4" :action="`${api}upload`" :show-file-list="false" :before-upload="beforeUploadVideo">
                    <video v-if="form.video" :src="form.video" class="avatar" controls="controls"></video>
                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                </el-upload>
            </el-form-item>
            <el-form-item label="视频链接" v-if='this.form.type == "2"'>
                <el-input v-model="form.video"></el-input>
            </el-form-item>
            <el-form-item label="内容" v-if='this.form.type == "1"'>
                <UE :defaultMsg=content :config=config ref="xx"></UE>
            </el-form-item>
            <el-form-item label="浏览量">
                <el-input v-model="form.view_number"></el-input>
            </el-form-item>
            <el-form-item label="点赞量">
                <el-input v-model="form.like_number"></el-input>
            </el-form-item>
            <el-form-item label="排序">
                <el-input v-model="form.sort"></el-input>
                <div style="font-size: 12px;color: #666;">越小越靠前</div>
            </el-form-item>
            <el-form-item label="状态">
                <el-radio v-model="form.status" label="1">显示</el-radio>
                <el-radio v-model="form.status" label="2">不显示</el-radio>
            </el-form-item> 
        </el-form>
        <el-button style="float: right;margin-right:20px" type="primary" @click="btn2" v-if="this.bjid == ''">确定新增</el-button>
        <el-button style="float: right;margin-right:20px" type="primary" @click="btn3" v-if='this.bjid != ""'>确定编辑</el-button>
        <el-button style="float: right;margin-right:20px" @click="btn1">返回列表</el-button>
    </div>
</template>

<script>
import UE from '../common/ue.vue';
    export default {
        name: '',
        components: {UE},
        data() {
            return {
                content:'请输入内容',
                config: {
                    initialFrameWidth: null,
                    initialFrameHeight: 350
                },
                bjid:'',
                form:{
                    type:'',
                    video:''
                },
            }
        },
        methods:{
            btn1(){
                this.$router.push({
                    name:'admin_news'
                })
            },
            // 新增
            btn2(){
                if(this.form.type == '1'){
                    this.form.content = this.$refs.xx.getUEContent()
                }
                this.$axios({
                    method:'post',
                    url:`${this.api}admin/news`,
                    data:this.form,
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        this.$router.push({
                            name:'admin_news'
                        })
                        this.$message.success(res.data.message)    
                    }else{
                        this.$message.error(res.data.message)                    }
                })
            },
            btn3(){
                if(this.form.type == '1'){
                    this.form.content = this.$refs.xx.getUEContent()
                }
                this.$axios({
                    method:'put',
                    url:`${this.api}admin/news/${this.bjid}`,
                    data:this.form,
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        this.$router.push({
                            name:'admin_news'
                        })
                        this.$message.success(res.data.message)    
                    }else{
                        this.$message.error(res.data.message)                    }
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
                            that.form.thumb = res.data.data.url;
                            console.log(that.form.thumb)
                        }
                    });
                }
            },
            beforeUploadVideo(file) {
                let that = this
                const isLt10M = file.size / 1024 / 1024  < 10;
                if (['video/mp4', 'video/ogg', 'video/flv','video/avi','video/wmv','video/rmvb'].indexOf(file.type) == -1) {
                    this.$message.error('请上传正确的视频格式');
                    return false;
                }
                if (!isLt10M) {
                    this.$message.error('上传视频大小不能超过10MB哦!');
                    return false;
                }
                let fd = new FormData();
                fd.append("media", file); //传文件
                that.$axios({
                    method: "post",
                    url: that.api + "upload",
                    data: fd
                }).then(res => {
                    // console.log(res);
                    if(res.data.status == 1){
                        that.form.video = res.data.data.url;
                        console.log(that.form.video)
                    }
                });
            },



            // 读取数据
            getinfo(){
                this.$axios({
                    method:"get",
                    url:`${this.api}admin/news/${this.bjid}`,
                    params:{

                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        this.form = res.data.data
                        this.form.type = this.form.type.toString()
                        this.form.status = this.form.status.toString()
                        if(this.form.type == '1'){
                            setTimeout(()=>{
                                this.$refs.xx.setUEContent(this.form.content)
                            },2000)
                        }
                        console.log(res.data.data)                        
                    }
                })
            },
        },
        created(){
            this.bjid = this.$route.query.bjid
            console.log(this.bjid == '')
            if(this.bjid != ''){
                this.getinfo()
            }
        }
    }
</script>

<style lang="less" scoped>
    
</style>