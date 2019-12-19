<template>
  <router-view id="app"></router-view>
</template>

<script>
  export default {
    name: 'app'
  }
</script>
<style>
body {
    margin:0; padding: 0;
}
</style>
<script>
export default {  
    name: "App",

    //初始化
    mounted() {
      var userName =  window.localStorage.getItem('userName');
      var token =  window.localStorage.getItem('token');
      if (userName && token) {
        this.$store.dispatch('Logined', {logined:true, userName: userName});
      }
    },
}
</script>