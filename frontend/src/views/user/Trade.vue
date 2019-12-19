<template>
  <div>
    <h1>收支明细</h1>
    <el-table
    :data="tableData"
    border
    style="width: 100%">
    <el-table-column
      prop="create_time"
      label="交易日期">
    </el-table-column>
    <el-table-column
      prop="typeText"
      label="交易类型">
    </el-table-column>
    <el-table-column
      prop="amount"
      label="交易金额">
    </el-table-column>
    <el-table-column
      prop="remark"
      label="来源">
    </el-table-column>
    <el-table-column
      prop="business_sn"
      label="相关单号">
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
    name: "userTrade",
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
        _this.$fetch('/user/trades',{"page": this.currentPage,"perPage": this.perPage}).then(function(resp) {
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
