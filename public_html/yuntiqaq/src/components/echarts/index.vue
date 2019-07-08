<template>
  <div :id="idName" class="echarts"/>
</template>

<script type="text/ecmascript-6">
import echarts from 'echarts'

export default {
  props: ['option', 'idName', 'isCarousel'],
  data() {
    return {
      echarts: '',
      echartsInterval: false
    }
  },
  computed: {
    // sidebar() {
    //   return this.$store.getters.sidebar
    // }
  },
  watch: {
    option: {
      handler(val) {
        setTimeout(() => {
          this.echarts.setOption(val)
          this.carousel()
        }, 50)
      },
      deep: true
    },
    // sidebar: {
    //   handler() {
    //     setTimeout(() => {
    //       this.echarts.resize()
    //     }, 500)
    //   },
    //   deep: true
    // }
  },
  created() {
  },
  mounted() {
    this.initAll()
  },
  beforeDestroy() {
    clearInterval(this.echartsInterval)
    this.echartsInterval = false
  },
  methods: {
    initAll() {
      setTimeout(() => {
        this.getEchart()
        this.resize()
      }, 50)
    },
    getEchart() {
      this.echarts = echarts.init(document.querySelector(`#${this.idName}`))
      this.echarts.setOption(this.option)
      /* 点击 */
      this.echarts.on('click', (params) => {
        this.$emit('axisClick', params)
      })
      this.carousel()
    },
    resize() {
      window.addEventListener('resize', () => {
        this.idName && setTimeout(() => {
          this.echarts.resize()
        }, 500)
      })
    },
    /* 轮播 */
    carousel() {
      if (!this.isCarousel) return
      clearInterval(this.echartsInterval)
      const app = {
        currentIndex: 0
      }
      /* 设置高亮 */
      this.echarts.dispatchAction({
        type: 'highlight',
        seriesIndex: 0,
        dataIndex: app.currentIndex
      })
      /* 设置Tip */
      this.echarts.dispatchAction({
        type: 'showTip',
        seriesIndex: 0,
        dataIndex: app.currentIndex
      })
      this.echartsInterval = setInterval(() => {
        const dataLen = this.option.series[0].data.length
        /* 取消高亮 */
        this.echarts.dispatchAction({
          type: 'downplay',
          seriesIndex: 0,
          dataIndex: app.currentIndex
        })
        app.currentIndex = (app.currentIndex + 1) % dataLen
        this.echarts.dispatchAction({
          type: 'highlight',
          seriesIndex: 0,
          dataIndex: app.currentIndex
        })
        this.echarts.dispatchAction({
          type: 'showTip',
          seriesIndex: 0,
          dataIndex: app.currentIndex
        })
      }, 3 * 1000)
    }
  }
}
</script>

<style lang="less" scoped>
</style>