<template>
    <div class="page">
        <el-form ref="form" :model="form" label-width="140px">
            <el-form-item label="短信appkey">
                <el-input v-model="form.appkey"></el-input>
            </el-form-item>
            <el-form-item label="模板id">
                <el-input v-model="form.tid"></el-input>
            </el-form-item>
            <el-form-item label="短信验证码变量">
                <el-input v-model="form.code"></el-input>
            </el-form-item>
            <el-form-item label="客服电话">
                <el-input v-model="form.service"></el-input>
            </el-form-item>
            <el-form-item label="协议">
                <UE :defaultMsg=content :config=config ref="xx"></UE>
            </el-form-item>
        </el-form>

        <el-button style="margin-left: 40%;" type="primary" @click="onSubmit">保存</el-button>
    </div>
</template>

<script>
import UE from '../../common/ue.vue';
    export default {
        name: '',
        components: {UE},
        data() {
            return {
                form:{},
                content:'请输入内容',
                config: {
                    initialFrameWidth: null,
                    initialFrameHeight: 350
                },
            }
        },
        methods:{
            onSubmit(){
                if(!(/^1[3456789]\d{9}$/.test(this.form.service))){ 
                    this.$message.error('手机号码有误，请重填') 
                    return; 
                } 
                this.form.agreement = this.$refs.xx.getUEContent()
                this.$axios({
                    method:'put',
                    url:`${this.api}admin/note/1`,
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

            getlist(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/note/1`,
                    params:{

                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        if(res.data.data != null){
                            this.form = res.data.data
                            setTimeout(()=>{
                                this.$refs.xx.setUEContent(this.form.agreement)
                            },2000)
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