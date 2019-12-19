<template>
  <div>
  <el-row class="clearfix">
    <el-col :span="6" v-for="(banner, key) in bannerList" :key="key" :offset="0" style=" margin-top: 20px; padding-right: 15px;
    padding-left: 15px;">
      <el-card style=" margin-bottom: 15px !important;">
        <center>
          <img height="90" :src="banner.thumb_url" class="image" />
        </center>
        <div class="text-box">
          <div class="title">{{banner.title}}</div>
          <div class="bottom clearfix">
            <div class="price ">￥{{banner.price}}</div>
            <div class="price " style="text-align:right;">返佣:{{banner.return_text}}</div>
          </div>
          <center>
            <el-button type="primary" plain @click="openDialog(banner)">立即推广</el-button>
          </center>
        </div>
      </el-card>
    </el-col>
  </el-row>
  <el-row v-if="pagination" classs="clearfix" style="margin-top:15px; float:right;" >
    <el-pagination
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
      :current-page="currentPage"
      :page-sizes="[12, 16, 32]"
      :page-size="perPage"
      background
      layout="total, sizes, prev, pager, next, jumper"
      :total="totalRows">
    </el-pagination>
  </el-row>
  </div>
</template>

<style scoped>
.title {
  height: 45px;/* 3row x 15px */
  line-height: 15px;
  overflow : hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 3;/*规定几行显示省略号*/
  -webkit-box-orient: vertical;
}
.text-box {
  padding: 14px;
}
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
  .price {
    font-size: 14px;
    color: #FF7300;
    float: left;
    width: 50%;
  }
  
  .bottom {
    margin-top: 10px;
    margin-bottom: 10px;
    line-height: 16px;
  }

  .image {
    max-width: 100%;
    display: block;
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

  export default {
    name: "home",
    props: ["pagination", 'perPage'],
    data() {
      return {
        bannerList: [],
        currentPage:1,
        totalRows:0,
        search:"",
      }
    },
    mounted(){
      if (this.$route.query.search) {
        this.search = this.$route.query.search
      } else {
        this.search = ''
      }
      this.getList()
    },
    methods : {

       handleSizeChange:function(size){
        this.perPage = size
        this.getList()
      },
      handleCurrentChange:function(page){
        if (this.currentPage == page) {
          return
        }
        this.currentPage = page;
        this.getList()
      },
      getList() {
        let _this = this;
        _this.$store.dispatch('Loading', true);
        _this.$fetch('banners',{"page": this.currentPage,"perPage": this.perPage, 'title': this.search}).then(function(resp) {
          _this.$store.dispatch('Loading', false);
          if (resp.code == 0) {
            _this.bannerList = resp.data.list
            _this.totalRows = resp.data.total
          } else {
            _this.$message({
              message: resp.msg,
              type: 'error'
            });
          }
        });
      },
      openDialog(banner) {
        let isLogined = this.$store.state.userLogined;
        if (! isLogined) {
          this.$confirm('此操作需要登录才可以继续', '提示', {
            confirmButtonText: '登录',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            this.$router.push({ path: '/login', query: { redirect: this.$router.currentRoute.fullPath } })
          }).catch(() => {
            //
          });
          return;
        }
        const e = this.$createElement;
        let uid = window.localStorage.getItem('userId');
        if (uid == undefined) {
            this.$router.push({ path: '/login', query: { redirect: this.$router.currentRoute.fullPath } })
            return
        }
        this.$msgbox({
          title: '推广',
          message: e('div', null, [
            e('textarea', {"style":"width:100%"}, banner.redirect_url + (banner.redirect_url.indexOf('&') == -1 ? '?' : '&') + 'from_uid=' + uid),
            e('p', {}, '立刻复制链接推广吧')
          ]),
          showCancelButton: true,
          confirmButtonText: '关闭',
          cancelButtonText: '取消',
          beforeClose: (action, instance, done) => {
            if (action === 'confirm') {
              instance.confirmButtonLoading = true;
              instance.confirmButtonText = '执行中...';
              done();
              setTimeout(() => {
                  instance.confirmButtonLoading = false;
                }, 300);
            } else {
              done();
            }
          }
        });
      }
    }
  }
</script>