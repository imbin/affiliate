<template>
<div>
  <!-- <el-row style="position: relative; clear: both;">

    <el-carousel :interval="4000" type="card" height="200px" style="">
      <el-carousel-item v-for="item in 6" :key="item">
        <img height="200" src="https://shadow.elemecdn.com/app/element/hamburger.9cf7b091-55e9-11e9-a976-7f4d0b07eef6.png" class="image">
      </el-carousel-item>
    </el-carousel>
  </el-row> -->
  <section class="join text-center clearfix">
    <div class="container">
      <h1 class="join-heading">加入联盟</h1>
      <p class="lead text-muted">开启网络赚佣金时代，就是现在! 已经累计为10000位联盟客，发放了超过￥1000万元佣金！</p>
      <p>
        <el-button type="primary" v-if="$store.state.userLogined === false" @click="$router.push('/login')">会员登录</el-button>
        <el-button type="success" v-if="$store.state.userLogined === false" @click="$router.push('/reg')">立即注册</el-button>
        <el-button type="primary" v-if="$store.state.userLogined === true" @click="$router.push('/user/profile')">会员中心</el-button>
      </p>
    </div>
  </section>

  <banner-list :perPage="perPage" :pagination="pagination"></banner-list>

</div>
</template>
<style scoped>
.text-center {
  text-align: center;
}
.join {
    padding-top: 3rem;
    padding-bottom: 3rem;
}
.join .container {
    max-width: 40rem;
    margin:0 auto;
}.join-heading {
    font-weight: 300;
    font-size: 32px;
}.text-muted {
    color: #6c757d !important;
}

.lead {
    font-size: 1.25rem;
    font-weight: 300;
}

  .clearfix:before,
  .clearfix:after {
      display: table;
      content: "";
  }
  
  .clearfix:after {
      clear: both
  }
</style>
<script>
import BannerList from '../components/BannerList'

  export default {
    name: "home",
    data() {
      return {
        pagination:false,
        perPage: 8,
      }
    },
    components: {
      BannerList
    },
    mounted(){
    },
    methods : {
    }
  }
</script>
