


<style>
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        width: 178px;
        height: 178px;
    }

    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }

    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 178px;
        height: 178px;
        line-height: 178px;
        text-align: center;
    }

    .avatar {
        width: 178px;
        height: 178px;
        display: block;
    }

    .image-preview {
        width: 178px;
        height: 178px;
        position: relative;
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        float: left;
    }

    .image-preview .image-preview-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .image-preview .image-preview-wrapper img {
        width: 100%;
        height: 100%;
    }

    .image-preview .image-preview-action {
        position: absolute;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        cursor: default;
        text-align: center;
        color: #fff;
        opacity: 0;
        font-size: 20px;
        background-color: rgba(0, 0, 0, .5);
        transition: opacity .3s;
        cursor: pointer;
        text-align: center;
        line-height: 200px;
    }

    .image-preview .image-preview-action .el-icon-delete {
        font-size: 32px;
    }

    .image-preview:hover .image-preview-action {
        opacity: 1;
    }
    input[type=file] {
        display: none!important;
    }
</style>

<div class="web_content" id="app">
    <el-card class="box-card" style="margin-top:10px">
        <el-form label-width="100px" :model="form" ref="form" >

            <el-form-item label="原密码" prop="oldPassword">
                <el-col :span="12">
                    <el-input type="password" size="small" place="请输入原始密码" v-model="form.oldPassword"></el-input>

            </el-form-item>
            </el-col>

            <el-form-item label="原密码" prop="newPassword">
                <el-col :span="12">
                    <el-input type="password" size="small" place="请输入新密码" v-model="form.newPassword"></el-input>
                </el-col>
            </el-form-item>
            <el-button type="primary" @click="update" :loading="btnLoading">保 存</el-button>
        </el-form>

    </el-card>
</div>
<script>
    new Vue({
        el:'#app',
        data(){
            return{
                form:{oldPassword:'',newPassword:''},
                btnLoading:false,
            }
        },
        created(){

        },
        methods:{
            update()
            {
                if(!this.form.oldPassword){
                    this.$message.warning("请输入原始密码");
                }
                if(!this.form.newPassword){
                    this.$message.warning("请输入新密码");
                }
                let _this = this;
                this.btnLoading = true;
                axios.post("{{route("account.index")}}", this.form).then(function(res){
                    console.log(res);
                    if(res.data.status == "success") {
                        _this.$message.success("修改成功!");
                        _this.btnLoading = false;
                    } else {
                        _this.btnLoading = false;
                        _this.$message.warning(res.data.msg);
                    }
                });
            }
        }
    })
</script>
<style>
    .img-box{
        width:60px;
        height:60px;
        border:1px solid #eee;
        border-radius:5px;
        background-size:cover;
        background-position:center center;
        cursor:pointer;
    }
</style>