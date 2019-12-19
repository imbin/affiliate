<template>
  <div>
    <el-row style="margin-top: 20px;z-index: 1;">
      <el-col :offset="7" :span="10" >
          <el-form status-icon :model="user" label-width="100px" ref="registerForm" :rules="rules" v-loading="$store.state.loading">
            <h1>会员注册</h1>
            <el-form-item label="账户名" prop="userName">
              <el-input type="text" v-model="user.userName" icon="fa-user-o" @keyup.enter.native="register" auto-complete="off" placeholder="请输入账户名" suffix-icon="fa fa-user"></el-input>
            </el-form-item>
            <el-form-item label="登录密码" prop="password">
              <el-input type="password" v-model="user.password" icon="fa-lock" @keyup.enter.native="register" auto-complete="off" placeholder="请输入登录密码" suffix-icon="fa fa-lock"></el-input>
            </el-form-item>
            <el-form-item label="确认密码" prop="passwordTwice">
              <el-input type="password" v-model="user.passwordTwice" icon="fa-lock" @keyup.enter.native="register" auto-complete="off" placeholder="请确认登录密码" suffix-icon="fa fa-lock"></el-input>
            </el-form-item>
            <el-form-item style="width:100%;">
              <el-button type="primary"  @click="register" :loading="$store.state.loading" >提交注册</el-button>
              <el-button @click="resetForm" >重置</el-button>
            </el-form-item>
          </el-form>
      </el-col>
    </el-row>
    
  </div>
</template>
<style scoped>

</style>
<script>
  export default {
    name: "register",
    data() {
      return {
        loading: false,
        user:{
          userName: '',
          password: '',
          passwordTwice:'',
        },
        rules: {
          userName: [{required: true, message: '请输入账户名'}],
          passwordTwice: [{required: true, message: '请确认登录密码'}],
          password: [{required: true, message: '请输入登录密码'}]
        }
      }
    },
    mounted() {
      this.$store.dispatch('Loading', false);
    },
    methods: {
      resetForm() {
        this.user.userName = '';
        this.user.password = '';
        this.user.passwordTwice = '';
      },
       //注册
      register() {
        var _this = this;
        
        _this.$refs['registerForm'].validate((valid) => {
          if (valid) {
            _this.$store.dispatch('Loading', true);
            _this.$post('/register', _this.user).then(res => {
              _this.$store.dispatch('Loading', false);
              if(res.code != 0) {
                _this.$message({
                  message: res.msg,
                  type:'error'
                });
              } else {
                _this.$message({
                  message: '恭喜你注册成功了!',
                  type:'success'
                });
                _this.$router.push('/login'); //跳转登录页
              }
            });
          } else {
            return false;
          }
        });
      }
    }
  }
</script>
