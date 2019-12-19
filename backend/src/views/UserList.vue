<template>
  <div>
    <h1>联盟客列表</h1>
    <el-form :inline="true" :model="formSearch">
  <el-form-item label="用户ID">
    <el-input v-model="formSearch.id" placeholder="用户ID"></el-input>
  </el-form-item>
  <el-form-item label="登录名">
    <el-input v-model="formSearch.userName" placeholder="登录名"></el-input>
  </el-form-item>
  <el-form-item label="昵称">
    <el-input v-model="formSearch.nickName" placeholder="昵称"></el-input>
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
      prop="id"
      label="ID"
      width="50">
    </el-table-column>
    <el-table-column
      prop="user_name"
      label="账号"
      width="180">
    </el-table-column>
    <el-table-column
      prop="email"
      label="Email">
    </el-table-column>
    <el-table-column
      prop="mobile"
      label="手机号">
    </el-table-column>
    <el-table-column
      prop="create_time"
      label="注册日期">
    </el-table-column>
    <el-table-column
      prop="statusText"
      label="审核状态">
    </el-table-column>
    <el-table-column
      prop="disableText"
      label="是否禁用">
    </el-table-column>
    <el-table-column
      prop="login_count"
      label="登录次数">
    </el-table-column>
    <el-table-column
      prop="last_login_time"
      label="最近登录日期">
    </el-table-column>
    <el-table-column
      fixed="right"
      label="操作"
      width="200">
      <template slot-scope="scope">
        <el-button @click="showRowHandler(scope.row)" type="text" size="small">查看</el-button>
        <el-button @click="financeRow(scope.row)" type="text" size="small">收支</el-button>
        <el-button type="text" size="small" v-if="scope.row.statusText=='待审核'" @click="statusRowHandler(scope.row, 'pass')">审核</el-button>
        <el-button type="text" size="small" v-if="scope.row.statusText=='待审核'" @click="statusRowHandler(scope.row, 'reject')">驳回</el-button>
        <el-button type="text" size="small" v-if="scope.row.is_disabled=='0'" @click="statusRowHandler(scope.row,'disable')">禁用</el-button>
        <el-button type="text" size="small" v-if="scope.row.is_disabled=='1'" @click="statusRowHandler(scope.row,'enable')">启用</el-button>
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
      <el-form-item label="登录名">
        <span>{{showRow.user_name}}</span>
      </el-form-item>
      <el-form-item label="昵称">
        <span>{{showRow.nick_name}}</span>
      </el-form-item>
      <el-form-item label="出生年月">
        <span>{{showRow.birthday}}</span>
      </el-form-item>
      <el-form-item label="性别">
        <span>{{showRow.gender == 1 ? '男' : (showRow.gender == 2 ? '女' : '未填')}}</span>
      </el-form-item>
      <el-form-item label="注册日期">
        <span>{{showRow.create_time}}</span>
      </el-form-item>
      <el-form-item label="登录次数">
        <span>{{showRow.login_count}}</span>
      </el-form-item>
      <el-form-item label="最近登录日期">
        <span>{{showRow.last_login_time}}</span>
      </el-form-item>
        <el-form-item label="重置登录密码" prop="passwd">
          <el-input style="width:200px" placeholder="重置登录密码" v-model="editRow.password" type="password"></el-input>
        </el-form-item>
    </el-form>
    <div class="demo-drawer__footer">
      <el-button type="primary" @click="postEditHandler(showRow.id)">确 定</el-button>
      <el-button @click="showEditDrawer = false">取 消</el-button>
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
          id:"", userName:"", nickName:""
        }
      }
    },
    mounted() {
      this.currentUserName = window.localStorage.getItem('userName')
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
        this.$router.push({path:"/user/trade", query: {id: row.id}})
      },
      getList:function(){
        this.$store.dispatch('Loading', true);
        let _this = this;
        let params = {"page":this.currentPage, "perPage":this.perPage}
        Object.assign(params, this.formSearch)
        _this.$post('users/list', params).then(res => {
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
      postEditHandler:function(){
        this.$store.dispatch('Loading', true);
        let _this = this;
        _this.$put('users/edit'+(this.showRow.id > 0 ? '/'+this.showRow.id : ''), this.editRow).then(res => {
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
              message: '操作成功',
              type: 'success'
            });
          _this.getList()
        })
      },
      statusRowHandler:function(row, action) {
          let _this = this
          _this.$store.dispatch('Loading', true);
          _this.$patch('users/' + row.id, {"action": action}).then(res => {
          _this.$store.dispatch('Loading', false);
            if (res.code != 0) {
              _this.$message({
                  message: res.msg,
                  type:'error'
                })
                return
            }
            this.$message({
              message: '操作成功',
              type: 'success'
            });
            _this.getList()
          })
      },
    }
  }
</script>
