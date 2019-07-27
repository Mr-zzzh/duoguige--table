<template>
    <div class="wrapper">
        <v-head></v-head>
        <v-sidebar-left @noIndex="noIndex"></v-sidebar-left>
        <div class="content-box" :class="{noindex:noindex,isOpen:isopen}">
            <v-tags></v-tags>
            <div class="content">
                <transition name="move" mode="out-in">
                    <!-- <keep-alive :include="tagsList"> -->
                    <router-view></router-view>
                    <!-- </keep-alive> -->
                </transition>
            </div>
        </div>
    </div>
</template>
<style>
/* .wrapper {
    min-width: 1440px;
    overflow-x: auto;
    overflow-y: hidden; 
} */
::-webkit-scrollbar {
    /* 滚动条整体部分 */
    width: 6px;
    height: 6px;
    background-color: #f5f5f5;
}
::-webkit-scrollbar-thumb {
    /* 滑块 */
    width: 6px;
    border-radius: 5px;
    background: #28b7a3;
}
.content-box {
    position: absolute;
    left: 0px;
    right: 0px;
    top: 60px;
    bottom: 0;
    /*border-radius: 20px 0 0 0 ;*/
    padding-bottom: 30px;
    background-color: #edf1f2;
    overflow-y: scroll;
    -webkit-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out;
    padding-right: 26px;
}

.content {
    margin: 15px 0 0 20px;
    height: 91%;
    box-sizing: border-box;
    min-width: 1220px;
    /* background-color: #fff; */
}
.noindex {
    position: absolute;
    left: 170px !important;
    top: 80px;
    bottom: 0;
}
.isOpen {
    position: absolute;
    right: 260px;
    top: 60px;
    bottom: 0;
}
</style>
<script>
import vHead from "./Header.vue";
import vSidebarLeft from "./SidebarLeft.vue";
import vTags from "./Tags.vue";
import bus from "./bus.js";
let content_box = document.getElementsByClassName("content-box");
export default {
    data() {
        return {
            tagsList: [],
            collapse: false,
            noindex: false,
            isopen: false
        };
    },
    components: {
        vHead,
        vSidebarLeft,
        vTags
    },
    methods: {
        noIndex(data) {
            if (data == true) {
                this.noindex = true;
            } else {
                this.noindex = false;
            }
        },
        isOpen(data) {
            // console.log('1111',data)
            if (data == false) {
                this.isopen = false;
            } else {
                this.isopen = true;
            }
        }
    },
    created() {
        // 只有在标签页列表里的页面才使用keep-alive，即关闭标签之后就不保存到内存中了。
        // bus.$on('tags', msg => {
        //     let arr = [];
        //     for(let i = 0, len = msg.length; i < len; i ++){
        //         msg[i].name && arr.push(msg[i].name);
        //     }
        //     this.tagsList = arr;
        // })
    }
};
</script>

