<template>
  <div>
    <h1>录入订单</h1>

    <el-form
        :model="editRow"  size="small"
        ref="editRow" style="width:600px"
        label-width="120px"
      >
        <el-form-item label="订单号" prop="order_sn">
          <el-input v-model="editRow.order_sn" style="width:70%;"></el-input>
          <span style="padding-left:10px;">格式: A1234</span>
        </el-form-item>
        <el-form-item label="用户 ID" prop="user_id">
          <el-input v-model="editRow.user_id" style="width:70%;"></el-input>
          <span style="padding-left:10px;">格式: 123</span>
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
        <el-form-item>
            <el-button @click.prevent="insertGoods()" size="small" type="primary" plain>+ 添加商品</el-button>
        </el-form-item>
        <div v-for="(goods,index) in editRow.goods_list" :key="index">
          <el-form-item :label="'SKU('+ (index+1) + ')'" :prop="`goods_list[${index}].sku`" :rules="{ required: true, message: '不能为空', trigger: 'blur' }">
            <el-input placeholder="SKU" v-model="goods.sku" style="width:70%;"></el-input>
          <span style="padding-left:10px;">格式: 123</span>
          </el-form-item>
          <el-form-item :label="'数量('+ (index+1) + ')'" :prop="`goods_list[${index}].sku_quantity`" :rules="{ required: true, message: '不能为空', trigger: 'blur' }">
            <el-input placeholder="数量" v-model="goods.sku_quantity" style="width:80px;"></el-input>
          </el-form-item>
          <el-form-item :label="'单价('+ (index+1) + ')'" :prop="`goods_list[${index}].sku_price`" :rules="{ required: true, message: '不能为空', trigger: 'blur' }">
            <el-input placeholder="单价" v-model="goods.sku_price" style="width:80px; padding-right:15px;"></el-input>
            <el-button @click.prevent="removeGoods(index)" size="small" type="warning" v-if="editRow.goods_list.length > 1">- 删除商品</el-button>
          </el-form-item>
        </div>
        <el-form-item>
          <el-button type="primary" @click="postEditHandler">提交保存</el-button>
          <el-button @click="resetEditRow(null)">重置</el-button>
        </el-form-item>
      </el-form>
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
          status:"", title:""
        },
        drawerTitle:"",
        uploadImageUrl: this.$apiHost() +'/upload/img'
      }
    },
    mounted() {
    },
    methods: {
      insertGoods:function() {
        this.editRow.goods_list.push({sku:"", sku_quantity:"", sku_price:""})
      },
      removeGoods:function(index) {
        if (this.editRow.goods_list.length == 1) {
          this.$message({
                message: "至少保留一个商品",
                type:'error'
              })
          return
        }
        this.editRow.goods_list.splice(index, 1)
      },
      resetEditRow:function(row){
        if (row === null) {
          this.editRow = {
            pic_url: "",
            thumb_url: "",
            redirect_url: "",
            status: '',
            title: "",
            sku: "",
            price: "",
            return_type: "",
            return_value: '',
            goods_list: [
              {sku:"", sku_price:"", sku_quantity:""}
            ],
          };
          this.showEditDrawer = false
          // this.$set(this.editRow, 'status', "")
        } else {
          this.editRow = {
            pic_url: row.pic_url,
            thumb_url: row.thumb_url,
            redirect_url: row.redirect_url,
            status: row.status,
            title: row.title,
            sku: row.sku,
            price: row.price,
            return_type: row.return_type,
            return_value: row.return_value,
            goods_list: row.goods_list,
          };
          // this.$set(this.editRow, 'status', row.status)
        }
      },
      postEditHandler:function(){
        this.$store.dispatch('Loading', true);
        let _this = this;
        _this.$post('orders', this.editRow).then(res => {
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
