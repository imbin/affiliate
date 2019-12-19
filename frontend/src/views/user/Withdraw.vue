<template>
  <div>
    <h1>余额提现</h1>
    <el-row style="width:600px;">
    <el-form ref="form" :model="form" :rules="rules" label-width="100px">
      <el-form-item label="总余额">
        <label>{{balance}} <span v-if="frozen > '0.00'">（含￥{{frozen}}元冻结中）</span></label>
      </el-form-item>
      <el-form-item label="可提余额">
        <label>{{available}} (大于￥100元就可以提现）</label>
      </el-form-item>
      <el-form-item label="提现方式" prop="way">
        <el-radio-group v-model="form.way">
          <el-radio label="1">银行卡</el-radio>
          <el-radio label="2">支付宝</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="账号/卡号" prop="card">
        <el-input v-model="form.card"></el-input>
      </el-form-item>
      <el-form-item label="姓名" prop="name">
        <el-input v-model="form.name"></el-input>
      </el-form-item>
      <el-form-item label="提现金额" prop="amount">
        <el-input v-model="form.amount"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="handlerWithdraw()">提交申请</el-button>
      </el-form-item>
    </el-form>
    </el-row>
  </div>
</template>
<style scoped>
</style>
<script>
export default {
  name: "userWithdraw",
  data() {
    var validateAmount = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请再次输入密码'));
        } else if (Number.parseFloat(value) < '100.00') {
          callback(new Error('提现金额需要最小100元!'));
        } else {
          callback();
        }
      };
    return {
      balance:"-",
      available:"-",
      frozen:"-",
      form: {
        way:"",
        card:"",
        name:"",
        amount:""
      },
      rules: {
        way:[{required: true, message: '请选择提现方式', trigger:"blur"}],
        card:[{required: true, message: '请输入账号或卡号', trigger:"blur"}],
        name:[{required: true, message: '请输入姓名', trigger:"blur"}],
        amount:[
          { required: true, message: '请输入提现金额', trigger:"blur"},
          { validator: validateAmount, trigger:"blur"},
          ],
      }
    };
  },
  mounted() {
    this.getList()
  },
  methods: {
    getList:function(){
      let _this = this;
      _this.$store.dispatch('Loading', true);
      _this.$fetch('/user/balance').then(function(resp) {
        _this.$store.dispatch('Loading', false);
        if (resp.code == 0) {
          _this.balance = resp.data.balance
          _this.available = resp.data.available
          _this.frozen = resp.data.frozen
        } else {
          _this.$message({
            message: resp.msg,
            type: 'error'
          });
        }
      })
    },
    handlerWithdraw: function() {
      let _this = this
      _this.$refs['form'].validate((valid) => {
        if (!valid) { 
          _this.$message({
            message: '填写有错误，请检查',
            type: 'error'
          });
          return false;
        }
        _this.$post('/user/withdraws', _this.form).then(function(resp) {
          _this.$store.dispatch('Loading', false);
          if (resp.code == 0) {
            _this.$message({
              message: resp.msg,
              type: 'success'
            });
            _this.$refs['form'].resetFields()
            _this.getList()
          } else {
            _this.$message({
              message: resp.msg,
              type: 'error'
            });
          }
        })
      })
    }
  }
}
</script>
