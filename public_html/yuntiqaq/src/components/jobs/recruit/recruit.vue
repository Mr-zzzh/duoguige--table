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
        v-for="item in  options1_son"
        :key="item.status"
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
        <template slot-scope="scope">
          <el-button @click="info(scope.row.id)" type="text" size="small">详情</el-button>
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
      options1: [],
      options1_son: [
        {
          status: 0,
          status_text: "待审"
        },
        {
          status: 1,
          status_text: "通过"
        },
        {
          status: 2,
          status_text: "不通过"
        },
        {
          status: 3,
          status_text: "招聘结束"
        }
      ]
    };
  },
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
      // this.options1 = data.data;
    },

    down() {
      this.page = 1;
      this.getRecruit();
    },
    search() {
      this.page = 1;
      this.getRecruit();
    },

    // 详细
    info(id) {
      console.log(id);
      this.$router.push({
        name: "admin_r_wait",
        params: { id: id }
      });
    },
    // 分页----这是选择每页多少条的时候触发
    handleSizeChange(val) {
      this.limit = val; //让其相等
       this.page = 1;
      this.getRecruit();
      console.log(`每页 ${val} 条`);
    },
    // 分页------当前页码切换的时候触发
    handleCurrentChange(val) {
      this.page = val;
      this.getRecruit();
      console.log(`当前页: ${val}`);
    }
  },
  created() {
    this.getRecruit();
  },
  mounted() {
    this.getRecruit();
  },
};
</script>
    

<style lang="less" scoped>
.recruit {
  background-color: #fff;
  padding: 8px;
}
</style>
