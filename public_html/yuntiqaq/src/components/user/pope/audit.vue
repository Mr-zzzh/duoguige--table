<template>
  <!-- 技术大师审核页面 -->
  <div class="audit">
    

    <!-- 用element-ui表单写 -->
    <el-form ref="form" :model="sizeForm" label-width="80px" size="mini">
      <el-form-item label="姓名">
        <el-input v-model="sizeForm.check.company_name"></el-input>
      </el-form-item>
      <el-form-item label="性别">
        <el-input v-model="sizeForm.name"></el-input>
      </el-form-item>
      <el-form-item label="身份证号">
        <el-input v-model="sizeForm.check.phone"></el-input>
      </el-form-item>
      <el-form-item label="企业名称">
        <el-input v-model="sizeForm.check.address"></el-input>
      </el-form-item>
      <el-form-item label="营业执照注册号">
        <el-input v-model="sizeForm.check.number"></el-input>
      </el-form-item>
      <el-form-item label="证件照">
        <div>
          <img :src="sizeForm.check.image" alt />
        </div>
        <div>
          <img :src="sizeForm.check.image" alt />
        </div>
        <div>
          <img :src="sizeForm.check.image" alt />
        </div>
      </el-form-item>

      <el-form-item label="审核状态">
        <el-radio v-model="radio" label="1" @change="btn">通过</el-radio>
        <el-radio v-model="radio" label="2" @change="btn">驳回</el-radio>
      </el-form-item>
      <el-form-item size="large">
        <el-button type="primary" @click="submitForm('ruleForm')" :loading="false">提交</el-button>
        <el-button @click="resetForm('ruleForm')">取消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getUserInfo, getAudit } from "@/components/apicom/index";

export default {
  data() {
    return {
      radio: 2,
      //备选按钮的选中状态
      id: 0,
      status: 0,
      list: [],
      state: "",
      numberValidateForm: {},
      sizeForm: {
        id: 0,
        name: "",
        phone: "",
        avatar: "",
        intro: null,
        status: 1,
        type: 2,
        createtime: "",
        normal: 1,
        status_text: "",
        type_text: "",
        normal_text: "",
        check: {
          id: 2,
          uid: 12,
          name: "",
          sex: 1,
          idcardno: "",
          license_number: "",
          company_name: "",
          company_image: "",
          prove_image: "",
          technician_image: "",
          dimission: "",
          createtime: ""
        }
      }
    };
  },
  methods: {
    // 这是获取用户列表的请求------这样发请求为什么不行
    // async get() {
    //   let data = await getUserInfo({
    //     id: this.id,
    //   });
    //   console.log(data);
    // }
    get() {
      getUserInfo(this.id).then(res => {
        console.log(res);
        //后台返回的数据
        //后台返回的数据
        this.sizeForm = res;
        console.log(this.sizeForm);
        // 在此回到这个页面的时候，审核过后，应该改变审核后改项的状态值
      });
    },
    // 获得当前点击提交按钮的时候吧数据写入
    async getAudit() {
      let data = await getAudit({
        id: this.id,
        status: this.status
      });
      console.log(data);
    },
    // 当选中的时候的,通过的值是1，驳回的值是2
    btn(val) {
      console.log(val);
      if (val == "1") {
        this.status = val = 1;
      } else {
        this.status = 2;
      }
    },
    querySearch() {},
    handleSelect() {},
    handleIconClick() {},
    // 点击提交按钮时触发-----调用审核的请求，并跳转到物业公司列表页
    submitForm() {
      this.getAudit();
      this.$router.push({
        name: "/admin_property"
      });
    },
    resetForm() {}
  },
  mounted() {},
  created() {
    // 调用store中的方法------获取到当前用户的详细情况
    var user = JSON.parse(localStorage.getItem("user") || "{}");
    console.log(user); // ----------是数组,这些操作知识为了获取当前的id，发请求，通过id获取更多的当前这行的数据
    user.forEach(element => {
      (this.id = element.id), (this.status = element.status);
      if (this.status == 1) {
        this.btn();
      }
    });
    this.get();
    this.btn();
  }
};
</script>


<style lang="less" scoped>
.content {
  .audit {
    color: #000;
    padding-top: 30px;
    background-color: rgba(255, 255, 255, 1);
    padding: 40px;
    background-color: #fff;

    .info {
      font-size: 18px;
      p {
        line-height: 40px;
      }
    }
    .photo {
      margin-top: 30px;
      // padding: 40px 0;
      height: 200px;
      box-sizing: border-box;

      h6 {
        font-size: 20px;
        font-weight: normal;
        margin: 8px 8px;
        box-sizing: border-box;
        margin-left: 0;
      }
      ul {
        clear: both;
        height: 100%;
        box-sizing: border-box;
        margin-left: 0;
        border-bottom: 1px solid #000;
        li {
          list-style: none;
          float: left;
          margin: 0 10px;
          margin-left: 0px;
          box-sizing: border-box;
          p {
            margin: 8px 0;
          }
          div {
            width: 200px;
            height: 120px;
            background-color: bisque;
            margin-right: 8px;
            img {
              width: 100%;
              height: 100%;
            }
          }
        }
      }
    }
    .check {
      margin-top: 15px;
      padding: 18px 0;

      h6 {
        font-size: 20px;
        font-weight: normal;
        margin: 20px 8px;
        box-sizing: border-box;
        margin-left: 0;
      }
      span {
        margin: 25px;
        margin-left: 0;
      }
      .tex {
        margin-top: 20px;
        span {
          font-size: 14px;
          line-height: 12px;
        }
      }
    }
  }
}
</style>

