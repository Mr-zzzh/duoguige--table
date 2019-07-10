<template>
    <div class="page">
        <el-tabs v-model="activeName">
            <el-tab-pane label="商品设置" name="first">
                <el-form ref="form" :model="form" label-width="120px" style="margin-top:40px">
                    <el-form-item label="排序">
                        <el-input v-model="form.sort"></el-input>
                        <div style="font-size: 12px;color: #666;">越小越靠前</div>
                    </el-form-item>
                    <el-form-item label="商品主标题" >
                        <el-input v-model="form.name"></el-input>
                    </el-form-item>
                    <el-form-item label="商品分类">
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
                    <el-form-item label="商品图片">
                        <el-upload
                        class="avatar-uploader"
                        :action="`${api}upload`"
                        :show-file-list="false"
                        :before-upload="beforeAvatarUpload">
                        <img v-if="form.thumbnail" :src="form.thumbnail" class="avatar" style="width:100px;height:100px;">
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="商品价格">
                        <el-input v-model="form.price"></el-input>
                    </el-form-item>
                </el-form>
            </el-tab-pane>

            
            <el-tab-pane label="详情设置" name="second">
                <el-form ref="form" :model="form" label-width="120px" style="margin-top:40px">
                    <el-form-item label="商品副标题">
                        <el-input v-model="form.subhead"></el-input>
                    </el-form-item>
                    <el-form-item label="商品标签">
                        <el-select v-model="form.label" multiple placeholder="请选择" style="width:100%">
                            <el-option
                            v-for="item in options2"
                            :key="item.name"
                            :label="item.name"
                            :value="item.name">
                            </el-option>
                        </el-select>
                        <el-button type="primary" style="margin-top:10px;" @click="xzbq">新增标签</el-button>
                    </el-form-item>
                    <el-form-item label="商品详情">
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
                    <el-form-item label="商品详情">
                        <UE :defaultMsg=content :config=config ref="xx"></UE>
                    </el-form-item>
                </el-form>
            </el-tab-pane>


            <el-tab-pane label="商品参数" name="third">
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
                    <el-form-item label="规格">
                        <el-input v-model="form.specification"></el-input>
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
                    <el-form-item label="颜色">
                        <el-input v-model="form.color"></el-input>
                    </el-form-item>
                </el-form>
                <el-button style="float: right;margin-right:20px" type="primary" @click="btn2" v-if="this.bjid == ''">确定新增</el-button>
                <el-button style="float: right;margin-right:20px" type="primary" @click="btn3" v-if='this.bjid != ""'>确定编辑</el-button>
                <el-button style="float: right;margin-right:20px" @click="btn1">返回列表</el-button>
            </el-tab-pane>
        </el-tabs>




        <el-dialog
        title="新增标签"
        :visible.sync="dialogVisible1"
        width="30%"
        :before-close="handleClose">
        <span>
            <el-form ref="form1" :model="form1" label-width="100px">
                <el-form-item label="标签名">
                    <el-input v-model="form1.name"></el-input>
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
            <el-button @click="dialogVisible1 = false">取 消</el-button>
            <el-button type="primary" @click="xzbtn">新增</el-button>
        </span>
        </el-dialog>


    </div>
</template>

<script>
import UE from '../../common/ue.vue';
    export default {
        name: '',
        components: {UE},
        data() {
            return {
                dialogVisible1:false,
                form1:{
                    name:''
                },
                dialogVisible:false,
                content:'请输入内容',
                1: false,
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
                    intro:'',
                    image:[]
                },
                options:[],
                options1:[],
                options2:[],
            }
        },
        methods:{
            // 新增标签
            xzbq(){
                this.dialogVisible1 = true
            },
            xzbtn(){
                this.$axios({
                    method:'post',
                    url:`${this.api}admin/goodslabel`,
                    data:this.form1
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        this.$message.success(res.data.message)
                        this.dialogVisible1 = false
                        this.getlist3()
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
            },
            // 弹窗
            handleClose(){
                this.dialogVisible1 = false
            },

            btn1(){
                this.$router.push({
                    name:'admin_dtgl'
                })
            },
            // 新增
            btn2(){
                if(!(/^1[3456789]\d{9}$/.test(this.form.phone))){ 
                    this.$message.error('手机号码有误，请重填') 
                    return; 
                } 
                console.log(this.$refs.xx.getUEContent())
                this.form.intro = this.$refs.xx.getUEContent()
                this.$axios({
                    method:'post',
                    url:`${this.api}admin/goods`,
                    data:this.form,
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        this.$router.push({
                            name:'admin_dtgl'
                        })
                        this.$message.success(res.data.message)    
                    }else{
                        this.$message.error(res.data.message)                    }
                })
            },
            btn3(){
                if(!(/^1[3456789]\d{9}$/.test(this.form.phone))){ 
                    this.$message.error('手机号码有误，请重填') 
                    return; 
                } 
                this.form.intro = this.$refs.xx.getUEContent()
                this.$axios({
                    method:'put',
                    url:`${this.api}admin/goods/${this.bjid}`,
                    data:this.form,
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                        this.$router.push({
                            name:'admin_dtgl'
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
                let b = this.form.image.indexOf(a)
                console.log(b)
                this.form.image.splice(b,1)
                this.dialogImageUrl.splice(b,1)
                console.log(this.form.image)
            },
            //点击已上传文件的事件
            handlePictureCardPreview(file) {
                this.dialogImageUrl = file.url;
                this.dialogVisible = true;
            },
            // 头像上传之前
            beforeAvatarUpload1(file) {
                    
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
                            that.form.image.push(res.data.data.url);
                            this.dialogImageUrl.push({
                                'url':res.data.data.url
                            })
                            console.log(that.form.image)
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
            getlist2(){
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
            getlist3(){
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
                        this.options2 = res.data.data.data
                        // res.data.data.data.forEach(res => {
                        //     this.options2.push(res)
                        // });
                    }else{
                        this.$message.error(res.data.message)
                    }
                })
            },

            // 读取数据
            getinfo(){
                this.$axios({
                    method:"get",
                    url:`${this.api}admin/goods/${this.bjid}`,
                    params:{

                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        this.form = res.data.data
                        this.form.image.forEach(red=>{
                            this.dialogImageUrl.push({
                                'url':red
                            })
                        })
                        setTimeout(()=>{
                            this.$refs.xx.setUEContent(this.form.intro)
                        },2000)
                        console.log(res.data.data)                        
                    }
                })
            },
        },
        created(){
            this.getlist1()
            this.getlist2()
            this.getlist3()
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