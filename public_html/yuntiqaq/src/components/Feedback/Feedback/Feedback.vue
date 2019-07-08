<template>
  <div>
    <el-table :data="tableData" style="width:100% ,display:flex">
      <el-table-column label="ID" style= "flex:1" type="index"></el-table-column>

      <el-table-column label="反馈内容" style= "flex:1" prop="content">
        <template slot-scope="scope">
          <el-popover trigger="hover" placement="top">
            <!-- 附框 -->
            <div slot="reference" class="name-wrapper">
              <el-tag size="medium">{{ scope.row.name }}</el-tag>
            </div>
          </el-popover>
        </template>
      </el-table-column>
      <el-table-column label="反馈人信息" style= "flex:1" prop="uname"></el-table-column>

      <el-table-column label="反馈时间" prop="createtime" style= "flex:1"></el-table-column>
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
import { getFeedback } from "@/components/apicom/index";
export default {
  data() {
    return {
      page: 1,
      limit: 15,
      keyword: "",
      total: 0,
      currentPage: 1,
      tableData: []
    };
  },
  mounted() {},
  methods: {
    // 这是意见反馈列表的请求
    async getFeedback() {
      let data = await getFeedback({
        keyword: this.keyword,
        limit: this.limit,
        page: this.page,
        type: 1
      });
      console.log(data);
      // this.tableData = data.data = [{}]//  模仿的假数据
      this.tableData = data.data;
      this.total = data.total;
    },
    // 分页
    handleSizeChange(val) {
      this.limit = val;
      this.page = 1;
      this.getUserTab();
      console.log(`每页 ${val} 条`);
    },
    // 分页
    handleCurrentChange(val) {
      this.limit = 15;
      this.page = val;
      this.getUserTab();
      console.log(`当前页: ${val}`);
    }
  },
  created() {
    this.getFeedback();
  }
};
</script>
    

<style lang="less" scoped>
</style>
