<template>
  <div class="sidebarleft">
    <el-menu
      :collapse="collapse"
      :default-active="$route.path"
      :default-openeds="defaultitems"
      class="el-menu-vertical-demo sidebar-el-menu"
      background-color="#1E222B"
      text-color="#ccc"
      @open="opensub"
      @close="closesub"
      active-text-color="#0177D5"
      unique-opened
      router
    >
      <!-- {{$route.path}} -->
      <!-- {{defaultitems}} -->
      <template v-for="item in items">
        <template v-if="item.subs">
          <el-submenu :index="item.index" :key="item.index">
            <template slot="title">
              <i :class="item.icon">
                <img
                  style="margin-bottom:3px"
                  :src="item.img"
                  alt
                  v-if="(item.index != openindex)||(item.index == closeindex)"
                />
                <img
                  style="margin-bottom:3px"
                  :src="item.img1"
                  alt
                  v-if="(item.index == openindex)&&(item.index!= closeindex)"
                />
              </i>
              <i class="Open" v-if="(item.index == openindex)&&(item.index!= closeindex)">
                <img src="../../../static/img/dfbrrbg35.png" alt />
              </i>
              <i class="Close" v-if="(item.index != openindex)||(item.index == closeindex)">
                <img src="../../../static/img/dfbrrbg34.png" alt />
              </i>
              <!-- <span slot="title" class="title_span" :class="(item.index == openindex)&&(item.index!= closeindex)? color_Orange :''">{{ item.title }}</span> -->
              <span
                slot="title"
                class="title_span"
                :class="(item.index == openindex)&&(item.index!= closeindex)? color_green :''"
              >
                <!-- {{item.index == openindex}} -->
                <!-- {{item.index!= closeindex}} -->
                <!-- {{closeindex}} -->
                <!-- {{openindex}} -->
                <!-- {{item.index}} -->
                {{ item.title }}
              </span>
            </template>
            <el-menu-item
              v-for="(subItem,i) in item.subs"
              :key="i"
              :index="subItem.index"
              @click="slideclick(item,subItem)"
            >{{subItem.title}}</el-menu-item>
          </el-submenu>
        </template>
        <template v-else>
          <el-menu-item :index="item.index" :key="item.index">
            <!-- <i :class="item.icon"></i> -->
            <span slot="title">{{ item.title }}</span>
          </el-menu-item>
        </template>
      </template>
    </el-menu>
  </div>
