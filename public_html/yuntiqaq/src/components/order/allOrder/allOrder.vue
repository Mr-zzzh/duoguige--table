<template>
  <!-- 最外层的大盒子，是根元素 -->
  <div class="user">
    <!-- 上面的搜索框 -->
    <div class="serch">
      <el-input
        placeholder="关键词搜索"
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
        @change="down2"
        v-model="paytype"
        placeholder="支付方式"
        style="width:250px;background: white;float: right;margin-right:15px"
      >
        <el-option
          v-for="item in options_2"
          :key="item.paytype "
          :label="item.paytype_text"
          :value="item.paytype"
        ></el-option>
      </el-select>

      <el-date-picker
        @change="xzfl(timer)"
        style=" width:400px;float:right;margin:0 20px"
        v-model="timer"
        type="datetimerange"
        value-format="yyyy-MM-dd"
        range-separator="至"
        start-placeholder="开始日期"
        end-placeholder="结束日期"
      ></el-date-picker>

      <el-select
        @change="down"
        v-model="status"
        placeholder="状态"
        style="width:250px;background: white;float: right;"
      >
        <el-option
          v-for="item in options"
          :key="item.status"
          :label="item.status_text"
          :value="item.status"
        ></el-option>
      </el-select>
    </div>
    <div class="orderNum">
      <p>
        订单数:
        <span>{{number}}</span>&nbsp;&nbsp;&nbsp;&nbsp;订单金额
        <span>{{money}}</span>
      </p>
    </div>
    <!-- 下面的表格 -->

    <el-table :data="tableData" style="width: 100%" @selection-change="selectionChange">
      <el-table-column type="selection" width="55"></el-table-column>
      <el-table-column label="ID" width="120" type="index" ></el-table-column>

      <el-table-column label="订单编号" width="180" prop="ordersn"></el-table-column>
      <el-table-column label="购买商品" prop="gname"></el-table-column>
      <el-table-column label="价格" prop="money"></el-table-column>
      <el-table-column label="买家" prop="dname"></el-table-column>
      <el-table-column label="下单时间" prop="createtime"></el-table-column>
      <el-table-column label="付款方式" prop="paytype_text"></el-table-column>
      <el-table-column label="状态" prop="status_text"></el-table-column>
      <el-table-column label="点击发货">
        <template slot-scope="scope">
          <el-button  v-if="status==2||status==3" disabled @click="fh(scope.row.id,scope.row)" type="text" size="small">发货</el-button>
          <el-button  v-else="" @click="fh(scope.row.id,scope.row)" type="text" size="small">发货</el-button>
        </template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
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
import {
  getGoodsOrder,
  delGoods,
  getGoodsdeliver
} from "@/components/apicom/index";

export default {
  data() {
    return {
      radio: "1",
      options: [
        {
          status: -1,
          status_text: "取消订单"
        },
        {
          status: 0,
          status_text: "待支付"
        },
        {
          status: 1,
          status_text: "支付"
        },
        {
          status: 2,
          status_text: "已发货"
        },
        {
          status: 3,
          status_text: "已收货"
        }
      ],

      options_2: [
        {
          paytype: 1,
          paytype_text: "支付宝"
        },
        {
          paytype: 2,
          paytype_text: "微信"
        }
      ],
      form: {},
      dialogVisible: false,
      paytype: "",
      status: "",
      page: 1,
      limit: 15,
      keyword: "",
      total: 0,
      currentPage: 1,
      tableData: [],
      timer: "",
      starttime: "",
      endtime: "",
      money: "",
      number: "",
      id: "",
      status_text: ""
    };
  },
  mounted() {},
  methods: {
    // 这是发货的请求getGoodsdeliver
    async getGoodsdeliver() {
      let data = await getGoodsdeliver({
        id: this.id
      });
      console.log(data);
    },
    fh(id, row) {
      this.$confirm("是否确定发货?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          getGoodsdeliver(id);
          // this.page = 1;
          this.getGoodsOrder();
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消发货"
          });
        });
    },

    // 这是获取全部订单的请求
    async getGoodsOrder() {
      let data = await getGoodsOrder({
        keyword: this.keyword,
        limit: this.limit,
        page: this.page,
        starttime: this.starttime,
        endtime: this.endtime,
        status: this.status,
        paytype: this.paytype,
        status_text: this.status_text
      });
      console.log(data);
      this.tableData = data.data;
      this.total = data.total;
      this.money = data.money;
      this.number = data.number;
      data.data.forEach(element => {
        this.status_text = element.status_text;
        this.status = element.status
      });
    },
    down(e) {
      this.page = 1;
      this.limit = 15;
      this.getGoodsOrder();
      // console.log(e);
      console.log(this.status);
    },
    down2(e) {
      this.page = 1;
      this.limit = 15;
      this.getGoodsOrder();
      console.log(this.paytype);
    },

    // 时间
    xzfl(e) {
      console.log(e);
      if (e == null) {
        this.starttime = "";
        this.endtime = "";
      } else {
        this.starttime = e[0];
        this.endtime = e[1];
        console.log(this.starttime);
        console.log(this.endtime);
      }
      this.page = 1;
      this.limit = 15;
      this.getGoodsOrder();
    },
    // 搜索
    search() {
      // console.log(1111);
      this.page = 1;
      this.limit = 15;
      this.getGoodsOrder();
    },

    // 删除
    del(id) {
      this.$confirm("是否确定删除?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          delGoods(id);
          this.page = 1;
          this.getGoodsOrder();
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消删除"
          });
        });
    },

    // 这三个是表格中的
    // 当选择项发生变化时会触发该事件
    selectionChange(list) {
      this.selectedList = list.map(v => v.id).join(",");
      console.log(this.selectedList);
    },
    // 分页----这是选择每页多少条的时候触发
    handleSizeChange(val) {
      this.limit = val; //让其相等
       this.page = 1;
      this.getGoodsOrder();
      console.log(`每页 ${val} 条`);
    },
    // 分页------当前页码切换的时候触发
    handleCurrentChange(val) {
      this.limit = 15;
      this.page = val;
      this.getGoodsOrder();
      console.log(`当前页: ${val}`);
    }
  },
  created() {
    this.getGoodsOrder();
  }
};
</script>


<style lang="less" scoped>
.user {
  height: 100%;
  background-color: #fff;
  padding: 8px;
}
.serch {
  height: 50px;
}
.orderNum {
  font-size: 20px;
  height: 50px;
  padding-left: 10px;
  span {
    color: red;
  }
}
</style>

