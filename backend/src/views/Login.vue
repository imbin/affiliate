<template>
  <div class="login" >
    <el-row style="margin-top: 100px;z-index: 1;">
      <el-col   :offset="10" :span="4" >
        <el-card class="login-box"  element-loading-background="rgba(0, 0, 0, 0.8)" v-loading="$store.state.loading">
          <el-form  :model="user" ref="user"   :rules="rules">
            <h1 class="title">后台登录</h1>
            <el-form-item prop="userName">
              <el-input type="text" v-model="user.userName" icon="fa-user-o" @keyup.enter.native="login" auto-complete="off" placeholder="请输入账户名" suffix-icon="fa fa-user"></el-input>
            </el-form-item>
            <el-form-item prop="password">
              <el-input type="password" v-model="user.password" icon="fa-lock" @keyup.enter.native="login" auto-complete="off" placeholder="请输入密码" suffix-icon="fa fa-lock"></el-input>
            </el-form-item>
            <el-form-item style="width:100%;">
              <el-button type="primary" style="width:100%" @click="login" :loading="$store.state.loading" >登录</el-button>
            </el-form-item>
          </el-form>
        </el-card>
      </el-col>
    </el-row>
    <el-row>
      <center>
        <br/>
        <el-tag>为避免产生可能的不兼容，推荐使用 Google Chrome 浏览器，双核浏览器请切换到 Chrome 核心</el-tag>
      </center>
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
        logining: false,
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
    mounted(){
      let token = window.localStorage.getItem("token");
      if (token) {
        this.$router.push('/dashboard'); //跳转用户中心页
      }
    },
    methods: {
       //登录
      login() {
        let _this = this;
        
        _this.$refs['user'].validate((valid) => {
          if (valid) {
            _this.$store.dispatch('Loading', true);
            _this.$post('/login',_this.user).then(res => {
              _this.$store.dispatch('Loading', false);
              if(res.code != 0) {
                _this.$message({
                  message: res.msg,
                  type:'error'
                });
              } else {
                window.localStorage.setItem("token", res.data.token);
                window.localStorage.setItem("userName", _this.user.userName);
                window.localStorage.setItem("realName", res.data.realName);
                if(_this.$route.query.redirect) {
                  _this.$router.push(_this.$route.query.redirect);
                } else {
                  _this.$router.push('/dashboard'); //跳转用户中心页
                }
              }
            });
          }else {
            return false;
          }
        });
        
      }
    }
  }
</script>
