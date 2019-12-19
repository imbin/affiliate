<template>
  <div>
    <el-row style="margin-top: 20px;z-index: 1;">
      <el-col :offset="5" :span="10" >
          <el-form status-icon :model="user" label-width="200px" ref="login"  :rules="rules" v-loading="$store.state.loading">
            <h1>会员登录</h1>
            <el-form-item label="登录名/Email/手机号" prop="userName">
              <el-input type="text" v-model="user.userName" icon="fa-user-o" @keyup.enter.native="login" auto-complete="off" placeholder="请输入账户名" suffix-icon="fa fa-user"></el-input>
            </el-form-item>
            <el-form-item label="密码" prop="password">
              <el-input type="password" v-model="user.password" icon="fa-lock" @keyup.enter.native="login" auto-complete="off" placeholder="请输入密码" suffix-icon="fa fa-lock"></el-input>
            </el-form-item>
            <el-form-item style="width:100%;">
              <el-button type="primary" style="width:100%" @click="login" :loading="$store.state.loading" >登录</el-button>
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
    name: "Login",
    data() {
      return {
        loading: false,
        user:{
          userName: '',
          password: '',
        },
        rules: {
          userName: [{required: true, message: '请输入账户名'}],
          password: [{required: true, message: '请输入密码'}]
        }
      }
    },
    mounted() {
      this.$store.dispatch('Loading', false);
    },
    methods: {
       //登录
      login() {
        var _this = this;
        _this.$refs['login'].validate((valid) => {
          if (!valid) { return false; }
          _this.$store.dispatch('Loading', true);
          _this.$post('/login', _this.user).then(res => {
            _this.$store.dispatch('Loading', false);
            if(res.code != 0) {
              _this.$message({
                message: res.msg,
                type:'error'
              });
            } else {
              window.localStorage.setItem("token", res.data.token);
              window.localStorage.setItem("userName", _this.user.userName);
              window.localStorage.setItem("userId", res.data.uid);
              _this.$store.dispatch('Logined', {logined:true, userName:_this.user.userName});
              if(_this.$route.query.redirect) {
                _this.$router.push(_this.$route.query.redirect);
              } else {
                _this.$router.push('/user/profile'); //跳转用户中心页
              }
            }
          });
        });
      }
    }
  }
</script>
