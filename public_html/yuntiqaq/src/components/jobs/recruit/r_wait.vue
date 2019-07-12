<template>
  <div class="form">
    <el-form ref="form" :model="info" label-width="100px"  size="mini">
      <el-form-item label="招聘信息">
         <el-button type="primary"  v-model="info.status_text" disabled >{{info.status_text}}</el-button>
      </el-form-item>
      <el-form-item label="招聘公司">
        <el-input v-model="info.name"></el-input>
      </el-form-item>

      <el-form-item label="联系方式">
        <el-input v-model="info.phone"></el-input>
      </el-form-item>
      <el-form-item label="招聘岗位">
        <el-input v-model="info.post"></el-input>
      </el-form-item>
      <el-form-item label="薪资范围">
        <el-input v-model="info.sname"></el-input>
      </el-form-item>
      <el-form-item label="学历要求">
        <el-input v-model="info.education"></el-input>
      </el-form-item>
      <el-form-item label="工作地点">
        <el-input v-model="info.city_text"></el-input>
      </el-form-item>
      <el-form-item label="详细地址">
        <el-input v-model="info.address"></el-input>
      </el-form-item>
      <el-form-item label="职位描述">
        <el-input v-model="info.description"></el-input>
      </el-form-item>
      <el-form-item label="岗位职责">
        <el-input v-model="info.duty"></el-input>
      </el-form-item>
      <el-form-item label="详细地址">
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
import { getRecruitInfo, RecruitEditstatus } from "@/components/apicom/index";

export default {
  data() {
    return {
      textarea: "",
      radio: 2,
      //备选按钮的选中状态
      form: {
        name: "",
        region: "",
        date1: "",
        date2: "",
        delivery: false,
        type: [],
        resource: "",
        desc: ""
      },
      status: "",
      id: this.$route.params.id,
      info: {},
      remark: ""
    };
  },
  methods: {
    // 获取详情的请求
    get() {
      getRecruitInfo(this.$route.params.id).then(res => {
        console.log(res);
        this.info = res;
        if (res.status == 1) {
          this.info.status == 1 && this.info.status_text == "通过";
        } else if (res.status == 0) {
          this.info.status == 0 && this.info.status_text == "待审";
        } else if (res.status == 2) {
          this.info.status == 2 && this.info.status_text == "不通过";
        } else {
          this.info.status == 3 && this.info.status_text == "已找到工作";
        }
      });
    },

    // 审核的接口
    async RecruitEditstatus() {
      let data = await RecruitEditstatus({
        id: this.id,
        status: this.status,
        remark: this.remark
      });
      this.info = data;
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
      this.RecruitEditstatus();
      this.$router.push({
        name: "admin_recruit"
      });
    }
  },
  created() {
    this.get();
  }
};
</script> 

<style lang="less" >
.form {
  padding: 8px;
  background-color: #fff;
}
.el-form {
  background-color: #fff;
}
</style>
