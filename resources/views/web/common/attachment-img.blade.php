@verbatim
<template id="attachment-img" v-cloak>
    <el-dialog title="选择图片" :visible.sync="dialogVisible" width="960px" @close='modalClose' @opened="dialogOpened">
        <div flex="box:first" class="attachment_img_box">
            <div class="attachment_img_left" v-loading="groupListLoading">
                <el-menu class="group-menu" mode='vertical'>
                    <el-scrollbar style="height: 450px;width: 100%;">
                        <el-menu-item index="all" @click="selected(0)">
                            <i class="el-icon-tickets"></i>
                            <span>全部</span>
                        </el-menu-item>
                        <template v-for="(item, index) in groupItem">
                            <el-menu-item :index='item.id' @click="selected(item.id)">
                                <div flex="dir:left box:last">
                                    <div style="overflow: hidden;text-overflow: ellipsis">
                                        <i class="el-icon-tickets"></i>
                                        <span>{{item.title}}</span>
                                    </div>
                                    <div flex="dir:left">
                                        <el-link :underline="false" type="primary" @click='deleteType(item.id)'>删除
                                        </el-link>
                                    </div>
                                </div>
                            </el-menu-item>
                        </template>
                    </el-scrollbar>
                    <el-popover placement="top" width="160" v-model="visible">
                        <el-input v-model="new_type_name" placeholder="请输入分组名称" size="mini"></el-input>
                        <div style="text-align: right; margin: 0;margin-top: 10px;">
                            <el-button size="mini" type="text" @click="visible = false">取消</el-button>
                            <el-button type="primary" size="mini" @click="addNewAttType">确定</el-button>
                        </div>
                        <el-button slot="reference" style="margin-left: 30%" size="small">新建分组</el-button>
                    </el-popover>
                </el-menu>
            </div>
            <div flex="dir:top" class="attachment_img_right" v-loading="rightLoading" element-loading-text="请求加载中">
                <el-scrollbar class="scrollbar">
                    <div style="padding: 10px;">
                        <el-input placeholder="搜索图片名称" suffix-icon="el-icon-search" v-model="atta_name" size="small"
                                  @keyup.enter.native="search(atta_name)" @input="search(atta_name)"
                                  style="width: 30%;padding-left: 12px;"> </el-input>
                        <div style="float: right;padding: 0 10px 0 0;">
                        <!-- {{_img.att_type}} -->
                            <!-- 七牛 -->
                            <el-upload v-if="att_type=='3'" class="upload-demo" :action="upload_url" :multiple="true"
                                       :file-list="fileList" :on-success="handleSuccess" :on-error="handleError"
                                       :before-upload="beforeUpload" :data="uploadData" :on-progress="handProgress" :show-file-list="false">
                                <el-button size="small" type="primary">上传图片</el-button>
                            </el-upload>
                            <!-- 本地 -->
                            <el-upload v-if="att_type=='1'" class="upload-demo" name='upfile' :action="upload_url" :multiple="true"
                                       :file-list="fileList" :on-success="handleSuccess"
                                       :on-error="handleError" :before-upload="beforeUpload" :data="uploadData" :on-progress="handProgress" :show-file-list="false">
                                <el-button size="small" type="primary">上传图片</el-button>
                            </el-upload>
                            <!-- 阿里云 -->
                            <el-upload v-if="att_type=='2'" class="upload-demo" name='upfile' :multiple="true"
                                       action="" :file-list="fileList" :before-upload="beforeUpload"
                                       :on-progress="handProgress" :show-file-list="false">
                                <el-button size="small" type="primary">上传图片</el-button>
                            </el-upload>
                            <!-- 腾讯云 -->
                            <el-upload v-if="att_type=='4'" class="upload-demo" name='upfile' :multiple="true"
                                       action="" :file-list="fileList" :before-upload="beforeUpload"
                                       :on-progress="handProgress" :show-file-list="false">
                                <el-button size="small" type="primary">上传图片</el-button>
                            </el-upload>
                        </div>
                    </div>

                    <div class="app-attachment-list">
                        <template v-for="(item, index) in attachments">
                            <el-tooltip class="item" effect="dark" :content="item.title" placement="top"
                                        :open-delay="1">
                                <div class="app-attachment-item" @click='selectedItem(index,item.url)'
                                     :style="getStyle(index)">
                                    <img v-if="item.type == 1" class="app-attachment-img" :src="item.url"
                                         style="width: 100px;height: 100px;">
                                    <div class="app-attachment-name">{{item.sort_title}}</div>
                                    <i class="el-icon-error delete" @click='deleteItem(item.id,item.oss_type,item.path)'></i>
                                </div>
                            </el-tooltip>
                        </template>
                    </div>
                </el-scrollbar>
                <div style="padding: 5px;text-align: right;margin-top:auto">
                    <el-pagination layout="prev, pager, next" :total="total" :page-size="pagesize"
                                   @current-change="current_change">
                    </el-pagination>
                </div>
            </div>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button @click="dialogVisible = false" size='small'>取 消</el-button>
            <el-button type="primary" @click="selectedItems()" size='small'>确 定</el-button>
        </span>
    </el-dialog>
