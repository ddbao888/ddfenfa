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

        <el-form label-width="150px" ref="form" :model="form">
            <el-tabs v-model="activeName">
                <el-tab-pane name="list"  label="应用列表">
                    <el-table :data="data" strip>
                        <el-table-column type="selection" width="55">

                        </el-table-column>
                        <el-table-column prop="id" label="编号" width="55">

                        </el-table-column>
                        <el-table-column label="应用图标" min-width="10%">
                            <template slot-scope="scope">
                                <div style="height:40px"><el-avatar size="medium" style="float:left" :src="scope.row.in_icon"></el-avatar</div>
                            </template>
                        </el-table-column>
                        <el-table-column label="应用名称" prop="in_name" min-width="10%">
                        </el-table-column>
                        <el-table-column label="应用平台" prop="in_form" min-width="10%">
                        </el-table-column>
                        <el-table-column prop="in_hits" label="安装统计" width="120">

                        </el-table-column>
                        <el-table-column label="链接" width="300">
                            <template slot-scope="scope">
                                <a  href="javascript:void(0);" data-href="" @click="copyUrl(scope.row.url)">{{env("APP_URL")}}/app/@{{ scope.row.id }}</a>
                            </template>
                        </el-table-column>

                        <el-table-column label="创建时间" prop="created_at" min-width="15%">

                        </el-table-column>
                        <el-table-column label="更新时间" prop="updated_at" min-width="15%">

                        </el-table-column>

                        <el-table-column min-width="15%" label="操作">
                            <template slot-scope="scope" >
                                <el-button type="primary" size='mini' @click="edit(scope.row)" icon="el-icon-edit">编辑</el-button>
                                <el-button type="danger" size='mini' @click="deleteApp(scope.row.id)" icon="el-icon-delete">删除</el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                    <div class="btn-toolbar">
                        <el-row>
                            <el-col :span="8">

                            </el-col>
                            <el-col :span="16">
                                <el-pagination background layout="prev, pager, next" :total="total" :page-size="pageSize"
                                               @current-change="currentChange" style="text-align:right">
                                </el-pagination>
                            </el-col>
                        </el-row>
                    </div>

                </el-tab-pane>
                <el-tab-pane name="new" label="新增应用">
                    <el-form label-width="100px" :model="form" ref="form" :rules="formRules">
                        <el-form-item label="Logo">
                            <el-upload
                                       class="avatar-uploader"
                                       accept="image/png"
                                       action="/web/img/upload"
                                       :show-file-list="false"
                                       :on-success="imgHandleSuccess"
                                       :on-progress="uploadProcess">
                                <img v-if="imageUrl.length > 10" :src="imageUrl" class="avatar">
                                <i v-show="imageUrl =='' && imgFlag == false" class="el-icon-plus avatar-uploader-icon"></i>
                                <el-progress v-show="imgFlag == true" type="circle" :percentage="percent" style="margin-top: 20px"></el-progress>
                            </el-upload>

                        </el-form-item>
                        <el-form-item label="应用包">
                            <el-upload
                                    class="upload-demo"
                                    ref="upload"
                                    :before-upload="beforeUpload"
                                    :action="serverUrl"
                                    :on-success="handleSuccess"
                                    :auto-upload="false">
                                <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
                            </el-upload>
                        </el-form-item>
                        <el-form-item>
                            <el-form-item label="">
                                <el-button type="primary" @click="submit()":loading="btnLoading">@{{btnTitle}}</el-button>
                            </el-form-item>
                        </el-form-item>
                    </el-form>
                </el-tab-pane>

            </el-tabs>

        </el-form>
    </el-card>
    @include('web.app.form');
