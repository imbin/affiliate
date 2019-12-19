<template>
  <div id="app" style="position:relative;">
    <el-container style="height: 100%">
      <el-header>
        <div class="logo">
          <h1>后台管理中心</h1>
        </div>
        <div class="header-nav">
          <el-menu mode="horizontal" background-color="#1976D2" text-color="#ffffff" active-text-color="#ffffff" :router="true">
            <el-submenu index="my">
              <template slot="title"><img src="../assets/avatar.png" style="width:30px;"> {{user.userName}}</template>
              <el-menu-item index="/admin/profile"><i class="fa fa-fw fa-cog"></i> 个人设置</el-menu-item>
              <el-menu-item index=""  @click="loginOut"><i class="fa fa-fw fa-sign-out"></i> 安全退出</el-menu-item>
            </el-submenu>
          </el-menu>
        </div>
      </el-header>
      <el-container>
        <el-aside width="240px">
          <el-menu :unique-opened="true" default-active="dashboard" :router="true">
            <el-menu-item index="/dashboard">
              <i class="el-icon-s-home"></i>
              <span slot="title">后台首页</span>
            </el-menu-item>
            <el-menu-item index="/banner">
              <i class="el-icon-picture"></i>
              <span>素材列表</span>
            </el-menu-item>
            <el-submenu index="#user">
              <template slot="title"><i class="el-icon-user-solid"></i>
                <span slot="title">联盟客管理</span></template>
              <el-menu-item index="/user">
                    <i class="el-icon-user-solid"></i>
                <span slot="title">联盟客列表</span>
              </el-menu-item>
              <el-menu-item index="/user/trade">
                    <i class="el-icon-money"></i>
                <span slot="title">收支明细</span>
              </el-menu-item>
            </el-submenu>
            <el-submenu index="#order">
              <template slot="title"><i class="el-icon-shopping-cart-full"></i>
                <span slot="title">订单管理</span></template>
              <el-menu-item index="/order">
                    <i class="el-icon-shopping-cart-full"></i>
                <span slot="title">订单列表</span>
              </el-menu-item>
              <el-menu-item index="/order/add">
                    <i class="el-icon-edit"></i>
                <span slot="title">录入订单</span>
              </el-menu-item>
              <el-menu-item index="/order/grant">
                    <i class="el-icon-money"></i>
                <span slot="title">发放佣金</span>
              </el-menu-item>
            </el-submenu>
            <el-menu-item index="/withdraw">
                  <i class="el-icon-s-finance"></i>
              <span slot="title">提现列表</span>
            </el-menu-item>
            <el-submenu index="admin">
              <template slot="title">
                <i class="el-icon-setting"></i>
                <span>管理员</span>
              </template>
              <el-menu-item index="/admin">
                <i class="el-icon-setting"></i>
                <span>管理员列表</span>
              </el-menu-item>
              <el-menu-item index="/admin/profile">
                <i class="el-icon-user"></i>
                <span>个人设置</span>
              </el-menu-item>
            </el-submenu>
          </el-menu>
        </el-aside>

          <el-main>
            <router-view></router-view>
          </el-main>
      </el-container>
    </el-container>
  </div>
</template>
<style scoped>
.logo h1 {
  color: white; padding-left: 15px;
}
.el-menu.el-menu--horizontal {
  border:none;
}.el-menu--horizontal>.el-menu-item.is-active {
  border:none;
}
header h1 {
  font-family: Helvetica Neue,Helvetica,PingFang SC,Hiragino Sans GB,Microsoft YaHei,SimSun,sans-serif;
  -webkit-font-smoothing: antialiased;
  -webkit-tap-highlight-color: transparent;
  color: #333;
  line-height: 80px;
  margin: 0;
  float: left;
  font-size: 32px;
  font-weight: 400;
}
.nav {
  font-family: Helvetica Neue,Helvetica,PingFang SC,Hiragino Sans GB,Microsoft YaHei,SimSun,sans-serif;
  font-weight: 400;
  -webkit-font-smoothing: antialiased;
  -webkit-tap-highlight-color: transparent;
  color: #fff;
  float: right;
  height: 100%;
  line-height: 80px;
  background: transparent;
  padding: 0;
  margin: 0;
}
.el-menu-item {
    border: none;
}
.nav-item.nav-algolia-search {
  font-family: Helvetica Neue,Helvetica,PingFang SC,Hiragino Sans GB,Microsoft YaHei,SimSun,sans-serif;
  font-weight: 400;
  -webkit-font-smoothing: antialiased;
  -webkit-tap-highlight-color: transparent;
  color: #fff;
  line-height: 80px;
  margin: 0;
  float: left;
  list-style: none;
  position: relative;
  cursor: default;
}
.header .nav-item.lang-item, .header .nav-item:last-child {
    cursor: default;
    margin-left: 34px;
}
</style>
<script>
  export default {
    name: "App",

    data() {
      return {
        input: '',
        activeIndex:'',
        user:{
          'userName':''
        }
      };
    },
    //初始化
    mounted(){
      var _this = this;
      var userName =  window.localStorage.getItem('userName');
      _this.user.userName = userName;
    },
    methods : {
      loginOut() {
        window.localStorage.removeItem("token");
        window.localStorage.removeItem("userName");
        window.localStorage.removeItem("user");
        this.$router.push("/");

      }
    }
  }
</script>
