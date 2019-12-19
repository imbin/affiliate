<template>
  <div>
    <h1>发放订单佣金</h1>

  <el-table
    :data="tableData"
    border v-loading="$store.state.loading"
    style="width: 100%">
    <el-table-column
      prop="order_sn"
      label="订单号"
      width="180">
    </el-table-column>
    <el-table-column
      prop="statusText"
      label="订单状态">
    </el-table-column>
    <el-table-column
      prop="commissionStatusText"
      label="佣金状态">
    </el-table-column>
    <el-table-column
      prop="order_time"
      label="下单日期">
    </el-table-column>
    <el-table-column
      prop="pay_time"
      label="付款日期">
    </el-table-column>
    <el-table-column
      prop="deliver_time"
      label="发货日期">
    </el-table-column>
    <el-table-column
      prop="audit_days"
      label="佣金审核周期">
    </el-table-column>
    <el-table-column
      prop="canGrantTime"
      label="可发佣金日期">
    </el-table-column>
    <el-table-column
      prop="create_time"
      label="收录日期">
    </el-table-column>
    <el-table-column
      prop="update_time"
      label="最后更新日期">
    </el-table-column>
    <el-table-column
      fixed="right"
      label="操作"
      width="100">
      <template slot-scope="scope">
        <el-button type="text" size="small" v-if="scope.row.commission_status == 1" @click="grantRowHandler(scope.row)">发放</el-button>
      </template>
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
        currentPage:1,
        perPage:10,
        totalRows:0,
        showEditDrawer:false,//编辑界面
        tableData: [],
        statusOptions: [
          {label:"未付款", value:"1"},
          {label:"已取消", value:"2"},
          {label:"已付款", value:"3"},
          {label:"已发货", value:"4"},
        ],
        formSearch:{
          status: [3,4], order_sn:""
        },
        drawerTitle:"",
      }
    },
    mounted() {
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
        Object.assign(params, {"status":this.formSearch.status, "order_sn":this.formSearch.order_sn})
        _this.$fetch('orders', params).then(res => {
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
      grantRowHandler:function(row){
        this.$store.dispatch('Loading', true);
        let _this = this;
        _this.$put('orders/grant/'+row.id).then(res => {
          _this.$store.dispatch('Loading', false);
          if (res.code != 0) {
            _this.$message({
                message: res.msg,
                type:'error'
              })
              return
          }
          this.$message({
            message: res.msg,
            type: 'success'
          });
          _this.getList()
        })
      }
    }
  }
</script>
