<template>
  <div>
    <h1>提现列表</h1>
    <el-form :inline="true" :model="formSearch">
  <el-form-item label="提现编号">
    <el-input v-model="formSearch.sn" placeholder="提现编号"></el-input>
  </el-form-item>
    <el-form-item label="状态">
      <el-select v-model="formSearch.status" clearable placeholder="不限">
        <el-option
          v-for="item in statusOptions"
          :key="item.value"
          :label="item.label"
          :value="item.value">
        </el-option>
      </el-select>
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
      prop="sn"
      label="提现编号"
      width="200">
    </el-table-column>
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
    <el-table-column
      prop="update_time"
      label="最近操作日期">
    </el-table-column>
    <el-table-column
      fixed="right"
      label="操作"
      width="200">
      <template slot-scope="scope">
        <el-button @click="showRowHandler(scope.row)" type="text" size="small" v-if="scope.row.statusText!='待审核'">查看</el-button>
        <el-button @click="showRowHandler(scope.row)" type="text" size="small" v-if="scope.row.statusText=='待审核'">审核</el-button>
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

  <el-drawer
  title="查看"
  :visible.sync="showEditDrawer"
  :direction="direction">
  <div class="drawer_form">
  <el-form :model="showRow">
      <el-form-item label="提现编号">
        <span>{{showRow.sn}}</span>
      </el-form-item>
      <el-form-item label="提现日期">
        <span>{{showRow.create_time}}</span>
      </el-form-item>
      <el-form-item label="提现方式">
        <span>{{showRow.wayText}}</span>
      </el-form-item>
      <el-form-item label="提现账号">
        <span>{{showRow.card}}</span>
      </el-form-item>
      <el-form-item label="提现金额（元/￥）">
        <span>{{showRow.amount}}</span>
      </el-form-item>
      <el-form-item label="提现状态">
        <span>{{showRow.statusText}}</span>
      </el-form-item>
      <el-form-item label="备注" prop="remark">
        <el-input placeholder="填写备注" v-if="showRow.statusText=='待审核'" v-model="showRow.remark"></el-input>
        <span v-if="showRow.statusText!='待审核'">{{showRow.remark}}</span>
      </el-form-item>
    </el-form>
    <div class="demo-drawer__footer">
      <el-button @click="showEditDrawer = false">返回</el-button>
        <el-button type="primary" v-if="showRow.statusText=='待审核'" @click="statusRowHandler(showRow, 'pass')">审核通过</el-button>
        <el-button type="primary" v-if="showRow.statusText=='待审核'" @click="statusRowHandler(showRow, 'reject')">驳回</el-button>
    </div>
    </div>
  </el-drawer>

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
        editRow: { password: ""},
        formSearch:{
          status:"", sn:""
        },
        statusOptions: [
          {label:"待审核", value:"1"},
          {label:"驳回", value:"2"},
          {label:"已通过", value:"3"},
        ],
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
      //查看收支明细
      financeRow:function (row) {
        this.showRow.id = row.id
      },
      getList:function(){
        this.$store.dispatch('Loading', true);
        let _this = this;
        let params = {"page":this.currentPage, "perPage":this.perPage}
        Object.assign(params, this.formSearch)
        _this.$fetch('withdraws', params).then(res => {
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
      showRowHandler:function(row) {
        this.showRow = row
        this.showEditDrawer = true;
      },
      statusRowHandler:function(row, action) {
          let _this = this
          _this.$store.dispatch('Loading', true);
          _this.$put('withdraws/' + row.id, {"action": action, "remark":this.showRow.remark}).then(res => {
          _this.$store.dispatch('Loading', false);
            if (res.code != 0) {
              _this.$message({
                  message: res.msg,
                  type:'error'
                })
                return
            }
            _this.showEditDrawer = false;
            _this.$message({
              message: '操作成功',
              type: 'success'
            });
            _this.getList()
          })
      },
    }
  }
</script>