</template>
<script>
import Bus from "./bus.js";
export default {
  data() {
    return {
      num1: [],
      num2: [],

      qaz: 1,
      qab: true,

      collapse: false,
      usertype: "",
      openindex: "",
      closeindex: "",
      color_green: "color_green",
      items: [],
      noindex: false,
      ms_menu: "",
      defaultitems: [],
      productarr: [],
      ms_level: sessionStorage.getItem("ms_level"),
      usequnxian: JSON.parse(sessionStorage.getItem("usequnxian")),
      // 侧边栏
      productlisttab: [
        {
          img: "../../../static/img/sz/dfbrrbg1.png",
          img1: "../../../static/img/sz/dfbrrbg18.png",
          index: "/admin_index",
          title: "会员管理",
          subs: [
            {
              index: "/admin_index",
              title: "全部会员"
            }
          ]
        },
          {
          img: "",
          img1: "",
          index: "/admin_index/user",
          title: "用户管理",
          subs: [
            {
              index: "/admin_index/user/index",
              title: "普通用户"
            },
            {
              index: "/admin_index/user/pope",//大师
              title: "技术大师"
            },
            {
              index: "/admin_index/user/audit",//审核
              title: "技术大师审核"
            },
            {
              index: "/admin_index/user/property",//物业公司
              title: "物业公司"
            },{
              index: "/admin_index/user/p_audit",//物业公司审核
              title: "物业公司审核"
            }
          ]
        }
      ]
    };
  },
  methods: {
    opensub(key, keyPath) {
      console.log(key);
      this.openindex = key;
      this.closeindex = "";
      // this.defaultitems = key
    },
    closesub(key, keyPath) {
      // console.log(key, keyPath);
      this.closeindex = key;
      this.openindex = "";
    },
    slideclick(item, sub) {},
    watchroute() {
      // console.log(JSON.parse(localStorage.getItem('ms_tab')))
      this.items = JSON.parse(localStorage.getItem("ms_tab"));
      // console.log('111',this.items)
      if (this.items == null || this.items.length == 0) {
        this.noindex = false;
      } else {
        this.noindex = true;
      }

      this.$emit("noIndex", this.noindex);
    }
  },
  watch: {
    //监听路由变化

    $route(to, from) {
      //路由的name
      console.log(to);
      // console.log(from)
      // 会员
      if (this.productarr.includes(to.name)) {
        // console.log('to.name',to.name)
        this.openindex = "allvip";
        this.defaultitems = ["allvip"];
        let ms_tabstr = JSON.stringify(this.productlisttab);
        localStorage.setItem("ms_tab", ms_tabstr);
      }
      sessionStorage.setItem("openindex", this.openindex);

      this.watchroute();
    }
  },
  created() {
    // 唯一一个侧边栏
    this.openindex = "allvip";
    console.log(this.openindex);
    // this.defaultitems =['allvip']
    let ms_tabstr = JSON.stringify(this.productlisttab);
    localStorage.setItem("ms_tab", ms_tabstr);

    this.openindex = sessionStorage.getItem("openindex");
    // console.log(this.openindex)
    // this.openindex=JSON.parse(sessionStorage.getItem('openindex'))
    this.watchroute();
    // console.log('usequnxian111',this.usequnxian)
  },
  mounted() {}
};
</script>
<style lang="less">
.sidebarleft {
  .color_green {
    // #0177D5      #28b7a3
    // color: #0177D5 !important;
    color: rgba(255, 255, 255, 1);
    // opacity: 1;
  }
  .el-menu {
    border-right: none;
  }
  // float: left;
  position: absolute;
  left: 0;
  top: 80px;
  bottom: 0;
  overflow-y: scroll;
  // &::-webkit-scrollbar {
  //     width: 0;
  // }
  .sidebar-el-menu:not(.el-menu--collapse) {
    width: 200px; /* 左侧公共侧边栏的宽度 */
  }

  // .title_span {
  // margin-left: 5px;
  // overflow-y: scroll;
  // }
  .sidebar-el-menu:not(.el-menu--collapse) {
    width: 200px !important; /* 左侧公共侧边栏的宽度 */
  }
  & > ul {
    background-color: #1e222b;
    height: 100%;
  }
  /* 二级目录 */
  .el-menu-item {
    background: #1e222b !important;
    box-sizing: border-box;
    font-size: 14px;
    // border-left: 2px solid #fe7d6f;
    padding-left: 45px !important;
    color: rgba(255, 255, 255, 0.5) !important;
  }
  .Open {
    float: right;
    width: 20px;
    img {
      width: 5px;
      height: 10px;
    }
  }
  .Close {
    width: 20px;
    float: right;
    img {
      width: 5px;
      height: 10px;
    }
  }
  .el-menu-item.is-active {
    background-color: #2fc6af !important;
  }
  .el-menu-item i {
    margin-right: 30px;
  }
  .is-active {
    color: white !important;
    /* border: 1px #FE7D6F solid; */
  }
  .el-submenu /deep/ .el-submenu__title {
    // padding-left: 35px !important;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.5) !important;
    // opacity: 0.4;
  }
  .el-submenu /deep/ .el-submenu__icon-arrow {
    // right:40px;
    // font-size:16px;
    // color: #C1C8D9;
    // background-image: url('../../../static/img/index/icon_childOpen.png');
    display: none;
  }
  .el-submenu__icon-arrow {
    display: none;
  }
}
</style>

