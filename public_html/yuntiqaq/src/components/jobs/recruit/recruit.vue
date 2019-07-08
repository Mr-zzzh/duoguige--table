<template>
  <div class="recruit">
    <el-input
      placeholder="请输入姓名/手机号搜索"
      v-model="keyword"
      class="input-with-select"
      style="width:500px;background: white;float: right;"
    >
      <el-button
        type="primary"
        slot="append"
        style="background:#409EFF;color: white;"
        @click="search(keyword)"
      >搜索</el-button>
    </el-input>

    <el-select
      v-model="status"
      placeholder="状态"
      style="width:250px;background: white;float: right;margin-right:15px"
      @change="down"
    >
      <el-option
        v-for="item in options1"
        :key="item.id"
        :label="item.status_text"
        :value="item.status"
      ></el-option>
    </el-select>

    <el-table :data="tableData" style="width: 100%">
      <el-table-column label="ID" width="180" type="index"></el-table-column>

      <el-table-column label="招聘公司" width="180" prop="uname"></el-table-column>
      <el-table-column label="招聘岗位" width="180" prop="post"></el-table-column>

      <el-table-column label="工作地点" prop="address"></el-table-column>

      <el-table-column label="发布时间" prop="createtime"></el-table-column>
      <el-table-column label="审核时间" prop="checktime"></el-table-column>
      <el-table-column label="状态" prop="status_text"></el-table-column>

      <el-table-column label="操作">
        <i class="el-icon-time"></i>
      </el-table-column>
    </el-table>
  </div>
</template>


    <script>
// 获得找聘信息的列表
import { getRecruit } from "@/components/apicom/index";
export default {
  data() {
    return {
      page: 1,
      limit: 15,
      keyword: "",
      total: 0,
      currentPage: 1,
      tableData: [],
      status: "",
      options1: []
    };
  },
  mounted() {},
  methods: {
    // 这是找聘信息列表的请求
    async getRecruit() {
      let data = await getRecruit({
        keyword: this.keyword,
        limit: this.limit,
        page: this.page,
        status: this.status
      });
      console.log(data);
      // this.tableData = data.data = [{}]//  模仿的假数据
      this.tableData = data.data;
      this.total = data.total;
      this.options1 = data.data;
    },

    down() {
      this.page = 1;
      this.limit = 15;
      this.getRecruit();
    },
    search() {
      this.page = 1;
      this.limit = 15;
      this.getRecruit();
    }
  },
  created() {
    this.getRecruit();
  }
};
</script>
    

<style lang="less" scoped>
.recruit {
  background-color: #fff;
}
</style>