</template>
@endverbatim
<script>
    Vue.component('attachment-img', {
        template: '#attachment-img',
        props: {
            type: {
                type: String,
                default: 'img'
            },
            op: {
                type: String
            }
        },
        data() {
            return {
                visible: false,
                atta_name: '',
                uploadParams: {
                    type_id: 0,
                    type: 1
                },
                total: 0,//默认数据总数
                pagesize: 10,//每页的数据条数
                currentPage: 1,//默认开始页面
                dialogVisible: false, //是否显示模态框
                groupItem: [], //分类组
                attachments: [],
                groupListLoading: true,
                lecturerId: 0,
                rightLoading: true,
                new_type_name: '',
                fileList: [],//文件列表
                selectedArr: {},
                attachmentList: '12',
                uploadData: {
                    key: "",
                    token: ""
                },
                ali: {},
                tx: {},
                // 七牛云上传储存区域的上传域名（华东、华北、华南、北美、东南亚）
                upload_url: "",
                // 七牛云返回储存图片的子域名
                upload_addr: "",
                file: {
                    Url: '',
                    path: '',
                    name: '',
                    size: 0
                },
                att_type: '',
            }
        },
        created() {
            _img = this;
            //_img.init();
            // console.log(_img)
        },
        methods: {
            getQiniuToken: function () {
                const _img = this;
                axios.get(appUrl + 'do=getQiniuToken')
                    .then(function (res) {
                        if (res.data) {
                            _img.uploadData.token = res.data;
                        } else {
                            this.$message.error("七牛云配置错误");
                        }
                    })
            },
            init() {
                _img = this;

                axios.get("{{route('zds.setting.storage')}}")
                    .then(function (res) {
                        if (res.data) {
                            // console.log(11111111111,res.data.data.type)
                            var type = res.data.data.type
                            _img.att_type = type
                            // console.log(att_type)
                            if (type == 1) {
                                // 本地服务器上传
                                _img.upload_url = "{php echo $this->createWebUrl('UploadFile')}"
                                _img.upload_addr = "{$_W['attachurl_local']}"
                            }
                            if (type == 2) {
                                // 腾讯云上传
                                _img.tx = JSON.parse(res.data.data.tengxun);
                                console.log(_img.tx);
                                _img.upload_addr = _img.tx.tx_url
                            }
                        } else {
                        }
                    })
            },
            //上传前判断
            beforeUpload: function (file) {
                var date = new Date();
                // filePath = 'yzd_edu/image/' + date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getTime() + this.getRandom(111111, 999999) + '.' + file.name.split(".")[1]
                filePath = 'zds/image/' + date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getTime() + this.getRandom(111111, 999999) + '.' + file.name.substring(file.name.lastIndexOf(".")+1)
                this.uploadData.key = filePath

                file_type = file.name.substring(file.name.lastIndexOf(".")+1)
                const isIMG =
                    file_type === 'jpg' || //jpg
                    file_type === 'jpeg' || //jpeg
                    file_type === 'png' || //png
                    file_type === 'gif' || //gif
                    file_type === 'JPG' || //jpg
                    file_type === 'JPEG' || //jpeg
                    file_type === 'PNG' || //png
                    file_type === 'GIF' //gif
                // const isIMG =
                //     file.type === 'image/jpg' ||
                //     file.type === 'image/jpeg' ||
                //     file.type === 'image/png' ||
                //     file.type === 'image/gif'
                const isLt = file.size / 1024 / 1024 < 50
                if (!isIMG) {
                    this.$message.error("上传图片只支持jpg、jpeg、png格式!");
                    return false;
                }
                if (!isLt) {
                    this.$message.error("文件大小不能超过 50MB!");
                    return false;
                }
                if (_img.att_type == 2) {
                    var imgtxurl = filePath
                    if (!_img.cos) _img.cos = new COS({
                        SecretId: _img.tx.tx_secret_id,
                        SecretKey: _img.tx.tx_secret_key,
                    });
                    console.log('bucket='+_img.tx.tx_bucket);
                    _img.cos.putObject({
                        Bucket: _img.tx.tx_bucket,
                        Region: _img.tx.tx_region,
                        Key: filePath,
                        StorageClass: 'STANDARD',
                        Body: file,
                        onProgress: function (progressData) {
                            console.log(JSON.stringify(progressData));
                        }
                    }, function (err, data) {
                        console.log(err || data);
                        _img.handleSuccess({
                            key:imgtxurl,
                        }, {
                            name: file.name,
                            size: file.size
                        })
                    });
                }
            },
            handleError: function (res) {
                this.$message({
                    message: "上传失败",
                    duration: 2000,
                    type: "warning"
                });
            },
            handleSuccess: function (res, file) {

                if (_img.att_type == 1) {
                    this.file.Url = res.url
                    this.file.path = res.path;
                } else {
                    this.file.Url = this.upload_addr + '/' + res.key;
                    this.file.path = res.key;
                }
                this.file.name = file.name;
                this.file.size = file.size;
                this.addAttachment();//保存到数据库

            },
            addAttachment() {
                axios.post("{{route('attachment.store')}}",
                    Qs.stringify({
                        attachment_group_id: this.uploadParams.type_id,
                        url: this.file.Url,
                        path: this.file.path,
                        title: this.file.name,
                        size: this.file.size,
                        type: 1,
                        uid: _img.lecturerId
                    }))
                    .then(function (res) {
                        _img.getAttachment_List()//刷新列表
                        _img.$message.success("上传成功");

                        // console.log(_img)
                        // _img.fileList = fileList.slice(1);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            getStyle(index) {
                var currentPage = _img.currentPage, s = _img.selectedArr[_img.type == 'img' ? 'danxuan' : 'item' + index + '' + currentPage]
                return s && s.page == currentPage && s.index == index ? 'border:1px solid #04ba8f;background:#e6f8f4' : ''
            },
            modalClose() {
                _img.selectedArr = {}
            },
            dialogOpened() {
                this.getgroupItem();
                this.getAttachment_List();
                this.init();
            },
            handProgress() {
                _img.rightLoading = true
            },
            //获取左边分组
            getgroupItem() {
                _img = this
                axios.get("{{route('attachment_group.list')}}?type=1")
                    .then(function (res) {
                        if (res.data.data) {
                            _img.groupItem = res.data.data
                            _img.groupListLoading = false
                            _img.visible = false
                        } else {
                            _img.groupListLoading = false
                        }
                    })
            },
            //获取附件列表
            getAttachment_List() {
                _img.rightLoading = true
                axios.get("{{route('attachment.list')}}",{
                    params:{
                        type:1,
                        group_id:_img.uploadParams.type_id,
                        page:_img.currentPage,
                    }
                }).then(function (res) {
                        if (res.data.data) {
                            _img.attachments = res.data.data
                            _img.total = res.data.total
                            _img.rightLoading = false

                        } else {
                            _img.rightLoading = false
                        }
                    })
            },
            //新建分组
            addNewAttType() {
                _img.groupListLoading = true
                axios.post("{{route('attachment_group.store')}}",
                    Qs.stringify({
                        title: _img.new_type_name,
                        type: 1,
                        lecturer_id: _img.lecturerId
                    }))
                    .then(function (res) {
                        _img.visible = false
                        _img.$message({
                            message: res.data.message,
                            type: 'success',
                            duration: 500,
                            onClose: () => {
                                _img.new_type_name = ''
                                _img.getgroupItem()
                            },
                        })
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            search(atta_name) {
                _img.currentPage = 1
                _img.getAttachment_List();
            },
            //切换分页
            current_change(currentPage) {
                _img.currentPage = currentPage;
                _img.getAttachment_List();
            },
            //删除分类
            deleteType(id) {
                // console.log(id)
                _img.groupListLoading = true
                axios.post("{{route('attachment_group.delete')}}",
                    Qs.stringify({
                        id: id
                    }))
                    .then(function (res) {
                        _img.visible = false
                        _img.$message({
                            message: res.data.message,
                            type: 'success',
                            duration: 500,
                            onClose: () => {
                                _img.getgroupItem()
                            },
                        })
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            //删除图片
            deleteItem(id,oss_type,path) {
                _img.rightLoading = true
                axios.post("{{route('attachment.delete')}}",
                    Qs.stringify({
                        id: id
                    }))
                    .then(function (res) {
                        _img.$message({
                            message: res.data.message,
                            type: 'success',
                            duration: 500,
                            onClose: () => {
                                _img.getAttachment_List()
                            },
                        })
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                if (oss_type == 1 || (oss_type == 0 &&_img.att_type == 1)) {
                    axios.post(appUrl + 'do=Deletefile',
                        Qs.stringify({
                            type: 'bendi',
                            path:path
                        }))
                        .then(function (res) {
                            console.log(res)
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
                if (oss_type == 2 || (oss_type == 0 &&_img.att_type == 2)) {
                    if (!_img.cos) _img.cos = new COS({
                        SecretId: _img.tx.tx_secretid,
                        SecretKey: _img.tx.tx_secretkey,
                    });
                    _img.cos.deleteObject({
                        Bucket: _img.tx.tx_bucket,
                        Region: _img.tx.tx_region,
                        Key: path,
                    }, function (err, data) {
                        console.log(err || data);
                    });

                }
                if (oss_type == 3 || (oss_type == 0 &&_img.att_type == 3)) {
                    axios.post(appUrl + 'do=Deletefile',
                        Qs.stringify({
                            type: 'qiniu',
                            path:path
                        }))
                        .then(function (res) {
                            console.log(res)
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }

                if (oss_type == 4 || (oss_type == 0 &&_img.att_type == 4)) {
                    if (!_img.client) _img.client = new OSS({
                        region: _img.ali.al_region,
                        accessKeyId: _img.ali.al_accesskey,
                        accessKeySecret: _img.ali.al_secretkey,
                        bucket: _img.ali.al_bucket
                    });
                    _img.client.delete(path).then(function (result) {
                        console.log(result)
                    }).catch(function (err) {
                        console.log(err)
                    });
                    console.log(filePath)
                }
            },
            getRandom(start, end, fixed = 0) {
                let differ = end - start
                let random = Math.random()
                return (start + differ * random).toFixed(fixed)
            },
            selected(id) {
                console.log(id);
                _img.rightLoading = true
                _img.uploadParams.type_id = id
                _img.getAttachment_List();
            },
            selectedItem(index, url) {
                var istype = _img.type == 'img', str = istype ? 'danxuan' : 'item' + index + '' + _img.currentPage, s = _img.selectedArr[str]
                if (s && s.index == index) {
                    _img.$delete(_img.selectedArr, str);
                } else {
                    if (istype) {
                        _img.$delete(_img.selectedArr, str);
                    }
                    _img.$set(_img.selectedArr, str, {
                        page: _img.currentPage,
                        index,
                        url
                    })
                }
            },
            selectedItems() {
                _img = this;
                var item = _img.selectedArr, str = '', arr = []
                for (const key in item) {
                    str += str ? ',' + item[key].url : item[key].url
                    arr.push(item[key].url)
                }
                _img.$emit('attachment', {
                    str,
                    arr
                })
                _img.dialogVisible = false
            }
        },
    })
</script>

<style>

    .el-upload-list{
        z-index: 9999999 !important;
    }


    input[type=file] {
        display: none;
    }

    .delete {
        position: absolute;
        top: -7px;
        right: -7px;
        font-size: 16px;
        display: none;
    }

    .app-attachment-item:hover .delete {
        display: block;
    }

    .el-upload-list {
        position: absolute;
        max-height: 355px;
        /* overflow-y: auto; */
        width: 252px;
        background: #fff;
        position: absolute;
        z-index: 1;
        border-radius: 4px;
        box-shadow: 0 1px 6px rgba(0, 0, 0, .2);
        right: 0;
    }
</style>