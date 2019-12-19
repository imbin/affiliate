<template>
    <el-menu :default-active="activeIndex" @select="handleSelect" class="nav" mode="horizontal" :router="true">
      <el-menu-item>
        <el-input class="nav-algolia-search" v-model="input" @change="searchBanner" placeholder="搜索素材"></el-input>
      </el-menu-item>
      <el-menu-item index="/">首页</el-menu-item>
      <el-menu-item index="/banner">素材中心</el-menu-item>
      <el-menu-item v-if="$store.state.userLogined">欢迎你: {{userName}},</el-menu-item>
      <el-submenu index="#user" v-if="$store.state.userLogined">
        <template slot="title">会员中心</template>
        <el-menu-item index="/user/order">订单列表</el-menu-item>
        <el-menu-item index="/user/trade">收支明细</el-menu-item>
        <el-menu-item index="/user/withdraw">余额提现</el-menu-item>
        <el-menu-item index="/user/withdraw/list">提现列表</el-menu-item>
        <el-menu-item index="/user/profile">个人资料</el-menu-item>
      </el-submenu>
      <el-menu-item index="/logout" @click="loginOut" v-if="$store.state.userLogined">退出登录</el-menu-item>
      <el-menu-item index="/login" v-if="$store.state.userLogined === false">会员登录</el-menu-item>
      <el-menu-item index="/reg" v-if="$store.state.userLogined === false">立即注册</el-menu-item>
    </el-menu>
</template>
<style scoped>
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
.el-menu.el-menu--horizontal {
  border:none;
}
/* .el-menu--horizontal>.el-menu-item.is-active {
  border:none;
} */
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
.el-menu--horizontal>.el-menu-item.is-active { border:none !important;}
.el-input__inner, .el-input__inner:focus { border:none !important; border-color: white !important; }

.nav-item.nav-algolia-search.is-active {
  border: none;
}
.header .nav-item.lang-item, .header .nav-item:last-child {
    cursor: default;
    margin-left: 34px;
}
</style>
<script>
  export default {
    name: "Nav",
    // props: ['activeIndex'],
    data() {
      return {
        input: '',
        activeIndex:'/',
        userName:'',
      };
    },
    mounted() {
      this.userName =  window.localStorage.getItem('userName');
    },
    methods : {
      loginOut() {
        window.localStorage.removeItem("token");
        this.$store.dispatch('Logined', {logined: false, userName: null});
        this.$router.push({path: '/'});
      },
      searchBanner(e) {
        console.log(e)
        this.$router.push({ path: '/banner', query: { search: this.input } })
      },
      handleSelect(key, keyPath) {
        this.activeIndex = key
        console.log(keyPath)
      }
    }
  }
</script>
