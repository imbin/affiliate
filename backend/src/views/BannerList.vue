<template>
  <div>
    <h1>素材列表</h1>

  <el-form :inline="true" :model="formSearch">
    <el-form-item label="标题">
      <el-input v-model="formSearch.title" placeholder="标题"></el-input>
    </el-form-item>
    <el-form-item label="上架状态">
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
      <el-button type="success" @click="createRow"  icon="el-icon-edit">添加素材</el-button>
    </el-form-item>
  </el-form>

  <el-table
    :data="tableData"
    border v-loading="$store.state.loading"
    style="width: 100%">
    <el-table-column
      prop="title"
      label="标题">
    </el-table-column>
    <el-table-column
      width="100"
      label="图片">
      <template slot-scope="scope"><a target="_blank" :href="scope.row.thumb_url">
        <img :src="scope.row.thumb_url" height="60" />
        </a>
      </template>
    </el-table-column>
    <el-table-column
      prop="redirect_url"
      label="跳转 URL">
    </el-table-column>
    <el-table-column
      prop="sku"
      label="SKU">
    </el-table-column>
    <el-table-column
      prop="row.status_text"
      label="状态">
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
        <el-button type="text" size="small" v-if="scope.row.row.status_text=='待审核'" @click="disableRowHandler(scope.row)">上架</el-button>
        <el-button type="text" size="small" v-if="scope.row.row.status_text=='审核通过'" @click="disableRowHandler(scope.row)">下架</el-button>
        <el-button type="text" size="small" @click="editRowHandler(scope.row)">编辑</el-button>
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
        :model="editRow"
        ref="editRow"
        label-width="120px"
      >
        <el-form-item label="素材标题" prop="title">
          <el-input v-model="editRow.title"></el-input>
        </el-form-item>
        <el-form-item label="素材图片">
          <el-upload
            class="upload-demo"
            name="image"
            accept=".jpg,.png,.gif,.jpeg"
            :action="uploadImageUrl"
            :on-success="handleUploadSuccess"
            :before-upload="beforeImageUpload"
            >
            <el-button size="small" type="primary">点击上传</el-button>
            <div class="el-upload__tip" slot="tip">只能上传jpg/png/gif文件，且不超过5MB</div>
          </el-upload>
          <img height="60" v-if="editRow.thumb_url" :src="editRow.thumb_url" />
        </el-form-item>
        <el-form-item label="跳转 URL" prop="redirect_url">
          <el-input v-model="editRow.redirect_url"></el-input>
        </el-form-item>
        <el-form-item label="SKU" prop="sku">
          <el-input  v-model="editRow.sku"></el-input>
        </el-form-item>
        <el-form-item label="售价" prop="price">
          <el-input  v-model="editRow.price"></el-input>
        </el-form-item>
        <el-form-item label="返佣方式" prop="return_type">
          <input type="radio" id="return_type_one" value="1" v-model="editRow.return_type">
          <label for="return_type_one">固定金额￥</label><span style="padding-left:15px;"></span>
          <input type="radio" id="return_type_two" value="2" v-model="editRow.return_type">
          <label for="return_type_two">比例 %</label>
        </el-form-item>
        <el-form-item label="返佣金额/比例" prop="return_value">
          <label style="padding-left:15px;" v-if="editRow.return_type == 1">￥</label>
          <el-input v-model="editRow.return_value" style="width:60px;"></el-input>
          <label style="padding-left:15px;" v-if="editRow.return_type == 2">%</label>
        </el-form-item>
        <el-form-item label="上架状态" prop="status">
          <input type="radio" id="one" value="1" v-model="editRow.status">
          <label for="one">上架</label><span style="padding-left:15px;"></span>
          <input type="radio" id="two" value="2" v-model="editRow.status">
          <label for="two">下架</label>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="postEditHandler">提交保存</el-button>
          <el-button @click="resetEditRow(null)">取消</el-button>
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
          id: 0,
          pic_url: "",
          redirect_url: "",
          status: "",
          title: "",
          sku: "",
          price: "",
          return_type: "",
          return_value: "",
        },
        tableData: [],
        statusOptions: [
          {label:"已上架", value:"1"},
          {label:"已下架", value:"2"},
        ],
        formSearch:{
          status:"", title:""
        },
        drawerTitle:"",
        uploadImageUrl: this.$apiHost() +'/upload/img'
      }
    },
    mounted() {
        this.getList()
    },
    methods: {
      beforeImageUpload(file) {
        var exp = new RegExp("^image\\/([jpeg|jpg|gif|png]+)$");

        const isImage = exp.test(file.type);
        const isLt5M = file.size / 1024 / 1024 <= 5;

        if (!isImage) {
          this.$message.error('上传图片只能是 jpg/gif/png 格式!');
        }
        if (!isLt5M) {
          this.$message.error('上传图片大小不能超过 2MB!');
        }
        return isImage && isLt5M;
      },
      changeSel:function(v) {
        this.$forceUpdate()
        console.log(v)
        console.log(this.editRow)
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
      handleUploadSuccess:function(res){
        if (res.code == 0) {
          this.editRow.pic_url = res.data.file_url;
          this.editRow.thumb_url = res.data.thumb_url;
        } else {
          this.$message({
              message: res.msg,
              type:'error'
            })
        }
      },
      getList:function(){
        this.$store.dispatch('Loading', true);
        let _this = this;
        let params = {"page":this.currentPage, "perPage":this.perPage}
        Object.assign(params, this.formSearch)
        _this.$fetch('banners', params).then(res => {
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
            id: 0,
            pic_url: "",
            thumb_url: "",
            redirect_url: "",
            status: '',
            title: "",
            sku: "",
            price: "",
            return_type: "",
            return_value: "",
          };
          this.showEditDrawer = false
          // this.$set(this.editRow, 'status', "")
        } else {
          this.editRow = {
            id: row.id,
            pic_url: row.pic_url,
            thumb_url: row.thumb_url,
            redirect_url: row.redirect_url,
            status: row.status,
            title: row.title,
            sku: row.sku,
            price: row.price,
            return_type: row.return_type,
            return_value: row.return_value,
          };
          // this.$set(this.editRow, 'status', row.status)
        }
      },
      createRow:function(){
        this.resetEditRow(null)
        this.drawerTitle = '添加';
        this.showEditDrawer = true;
      },
      editRowHandler:function(row) {
        this.resetEditRow(row)
        this.drawerTitle = '编辑';
        this.showEditDrawer = true;
      },
      postEditHandler:function(){
        this.$store.dispatch('Loading', true);
        let _this = this;
        _this.$post('banners'+(this.editRow.id > 0 ? '/'+this.editRow.id : ''), this.editRow).then(res => {
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
      },
      delRowHandler:function(row) {
        this.$confirm('此操作将永久删除该素材:'+ row.title + ', 是否继续?', '提示', {
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
