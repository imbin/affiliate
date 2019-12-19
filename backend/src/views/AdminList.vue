<template>
  <div>
    <h1>管理员列表 <el-button type="primary" icon="el-icon-edit" @click="createRow()">添加管理员</el-button></h1>
    <el-table
    :data="tableData"
    border v-loading="$store.state.loading"
    style="width:800px;">
    <el-table-column
      prop="user_name"
      label="登录名"
      width="180">
    </el-table-column>
    <el-table-column
      prop="real_name"
      label="姓名"
      width="180">
    </el-table-column>
    <el-table-column
      prop="create_time"
      label="添加日期"
      width="180">
    </el-table-column>
    <el-table-column
      label="操作">
      <template slot-scope="scope">
        <el-button @click="editRowHandler(scope.row)" v-if="scope.row.user_name != currentUserName" type="text" size="small">修改</el-button>
        <el-button @click="delRowHandler(scope.row)" v-if="scope.row.user_name != currentUserName" type="text" size="small">删除</el-button>
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
  :title="editRow.title"
  :visible.sync="showEditDrawer"
  :direction="direction">
  <div class="drawer_form">
  <el-form :model="editRow">
      <el-form-item label="登录名">
        <el-input v-model="editRow.userName"></el-input>
        <span>仅支持：[a~zA~Z0-9]字符做登录名,不区分大小写</span>
      </el-form-item>
      <el-form-item label="姓名">
        <el-input v-model="editRow.realName"></el-input>
      </el-form-item>
        <el-form-item label="登录密码" prop="password">
          <el-input placeholder="登录密码" type="password" v-model="editRow.password"></el-input>
        </el-form-item>
    </el-form>
    <div class="demo-drawer__footer">
      <el-button @click="showEditDrawer = false">取 消</el-button>
      <el-button type="primary" @click="postEditHandler()">确 定</el-button>
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
        showEditDrawer:false,//编辑界面
        editRow:{
          userName:"", realName:"", password:"", id:0, title:'编辑'
        },
        tableData: [],
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
      getList:function(){
        this.$store.dispatch('Loading', true);
        let _this = this;
        _this.$post('admin/list', {"page":this.currentPage, "perPage":this.perPage}).then(res => {
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
      createRow:function(){
        this.editRow = {
          userName:"",
          realName:"",
          id:0,
          password:"",
          title: '添加'
        }
        this.showEditDrawer = true;
      },
      editRowHandler:function(row) {
        this.editRow = {
          userName: row.user_name,
          realName: row.real_name,
          id: row.id,
          password: "",
          title: '编辑'
        }
        this.showEditDrawer = true;
      },
      postEditHandler:function(){
        this.$store.dispatch('Loading', true);
        let _this = this;
        _this.$put('admin/edit'+(this.editRow.id > 0 ? '/'+this.editRow.id : ''), this.editRow).then(res => {
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
              message: _this.editRow.title + '成功',
              type: 'success'
            });
          _this.getList()
        })
      },
      delRowHandler:function(row) {
        this.$confirm('此操作将永久删除该账号:'+ row.real_name + '(' + row.user_name + '), 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.$store.dispatch('Loading', true);
          let _this = this
          _this.$delete('admin/' + row.id).then(res => {
          _this.$store.dispatch('Loading', false);
            if (res.code != 0) {
              _this.$message({
                  message: res.msg,
                  type:'error'
                })
                return
            }
            this.$message({
              message: '已经删除',
              type: 'success'
            });
            _this.getList()
          })
        }).catch(() => {       
        });
      }
    }
  }
</script>
