<template>
  <div>
    <h1>订单列表</h1>

  <el-form :inline="true" :model="formSearch">
    <el-form-item label="订单号">
      <el-input v-model="formSearch.order_sn" placeholder="订单号"></el-input>
    </el-form-item>
    <el-form-item label="订单状态">
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
      <el-button type="success" @click="createRow"  icon="el-icon-edit">录入订单</el-button>
    </el-form-item>
  </el-form>

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
      prop="create_time"
      label="上传日期">
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
        <el-button type="text" size="small" v-if="scope.row.commission_status == 1" @click="editRowHandler(scope.row)">编辑</el-button>
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
  :title="drawerTitle"
  :visible.sync="showEditDrawer"
  :direction="direction">
  <div class="drawer_form">
    <el-form
        :model="editRow"  size="small"
        ref="editRow"
        label-width="120px"
      >
        <el-form-item label="订单号" prop="order_sn">
          <span>{{editRow.order_sn}}</span>
        </el-form-item>
        <el-form-item label="用户" prop="user_id">
          <span>{{editRow.userInfo ? editRow.userInfo.user_name : ''}}</span>
        </el-form-item>
        <el-form-item label="订单金额" prop="order_amount">
          <span>￥{{editRow.order_amount}}</span>
        </el-form-item>
        <el-form-item label="订单佣金" prop="commission">
          <span>￥{{editRow.commission}}</span>
        </el-form-item>
        <el-form-item label="订单状态" prop="order_status">
          <input type="radio" id="order_status_1" value="1" v-model="editRow.order_status">
          <label for="order_status_1">未付款</label><span style="padding-left:5px;"></span>
          <input type="radio" id="order_status_2" value="2" v-model="editRow.order_status">
          <label for="order_status_2">已取消</label><span style="padding-left:5px;"></span>
          <input type="radio" id="order_status_3" value="3" v-model="editRow.order_status">
          <label for="order_status_3">已付款</label><span style="padding-left:5px;"></span>
          <input type="radio" id="order_status_4" value="4" v-model="editRow.order_status">
          <label for="order_status_4">已发货</label><span style="padding-left:5px;"></span>
        </el-form-item>
        <el-form-item label="下单日期" prop="order_time">
          <el-date-picker value-format="yyyy-MM-dd HH:mm:ss"
            v-model="editRow.order_time"
            type="datetime"
            placeholder="选择日期时间">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="付款日期" prop="pay_time">
          <el-date-picker value-format="yyyy-MM-dd HH:mm:ss"
            v-model="editRow.pay_time"
            type="datetime"
            placeholder="选择日期时间">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="发货日期" prop="deliver_time">
          <el-date-picker value-format="yyyy-MM-dd HH:mm:ss"
            v-model="editRow.deliver_time"
            type="datetime"
            placeholder="选择日期时间">
          </el-date-picker>
        </el-form-item>
        <div v-for="(goods,index) in editRow.goods_list" :key="index">
          <el-form-item :label="'商品' + (index + 1)">
            <span>SKU:{{goods.sku}} 数量:{{goods.sku_quantity}} 单价:{{goods.sku_price}}</span>
          </el-form-item>
        </div>
        <el-form-item>
          <el-button type="primary" @click="postEditHandler">提交保存</el-button>
          <el-button @click="resetEditRow">取消</el-button>
        </el-form-item>
      </el-form>
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
        currentPage:1,
        perPage:10,
        totalRows:0,
        showEditDrawer:false,//编辑界面
        editRow: {
          order_sn: "",
          user_id: "",
          order_status: "",
          commission: "",
          order_amount: "",
          order_time: "",
          pay_time: "",
          deliver_time: "",
          userInfo: null,
          goods_list: [{
            sku:"", sku_quantity:"", sku_price:""
          }]
        },
        tableData: [],
        statusOptions: [
          {label:"未付款", value:"1"},
          {label:"已取消", value:"2"},
          {label:"已付款", value:"3"},
          {label:"已发货", value:"4"},
        ],
        formSearch:{
          status:"", order_sn:""
        },
        drawerTitle:"",
        uploadImageUrl: this.$apiHost() +'/upload/img'
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
        Object.assign(params, {"status":[this.formSearch.status], "order_sn":this.formSearch.order_sn})
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
      resetEditRow:function(row){
        if (row === null) {
          this.editRow = {
            order_sn: "",
            user_id: "",
            order_status: "",
            commission: "",
            order_amount: "",
            order_time: "",
            pay_time: "",
            deliver_time: "",
          userInfo: null,
            goods_list: [
              {sku:"", sku_price:"", sku_quantity:""}
            ],
          };
          this.showEditDrawer = false
          // this.$set(this.editRow, 'status', "")
        } else {
          this.editRow = {
            id: row.id,
            order_sn: row.order_sn,
            user_id: row.user_id,
            order_status: row.order_status,
            commission: row.commission,
            order_amount: row.order_amount,
            order_time: row.order_time,
            pay_time: row.pay_time,
            deliver_time: row.deliver_time,
            userInfo: row.userInfo,
            goods_list: row.goods_list,
          };
          // this.$set(this.editRow, 'status', row.status)
        }
      },
      createRow:function(){
        this.$router.push('/order/add');
        // this.resetEditRow(null)
        // this.drawerTitle = '添加';
        // this.showEditDrawer = true;
      },
      editRowHandler:function(row) {
        this.resetEditRow(row)
        this.drawerTitle = '编辑';
        this.showEditDrawer = true;
      },
      postEditHandler:function(){
        this.$store.dispatch('Loading', true);
        let _this = this;
        _this.$post('orders'+(this.editRow.id > 0 ? '/'+this.editRow.id : ''), this.editRow).then(res => {
          _this.$store.dispatch('Loading', false);
          if (res.code != 0) {
            _this.$message({
                message: res.msg,
                type:'error'
              })
              return
          }
          _this.showEditDrawer = false;
            this.$message({
              message: _this.drawerTitle + '成功',
              type: 'success'
            });
          _this.getList()
        })
      }
    }
  }
</script>
