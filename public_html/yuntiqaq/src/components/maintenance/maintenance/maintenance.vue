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

    <!--   placeholder="状态:待审核/已审核/已接单/维权" -->
    <el-select
      @change="categry"
      v-model="status"
      placeholder="状态:审核通过/审核不通过"
      style="width:250px;background: white;float: right;margin-right:15px"
    >
      <!-- label:分组的名称 -->
      <!-- value:选项的值 -->
      <el-option
        v-for="item in options1"
        :key="item.status"
        :label="item.status_text"
        :value="item.status"
      ></el-option>
    </el-select>

    <el-select
      @change="categry"
      v-model="genre"
      placeholder="维修单/保养单"
      style="width:250px;background: white;float: right;margin-right:15px"
    >
      <!-- label:分组的名称 -->
      <!-- value:选项的值 -->
      <el-option
        v-for="item in options"
        :key="item.genre"
        :label="item.genre_text"
        :value="item.genre"
      ></el-option>
    </el-select>

    <el-table :data="tableData" style="width: 100%">
      <el-table-column label="ID" width="180" type="index"></el-table-column>

      <el-table-column label="单位名称" width="180" prop="company"></el-table-column>
      <el-table-column label="电梯品牌" width="180" prop="brand"></el-table-column>

      <el-table-column label="电梯型号" prop="model"></el-table-column>

      <el-table-column label="楼层数" prop="floor_number"></el-table-column>
      <el-table-column label="类型" prop="genre_text"></el-table-column>
      <el-table-column label="下单时间" prop="createtime"></el-table-column>
      <el-table-column label="审核时间" prop="checktime"></el-table-column>
      <el-table-column label="状态" prop="status_text"></el-table-column>

      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button @click="info(scope.row.id)" type="text" size="small">详情</el-button>
          <el-button @click="del(scope.row.id)" type="text" size="small">删除</el-button>
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
import { delM } from "@/components/apicom/index";

export default {
  data() {
    return {
      page: 1,
      limit: 15,
      keyword: "",
      total: 0,
      currentPage: 1,
      tableData: [],
      aa: "",
      status: "",
      options: [
        {
          genre: 1,
          genre_text: "维修单"
        },
        {
          genre: 2,
          genre_text: "保养单"
        }
      ],
      genre: "",
      genre_text: "",
      options1: [
        {
          status: 1,
          status_text: "审核通过"
        },
        {
          status: 2,
          status_text: "审核不通过"
        }
      ]
    };
  },
  methods: {
    // 这是维保管理列表的请求
    async getMaintenance() {
      let data = await getMaintenance({
        keyword: this.keyword,
        limit: this.limit,
        page: this.page,
        status_text: this.status_text,
        status: this.status,
        genre_text: this.genre_text,
        genre: this.genre
      });
      console.log(data);
      // this.tableData = data.data = [{}]//  模仿的假数据
      this.tableData = data.data;
      this.total = data.total;
    },
    // 删除
    del(id) {
      this.$confirm("是否确定删除?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          delM(id);
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
    categry() {
      this.page = 1;
      this.limit = 15;
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
    info(id) {
      console.log(id);
      this.$router.push({
        name: "admin_aa",
        params: { id: id }
      });
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
  },
  mounted() {
    this.getMaintenance();
  }
};
</script>
    

<style lang="less" scoped>
.user {
  background-color: #fff;
  padding: 15px;
}
</style>
