<template>
  <!-- 技术大师审核页面 -->
  <div class="audit">
    <div class="info">
      <p>姓名&nbsp;&nbsp;王建国</p>
      <p>性别&nbsp;&nbsp;男</p>
      <p>身份证号&nbsp;&nbsp;402626198906110573</p>
      <p>企业名称&nbsp;&nbsp;这里是企业的名称呀</p>
      <p>营业执照注册号&nbsp;&nbsp; 123456789</p>
    </div>
    <div class="photo">
      <h6>证件照</h6>
      <ul>
        <li>
          <p>在职证明</p>
          <div>
            <img src alt />
          </div>
        </li>
        <li>
          <p>营业执照</p>
          <div>
            <img src alt />
          </div>
        </li>
        <li>
          <p>技师证件(正面照)</p>
          <div>
            <img src alt />
          </div>
        </li>
        <li>
          <p>技师证件(反面照)</p>
          <div>
            <img src alt />
          </div>
        </li>
      </ul>
    </div>
    <div class="check">
      <h6>审核</h6>
      <p>审核状态</p>
       <div class="button">
        <el-radio v-model="radio" label="1">备选项</el-radio>
  <el-radio v-model="radio" label="2">备选项</el-radio>
      </div>
      <label for="one"></label>
      <textarea name="" id="one" cols="30" rows="10" placeholder="请输入" style="width:1300px"></textarea>
    </div>
  </div>
</template>

<script>
export default {
  data () {
      return {
         //备选按钮的选中状态
        radio: '1'
      };
    }
};
</script>


<style lang="less" scoped>
.content{
.audit {
  color: #000;
  padding-top: 30px;
  background-color: rgba(255, 255, 255, 1);
  .info {
    p {
      line-height: 35px;
    }
  }
  .photo {
    margin-top: 30px;
    padding: 20px 0;
    height: 250px;
    box-sizing: border-box;
    h6 {
      font-size: 20px;
      font-weight: normal;
      margin: 8px 8px;
      box-sizing: border-box;
    }
    ul {
      clear: both;
      height: 100%;
      border-bottom: 1px solid #000;
      box-sizing: border-box;
      li {
        list-style: none;
        float: left;
        margin: 0 10px;
        box-sizing: border-box;
        p {
          margin: 8px 0;
        }
        div {
          width: 200px;
          height: 120px;
          background-color: #fff;
        }
      }
    }
  }
  .check{
      padding: 18px 0;
       h6 {
      font-size: 20px;
      font-weight: normal;
      margin: 20px 8px;
      box-sizing: border-box;
    }
  }
}
}

</style>

