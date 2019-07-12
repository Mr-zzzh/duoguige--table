<template>
  <!-- 技术大师审核页面 -->
  <div class="p_audit">
    <!-- 用element-ui表单写 -->
    <el-form ref="form" :model="sizeForm" label-width="80px" size="mini">
      <el-form-item label="公司名称">
        <el-input v-model="sizeForm.check.company_name"></el-input>
      </el-form-item>
      <el-form-item label="法人姓名">
        <el-input v-model="sizeForm.name"></el-input>
      </el-form-item>
      <el-form-item label="联系电话">
        <el-input v-model="sizeForm.check.phone"></el-input>
      </el-form-item>
      <el-form-item label="公司地址">
        <el-input v-model="sizeForm.check.address"></el-input>
      </el-form-item>
      <el-form-item label="电梯数量">
        <el-input v-model="sizeForm.check.number"></el-input>
      </el-form-item>
      <el-form-item label="电梯品牌">
        <el-input v-model="sizeForm.check.brand"></el-input>
      </el-form-item>
      <el-form-item label="营业执照">
        <div class="bb">
          <img :src="sizeForm.check.image" alt />
        </div>
      </el-form-item>
      <el-form-item label="审核状态">
        <el-radio v-model="radio" label="1" @change="btn">通过</el-radio>
        <el-radio v-model="radio" label="2" @change="btn">驳回</el-radio>
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
      radio: 2,
      remark: "",
      //备选按钮的选中状态
      sizeForm: {
        id: 0,
        name: "",
        phone: 0,
        avatar: "",
        intro: "",
        status: 0,
        createtime: "",
        check: {
          company_name: "",
          name: "",
          phone: 0,
          address: "",
          number: 0,
          brand: "",
          image: ""
        }
      },
      id: this.$route.params.id,
      status: 1
    };
  },
  methods: {
    // 这是获取用户列表的请求
    get() {
      getUserInfo(this.id).then(res => {
        console.log(res);
        //后台返回的数据
        this.sizeForm = res;
        console.log(this.sizeForm);
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
    // 点击提交按钮时触发-----调用审核的请求，并跳转到物业公司列表页
    submitForm() {
      this.getAudit();
      this.$router.push({
        name: "/admin_property"
      });
    }
  },

  created() {
    // var user = JSON.parse(localStorage.getItem("user") || "{}");
    // console.log(user, 33333); // ----------是数组,这些操作知识为了获取当前的id，发请求，通过id获取更多的当前这行的数据
    // user.forEach(element => {
    //   (this.id = element.id), (this.status = element.status);
    // });
    this.get();
  }
};
</script>


<style lang="less" scoped>
.content {
  .p_audit {
    color: #000;
    padding-top: 30px;
    background-color: rgba(255, 255, 255, 1);
    padding: 40px;
  }
}
.bb {
  img {
    display: inline-block;
    width: 200px;
    height: 200px;
  }
}
</style>

