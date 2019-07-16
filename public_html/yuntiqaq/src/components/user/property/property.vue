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

    <el-select
      @change="categry"
      v-model="status"
      placeholder="状态"
      style="width:250px;background: white;float: right;margin-right:10px"
    >
      <el-option
        v-for="item  in options"
        :key="item.status"
        :label="item.status_text"
        :value="item.status"
      ></el-option>
    </el-select>

    <!-- 下面的表格 -->
    <el-table :data="tableData" style="width: 100%" @selection-change="selectionChange">
      <el-table-column type="selection" width="55"></el-table-column>
      <el-table-column label="ID" width="180"  type="index"></el-table-column>

      <el-table-column label="公司名称" width="180" prop="company_name"></el-table-column>
      <el-table-column label="法人姓名" width="180" prop="name"></el-table-column>

      <el-table-column label="联系电话" prop="phone"></el-table-column>

      <el-table-column label="状态" prop="status_text"></el-table-column>
      <el-table-column label="申请时间" prop="createtime"></el-table-column>
      <el-table-column label="审核时间" prop="checktime"></el-table-column>
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
import { getUserTab } from "@/components/apicom/index";
import { delUser } from "@/components/apicom/index";

export default {
  data() {
    return {
      // 选择框里面要下拉的选项
      options: [
        {
          status: "0",
          status_text: "待审"
        },
        {
          status: "1",
          status_text: "通过"
        },
        {
          status: "2",
          status_text: "不通过"
        }
      ],
      page: 0,
      limit: 15,
      keyword: "",
      total: 0,
      currentPage: 1,
      tableData: [],
      aa: "",
      id: "",
      status: "",
      // 存贮当前的一条的所有的信息
      userInfo: {}
    };
  },
  methods: {
    // 当选择项发生变化时会触发该事件，只有被勾上的时候才会被触发
    selectionChange(list) {
      this.selectedList = list.map(v => v.id).join(",");
      console.log(this.selectedList);
      this.id = this.selectedList;
      console.log(this.id);
      if (this.selectedList) {
        this.$message.success("已启用");
      } else {
        this.$message.info("已禁用");
      }
    },

    // 点击去详情页
    info(id) {
      this.$router.push({
        name: "/admin_p_audit",
        params: { id }
      });
      console.log(this.userInfo, 22222);
      // localStorage.setItem("user", JSON.stringify(this.userInfo));
    },
    // 获得物业公司列表的请求
    async getUserTab() {
      let data = await getUserTab({
        keyword: this.keyword,
        limit: this.limit,
        page: this.page,
        // 因为这里是技术大师的页面。所以需要选择类型
        type: 3,
        status: this.status,
        status_text: this.status
      });
      console.log(data);
      // this.tableData = data.data = [{}]//  模仿的假数据
      this.tableData = data.data;
      this.total = data.total;
      this.page = data.total / this.limit;
      data.data.forEach(item => {
        this.id = item.id;
      });
      // 主要是要在这个页面获取当前条的信息
      this.userInfo = data.data.filter(i => {
        if (i.id == this.id) {
          this.userInfo = i;
          return true;
        }
      });
      console.log(this.userInfo, 11111);
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
          this.getUserTab();
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消删除"
          });
        });
    },

    // 获取分类
    categry() {
      this.page = 1;
      this.getUserTab();
    },

    // 分页----这是选择每页多少条的时候触发
    handleSizeChange(val) {
      this.limit = val; //让其相等
      this.getUserTab();
      console.log(`每页 ${val} 条`);
    },
    // 分页------当前页码切换的时候触发
    handleCurrentChange(val) {
      this.page = val;
      this.getUserTab();
      console.log(`当前页: ${val}`);
    },
    // 搜索
    search_3() {
      this.page = 1;
      this.getUserTab();
    }
  },
  mounted() {
    this.getUserTab();
  },
  created() {
    this.getUserTab();
  }
};
</script>


<style lang="less" scoped>
.user {
  background-color: #fff;
  padding: 8px;
   width: 100%;
  height: 100%;
  position: relative;
}
.fenye{
  position: absolute;
  left: 15px;
  bottom: 15px;
}
</style>

