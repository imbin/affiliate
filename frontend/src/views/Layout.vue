<template>
  <div id="app" style="position:relative;">
    <el-container>
      <el-row  style="margin:0 auto; width: 1200px; ">
      <el-header>
        <h1>广告联盟</h1>
        <Nav></Nav>
      </el-header>
      <router-view></router-view>
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
    name: "Layout",
    components: {
      Nav
    }
  }
</script>
