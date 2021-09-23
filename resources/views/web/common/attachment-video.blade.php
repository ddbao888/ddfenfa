@verbatim
<template id="attachment-video" v-cloak>
    <el-dialog title="选择视频" :visible.sync="dialogVisible" width="880px" @close='modalClose' @opened="dialogOpened">
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
                        <el-input placeholder="搜索视频名称" suffix-icon="el-icon-search" v-model="atta_name" size="small"
                                  @keyup.enter.native="search(atta_name)" @input="search(atta_name)"
                                  style="width: 30%;padding-left: 12px;"> </el-input>
                        <div style="float: right;padding: 0 10px 0 0;">
                            <!-- 七牛云 -->
                            <el-upload v-if="att_type=='3'" class="upload-demo" :action="upload_url" :multiple="true"
                                       :file-list="fileList" :on-success="handleSuccess" :on-error="handleError"
                                       :before-upload="beforeUpload" :data="uploadData" :on-progress="handProgress" :show-file-list="false">
                                <el-button size="small" type="primary">上传视频</el-button>
                            </el-upload>
                            <!-- 本地 -->
                            <el-upload v-if="att_type=='1'" class="upload-demo" name='upfile' :action="upload_url"
                                       :multiple="true" :file-list="fileList" :on-success="handleSuccess"
                                       :on-error="handleError" :before-upload="beforeUpload" :data="uploadData"
                                       :on-progress="handProgress" :show-file-list="false">
                                <el-button size="small" type="primary">上传视频</el-button>
                            </el-upload>
                            <!-- 阿里云 -->
                            <el-upload v-if="att_type=='2'" class="upload-demo" name='upfile' :multiple="true"
                                       action="" :file-list="fileList" :before-upload="beforeUpload"
                                       :on-progress="handProgress" :show-file-list="false">
                                <el-button size="small" type="primary">上传视频</el-button>
                            </el-upload>
                            <!-- 腾讯云 -->
                            <el-upload v-if="att_type=='4'" class="upload-demo" name='upfile' :multiple="true"
                                       action="" :file-list="fileList" :before-upload="beforeUpload"
                                       :on-progress="handProgress" :show-file-list="false">
                                <el-button size="small" type="primary">上传视频</el-button>
                            </el-upload>

                        </div>
                    </div>
                    <div class="app-attachment-list" style="display: flex;flex-flow: wrap;padding:10px">
                        <template v-for="(item, index) in attachments">
                            <div @click='selectedItem(index,item.url,item.title,item.time)' class="app-attachment-item"
                                 :style="getStyle(index)"
                                 style="margin-bottom: 5px;float: left;width: 270px;height: 68px;border-radius: 4px;border: 1px solid #ededed;margin-right: 10px;padding: 8px 12px;display: flex;position: relative;">
                                <div
                                        style="width: 48px;height: 60px;border-radius: 4px;display: block;text-align: center;line-height: 60px;">
                                    <audio @canplay="getDuration(item,index)" @timeupdate="updateTime" ref="audio"
                                           :src="item.url" style="width: 48px;height: 60px;" v-if="!item.time"></audio>
                                    <video :src="item.url" style="width: 60px;height: 60px;"></video>
                                    <i class="el-icon-caret-right"
                                       style="font-size: 26px;color: rgb(51, 51, 51);display: none;"></i>
                                </div>
                                <div
                                        style="padding-top: 10px;-webkit-box-flex: 1;-webkit-flex: 1;-ms-flex: 1;flex: 1;padding-left: 14px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;color: #333;">
                                    <div>{{ item.title }}</div>
                                    <div style="font-size: 12px;color: #999;">
                                        <div style="float: left;">
                                            {{ item.size }}
                                        </div>
                                        <div style="float: right;">
                                            {{ item.time }}
                                        </div>
                                    </div>
                                </div>
                                <i class="el-icon-error delete" @click='deleteItem(item.id,item.oss_type,item.path)'></i>
                            </div>
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
    var audio_length = 0, audio_index = 0
    Vue.component('attachment-video', {
        template: '#attachment-video',
        props: {
            type: {
                type: String,
                default: 'video'
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
                    type: 3
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
            _video = this;
            _video.init()
        },
        methods: {
            getQiniuToken: function () {
                const _video = this;
                axios.get(appUrl + 'do=getQiniuToken')
                    .then(function (res) {
                        if (res.data) {
                            _video.uploadData.token = res.data;
                        } else {
                            this.$message.error("七牛云配置错误");
                        }
                    })
            },
            init() {
                _video = this;
                axios.get("{{route('zds.setting.storage')}}")
                    .then(function (res) {
                        if (res.data) {
                            var type = res.data.data.type
                            _video.att_type = type

                            // console.log(type)
                            if (type == 1) {
                                // 本地服务器上传
                                _video.upload_url = "{php echo $this->createWebUrl('UploadFile')}"
                                _video.upload_addr = "{$_W['attachurl_local']}"
                            }
                            if (type == 2) {
                                // 腾讯云上传
                                _video.tx = JSON.parse(res.data.data.tengxun);
                                _video.upload_addr = _video.tx.tx_url
                            }
                            if (type == 3) {
                                _video.ali = res.data.data.aliyun
                                _video.upload_addr = res.data.data.aliyun.al_url
                            }
                            if (type == 4) {
                                // 七牛上传
                                _video.getQiniuToken();
                                _video.upload_url = res.data.data.qiniu.qn_region
                                _video.upload_addr = res.data.data.qiniu.qn_url
                            }

                        } else {
                        }
                    })
            },
            beforeUpload: function (file) {
                var date = new Date();
                // filePath = 'yzd_edu/video/' + date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getTime() + this.getRandom(111111, 999999) + '.' + file.name.split(".")[1]
                filePath = 'zds/video/' + date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getTime() + this.getRandom(111111, 999999) + '.' + file.name.substring(file.name.lastIndexOf(".")+1)
                this.uploadData.key = filePath

                file_type = file.name.substring(file.name.lastIndexOf(".")+1)
                const isAudio =
                    file_type === 'mp4' || //mp4
                    file_type === 'webm' || //webm
                    file_type === 'ogg' || //ogg
                    file_type === 'MP4' || //mp4
                    file_type === 'WEBM' || //webm
                    file_type === 'OGG' //ogg
                // const isAudio =
                //     file.type === 'video/mp4' ||
                //     file.type === 'video/webm' ||
                //     file.type === 'video/ogg'
                const isLt = file.size / 1024 / 1024 < 2048
                if (!isAudio) {
                    this.$message.error("文件只支持mp4、webm、ogg格式!");
                    return false;
                }
                if (!isLt) {
                    this.$message.error("文件大小不能超过 2GB!");
                    return false;
                }
                if (_video.att_type == 2) {
                    //腾讯云
                    var imgtxurl = filePath
                    if (!_video.cos) _video.cos = new COS({
                        SecretId: _video.tx.tx_secret_id,
                        SecretKey: _video.tx.tx_secret_key,
                    });
                    _video.cos.putObject({
                        Bucket: _video.tx.tx_bucket,
                        Region: _video.tx.tx_region,
                        Key: filePath,
                        StorageClass: 'STANDARD',
                        Body: file,
                        onProgress: function (progressData) {
                            // console.log(JSON.stringify(progressData));
                        }
                    }, function (err, data) {
                        _video.handleSuccess({
                            key: imgtxurl,
                        }, {
                            name: file.name,
                            size: file.size
                        }, _video.fileList)
                    });


                }
                if (_video.att_type == 4) {

                    if (!_video.client) _video.client = new OSS({
                        region: _video.ali.al_region,
                        accessKeyId: _video.ali.al_accesskey,
                        accessKeySecret: _video.ali.al_secretkey,
                        bucket: _video.ali.al_bucket
                    });
                    _video.client.multipartUpload(filePath, file).then(function (result) {
                        console.log(result)
                        _video.handleSuccess({
                            key: result.name,
                        }, {
                            name: file.name,
                            size: file.size
                        })
                    }).catch(function (err) {
                    });
                }
            },
            getRandom(start, end, fixed = 0) {
                let differ = end - start
                let random = Math.random()
                return (start + differ * random).toFixed(fixed)
            },
            handleSuccess: function (res, file, fileList) {
                if (_video.att_type == 1) {
                    this.file.Url = res.url
                    this.file.path = res.path;
                } else {
                    this.file.Url = this.upload_addr + '/' + res.key;
                    this.file.path = res.key;
                }
                this.file.name = file.name;
                this.file.size = file.size;
                this.addAttachment();//保存到数据库
                // _video.fileList = fileList.slice(1);
            },
            getDuration(item, index) {
                if (audio_length == 0) {
                    for (let i = 0; i < this.attachments.length - 1; i++) {
                        if (!this.attachments[i].time) {
                            audio_length++
                        }
                    }
                }
                if (item.time) return;
                if (audio_length == audio_index) this.$forceUpdate();
                audio_index++
                var time = this.attachments[index].time
                time = this.$refs.audio[index].duration
                if (time > 60 && time < 60 * 60) {
                    time = parseInt(time / 60.0) + ":" + parseInt((parseFloat(time / 60.0) -
                        parseInt(time / 60.0)) * 60) + "";
                } else {
                    time = '00:' + time.toFixed(0)
                }
                this.attachments[index].time = time
            },
            updateTime(e) {
                this.currentTime = e.target.currentTime;  //获取audio当前播放时间
                console.log(this.currentTime)
            },
            getStyle(index) {
                var currentPage = _video.currentPage, s = _video.selectedArr[_video.type == 'video' ? 'danxuan' : 'item' + index + '' + currentPage]
                return s && s.page == currentPage && s.index == index ? 'border:1px solid #04ba8f;background:#e6f8f4' : ''
            },
            modalClose() {
                _video.selectedArr = {}
            },
            dialogOpened() {
                this.getgroupItem();
                this.getAttachment_List();
            },

            addAttachment() {
                axios.post("{{route('attachment.store')}}",
                    Qs.stringify({
                        attachment_group_id: this.uploadParams.type_id,
                        url: this.file.Url,
                        path: this.file.path,
                        title: this.file.name,
                        size: this.file.size,
                        type: 3,
                        lecturer_id: _video.lecturerId
                    }))
                    .then(function (res) {
                        _video.getAttachment_List()//刷新列表
                        _video.$message.success("上传成功");
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            handProgress() {
                _video.rightLoading = true
            },
            handleError: function (res) {
                this.$message({
                    message: "上传失败",
                    duration: 2000,
                    type: "warning"
                });
            },
            //获取左边分组
            getgroupItem() {
                _video = this
                axios.get("{{route('attachment_group.list')}}?type=3")
                    .then(function (res) {
                        if (res.data.data) {
                            _video.groupItem = res.data.data
                            _video.groupListLoading = false
                            _video.visible = false
                        } else {
                            _video.groupListLoading = false
                        }
                    })
            },
            //获取附件列表
            getAttachment_List() {
                _video.rightLoading = true
                axios.get("{{route('attachment.list')}}?type=3")
                    .then(function (res) {
                        if (res.data.data) {
                            _video.attachments = res.data.data
                            audio_length = 0
                            audio_index = 0 //初始化当前页未初始化音频时间
                            for (let i = 0; i < _video.attachments.length; i++) {
                                _video.attachments[i].size = (_video.attachments[i].size / 1024 / 1024).toFixed(2) + 'MB'
                            }
                            _video.total = res.data.total
                            _video.rightLoading = false

                        } else {
                            _video.rightLoading = false
                        }
                    })
            },
            //新建分组
            addNewAttType() {
                _video.groupListLoading = true
                axios.post("{{route('attachment_group.store')}}",
                    Qs.stringify({
                        title: _video.new_type_name,
                        type: 3,
                        lecturer_id: _video.lecturerId
                    }))
                    .then(function (res) {
                        _video.visible = false
                        _this.$message({
                            message: res.data.message,
                            type: 'success',
                            duration: 500,
                            onClose: () => {
                                _video.new_type_name = ''
                                _video.getgroupItem()
                            },
                        })
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            search(atta_name) {
                _video.currentPage = 1
                _video.getAttachment_List();
            },
            //切换分页
            current_change(currentPage) {
                _video.currentPage = currentPage;
                _video.getAttachment_List();
            },
            //删除分类
            deleteType(id) {
                // console.log(id)
                _video.groupListLoading = true
                axios.post("{{route('attachment_group.delete')}}",
                    Qs.stringify({
                        id: id
                    }))
                    .then(function (res) {
                        _video.visible = false
                        _this.$message({
                            message: res.data.message,
                            type: 'success',
                            duration: 500,
                            onClose: () => {
                                _video.getgroupItem()
                            },
                        })
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            //删除附件
            deleteItem(id,oss_type,path) {
                _video.rightLoading = true

                axios.post("{{route('attachment.delete')}}",
                    Qs.stringify({
                        id: id
                    }))
                    .then(function (res) {
                        _this.$message({
                            message: res.data.message,
                            type: 'success',
                            duration: 500,
                            onClose: () => {
                                _video.getAttachment_List()
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
            selected(id) {
                _video.rightLoading = true
                _video.uploadParams.type_id = id
                _video.getAttachment_List();
            },
            selectedItem(index, url, title, time) {
                var istype = _video.type == 'video', str = istype ? 'danxuan' : 'item' + index + '' + _video.currentPage, s = _video.selectedArr[str]
                if (s && s.index == index) {
                    _video.$delete(_video.selectedArr, str);
                } else {
                    if (istype) {
                        _video.$delete(_video.selectedArr, str);
                    }
                    _video.$set(_video.selectedArr, str, {
                        page: _video.currentPage,
                        index,
                        url,
                        title,
                        time
                    })
                }
            },
            selectedItems() {
                _video = this;
                var item = _video.selectedArr, str = '', arr = [], video = []
                for (const key in item) {
                    str += str ? ',' + item[key].url : item[key].url
                    arr.push(item[key].url)
                }
                video.src = str
                video.src_title = _video.selectedArr.danxuan.title
                video.src_time = _video.selectedArr.danxuan.time
                _video.$emit('video', {
                    video,
                    arr
                })
                _video.dialogVisible = false
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