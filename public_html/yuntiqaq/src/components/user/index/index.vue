<template>
  <!-- 最外层的大盒子，是根元素 -->
  <div class="user">
    <!-- 上面的搜索框 -->
    <div class="serch">
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
        @change="categry"
        v-model="status"
        placeholder="状态"
        style="width:250px;background: white;float: right;"
      >
        <el-option
          v-for="item  in options"
          :key="item.status"
          :label="item.status_text"
          :value="item.status"
        ></el-option>
      </el-select>
    </div>

    <div class="btn">
      <el-button type="danger" @click="get()">设置黑名单</el-button>
      <el-button type="primary" @click="cancelBlackList">取消黑名单</el-button>
    </div>

    <!-- 下面的表格 -->
    <div class="table">
      <el-table :data="tableData" style="width: 100%" @selection-change="selectionChange">
        <el-table-column type="selection" width="55"></el-table-column>
        <el-table-column label="ID" width="180" type="index"></el-table-column>

        <el-table-column label="姓名" width="180" prop="name"></el-table-column>
        <el-table-column label="电话" prop="phone"></el-table-column>
        <el-table-column label="类型" prop="type_text"></el-table-column>
        <el-table-column label="注册时间" prop="createtime"></el-table-column>
        <el-table-column label="成交" prop="createtime">
          <template slot-scope="scope">
            <span>订单:{{ scope.row.order || 10 }}</span>
            <br />
            <span>金额:{{ scope.row.price || 10 }}</span>
          </template>
        </el-table-column>

        <el-table-column label="黑名单">
           <template slot-scope="scope">
          <el-button   v-if="scope.row.normal==1" size="small" type="primary" v-model="normal">启用</el-button>
          <el-button   v-else size="small"  type="info">禁用</el-button>
        </template>
        </el-table-column>
        <el-table-column label="状态" prop="status_text"></el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <i class="el-icon-delete" @click="del(scope.row.id)"></i>
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
import { getUserTab } from "@/components/apicom/index";
import { delUser } from "@/components/apicom/index";
import { getForbidden } from "@/components/apicom/index";

export default {
  data() {
    return {
      // 下拉列表里面的数据
      page: 0, //获取的页面
      limit: 15, //每页记录数
      keyword: "", //关键字检索
      total: 0, //默认的总条数
      currentPage: 1, // 当前页
      tableData: [], //获得的数据
      // 需要做判断的类型
      selectedList: "",
      // 选择框里的黑名单和正常的时候
      normal: "",
      options: [
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
        }
      ],
      normal_text: "",
      normal: "",
      id:"",
      status: ""
    };
  },

  methods: {
    // 这三个是表格中的
    // 当选择项发生变化时会触发该事件
    selectionChange(list) {
      this.selectedList = Number(list.map(v => v.id).join(","));
      this.id = this.selectedList;
      console.log(this.id);
    },
    // 这是自己封装的，意思是当点击设置或是取消的时候出发
     get() {
      getForbidden({
        id:this.id,
        normal:this.normal,
        status:this.status
      }).then(res=>{
        console.log(res);
        
      })
    },
    async cancelBlackList() {
      // await cancelBlackList(this.selectedList)
      // this.getUserTab();
    },
    // 这是获取用户列表的请求
    async getUserTab() {
      let data = await getUserTab({
        keyword: this.keyword,
        limit: this.limit,
        page: this.page,
        type: 1,
        // id: this.id
        normal: this.normal,
        status:this.status,
      });
      console.log(data, 11111);
      this.tableData = data.data;
      this.total = data.total;
      this.page = data.total / this.limit;
      data.data.forEach(element => {
        console.log(element);
        this.id = element.id;
        this.normal = element.normal;
      });
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
          // this.page = 1;
          this.getUserTab();
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消删除"
          });
        });
    },
      handleEdit(index, row) {
        console.log(index, row.normal);
      },
    // 搜索
    search() {
      this.normal = 2;
      this.getUserTab();
    },
    // 获取分类
    categry() {
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
      this.limit = 15;
      this.page = val;
      this.getUserTab();
      console.log(`当前页: ${val}`);
    }
  },
  mounted() {
    // this.getUserTab();
  },
  created() {
    this.getUserTab();
  }
};
</script>


<style lang="less" scoped>
.user {
  padding: 8px;
  background-color: #fff;
}
.serch {
  height: 60px;
  .el-input {
    margin-left: 18px;
  }
}
.btn {
  margin: 10px 5px;
  padding-left: 10px;
}
.el-table__row .el-table td {
  padding: 8px;
}
</style>

