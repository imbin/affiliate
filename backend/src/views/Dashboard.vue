<template>
<div>
  
  <p>你好，admin, 现在是{{currentDate}}</p>
  <el-row>
  <el-col :span="8" :offset="2">
    <el-card class="box-card">
      <p>昨日新增注册联盟客：{{users.yesterday}}个</p>
      <p>今日新增注册联盟客：{{users.today}}个</p>
      <p>本月新增注册联盟客：{{users.month}}个</p>
      <p>截止昨日联盟客总数：{{users.total}}个</p>
    </el-card>
  </el-col>
  <el-col :span="8" :offset="2">
    <el-card class="box-card">
      <p>昨日新增有效订单：{{orders.yesterday}}单</p>
      <p>本月订单销售：{{orders.monthSale}}元</p>
      <p>本月提现佣金：{{orders.monthCommission}}元</p>
      <p>审核中的提现单：{{orders.withdrawPending}}单</p>
    </el-card>
  </el-col>
  </el-row>

</div>
</template>
<style scoped>

</style>
<script>

  export default {
    name: "Dashboard",
    data() {
      return {
        currentDate: new Date().toLocaleString(),
        users:{
          "yesterday":0,
          "total":0,
          "today":0,
          "month":0,
        },
        orders:{
          "yesterday":0,
          "monthSale":0,
          "monthCommission":0,
          "withdrawPending":0,
        }
      }
    },
    mounted(){
      var _this = this;
      _this.$store.dispatch('Loading', true);
      _this.$fetch('/dashboard').then(function(resp) {
        _this.$store.dispatch('Loading', false);
        if (resp.code == 0) {
          _this.users = resp.data.users;
          _this.orders = resp.data.orders;
        } else {
          _this.$message({
            message: resp.msg,
            type: 'error'
          });
        }
      });
    }
  }
</script>
