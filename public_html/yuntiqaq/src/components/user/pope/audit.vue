<template>
  <!-- 技术大师审核页面 -->
  <div class="audit">
    <!-- 用element-ui表单写 -->
    <el-form ref="form" :model="sizeForm" label-width="80px" size="mini">
      <el-form-item label="姓名">
        <el-input v-model="sizeForm.check.name"></el-input>
      </el-form-item>
      <el-form-item label="性别">
        <el-input v-model="sizeForm.check.sex==1?'男':'女'"></el-input>
      </el-form-item>
      <el-form-item label="身份证号">
        <el-input v-model="sizeForm.check.idcardno"></el-input>
      </el-form-item>
      <el-form-item label="企业名称">
        <el-input v-model="sizeForm.check.company_name"></el-input>
      </el-form-item>
      <el-form-item label="营业执照注册号">
        <el-input v-model="sizeForm.check.license_number"></el-input>
      </el-form-item>
      <el-form-item label="证件照" class="box">
        <div  class="aa">
          <p>在职证明</p>
          <img :src="sizeForm.check.prove_image" alt />
        </div>
        <div class="aa">
          <p>营业执照</p>
          <img :src="sizeForm.check.company_image" alt />
        </div>
        <div class="aa">
          <p>技师证件</p>
          <img :src="sizeForm.check.technician_image" alt />
        </div>
      </el-form-item>

      <el-form-item label="审核状态">
        <el-radio v-model="sizeForm.status" label="1" @change="btn">通过</el-radio>
        <el-radio v-model="sizeForm.status" label="2" @change="btn">驳回</el-radio>
      </el-form-item>
      <el-form-item label="备注">
        <el-input type="textarea" :rows="2" placeholder="请输入内容" v-model="remark"></el-input>
      </el-form-item>
      <el-form-item size="large">
        <el-button type="primary" @click="submitForm('ruleForm')" :loading="false">提交</el-button>
        <el-button @click="resetForm('ruleForm')">取消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getUserInfo, getAudit } from "@/components/apicom/index";

export default {
  data() {
    return {
      // radio: 2,
      //备选按钮的选中状态
      id: this.$route.params.id,
      list: [],
      state: "",
      status:"",
      numberValidateForm: {},
      textarea: "",
      remark: "",
      sizeForm: {
        id: 0,
        name: "",
        phone: "",
        avatar: "",
        intro: null,
        status: "",
        type: 2,
        createtime: "",
        normal: 1,
        status_text: "",
        type_text: "",
        normal_text: "",
        check: {
          id: "",
          uid: "",
          name: "",
          sex: "",
          idcardno: "",
          license_number: "",
          company_name: "",
          company_image: "",
          prove_image: "",
          technician_image: "",
          dimission: "",
          createtime: ""
        }
      }
    };
  },
  methods: {
    get() {
      getUserInfo(this.id).then(res => {
        console.log(res);
        //后台返回的数据
        this.sizeForm = res;
        this.sizeForm.status = res.status.toString();
        console.log(this.sizeForm);
        // 在此回到这个页面的时候，审核过后，应该改变审核后改项的状态值
      });
    },
    // 获得当前点击提交按钮的时候吧数据写入
    async getAudit() {
      let data = await getAudit({
        id: this.id,
        status: this.status,
        remark: this.remark
      });
      console.log(data);
    },
    // 当选中的时候的,通过的值是1，驳回的值是2
    btn(val) {
      console.log(val);
      if (val == "1") {
        this.status = val = 1;
      } else {
        this.status = 2;
      }
    },
    querySearch() {},
    handleSelect() {},
    handleIconClick() {},
    // 点击提交按钮时触发-----调用审核的请求，并跳转到物业公司列表页
    submitForm() {
      this.getAudit();
      this.$router.push({
        name: "/admin_pope"
      });
    },
    resetForm() {}
  },
  mounted() {},
  created() {
    this.get();
    this.btn();
  }
};
</script>


<style lang="less" scoped>
.audit {
  color: #000;
  padding: 20px;
  background-color: #fff;
  font-size: 20px;
  .box {
    div {
      margin: 0 20px;
      float: left;
    }
  }
}
.el-form-item__label {
  width: 120px;
}
.aa{
  img{
    display: block;
    width: 200px;
    height: 200px;
  }
}
</style>

