<template>
  <!-- 最外层的大盒子，是根元素 -->
  <div class="user">
    <!-- 上面的搜索框 -->
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
        @click="search_2(keyword)"
      >搜索</el-button>
    </el-input>

    <!-- 下面的表格 -->

    <el-table :data="tableData" style="width: 100%" @selection-change="selectionChange">
      <el-table-column type="selection" width="55"></el-table-column>
      <el-table-column label="ID" width="180" type="index"></el-table-column>
      <el-table-column label="姓名" width="180" prop="name"></el-table-column>
      <el-table-column label="手机号" prop="phone"></el-table-column>
      <el-table-column label="类型">技术大师</el-table-column>
      <el-table-column label="综合分数" prop="score"></el-table-column>
      <el-table-column label="综合订单数" prop="number"></el-table-column>
      <el-table-column label="级别" prop="grade"></el-table-column>
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
import { getdashi } from "@/components/apicom/index";

export default {
  data() {
    return {
      page: 0,
      limit: 15,
      keyword: "",
      total: 0,
      currentPage: 1,
      tableData: [],
    };
  },

  methods: {
    // 当选择项发生变化时会触发该事件，只有被勾上的时候才会被触发
    selectionChange(list) {
      this.selectedList = list.map(v => v.id).join(",");
      console.log(this.selectedList);
      this.id = this.selectedList; //获取到了当前行的id
      if (this.selectedList) {
        this.$message.success("已启用");
      } else {
        this.$message.info("已禁用");
      }
    },
    // 获得技术大师列表的请求
    async getdashi() {
      let data = await getdashi({
        keyword: this.keyword,
        limit: this.limit,
        page: this.page,
        // 因为这里是技术大师的页面。所以需要选择类型
        type: 2
      });
      console.log(data);
      this.tableData = data.data;
      this.total = data.total;
      this.page = data.total / this.limit;
      console.log(this.tableData);
    },

    // 获取分类
    categry() {
      this.limit = 15;
      this.page = 1;
      this.getdashi();
    },
    // 搜索
    search_2() {
      this.limit = 15;
      this.page = 1;
      this.getdashi();
    },
    // 分页----这是选择每页多少条的时候触发
    handleSizeChange(val) {
      this.limit = val; //让其相等
      this.page = 1;
      this.getdashi();
      console.log(`每页 ${val} 条`);
    },
    // 分页------当前页码切换的时候触发
    handleCurrentChange(val) {
      this.limit = 15;
      this.page = val;
      this.getdashi();
      console.log(`当前页: ${val}`);
    }
  },
  mounted() {},
  created() {
    this.getdashi();
  }
};
</script>


<style lang="less" scoped>
.user {
  padding: 8px;
  background-color: #fff;
}

.el-button--primary.is-plain {
  background-color: #409eff;
  color: none;
}
</style>

