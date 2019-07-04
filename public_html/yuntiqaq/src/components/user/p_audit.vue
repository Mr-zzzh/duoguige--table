<template>
  <!-- 技术大师审核页面 -->
  <div class="audit">
    <div class="info">
      <p>公司名称&nbsp;&nbsp; 武汉物业管理公司</p>
      <p>法人姓名&nbsp;&nbsp; 王建国</p>
      <p>联系电话&nbsp;&nbsp; 13212345678</p>
      <p>公司地址&nbsp;&nbsp; 湖北省武汉市洪山无光谷总部国际</p>
      <p>电梯数量&nbsp;&nbsp; 4部</p>
      <p>电梯品牌&nbsp;&nbsp; 品牌1 &nbsp;&nbsp; 品牌1 &nbsp;&nbsp; 品牌1</p>
    </div>
    <div class="photo">
      <h6>营业执照</h6>
      <div>
        <img src alt />
      </div>
    </div>
    <div class="check">
      <h6>审核</h6>
      <p>审核状态</p>
      <div class="button">
         <el-radio v-model="radio" label="1">备选项</el-radio>
  <el-radio v-model="radio" label="2">备选项</el-radio>
      </div>
      <label for="one"></label>
      <textarea name id="one" cols="30" rows="10" placeholder="请输入"></textarea>
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
.audit {
  color: #000;
  padding-top: 30px;
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
       border-bottom: 1px solid #000;
    h6 {
      font-size: 20px;
      font-weight: normal;
      margin: 8px 8px;
      box-sizing: border-box;
    }
    div {
          width: 200px;
          height: 120px;
          background-color: #fff;
        }
      }
    
  .check {
    padding: 18px 0;
    h6 {
      font-size: 20px;
      font-weight: normal;
      margin: 20px 8px;
      box-sizing: border-box;
    }
  }
}

</style>

