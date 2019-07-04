<template>
    <div class="page">
        <el-tabs v-model="activeName">
            <el-tab-pane label="电梯设置" name="first">
                <el-form ref="form" :model="form" label-width="120px" style="margin-top:40px">
                    <el-form-item label="排序">
                        <el-input v-model="form.sort"></el-input>
                        <div style="font-size: 12px;color: #666;">越小越靠前</div>
                    </el-form-item>
                    <el-form-item label="电梯主标题" >
                        <el-input v-model="form.name"></el-input>
                    </el-form-item>
                    <el-form-item label="电梯分类">
                        <el-select v-model="form.cid" placeholder="请选择">
                            <el-option
                            v-for="item in options"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="销售电话">
                        <el-input v-model="form.phone"></el-input>
                    </el-form-item>
                    <el-form-item label="电梯图片">
                        <el-upload
                        class="avatar-uploader"
                        :action="`${api}upload`"
                        :show-file-list="false"
                        :before-upload="beforeAvatarUpload">
                        <img v-if="form.thumbnail" :src="form.thumbnail" class="avatar" style="width:100px;height:100px;">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="电梯价格">
                        <el-input v-model="form.price"></el-input>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            
            <el-tab-pane label="详情设置" name="second">
                <el-form ref="form" :model="form" label-width="120px" style="margin-top:40px">
                    <el-form-item label="电梯副标题">
                        <el-input v-model="form.subhead"></el-input>
                    </el-form-item>
                    <el-form-item label="电梯标签">
                        <el-select v-model="form.label" multiple placeholder="请选择" style="width:100%">
                            <el-option
                            v-for="item in options2"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="电梯详情">
                        <el-upload
                        :action="api+'upload'"
                        list-type="picture-card"
                        :on-preview="handlePictureCardPreview"   
                        :on-remove="handleRemove"                      
                        :before-upload="beforeAvatarUpload1"    
                        :file-list='this.dialogImageUrl'
                        >
                        <i class="el-icon-plus"></i>
                        </el-upload>
                        <el-dialog :visible.sync="dialogVisible">
                        <img width="100%" :src="dialogImageUrl" alt="">
                        </el-dialog>
                    </el-form-item>
                    <el-form-item label="电梯详情">
                        <UE :defaultMsg=form.intro :config=config ref="ue"></UE>
                    </el-form-item>
                </el-form>
            </el-tab-pane>


            <el-tab-pane label="电梯参数" name="third">
                <el-form ref="form" :model="form" label-width="120px" style="margin-top:40px">
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
                    <el-form-item label="型号">
                        <el-input v-model="form.model"></el-input>
                    </el-form-item>
                    <el-form-item label="产地">
                        <el-input v-model="form.area"></el-input>
                    </el-form-item>
                    <el-form-item label="省份">
                        <el-input v-model="form.province"></el-input>
                    </el-form-item>
                    <el-form-item label="地点">
                        <el-input v-model="form.address"></el-input>
                    </el-form-item>
                    <el-form-item label="厂家">
                        <el-input v-model="form.manufacturers"></el-input>
                    </el-form-item>
                </el-form>
                <el-button style="float: right;margin-right:20px" type="primary" @click="btn2" v-if="this.bjid == ''">确定新增</el-button>
                <el-button style="float: right;margin-right:20px" type="primary" @click="btn3" v-if='this.bjid != ""'>确定编辑</el-button>
                <el-button style="float: right;margin-right:20px" @click="btn1">返回列表</el-button>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
import UE from '../../common/ue.vue';
    export default {
        name: '',
        components: {UE},
        data() {
            return {
                dialogImageUrl:[],
                config: {
                    initialFrameWidth: null,
                    initialFrameHeight: 350
                },
                bjid:'',
                activeName: 'first',
                form:{
                    thumbnail:'',
                    label:[],
                    intro:''
                },
                options:[],
                options1:[],
                options2:[],
            }
        },
        methods:{
            btn1(){
                this.$router.push({
                    name:'admin_dtgl'
                })
            },
            btn2(){

            },
            btn3(){

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
                            that.form.thumbnail = res.data.data.url;
                            console.log(that.form)
                        }
                    });
                }
            },


            //点击移除时的事件
            handleRemove(file, fileList) {
                console.log(file, fileList);
                let a = file.url
                console.log(a)
                let b = this.imageUrl.indexOf(a)
                console.log(b)
                this.imageUrl.splice(b,1)
                this.dialogImageUrl.splice(b,1)
                console.log(this.imageUrl)
            },
            //点击已上传文件的事件
            handlePictureCardPreview(file) {
                this.dialogImageUrl = file.url;
                this.dialogVisible = true;
            },
            // 头像上传之前
            beforeAvatarUpload14(file) {
                    
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
                            that.form.image.push(res.data.result.url);
                            this.dialogImageUrl.push({
                                    'url':res.data.result.url
                                })
                            console.log(that.imageUrl)
                        }
                    });
                }
            },
            // 获取分类
            getlist1(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/goodscate`,
                    params:{
                        limit:99999999,
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
            // 获取品牌
            getlist1(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/brand`,
                    params:{
                        limit:99999999,
                        page:1
                    },
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        res.data.data.data.forEach(res => {
                            this.options1.push(res)
                        });
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
            },
            // 获取标签
            getlist1(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/goodslabel`,
                    params:{
                        limit:99999999,
                        page:1
                    },
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        res.data.data.data.forEach(res => {
                            this.options2.push(res)
                        });
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
            },
        },
        created(){
            this.bjid = this.$route.query.bjid
            console.log(this.bjid == '')
            this.getlist1()
        }
    }
</script>

<style lang="less" scoped>
    
</style>