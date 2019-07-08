<template>
  <div class="user">
    <el-input
      placeholder="请输入关键字"
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
      @change="categry"
      v-model="status"
      placeholder="状态:待审核/已审核/已接单/维权"
      style="width:250px;background: white;float: right;margin-right:15px"
    >
      <!-- label:分组的名称 -->
      <!-- value:选项的值 -->
      <el-option
        v-for="item in options1"
        :key="item.id"
        :label="item.status_text"
        :value="item.status"
      ></el-option>
    </el-select>

    <el-table :data="tableData" style="width: 100%" @row-click="go(row)" ref="moviesTable">
      <el-table-column label="ID" width="180" type="index"></el-table-column>

      <el-table-column label="单位名称" width="180" prop="company"></el-table-column>
      <el-table-column label="电梯品牌" width="180" prop="brand"></el-table-column>

      <el-table-column label="电梯型号" prop="model"></el-table-column>

      <el-table-column label="楼层数" prop="floor_number"></el-table-column>
      <el-table-column label="类型" prop="genre_text"></el-table-column>
      <el-table-column label="下单时间" prop="createtime"></el-table-column>
      <el-table-column label="审核时间" prop="checktime"></el-table-column>
      <!-- 点击状态的时候去哪里 -->
      <el-table-column label="状态" prop="status_text"></el-table-column>

      <el-table-column label="操作">
        <template slot-scope="scope">
          <i class="el-icon-delete" @click="del(scope.row.id)"></i>
        </template>
      </el-table-column>
    </el-table>

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
import { getMaintenance } from "@/components/apicom/index";
export default {
  data() {
    return {
      page: 1,
      limit: 15,
      keyword: "",
      total: 0,
      currentPage: 1,
      tableData: [],
      options1: [],
      aa: "",
      status: "",
      // status_text: ["取消","待审","审核通过","不通过","已接单","已完成","投诉","投诉已处理"]
    };
  },
  mounted() {},
  methods: {
    // 这是维保管理列表的请求
    async getMaintenance() {
      let data = await getMaintenance({
        keyword: this.keyword,
        limit: this.limit,
        page: this.page,
        status_text: this.status_text,
        status: this.status
      });
      console.log(data);
      // this.tableData = data.data = [{}]//  模仿的假数据
      this.tableData = data.data;
      this.total = data.total;
      this.options1 = data.data;
      console.log(this.options1);
    },
    // 删除
    del(id) {
      this.$confirm("是否确定删除?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          delUser(id);
          this.page = 1;
          this.getMaintenance();
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消删除"
          });
        });
    },
    // 点击状态的时候去到那里
    go(row, event, column) {
      console.log(1111111);
      console.log(row, event, column);
      // this.$router.push({
      //   name: "/admin_check"
      // });
    },
    categry() {
      console.log(1111);

      this.getMaintenance();
    },
    // 搜索
    search() {
      // console.log(1111);
      this.page = 1;
      this.limit = 15;
      this.getMaintenance();
    },
    // 分页
    handleSizeChange(val) {
      this.limit = val;
      this.page = 1;
      this.getMaintenance();
      console.log(`每页 ${val} 条`);
    },
    // 分页
    handleCurrentChange(val) {
      this.limit = 15;
      this.page = val;
      this.getMaintenance();
      console.log(`当前页: ${val}`);
    }
  },
  created() {
    this.getMaintenance();
    this.go();
  }
};
</script>
    

<style lang="less" scoped>
.user {
  background-color: #fff;
}
</style>
