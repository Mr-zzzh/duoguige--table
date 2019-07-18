<template>
  <div class="page">
    <el-form ref="form" :model="form" label-width="160px">
      <el-form-item label="ios最新版本">
        <el-input v-model="form.ios_new_version"></el-input>
      </el-form-item>
      <el-form-item label="ios最小兼容版本">
        <el-input v-model="form.ios_min_version"></el-input>
      </el-form-item>
      <el-form-item label="android最新版本">
        <el-input v-model="form.android_new_version"></el-input>
      </el-form-item>
      <el-form-item label="	android最小兼容版本">
        <el-input v-model="form.android_min_version"></el-input>
      </el-form-item>
      <el-form-item label="	ios下载地址">
        <el-input v-model="form.ios_url"></el-input>
      </el-form-item>
      <el-form-item label="	android地址">
        <el-input v-model="form.android_url"></el-input>
      </el-form-item>
    </el-form>

    <el-button style="margin-left: 40%;" type="primary" @click="onSubmit">保存</el-button>
  </div>
</template>

<script>
export default {
  name: "",
  data() {
    return {
      form: {},
    };
  },
  methods: {
    onSubmit() {
      this.$axios({
        method: "put",
        url: `${this.api}admin/version/1`,
        data: this.form
      }).then(res => {
        if (res.data.status == 1) {
          this.$message.success(res.data.message);
          console.log(res.data.data);
        } else {
          this.$message.error(res.data.message);
        }
      });
    },

    getlist() {
      this.$axios({
        method: "get",
        url: `${this.api}admin/version/1`,
        params: {}
      }).then(res => {
        if (res.data.status == 1) {
          if (res.data.data != null) {
            this.form = res.data.data;
          }
          console.log(res.data.data);
        }
      });
    }
  },
  created() {
    this.getlist();
  }
};
</script>

<style lang="less" scoped>
</style>