</div>
<script>
    new Vue({
        el:'#app',
        data(){
            return{
                visible:false,
                btnTitle:'上传',
                title:"正在解析应用，请稍等...",
                serverUrl: "/web/app/upload", // 后台上传接口
                imgFlag: false,
                dialogVisible:false,
                percent: 0,
                imageUrl: '',
                logoUrl:'',
                logoFlag:false,
                cardLoading:false,
                btnLoading:false,
                activeName:'list',
                data:[],
                page:1,
                total:0,
                pageSize:15,
                form:{
                    logo:'',
                },
                inForm:{
                    id:0,
                    in_icon:'',
                    in_name:''
                },
                formRules:{
                    level_name:[{required:true, message:'物品名称不能为空', trigger:'blur'}],
                    pic:[{required:true, message:'物品图片不能为空', trigger:'blur'}],
                },
                img:'',
                images:[],
            }
        },
        created(){
            _this = this;
            this.init();
        },
        methods:{
            copyUrl(url) {
                // 创建一个 Input标签
                const cInput = document.createElement('input')
                cInput.value = url
                document.body.appendChild(cInput)
                cInput.select() // 选取文本域内容;
                // 执行浏览器复制命令
                // 复制命令会将当前选中的内容复制到剪切板中（这里就是创建的input标签）
                // Input要在正常的编辑状态下原生复制方法才会生效
                document.execCommand('Copy')
                this.$message.success('复制成功') // antd框架封装的通知,如使用别的UI框架，换掉这句
                /// 复制成功后再将构造的标签 移除
                cInput.remove()
            },
            handleRemove(file, fileList) {
                this.imageUrl = '';
            },
            beforeUpload(file) {

                const isLt10M = file.size / 1024 / 1024  < 200;

                if (!isLt10M) {
                    this.$message.error('上传图片不能超过200MB哦!');
                    return false;
                }
            },
            logoBeforeUpload(file){
                const isLt10M = file.size / 1024 / 1024  < 2;
                if (!isLt10M) {
                    this.$message.error('上传图片不能超过2MB哦!');
                    return false;
                }
            },
            logoHandleSuccess(res,file){
              this.inForm.in_icon = res.url;
              this.logoUrl = res.url;
            },
            imgHandleSuccess(res,file){
                console.log(res);
                if(res.status == "success"){
                    this.form.logo = res.url;
                    this.imageUrl = res.url;
                    this.serverUrl = "/web/app/upload?logo="+res.url;
                } else {
                    this.$message.warning(res.msg);
                }
            },
            handleError(){
                this.$message.error("文件上传失败");
                this.btnLoading = false;
                this.btnTitle = "上传";
            },
            handleSuccess(res, file) {
                console.log(res);
                let _this = this;
                this.imgFlag = false;
                this.percent = 0;

                if (res.status == "success") {
                    this.imageUrl = res.path; // 项目中用后台返回的真实地址
                    this.btnLoading = false;
                    this.btnTitle = "上传";
                    _this.message.success("上传成功!");
                    _this.activeName="list";
                    _this.init();
                } else if(res.status == "warning"){
                    this.$message.warning(res.msg);
                } else if(res.status == "error") {
                    this.$message.error('应用上传失败，请重新上传！');
                } else if(res.status == "continue") {
                    this.btnTitle = "正在解析应用包..."
                    axios.post(this.serverUrl, {"time": res.time,"size" :res.size}).then(function(res){
                        if(res.data.status == "success") {
                            _this.btnTitle ="上传";
                            _this.btnLoading = false;
                            _this.$message.success("上传成功!");
                            _this.activeName="list";
                            _this.init();
                        }
                       // console.log(res);
                    }).catch(function(err){
                        _this.$message.error("解析错误,请联系管理员");
                        _this.btnLoading = false;
                        _this.btnTitle="上传";
                    })
                }

            },
            uploadProcess(event, file, fileList) {
                this.imgFlag = true;
                console.log(event.percent);
                this.percent = Math.floor(event.percent);
            },
            init(){
                let _this  = this;
                _this.cardLoading = true;
                axios.get("{{route('app.list')}}?page="+this.page).then(function(resp){
                    var data = resp.data;
                    _this.total = data.total;
                    _this.data = data.data;
                    _this.cardLoading = false;
                }).catch(function(error){

                })
            },

            submit(){
                var files = this.$refs.upload.uploadFiles;
                if(files.length ==0) {
                    this.$message.warning("请选择上传的app");
                    return;
                }
                this.btnTitle = "正在上传文件...";
                this.btnLoading = true;
                this.$refs.upload.submit();
            },
            edit(e){
                this.inForm = e;
                console.log(e);
                this.logoUrl = e.in_icon;
                this.dialogVisible = true;
            },
            update(){

                        _this.btnLoading = true;
                        axios.post("{{route('app.update')}}/"+_this.inForm.id, Qs.stringify(_this.inForm)).then(function(resp){
                            var data = resp.data;
                            if(data.status == 'success'){
                                _this.$message({
                                    message:'编辑成功!',
                                    type:'success',
                                    duration:1000,
                                    onClose:function(){
                                        _this.btnLoading = false;
                                        _this.dialogVisible = false;
                                        _this.init();
                                    }
                                })
                            } else {
                                _this.$message({
                                    message:resp.data.msg,
                                    type:'warning',
                                    duration:1000
                                })
                            }
                            _this.btnLoading = false;
                        }).catch(function(error){
                            _this.$message.error('系统错误');
                        })
            },
            deleteApp(id){
                console.log('删除!');
                let _this = this;
                _this.$confirm("确认要删除吗？", "提示", {
                    confirmButtonText:'确定',
                    cancelButtonText:'取消',
                }).then(()=>{
                    axios.delete("{{route("app.delete")}}/"+id).then(function(res){
                        var data = res.data;
                        if(data.status == "success") {
                            _this.$message({
                                message:'删除成功!',
                                type:'success',
                            })
                            _this.init();
                        } else{
                            _this.$message.warning('删除失败');
                        }
                    }).catch(function(error){
                        _this.$message.error('系统错误');
                    })
                })
            },
            chooseImg(name){

                this.img = name;
                this.$refs['img'].dialogVisible = true;
            },
            selectImgOk(e){
                console.log(e);
                if(e.str) {
                    this.form.pic = e.arr;
                    this.img = e.arr[0];
                    this.images = e.arr;
                }

            },
            addressConfirm(e) {
                console.log(e);
            },
            openMap(name) {
                this.$refs['address'].dialogVisible = true;
            },
            selectArea(e) {
                console.log(e);
            },
            currentChange(e){
                _this.page = e;
                _this.init();
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