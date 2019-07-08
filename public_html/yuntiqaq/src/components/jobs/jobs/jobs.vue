<template>
  <!-- 最外层的大盒子，是根元素 -->
  <div class="user">
    <!-- 上面的搜索框 -->
    <el-input
      placeholder="请输入关键字搜索"
      v-model="keyword"
      class="input-with-select"
      style="width:500px;background: white;float: right;"
    >
      <el-button
        type="primary"
        slot="append"
        style="background:#409EFF;color: white;"
        @click="search_2(keyword)"
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

    <!-- 下面的表格 -->
    <div class="table">
      <el-table :data="tableData" style="width: 100%">
        <el-table-column label="ID" width="180" type="index"></el-table-column>

        <el-table-column label="求职者信息" width="180" prop="education"></el-table-column>
        <el-table-column label="求职岗位" width="180" prop="post"></el-table-column>

        <el-table-column label="工作地点" prop="city_text"></el-table-column>

        <el-table-column label="申请时间" prop="createtime"></el-table-column>
        <el-table-column label="审核时间" prop="checktime"></el-table-column>
        <el-table-column label="状态" prop="status_text"></el-table-column>

        <el-table-column label="操作">
          <template slot-scope="scope">
            <i class="el-icon-time" @click="info(scope.row.id)"></i>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <!-- 分页 -->
    <div class="fenye">
      <el-pagination
        style="margin-top:20px"
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        :current-page="currentPage"
        :page-sizes="[15, 20, 30, 40]"
        :page-size="100"
        layout="total, sizes, prev, pager, next, jumper"
        :total="this.total"
      ></el-pagination>
    </div>
  </div>
</template>

<script>
import { getJobs } from "@/components/apicom/index";

export default {
  data() {
    return {
      radio: 3,
      status: "",
      page: 1,
      limit: 15,
      keyword: "",
      total: 0,
      currentPage: 1,
      tableData: [],
      aa: "",
      options1: []
    };
  },
  mounted() {},
  methods: {
    // 获得求职信息的请求
    async getJobs() {
      let data = await getJobs({
        keyword: this.keyword,
        limit: this.limit,
        page: this.page,
        status:this.status
      });
      console.log(data);
      this.tableData = data.data;
      this.total = data.total;
      this.options1 = data.data;
    },
    down() {
      this.getJobs();
      console.log(1111111);
    },
    // 搜索
    search_2() {
      // console.log(1111);
      this.page = 1;
      this.limit = 15;
      this.getJobs();
    },

    // 详细
    info() {
      this.$router.push({
        name: "admin_wait"
      });
    },

    // 分页
    handleSizeChange(val) {
      this.limit = val;
      this.page = 1;
      this.getJobs();
      console.log(`每页 ${val} 条`);
    },
    // 分页
    handleCurrentChange(val) {
      this.limit = 15;
      this.page = val;
      this.getJobs();
      console.log(`当前页: ${val}`);
    }
  },
  created() {
    this.getJobs();
  }
};
</script>


<style lang="less" scoped>
.user {
  padding: 15px;
  background-color: #fff;
}
</style>

