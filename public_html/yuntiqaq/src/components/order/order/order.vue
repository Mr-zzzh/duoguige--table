<template>
  <div class="order">
    <div class="box">
      <div class="one">
        <div class="top">
          <h5>今日成交</h5>

          <div>
            <span>人均消费:0</span>
          </div>
        </div>
        <div class="button">
          <div>
            <div>成交量/交易量(件)</div>
            <div>0/0</div>
          </div>
          <div>
            <div>成交量/交易量(件)</div>
            <div>0/0</div>
          </div>
        </div>
      </div>
      <div class="one">
        <div class="top">
          <h5>昨日成交</h5>
          <div>
            <span>人均消费:0</span>
          </div>
        </div>
        <div class="button">
          <div>
            <div>成交量/交易量(件)</div>
            <div>0/0</div>
          </div>
          <div>
            <div>成交量/交易量(件)</div>
            <div>0/0</div>
          </div>
        </div>
      </div>
      <div class="one">
        <div class="top">
          <h5>近7日成交</h5>
          <div>
            <span>人均消费:￥120元</span>
          </div>
        </div>
        <div class="button">
          <div>
            <div>成交量/交易量(件)</div>
            <div>0/0</div>
          </div>
          <div>
            <div>成交量/交易量(件)</div>
            <div>0/0</div>
          </div>
        </div>
      </div>
      <div class="one">
        <div class="top">
          <h5>近一个月成交</h5>
          <div>
            <span>人均消费:0</span>
          </div>
        </div>
        <div class="button">
          <div>
            <div>成交量/交易量(件)</div>
            <div>0/0</div>
          </div>
          <div>
            <div>成交量/交易量(件)</div>
            <div>0/0</div>
          </div>
        </div>
      </div>
    </div>

    <div class="echarts">
      <se-echarts :option="option" idName="option" :isCarousel="true" />
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
      option: {
    xAxis: {
        type: 'category',
        boundaryGap: false,
        data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
    },
    yAxis: {
        type: 'value'
    },
    series: [{
        data: [820, 932, 901, 934, 1290, 1330, 1320],
        type: 'line',
        areaStyle: {}
    }]
},
      today: {}, // 今日
      yesterday: {}, //昨日
      seven: {}, //近七日
      month: {}, //近一个月
      info: {}
    };
  },
  methods: {
    // 这是获取全部订单的请求
    async Goodssummarize() {
      let data = await Goodssummarize({});
      console.log(data);
      this.info = data;
      // this.month=data.month
      // this.yesterday=data.yesterday
      // this.seven=data.seven
      // this.today=data.today
      console.log(this.today);
    }
  },
  created() {
    this.Goodssummarize(this.today);
  }
};
</script>

<style lang="less" scoped>
.echarts {
  height: 40vh;
  background-color: #fff;
  margin: 0 66px;
}
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
</style>
