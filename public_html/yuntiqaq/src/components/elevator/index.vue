<template>
  <!-- 这里是电梯的页面 -->
  <div class="elevator">
    <el-button type="primary">添加新分类</el-button>
电梯管理
    <el-table :data="tableData" style="width: 100%">
      <el-table-column prop="date" label="ID" width="180"></el-table-column>
      <el-table-column prop="name" label="显示顺序" width="180"></el-table-column>
      <el-table-column prop="address" label="分类名称"></el-table-column>
      <el-table-column prop="address" label="状态"></el-table-column>
      
    </el-table>
  </div>
</template>

<script>
// 导入获取分类列表的请求
import { getCategory } from "@/components/apicom"

export default {
  data() {
    return {
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
  methods: {
      getCategory(){

      }
  }
};
</script>

<style lang="less" scoped>

</style>
