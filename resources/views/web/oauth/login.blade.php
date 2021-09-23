<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/static/css/bootstrap.min.css?v=201903090001" rel="stylesheet">
    <link href="/static/css/common.css" rel="stylesheet">
    <link href="/static/css/style.css" rel="stylesheet">
    <link href="/static/css/element_yzd.css" rel="stylesheet">
    <link href="/static/css/iconfont/iconfont.css" rel="stylesheet">
    <link href="/static/css/color_ui/iconfont.css" rel="stylesheet">

    <link href="/static/css/flex.css" rel="stylesheet">
    <link href="/lib/elementui/theme/index.css" rel="stylesheet">
    <link href="/static/css/diy.css" rel="stylesheet">
    <script src="/lib/jquery/jquery.min.js"></script>
    <script src="/lib/vue/vue.js"></script>
    <script src="/lib/axios/dist/axios.min.js"></script>
    <script src="/lib/qs/dist/qs.js"></script>
    <script src="/lib/elementui/lib/index.js"></script>
    <script type="text/javascript" src="/static/js/common.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>
</head>
<body>
<div class="login" id="app">
    <el-row :gutter="20">
        <el-col :span="8" :offset="8">
            <div class="login-sr">
                <el-form :model="Form" ref="Form" :rules="FormRules">
                    <h3 class="login-title">分发系统</h3>
                    <el-form-item label="用户名:" prop="user_name">
                        <el-input type="text" v-model="Form.user_name" placeholder="请输入用户名" prefix-icon="el-icon-user"></el-input>
                    </el-form-item>
                    <el-form-item label="密码:" prop="password">
                        <el-input type="password" @keydown="submitForm('Form')" v-model="Form.password" placeholder="请输入密码" prefix-icon="el-icon-lock"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button @click="submitForm('Form')"  type="primary" :loading="btnLoading" class="btn-block">登录</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </el-col>
    </el-row>
</div>
</body>
<script>
    var token = $('meta[name="csrf-token"]').attr('content');
    var app = new Vue({
        el:'#app',
        data(){
            return {
                btnLoading:false,
                Form:{
                    user_name:'',
                    password:'',
                },
                FormRules:{
                    user_name:{required:true, message:"用户名不能为空!", onblur:true},
                    password:{required:true, message:'密码不能为空!', onblur:true}
                }
        }
        },
        methods:{
            submitForm(formName){
                var _this = this;
                _this.btnLoading = true;
                _this.$refs[formName].validate((valid) =>{
                    if(valid) {
                        axios.post("{{route('auth.login')}}", Qs.stringify({user_name:_this.Form.user_name,_token:token,password:_this.Form.password}))
                        .then(function(res) {
                            console.log(res);
                            if(res.data.status=='success') {
                                _this.$message({
                                    message:'登录成功',
                                    type:'success',
                                    duration:1500,
                                    close:function () {
                                        _this.btnLoading = false;
                                        location.href="/web/home";
                                    }
                                })
                            } else {
                                _this.btnLoading = false;
                                _this.$message({
                                    message:res.data.msg,
                                    type:'warning',
                                    duration:1500,
                                })
                            }

                        })
                        .catch(function(error) {
                            _this.btnLoading = false;
                            _this.$message({
                                message:'失败!',
                                type:'error',
                                duration:1000
                            })
                        })
                    } else{
                        alert('验证失败!');
                    }
                    }
                )
            }
        }
    })
</script>
<style>
    body{
        background:url(/static/img/bg.jpg) no-repeat!important;
        background-size: cover;
    }
    .login{
        margin:10% auto;
    }
    .login-sr{

        padding:15px;
        border-radius: 15px;
    }
    .el-form-item__label{
        color:#fff;
    }
    .login-title{
        text-align:center;
        color:#fff;
    }
    .img-box{
        width:100%;
        height:100%;
        border:1px solid #eee;
        border-radius:5px;
        background-size:cover;
        background-position:center center;
        cursor:pointer;
    }
</style>
</html>