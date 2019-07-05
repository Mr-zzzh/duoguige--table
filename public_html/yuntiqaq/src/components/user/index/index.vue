<template>
  <!-- 最外层的大盒子，是根元素 -->
  <div class="user">
    <!-- 上面的搜索框 -->
    <div class="serch">
      <el-select
        v-model="value"
        multiple
        filterable
        remote
        reserve-keyword
        placeholder="黑名单"
        :remote-method="remoteMethod"
        :loading="loading"
      >
        <el-option
          v-for="item in options"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        ></el-option>
      </el-select>
      <el-select
        v-model="value"
        multiple
        filterable
        remote
        reserve-keyword
        placeholder="请输入姓名/手机号搜索"
        :remote-method="remoteMethod"
        :loading="loading"
      >
        <el-option
          v-for="item in options"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        ></el-option>
      </el-select>
      <el-button type="primary" icon="el-icon-search">搜索</el-button>
    </div>

    <div id="button">
      <div class="button">
        <el-radio v-model="radio" label="1" id="btn">设置黑名单</el-radio>
        <el-radio v-model="radio" label="2">取消黑名单</el-radio>
      </div>
    </div>
    <!-- 下面的表格 -->
    <div class="table">
      <el-table :data="tableData" style="width: 100%">
        <el-table-column type="selection" width="55"></el-table-column>
        <el-table-column label="ID" width="180">
          <span>1</span>
        </el-table-column>

        <el-table-column label="姓名" width="180">
          <span>王建国</span>
        </el-table-column>
        <el-table-column label="电话" prop>
          <span>12345678900</span>
        </el-table-column>
        <el-table-column label="类型" prop>
          <span>普通会员</span>
        </el-table-column>
        <el-table-column label="注册时间" prop="date">
         
        </el-table-column>
        <el-table-column label="成交" prop="">
           <div>订单:111</div>
          <div>金额:111</div>
        </el-table-column>
        <el-table-column label="黑名单" prop="date">
          
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button size="mini" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
            <el-button size="mini" type="danger" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import { getCategory } from "@/components/apicom/index";

export default {
  data() {
    return {
      radio: 3,
      options: [],
      value: [],
      list: [],
      loading: false,
      states: [
        "Alabama",
        "Alaska",
        "Arizona",
        "Arkansas",
        "California",
        "Colorado",
        "Connecticut",
        "Delaware",
        "Florida",
        "Georgia",
        "Hawaii",
        "Idaho",
        "Illinois",
        "Indiana",
        "Iowa",
        "Kansas",
        "Kentucky",
        "Louisiana",
        "Maine",
        "Maryland",
        "Massachusetts",
        "Michigan",
        "Minnesota",
        "Mississippi",
        "Missouri",
        "Montana",
        "Nebraska",
        "Nevada",
        "New Hampshire",
        "New Jersey",
        "New Mexico",
        "New York",
        "North Carolina",
        "North Dakota",
        "Ohio",
        "Oklahoma",
        "Oregon",
        "Pennsylvania",
        "Rhode Island",
        "South Carolina",
        "South Dakota",
        "Tennessee",
        "Texas",
        "Utah",
        "Vermont",
        "Virginia",
        "Washington",
        "West Virginia",
        "Wisconsin",
        "Wyoming"
      ],
      tableData: [
        {
          date: "2016-05-02",
          name: "王小虎",
          address: "上海市普陀区金沙江路 1518 弄"
        },
        {
          date: "2016-05-04",
          name: "王小虎",
          address: "上海市普陀区金沙江路 1517 弄"
        },
        {
          date: "2016-05-01",
          name: "王小虎",
          address: "上海市普陀区金沙江路 1519 弄"
        },
        {
          date: "2016-05-03",
          name: "王小虎",
          address: "上海市普陀区金沙江路 1516 弄"
        }
      ]
    };
  },
  mounted() {
    this.list = this.states.map(item => {
      return { value: item, label: item };
      console.log(1111);
    });
  },
  methods: {
    remoteMethod(query) {
      if (query !== "") {
        this.loading = true;
        setTimeout(() => {
          this.loading = false;
          this.options = this.list.filter(item => {
            return item.label.toLowerCase().indexOf(query.toLowerCase()) > -1;
          });
        }, 200);
      } else {
        this.options = [];
      }
    },
    handleEdit(index, row) {
      console.log(index, row);
      this.getUser();
    },
    handleDelete(index, row) {
      console.log(index, row);
    },
    // 这是获取用户列表的请求
    getUser() {
      console.log(22222);

      getCategory().then(res => {
        console.log(res);
        console.log(111);
      });
    }
  },
  created() {
    this.getUser();
    console.log(1111);
  }
};
</script>、


<style lang="less" scoped>
/deep/.content {
  margin-top: 80px;
}
.user {
  position: relative;
  height: 100%;
  clear: both;
  background-color: rgba(255, 255, 255, 1);

  .serch {
    position: absolute;
    top: 25px;
    right: 20px;
    clear: both;
    height: 100px;
    .el-select:nth-of-type(2) {
      width: 350px;
    }
  }
  #button {
    .button {
      width: 100%;
      height: 80px;
      margin-top: 40px;
      > #btn {
        margin-top: 70px;
        > span {
          border-radius: no;
          /deep/span {
            display: block;
            width: 25px;
            height: 25px;
            border-radius: no;
            .el-radio__original {
              border-radius: no;
            }
          }
        }
      }
    }
  }
  .table {
    clear: both;
    margin-top: 10px;
    .el-table {
      border-top: 1px solid #000;
      thead.has-gutter {
        color: #000;
        tr {
          background-color: pink;
        }
      }
    }
  }
}
</style>

