<template>
  <div class="order">
    <div class="box">
      <div class="one">
        <div class="top">
          <h5>今日成交</h5>

          <div>
            <span>人均消费:￥{{today.average}}</span>
          </div>
        </div>
        <div class="button">
          <div>
            <div>成交量/交易量(件)</div>
            <div>{{today.turnover}}/{{today.volume}}</div>
          </div>
          <div>
            <div>成交额/成交额(件)</div>
            <div>{{today.number}}/{{today.number1}}</div>
          </div>
        </div>
      </div>
      <div class="one">
        <div class="top">
          <h5>昨日成交</h5>
          <div>
            <span>人均消费:￥{{yesterday.average}}</span>
          </div>
        </div>
        <div class="button">
          <div>
            <div>成交量/交易量(件)</div>
            <div>{{yesterday.turnover}}/{{yesterday.volume}}</div>
          </div>
          <div>
            <div>成交额/成交额(件)</div>
            <div>{{yesterday.number}}/{{yesterday.number1}}</div>
          </div>
        </div>
      </div>
      <div class="one">
        <div class="top">
          <h5>近7日成交</h5>
          <div>
            <span>人均消费:￥{{seven.average}}</span>
          </div>
        </div>
        <div class="button">
          <div>
            <div>成交量/交易量(件)</div>
            <div>{{seven.turnover}}/{{seven.volume}}</div>
          </div>
          <div>
            <div>成交额/成交额(件)</div>
            <div>{{seven.number}}/{{seven.number1}}</div>
          </div>
        </div>
      </div>
      <div class="one">
        <div class="top">
          <h5>近一个月成交</h5>
          <div>
            <span>人均消费:￥{{month.average}}</span>
          </div>
        </div>
        <div class="button">
          <div>
            <div>成交量/交易量(件)</div>
            <div>{{month.turnover}}/{{month.volume}}</div>
          </div>
          <div>
            <div>成交额/成交额(件)</div>
            <div>{{month.number}}/{{month.number1}}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="cc">
      <div id="myChart" :style="{width: '100%', height: '300px'}"></div>
    </div>
  </div>
</template>

<script>
import { Goodssummarize } from "@/components/apicom/index";

import seEcharts from "@/components/echarts";
import echarts from "echarts";
export default {
  components: { seEcharts },
  data() {
    return {
      today: {}, // 今日
      yesterday: {}, //昨日
      seven: {}, //近七日
      month: {}, //近一个月
      info: {}
    };
  },
  methods: {
    // 获得数据
    getshuju() {
      Goodssummarize().then(data => {
        console.log(data);
        this.info = data;
        this.month = data.month;
        this.yesterday = data.yesterday;
        this.seven = data.seven;
        this.today = data.today;
        this.drawLine();
      });
    },
    drawLine() {
      // 基于准备好的dom，初始化echarts实例
      let myChart = this.$echarts.init(document.getElementById("myChart"));
      // 绘制图表
      myChart.setOption({
        tooltip: {
          trigger: "axis"
        },
        legend: {
          data: this.info.trend.legend
        },
        title: {
          text: "               近一个月交易走势"
        },
        grid: {
          left: "3%",
          right: "4%",
          bottom: "3%",
          containLabel: true
        },
        xAxis: {
          type: "category",
          boundaryGap: false,
          data: this.info.trend.time
        },
        yAxis: {
          type: "value"
        },
        series: this.info.trend.series,
      });
    }
  },
  created() {
    this.getshuju();
  }
};
</script>

<style lang="less" scoped>
.box {
  display: flex;
  justify-content: space-around;
  flex-wrap: wrap;
  align-items: space-around;
  padding-top: 10px;
  background-color: none !important;
}
.one {
  width: 700px;
  height: 300px;
  background-color: #fff;
  margin: 15px;
  .top {
    height: 50px;
    display: flex;
    justify-content: space-end;
    h5 {
      padding: 0;
      margin: 0;
      font-size: 18px;
      padding: 8px;
      line-height: 50px;
    }
    div {
      flex: 1;
      border-bottom: 1px solid #ccc;
      line-height: 50px;
      clear: both;
      position: relative;
      span {
        position: absolute;
        right: 8px;
      }
    }
  }
  .button {
    height: 250px;
    display: flex;
    justify-content: space-around;
    align-items: center;
  }
}

.cc {
  margin: 0 70px;
  width: 92%;
  /deep/div {
    background-color: #fff;
  }
}
</style>
