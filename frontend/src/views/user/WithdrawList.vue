<template>
  <div>
    <h1>提现列表</h1>
    <el-table
    :data="tableData"
    border
    style="width: 100%">
    <el-table-column
      prop="create_time"
      label="提现日期"
      width="180">
    </el-table-column>
    <el-table-column
      prop="wayText"
      label="提现方式">
    </el-table-column>
    <el-table-column
      prop="card"
      label="提现账号"
      width="180">
    </el-table-column>
    <el-table-column
      prop="amount"
      label="提现金额（元/￥）"
      width="180">
    </el-table-column>
    <el-table-column
      prop="statusText"
      label="提现状态">
    </el-table-column>
    <el-table-column
      prop="remark"
      label="备注">
    </el-table-column>
  </el-table>
  <el-row classs="clearfix" style="margin-top:15px; float:right;" >
    <el-pagination
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
      :current-page="currentPage"
      :page-sizes="[10, 20, 30, 50]"
      :page-size="perPage"
      background
      layout="total, sizes, prev, pager, next, jumper"
      :total="totalRows">
    </el-pagination>
  </el-row>
  </div>
</template>
<style scoped>

</style>
<script>
  export default {
    name: "userOrder",
    data() {
      return {
        currentPage:1,
        totalRows:0,
        perPage: 10,
        tableData: []
      }
    },
    mounted() {
      this.getList();
    },
    methods: {
      getList:function(){
        let _this = this;
        _this.$store.dispatch('Loading', true);
        _this.$fetch('/user/withdraws',{"page": this.currentPage,"perPage": this.perPage}).then(function(resp) {
          _this.$store.dispatch('Loading', false);
          if (resp.code == 0) {
            _this.tableData = resp.data.list
            _this.totalRows = resp.data.totalRows
          } else {
            _this.$message({
              message: resp.msg,
              type: 'error'
            });
          }
        })
      },
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
    }
  }
</script>
