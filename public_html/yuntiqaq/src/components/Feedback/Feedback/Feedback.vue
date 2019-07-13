<template>
  <div class="user">
    <el-table :data="tableData" style="width: 100%">
      <el-table-column label="ID" type="index" width="180"></el-table-column>

      <el-table-column
        label="反馈内容"
        prop="content"
        overflow="hidden"
        text-overflow="ellipsis"
        white-space="nowrap"
      ></el-table-column>
      <el-table-column label="反馈人信息" prop="uname"></el-table-column>

      <el-table-column label="反馈时间" prop="createtime"></el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button @click="del(scope.row.id)" type="text" size="small">删除</el-button>
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

    <!-- 新增or编辑 -->
    <el-dialog title="反馈信息" :visible.sync="dialogVisible" width="60%" :before-close="handleClose">
      <span>
        <el-form ref="form" :model="form" label-width="100px" disabled>
          <el-form-item label="反馈人信息">
            <el-input v-model="form.uname"></el-input>
          </el-form-item>
          <el-form-item label="反馈内容">
            <el-input type="textarea" v-model="form.content"></el-input>
          </el-form-item>
        </el-form>
      </span>

      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false" type="primary">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>


    <script>
import { getFeedback, delFeedback } from "@/components/apicom/index";
export default {
  data() {
    return {
      page: 1,
      limit: 15,
      keyword: "",
      total: 0,
      currentPage: 1,
      tableData: [],

      // 与弹窗相关
      dialogVisible: false,
      form: {
        content: "",
        createtime: "",
        id: "",
        uid: "",
        uname: ""
      }
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
        type: 1,
        id: this.id
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
      this.getFeedback();
      console.log(`每页 ${val} 条`);
    },
    // 分页
    handleCurrentChange(val) {
      this.page = val;
      this.getFeedback();
      console.log(`当前页: ${val}`);
    },

    // 删除
    del(id) {
      this.$confirm("是否确定删除?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          delFeedback(id);
          this.page = 1;
          this.getFeedback();
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消删除"
          });
        });
    },
    info(id) {
      console.log(id);
      console.log(this.tableData);
      this.tableData.forEach(item => {
        console.log(item);
        if (id == item.id) {
          this.form = item;
          this.form.content = item.content;
          this.form.uname = item.uname;
          console.log(this.form);
        }
      });
      this.dialogVisible = true;
    },
    // 弹窗
    handleClose() {
      this.dialogVisible = false;
    }
  },
  created() {
    this.getFeedback();
  }
};
</script>
    

<style lang="less" scoped>
.user {
  background-color: #fff;
  padding: 8px;
}
</style>
