<template>
  <div class="user">
    <el-form ref="form" :model="info" label-width="100px">
      <el-form-item label="状态">
        <el-button type="primary" v-model="info.status_text" disabled>{{info.status_text}}</el-button>
      </el-form-item>
      <el-form-item label="下单时间">
        <el-input v-model="info.createtime"></el-input>
        <!-- <el-input v-model="info.status_text"></el-input> -->
      </el-form-item>
      <el-form-item label="单位名称">
        <el-input v-model="info.company"></el-input>
      </el-form-item>

      <el-form-item label="电梯型号">
        <el-input v-model="info.model"></el-input>
      </el-form-item>
      <el-form-item label="楼层数">
        <el-input v-model="info.floor_number"></el-input>
      </el-form-item>
      <el-form-item label="类型">
        <el-input v-model="info.genre_text"></el-input>
      </el-form-item>
      <el-form-item label="电梯地址">
        <el-input v-model="info.address"></el-input>
      </el-form-item>
      <el-form-item label="审核状态">
        <el-radio v-model="info.status" label="1" @change="btn">通过</el-radio>
        <el-radio v-model="info.status" label="2" @change="btn">驳回</el-radio>
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
import { getinfo, editstatus } from "@/components/apicom/index";

export default {
  data() {
    return {
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
      textarea: "",
      radio: 2,
      id: this.$route.params.id,
      limit: 15,
      page: 1,
      keyword: "",
      status_text: "",
      status: "",
      //备选按钮的选中状态
      info: {},
      remark: ""
    };
  },
  methods: {
    // 审核的接口
    async editstatus() {
      let data = await editstatus({
        id: this.id,
        status: this.status,
        remark: this.remark
      });
      console.log(data);
    },

    // 获取详情的请求
    get() {
      getinfo(this.$route.params.id).then(res => {
        console.log(res);
        this.info = res;
         this.info.status = res.status.toString();
        this.status=res.status
        if (res.status == 1) {
          this.info.status == 1 && this.info.status_text == "审核通过";
        } else if (res.status == 0) {
          this.info.status == 0 && this.info.status_text == "待审";
        } else if (res.status == -1) {
          this.info.status == -1 && this.info.status_text == "取消";
        } else if (res.status == 2) {
          this.info.status == 2 && this.info.status_text == "不通过";
        } else if (res.status == 4) {
          this.info.status == 4 && this.info.status_text == "已完成";
        } else if (res.status == 6) {
          this.info.status == 6 && this.info.status_text == "投诉已处理";
        } else if (res.status == 5) {
          this.info.status == 5 && this.info.status_text == "投诉";
        } else {
          this.info.status == 3 && this.info.status_text == "已接单";
        }
      });
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
      this.editstatus();
      this.$router.push({
        name: "admin_maintenance"
      });
    }
  },
  mounted() {},
  created() {
    this.get();
    console.log(this.id);
  }
};
</script> 


<style lang="less" scoped>
.user {
  background-color: #fff;
  padding: 8px;
}
</style>
