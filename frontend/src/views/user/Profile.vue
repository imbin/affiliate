<template>
  <div>
    <h1>个人资料</h1>
    <el-row>
    <el-col :span="10">
    <el-form ref="form" :model="form" label-width="80px">
      <el-form-item label="登录名">
        <label>{{form.userName}}</label>
      </el-form-item>
      <el-form-item label="Email">
        <el-input v-model="form.email"></el-input>
      </el-form-item>
      <el-form-item label="手机号">
        <el-input v-model="form.mobile"></el-input>
      </el-form-item>
      <el-form-item label="性别">
        <el-radio-group v-model="form.gender">
          <el-radio :label="2">女</el-radio>
          <el-radio :label="1">男</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="出生年月">
          <el-date-picker type="date" placeholder="选择日期" format="yyyy-MM-dd" value-format="yyyy-MM-dd" v-model="form.birthday" style="width: 100%;"></el-date-picker>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="editProfile">提交修改</el-button>
        <el-button @click="resetFormProfile">重置</el-button>
      </el-form-item>
    </el-form>
    </el-col>
    <el-col :offset="4" :span="10">
    <el-form ref="formPwd" :model="formPwd" label-width="100px">
      <el-form-item label="">
        <div style="coloe:white; height:30px;">&nbsp;</div>
      </el-form-item>
      <el-form-item label="旧密码">
        <el-input type="password" v-model="formPwd.passwordOld"></el-input>
      </el-form-item>
      <el-form-item label="新密码">
        <el-input type="password" v-model="formPwd.passwordNew"></el-input>
      </el-form-item>
      <el-form-item label="确认新密码">
        <el-input type="password" v-model="formPwd.passwordTwice"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="editPwd">修改密码</el-button>
        <el-button @click="resetFormPwd">重置</el-button>
      </el-form-item>
    </el-form>
    </el-col>
    </el-row>
  </div>
</template>
<script>
  export default {
    name:'userProfile',
    data() {
      return {
        form: {
          userName: '',
          email: '',
          mobile: '',
          gender: '',
          birthday: ''
        },
        formPwd: {
          passwordOld:"",
          passwordNew:"",
          passwordTwice:""
        }
      }
    },
    mounted() {
      this.form.userName = window.localStorage.getItem('userName');
      if (this.form.userName) {
        this.$store.dispatch('Logined', {logined:true, userName:this.form.name});
      }
      this.initProfile()
    },
    methods: {
      onSubmit() {
        
      },
      initProfile() {
        let _this = this;
        _this.$store.dispatch('Loading', true);
        this.$fetch('/user/profile').then(res => {
          _this.$store.dispatch('Loading', false);
          if(res.code != 0) {
            _this.$message({
              message: res.msg,
              type:'error'
            });
          } else {
            _this.form = res.data
          }
        })
      },
      editProfile() {
        let _this = this;
        _this.$store.dispatch('Loading', true);
        this.$put('/user/edit-profile', this.form).then(res => {
          _this.$store.dispatch('Loading', false);
          if(res.code != 0) {
            _this.$message({
              message: res.msg,
              type:'error'
            });
          } else {
            _this.$message({
              message: res.msg,
              type:'success'
            });
            _this.initProfile()
          }
        })
      },
      editPwd() {
        let _this = this;
        _this.$store.dispatch('Loading', true);
        this.$put('/user/edit-pwd', this.formPwd).then(res => {
          _this.$store.dispatch('Loading', false);
          if(res.code != 0) {
            _this.$message({
              message: res.msg,
              type:'error'
            });
          } else {
            _this.resetFormPwd()
            _this.$message({
              message: res.msg,
              type:'info'
            });
          }
        })
      },
      resetFormProfile() {
        this.form = {
          userName: '',
          email: '',
          mobile: '',
          gender: '',
          birthday: ''
        }
      },
      resetFormPwd() {
        this.formPwd = {
          passwordOld:"",
          passwordNew:"",
          passwordTwice:""
        }
      }
    }
  }
</script>
