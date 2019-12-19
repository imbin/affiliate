<template>
  <div>
    <h1>收支明细</h1>
    <el-form :inline="true" :model="formSearch">
  <el-form-item label="提现编号">
    <el-input v-model="formSearch.sn" placeholder="提现编号"></el-input>
  </el-form-item>
  <el-form-item label="交易账号ID">
    <el-input v-model="formSearch.user_id" placeholder="交易账号ID"></el-input>
  </el-form-item>
  <el-form-item>
    <el-button type="primary" @click="getList">查询</el-button>
  </el-form-item>
</el-form>

    <el-table
    :data="tableData"
    border v-loading="$store.state.loading"
    style="width: 100%">
    <el-table-column
      prop="business_sn"
      label="相关编号"
      width="180">
    </el-table-column>
    <el-table-column
      prop="create_time"
      label="交易日期"
      width="180">
    </el-table-column>
    <el-table-column
      prop="typeText"
      label="交易类型">
    </el-table-column>
    <el-table-column
      prop="user_id"
      label="交易账号ID"
      width="180">
    </el-table-column>
    <el-table-column
      prop="user_name"
      label="交易账号"
      width="180">
    </el-table-column>
    <el-table-column
      prop="amount"
      label="交易金额（元/￥）"
      width="180">
    </el-table-column>
    <el-table-column
      prop="remark"
      label="备注">
    </el-table-column>
    <el-table-column
      prop="update_time"
      label="最近操作日期">
    </el-table-column>
  </el-table>
  <el-row classs="clearfix" style="margin-top:15px; float:right;" >
    <el-pagination
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
      :current-page="currentPage"
      :page-sizes="[10, 20, 50]"
      :page-size="perPage"
      background
      layout="total, sizes, prev, pager, next, jumper"
      :total="totalRows">
    </el-pagination>
  </el-row>


  </div>
</template>
<style scoped>

.drawer_form {
  margin:0 15px;
}
</style>
<script>
  export default {
    name: "Login",
    data() {
      return {
        direction:"rtl",//抽屉方向:ltr 从左往右,rtl 从右往左,ttb 从上往下,btt 从下往上
        currentUserName:"",
        currentPage:1,
        perPage:10,
        totalRows:0,
        tableData: [],
        showEditDrawer:false,//编辑界面
        showRow:{},
        formSearch:{
          type:"", sn:"", user_id:""
        },
        statusOptions: [
          {label:"收入", value:"1"},
          {label:"支出", value:"2"},
        ]
      }
    },
    mounted() {
      if (this.$route.query.id) {
        this.formSearch.user_id = this.$route.query.id
      } else {
        this.formSearch.user_id = ''
      }
      this.getList()
    },
    methods: {
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
      getList:function(){
        this.$store.dispatch('Loading', true);
        let _this = this;
        let params = {"page":this.currentPage, "perPage":this.perPage}
        Object.assign(params, this.formSearch)
        _this.$fetch('trades', params).then(res => {
          _this.$store.dispatch('Loading', false);
          if (res.code != 0) {
            _this.$message({
                message: res.msg,
                type:'error'
              })
              return
          }
          _this.tableData = res.data.list;
          _this.totalRows = res.data.totalRows;
        })
      },
    }
  }
</script>
