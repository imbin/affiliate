<template>
  <div>
    <el-row class="row" style="width:60%">
      <h1>个人设置</h1>
      <el-form
        :model="formModel"
        :rules="rules"
        ref="formModel"
        label-width="100px"
        class="demo-formModel"
      >
        <el-form-item label="登录名" prop="userName">
          <label>{{formModel.userName}}</label>
        </el-form-item>
        <el-form-item label="姓名" prop="realName">
          <el-input placeholder="姓名" v-model="formModel.realName"></el-input>
        </el-form-item>
        <el-form-item label="旧密码" prop="passwordOld">
          <el-input placeholder="旧密码" type="password" v-model="formModel.passwordOld"></el-input>
        </el-form-item>
        <el-form-item label="新密码" prop="passwordNew">
          <el-input placeholder="新密码" type="password" v-model="formModel.passwordNew"></el-input>
          <span>密码规则:只允许0~9A~Za~z和~!@#$%^&*()_+{}[]\|-=</span>
        </el-form-item>
        <el-form-item label="确认新密码" prop="passwordTwice">
          <el-input placeholder="确认新密码" type="password" v-model="formModel.passwordTwice"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submitForm('formModel')">提交保存</el-button>
          <el-button @click="resetForm('formModel')">重置</el-button>
        </el-form-item>
      </el-form>
    </el-row>
  </div>
</template>
<style scoped>
.row {
  background-color: white;
  padding-top: 15px;
  padding-right: 15px;
}
.row h1 {
  padding-left: 15px;
}
.row form {
  width: 70%;
}
</style>

<script>
export default {
  data() {
    return {
      formModel:{
        passwordOld: "",
        passwordNew: "",
        passwordTwice: "",
        userName: "",
        realName: "",
      },
      rules: {
        realName: [
          { required: true, message: "请输入姓名", trigger: "blur" },
          { min: 1, max: 20, message: "长度在 1 到 20 个字符", trigger: "blur" }
        ]
      }
    };
  },
  mounted(){
    this.formModel.userName = window.localStorage.getItem('userName');
    this.formModel.realName = window.localStorage.getItem('realName');
  },
  methods: {
    submitForm(formName) {
      let _this = this;
      _this.$refs[formName].validate(valid => {
        if (valid) {
          _this.$store.dispatch('Loading', true);
          let params = {"realName": _this.formModel.realName}
          if (_this.formModel.passwordOld.length > 0) {
            params['passwordOld'] = _this.formModel.passwordOld
          }
          if (_this.formModel.passwordNew.length > 0) {
            params['passwordNew'] = _this.formModel.passwordNew
          }
          if (_this.formModel.passwordTwice.length > 0) {
            params['passwordTwice'] = _this.formModel.passwordTwice
          }
          _this.$put('/user/pwd', params).then(res => {
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
              if (params.passwordOld.length > 0 && params.passwordNew.length > 0 && params.passwordTwice.length > 0) {
                window.localStorage.removeItem('realName')
                window.localStorage.removeItem('userName')
                window.localStorage.removeItem('token')
                _this.$router.push("/login")
              }
            }
          })
        } else {
          // console.log('error submit!!');
          return false;
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
      this.formModel.userName = window.localStorage.getItem('userName');
      this.formModel.realName = window.localStorage.getItem('realName');
    }
  }
};
</script>
