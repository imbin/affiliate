<template>
  <div id="app" style="position:relative;">
    <el-container>
      <el-row style="margin:0 auto; width: 1200px; ">
        <el-header class="clearfix">
          <h1>广告联盟</h1>
          <Nav></Nav>
        </el-header>

        <el-row class="clearfix subtitle" style="position:relative; height:64px; padding-top:15px;">
          <el-row class="clearfix">
            <h1>会员中心</h1>
          </el-row>
        </el-row>
        <el-container class="clearfix">
          <el-aside width="200px">
              <el-menu :default-active="activeIndexUser" @select="handleSelect" mode="" :router="true">
                <el-menu-item index="/user/order">
                  <template slot="title">
                    <i class="el-icon-s-order"></i>
                    <span>订单列表</span>
                  </template>
                </el-menu-item>
                <el-menu-item index="/user/trade">
                  <template slot="title">
                    <i class="el-icon-tickets"></i>
                    <span>收支明细</span>
                  </template>
                </el-menu-item>
                <el-menu-item index="/user/withdraw">
                  <template slot="title">
                    <i class="el-icon-money"></i>
                    <span>余额提现</span>
                  </template>
                </el-menu-item>
                <el-menu-item index="/user/withdraw/list">
                  <template slot="title">
                    <i class="el-icon-money"></i>
                    <span>提现列表</span>
                  </template>
                </el-menu-item>
                <el-menu-item index="/user/profile">
                  <template slot="title">
                    <i class="el-icon-user"></i>
                    <span>个人资料</span>
                  </template>
                </el-menu-item>
            </el-menu>
          </el-aside>
          <el-main>
              <router-view></router-view>
          </el-main>
        </el-container>
      </el-row>
      <el-footer style="background-color:#EEE; bottom:0; left:0; height:70px;  margin-top:15px; width: 100%;">
        <center style="padding-top:15px;">
          Copyright &copy; 2019 abc123.com All rights reserved.
        </center>
      </el-footer>
    </el-container>
  </div>
</template>
<style scoped>
.subtitle h1 {
  position: absolute; left: 0; top:0; height: 30px; margin-left: 15px;
}

.el-header {
  background: transparent;
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
.el-menu-item {
  border: none;
}
.el-menu.el-menu--horizontal {
  border:none;
}
.el-menu--horizontal>.el-menu-item.is-active {
  border-bottom: 2px solid #409EFF;
}
</style>
<script>
import Nav from '../components/Nav'

  export default {
    name: "userLayout",
    components: {
      Nav
    },
    data(){
      return {
        activeIndexUser:''
      }
    },methods: {
      handleSelect(key) {
        this.activeIndexUser = key
      }
    }
  }
</script>
