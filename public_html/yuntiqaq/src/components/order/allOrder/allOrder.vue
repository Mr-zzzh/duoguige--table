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
          v-for="(item,i) in options_2"
          :key="i "
          :label="item.paytype"
          :value="item.paytype_text"
        ></el-option>
      </el-select>

      <el-date-picker
        @change="xzfl(timer)"
        style=" width:250px;float:right;margin:0 20px"
        v-model="timer"
        type="datetimerange"
        value-format="yyyy-MM-dd hh-mm-ss"
        range-separator="至"
        start-placeholder="开始日期"
        end-placeholder="结束日期"
      ></el-date-picker>

      <el-select
        @change="down"
        v-model="options.item"
        placeholder="状态"
        style="width:250px;background: white;float: right;"
      >
        <el-option
          v-for="(item ,index) in options"
          :key="index"
          :label="item.status_text"
          :value="item.value"
        ></el-option>
      </el-select>
    </div>
    <div class="orderNum">
      <p>
        订单数:
        <span>00000</span>&nbsp;&nbsp;&nbsp;&nbsp;订单金额
        <span>9999999</span>
      </p>
    </div>
    <!-- 下面的表格 -->

    <el-table :data="tableData" style="width: 100%">
      <el-table-column type="selection" width="55"></el-table-column>
      <el-table-column label="ID" width="180" type="index"></el-table-column>

      <el-table-column label="订单编号" width="180" prop="ordersn"></el-table-column>
      <el-table-column label="购买商品" prop="gname"></el-table-column>
      <el-table-column label="价格" prop="money"></el-table-column>
      <el-table-column label="买家" prop="dname"></el-table-column>
      <el-table-column label="下单时间" prop="createtime"></el-table-column>
      <el-table-column label="付款方式" prop="paytype_text"></el-table-column>
      <el-table-column label="状态" prop="status_text"></el-table-column>
    </el-table>
  </div>
</template>

<script>
import { getGoodsOrder } from "@/components/apicom/index";

export default {
  data() {
    return {
      options: [
        {
          value: -1,
          status_text: "取消订单"
        },
        {
          value: 0,
          status_text: "待支付"
        },
        {
          value: 1,
          status_text: "支付"
        },
        {
          value: 2,
          status_text: "已发货"
        },
        {
          value: 3,
          status_text: "已收货"
        }
      ],
      paytype: "",
      options_2: [
        {
          value: 1,
          paytype_text: "支付宝"
        },
        {
          value: 2,
          paytype_text: "微信"
        }
      ],

      page: 1,
      limit: 15,
      keyword: "",
      total: 0,
      currentPage: 1,
      tableData: [],
      timer: "",
      status: null,
      aa: "",
      bb: null,
      option_1: {},
      starttime: "",
      endtime: ""
    };
  },
  mounted() {},
  methods: {
    // 这是获取全部订单的请求
    async getGoodsOrder() {
      let data = await getGoodsOrder({
        keyword: this.keyword,
        limit: this.limit,
        page: this.page,
        starttime: this.starttime,
        endtime: this.endtime,
        status: this.options.value,
        paytype: this.paytype.value
      });
      console.log(data);
      // this.tableData = data.data = [{}]//  模仿的假数据
      this.tableData = data.data;
      this.total = data.total;
      data.data.forEach(element => {
        // console.log(element);
        // this.option_1.value = element.status;
        // this.option_1.status_text = element.status_text;
        // this.options.push(this.option_1)
      });
    },
    down(e) {
      this.page = 1;
      this.limit = 15;
      this.getGoodsOrder();
      console.log(e);
    },
    down2(e) {
      this.page = 1;
      this.limit = 15;
      this.getGoodsOrder();
    },

    // 时间
    xzfl(e) {
      if (e == null) {
        this.starttime = "";
        this.endtime = "";
      } else {
        this.starttime = e[0];
        this.endtime = e[1];
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

