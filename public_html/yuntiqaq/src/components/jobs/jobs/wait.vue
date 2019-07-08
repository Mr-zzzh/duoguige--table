<template>
  <div>
    <!-- 用element-ui表单写 -->
    <el-form ref="form" :model="sizeForm" label-width="80px" size="mini">
      <el-form-item label="姓名">
        <el-input v-model="sizeForm.check.company_name"></el-input>
      </el-form-item>
      <el-form-item label="性别">
        <el-input v-model="sizeForm.name"></el-input>
      </el-form-item>
      <el-form-item label="身份证号">
        <el-input v-model="sizeForm.check.phone"></el-input>
      </el-form-item>
      <el-form-item label="企业名称">
        <el-input v-model="sizeForm.check.address"></el-input>
      </el-form-item>
      <el-form-item label="营业执照注册号">
        <el-input v-model="sizeForm.check.number"></el-input>
      </el-form-item>
      <el-form-item label="证件照">
        <div>
          <img :src="sizeForm.check.image" alt />
        </div>
        <div>
          <img :src="sizeForm.check.image" alt />
        </div>
        <div>
          <img :src="sizeForm.check.image" alt />
        </div>
      </el-form-item>

      <el-form-item label="审核状态">
        <el-radio v-model="radio" label="1" @change="btn">通过</el-radio>
        <el-radio v-model="radio" label="2" @change="btn">驳回</el-radio>
      </el-form-item>
      <el-form-item size="large">
        <el-button type="primary" @click="submitForm('ruleForm')" :loading="false">提交</el-button>
        <el-button @click="resetForm('ruleForm')">取消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  data() {
    return {
        sizeForm:{}
    };
  }
};
</script>

<style lang="less" scoped>
</style>

