<template>
    <div>
        <!-- 头部 -->
        <header>
            <div class="header-t">
                数据概况
            </div>

            <div class="header-b">
                <div class="header-box">
                    <div>
                        <img src="../../../static/img/industry1.png" alt="">
                    </div>
                    <div>
                        <div>今日付款金额（元）</div>
                        <div>{{this.form.money}}</div>
                    </div>
                </div>
                <div class="header-box">
                    <div>
                        <img src="../../../static/img/industry2.png" alt="">
                    </div>
                    <div>
                        <div>今日订单数（单）</div>
                        <div>{{this.form.number}}</div>
                    </div>
                </div>
                <div class="header-box">
                    <div>
                        <img src="../../../static/img/industry3.png" alt="">
                    </div>
                    <div>
                        <div>今日已付款订单数（单）</div>
                        <div>{{this.form.pay_number}}</div>
                    </div>
                </div>
                <div class="header-box">
                    <div>
                        <img src="../../../static/img/industry4.png" alt="">
                    </div>
                    <div>
                        <div>今日新增会员（人）</div>
                        <div>{{this.form.member}}</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- 中 -->
        <section class="section1">
            <div class="section-l">
                <div>
                    订单概述
                    <span>
                        <span @click="btn1" :class="type1 == '1'?'bs':''">今日</span>
                        <span @click="btn2" :class="type1 == '2'?'bs':''">昨日</span>
                        <span @click="btn3" :class="type1 == '3'?'bs':''">近7日</span>
                        <span @click="btn4" :class="type1 == '4'?'bs':''">本月</span>
                    </span>
                </div>

                <div>
                    <div>
                        <div>{{this.form1.turnover}}/{{this.form1.volume}}</div>
                        <div>成交量/交易量（件）</div>
                    </div>
                    <div>
                        <div>{{this.form1.number}}/{{this.form1.number1}}</div>
                        <div>成交额/交易额（元）</div>
                    </div>
                    <div>
                        <div>{{this.form1.average}}</div>
                        <div>人均消费（元）</div>
                    </div>
                </div>
            </div>


            <div class="section-r">
                <div>
                    销售排行
                    <span>
                        <span @click="btn11" :class="type2 == '1'?'bs':''">今日</span>
                        <span @click="btn22" :class="type2 == '2'?'bs':''">昨日</span>
                        <span @click="btn33" :class="type2 == '3'?'bs':''">近7日</span>
                        <span @click="btn44" :class="type2 == '4'?'bs':''">本月</span>
                    </span>
                </div>
                <div class="bll" style="background: #f8f8f8;padding: 10px;">
                    <div>排名</div>
                    <div>电梯名称</div>
                    <div>成交量</div>
                    <div>成交金额</div>
                </div>
                <!-- <div class="bll1" >
                    <div>1</div>
                    <div>23</div>
                    <div>12414</div>
                    <div>asdasd</div>
                </div> -->
                <div class="bll1" v-for="(ia,index) in this.form2" :key="ia.id">
                    <div>{{index}}</div>
                    <div>{{ia.name}}</div>
                    <div>{{ia.number}}</div>
                    <div>{{ia.money}}</div>
                </div>
            </div>
        </section>

        <!-- 下 -->
        <section class="section2">
            <div class="section-l">
                <div>
                    近七日交易走势
                </div>

                <div id="myChart" :style="{width: '100%', height: '300px'}"></div>	
            </div>


            <div class="section-r">
                <div>
                    热点新闻
                    <span @click="tonews">
                        查看更多
                    </span>
                </div>
                <div>
                    <div v-for="(item,index) in this.form3" :key="index" @click="tonesxq(item)">
                        <span class="yc" style="width: 60%;display: inline-block;">{{index+1}}、{{item.title}}</span>
                        <span>{{item.createtime}}</span>
                    </div>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    export default {
        name: '',
        data() {
            return {
                form:{},
                form1:{},
                form2:[],
                form3:[],
                type1:'1',
                type2:'1',
            }
        },
        methods:{
            // 订单概述---时间
            btn1(){
                this.type1 = '1'
                this.getlist2()
            },
            btn2(){
                this.type1 = '2'
                this.getlist2()
            },
            btn3(){
                this.type1 = '3'
                this.getlist2()
            },
            btn4(){
                this.type1 = '4'
                this.getlist2()
            },
            // 销售排行---时间
            btn11(){
                this.type2 = '1'
                this.getlist3()
            },
            btn22(){
                this.type2 = '2'
                this.getlist3()
            },
            btn33(){
                this.type2 = '3'
                this.getlist3()
            },
            btn44(){
                this.type2 = '4'
                this.getlist3()
            },
            // 去新闻列表
            tonews(){
                this.$router.push({
                    name:"admin_news"
                })
            },
            // 去新闻详情
            tonesxq(e){
                console.log(e)
                this.$router.push({
                    name:'admin_newsxq',
                    query:{
                        id:e.id
                    }
                })
            },

            drawLine(){
                // 基于准备好的dom，初始化echarts实例
                let myChart = this.$echarts.init(document.getElementById('myChart'))
                // 绘制图表
                myChart.setOption({
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        data:this.form.trend.legend
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        boundaryGap: false,
                        data: this.form.trend.time
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: this.form.trend.series
                });
            },




            // 获取新闻
            getlist4(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/news`,
                    params:{
                        limit:5,
                        page:1
                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        this.form3 = res.data.data.data
                        console.log(res.data.data.data)
                    }
                })
            },
            // 销售排行
            getlist3(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/market`,
                    params:{
                        type:this.type2
                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        this.form2 = res.data.data
                        console.log(res.data.data)
                    }
                })
            },
            // 订单概述
            getlist2(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/summarize`,
                    params:{
                        type:this.type1
                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        this.form1 = res.data.data
                        console.log(res.data.data)
                    }
                })
            },
            // 头部和图
            getlist1(){
                this.$axios({
                    url:`${this.api}admin/index`,
                    methods:'get',
                    params:{

                    }
                }).then(res=>{
                    this.form = res.data.data
                    this.drawLine();
                    console.log(res.data.data)
                })
            }
        },
        created(){
            this.getlist1()
            this.getlist2()
            this.getlist3()
            this.getlist4()
        }
    }
</script>

<style lang="less" scoped>
    .bs{
        color: #0177d5 !important
    }

    header{
        background: #fff;
        padding: 30px 20px 30px 20px;
        .header-t{
            text-align: left;
            font-weight: bold;
        }
        .header-b{
            margin-top: 20px;
            display: flex;
            >div{
                flex: 1;
                margin-right: 100px;
                border: 1px solid #ccc;
            }
        }
        .header-box{
            display: flex;
            padding: 15px;
            >div:nth-child(1){
                flex:1;
            }
            >div:nth-child(2){
                flex: 2;
                >div:nth-child(1){
                    color: #666;
                }
                >div:nth-child(2){
                    font-weight: bold;
                    margin-top: 10px;
                    font-size: 28px;
                }
            }
        }
    }

    .section1{
        margin-top: 30px;
        display: flex;
        >div{
            flex:1;
            background: #fff;
            padding: 30px 20px 30px 20px;
        }
        >div:nth-child(1){
            margin-right: 20px;
        }

        .section-l{
            >div:nth-child(1){
                font-weight: bold;
                border-bottom: 1px solid #ccc;
                padding-bottom: 15px;
                >span{
                    float: right;
                    font-weight: 500;
                    font-size: 14px;
                    color: #666;
                    >span{
                        margin-right: 10px;
                        cursor: pointer;
                    }
                }
            }
            >div:nth-child(2){
                display: flex;
                margin: 40px 20px 40px 20px;
                >div{
                    flex:1;
                    text-align: center;
                    >div:nth-child(1){
                        margin-bottom: 20px;
                    }
                }
            }
        }

        .section-r{
            >div:nth-child(1){
                font-weight: bold;
                border-bottom: 1px solid #ccc;
                padding-bottom: 15px;
                >span{
                    float: right;
                    font-weight: 500;
                    font-size: 14px;
                    color: #666;
                    >span{
                        margin-right: 10px;
                        cursor: pointer;
                    }
                }
            }
            >div:nth-child(2){
                display: flex;
            }
        }
    }

    .section2{
        margin-top: 30px;
        display: flex;
        >div{
            flex:1;
            background: #fff;
            padding: 30px 20px 30px 20px;
        }
        >div:nth-child(1){
            margin-right: 20px;
        }

        .section-l{
            >div:nth-child(1){
                font-weight: bold;
                border-bottom: 1px solid #ccc;
                padding-bottom: 15px;
                >span{
                    float: right;
                    font-weight: 500;
                    font-size: 14px;
                    color: #666;
                    >span{
                        margin-right: 10px;
                        cursor: pointer;
                    }
                }
            }
  
        }

        .section-r{
            >div:nth-child(1){
                font-weight: bold;
                border-bottom: 1px solid #ccc;
                padding-bottom: 15px;
                >span{
                    float: right;
                    font-weight: 500;
                    font-size: 14px;
                    color: #666;
                    cursor: pointer;
                }
            }
            >div:nth-child(2){
                >div{
                    color: #666;
                    padding-bottom: 10px;
                    margin-top: 20px;
                    border-bottom: 0.25px solid #ccc;
                    cursor: pointer;
                    >span:nth-child(2){
                        float: right;
                    }
                }
            }
        }
    }


    .yc{
        overflow: hidden;
        text-overflow: ellipsis;
        //判断超出一行隐藏
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }



    .bll{
        display: flex;
        margin-top: 10px;
        >div{
            flex:1;
            text-align: center;
        }
    }
    .bll1{
        border-top: 0.25px solid #ccc;
        padding-top: 10px;
        padding-bottom: 10px;
        margin: 0;
        display: flex;
        font-size: 14px;
        color: #666;
        >div{
            flex:1;
            text-align: center;
        }
    }
</style>