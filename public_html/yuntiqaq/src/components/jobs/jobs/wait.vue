<template>
  <div class="form">
    <el-form ref="form" :model="info" label-width="80px">
      <el-form-item label="求职者信息">
        <!-- <el-input v-model="info.name"></el-input> -->
        <!-- <el-button type="primary" disabled>待审</el-button> -->
        <!-- <div v-model="info.status_text"></div> -->
        <el-input v-model="info.status_text"></el-input>
      </el-form-item>
      <el-form-item label="姓名">
        <el-input v-model="info.name"></el-input>
      </el-form-item>

      <el-form-item label="电话">
        <el-input v-model="info.name"></el-input>
      </el-form-item>
      <el-form-item label="求职岗位">
        <el-input v-model="form.name"></el-input>
      </el-form-item>
      <el-form-item label="期望薪资">
        <el-input v-model="form.name"></el-input>
      </el-form-item>
      <el-form-item label="工作地址">
        <el-input v-model="form.name"></el-input>
      </el-form-item>
      <el-form-item label="自我描述">
        <el-input v-model="form.name"></el-input>
      </el-form-item>
      <el-form-item label="详细地址">
        <el-radio v-model="radio" label="1" @change="btn">通过</el-radio>
        <el-radio v-model="radio" label="2" @change="btn">驳回</el-radio>
      </el-form-item>
      <el-form-item label="备注">
        <el-input type="textarea" :rows="2" placeholder="请输入内容" v-model="textarea"></el-input>
      </el-form-item>
      <el-form-item size="large">
        <el-button type="primary" @click="submitForm('ruleForm')" :loading="false">提交</el-button>
        <el-button @click="resetForm('ruleForm')">取消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>


<script>
import { getJobsinfo, jobeditstatus } from "@/components/apicom/index";

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
      id: this.$route.params.id,
      status: this.status,
      info: {}
    };
  },
  methods: {
    // 获取详情的请求
    get() {
      getJobsinfo(this.$route.params.id).then(res => {
        console.log(res);
        this.info = res;    if (res.status == 1) {
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
    async jobeditstatus() {
      let data = await jobeditstatus({
        id: this.id,
        status: this.status
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
      this.jobeditstatus();
      this.$router.push({
        name: "admin_jobs"
      });
    }
  },
  created() {
    this.get();
  }
};
</script> 

<style lang="less" scoped>
.form {
  padding: 8px;
  background-color: #fff;
}
.el-form {
  background-color: #fff;
}
label.el-form-item__label {
  width: 100px;
}
div.el-input {
  width: 90%;
  border: none;
}
input.el-input__inner {
  border: none;
  -webkit-transition: none;
  transition: none;
  border: 1px solid #fff;
}
.el-input__inner:hover {
  border: none;
}
</style>
