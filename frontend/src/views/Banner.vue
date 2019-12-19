<template>
<div style="position: relative; padding-top:15px; ">
  <el-row class="clearfix">
    <h1>素材中心</h1>
  </el-row>

  <banner-list :perPage="perPage" :pagination="pagination"></banner-list>

</div>
</template>
<style scoped>
h1 {
  font-weight: 300; margin-left: 15px;
}
.text-center {
  text-align: center;
}
.join {
    padding-top: 4rem;
    padding-bottom: 4rem;
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
  .time {
    font-size: 13px;
    color: #999;
  }
  
  .bottom {
    margin-top: 13px;
    margin-bottom: 5px;
    line-height: 12px;
  }

  .button {
    padding: 0;
    float: right;
  }

  .image {
    max-width: 100%;
    display: block;
  }

</style>
<script>

import BannerList from '../components/BannerList'

  export default {
    name: "ad",
    data() {
      return {
        pagination: true,
        perPage: 12,
      }
    },
    components: {
      BannerList
    },
    mounted(){
    },
    methods: {
    }
  }
</script>
