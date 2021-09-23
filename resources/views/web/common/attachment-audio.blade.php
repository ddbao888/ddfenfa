@verbatim
<template id="attachment-audio" v-cloak>
    <el-dialog title="选择音频" :visible.sync="dialogVisible" width="880px" @close='modalClose' @opened="dialogOpened">
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
            <div flex="dir:top" class="attachment_img_right" v-loading="rightLoading"element-loading-text="请求加载中">
                <el-scrollbar class="scrollbar">

                    <div style="padding: 10px;">
                        <el-input placeholder="搜索音频名称" suffix-icon="el-icon-search" v-model="atta_name" size="small"
                                  @keyup.enter.native="search(atta_name)" @input="search(atta_name)"
                                  style="width: 30%;padding-left: 12px;"> </el-input>
                        <div style="float: right;padding: 0 10px 0 0;">
                            <!-- 七牛云 -->
                            <el-upload v-if="att_type=='3'" class="upload-demo" :action="upload_url" :multiple="true" :file-list="fileList"
                                       :on-success="handleSuccess" :on-error="handleError" :before-upload="beforeUpload" :data="uploadData" :on-progress="handProgress" :show-file-list="false">
                                <el-button size="small" type="primary">上传音频</el-button>
                            </el-upload>
                            <!-- 本地 -->
                            <el-upload v-if="att_type=='1'" class="upload-demo" name='upfile' :action="upload_url" :multiple="true"
                                       :file-list="fileList" :on-success="handleSuccess" :on-error="handleError"
                                       :before-upload="beforeUpload" :data="uploadData" :on-progress="handProgress" :show-file-list="false">
                                <el-button size="small" type="primary">上传音频</el-button>
                            </el-upload>
                            <!-- 阿里云 -->
                            <el-upload v-if="att_type=='2'" class="upload-demo" name='upfile'  :multiple="true" action=""
                                       :file-list="fileList"
                                       :before-upload="beforeUpload"  :on-progress="handProgress" :show-file-list="false">
                                <el-button size="small" type="primary">上传音频</el-button>
                            </el-upload>
                            <!-- 腾讯云 -->
                            <el-upload v-if="att_type=='4'" class="upload-demo" name='upfile' :multiple="true"
                                       action="" :file-list="fileList" :before-upload="beforeUpload"
                                       :on-progress="handProgress" :show-file-list="false">
                                <el-button size="small" type="primary">上传音频</el-button>
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
                                    <!-- <audio :src="item.url" style="width: 48px;height: 60px;"></audio> -->

                                    <audio @canplay="getDuration(item,index)" @timeupdate="updateTime" ref="audio"
                                           :src="item.url" style="width: 48px;height: 60px;" v-if="!item.time"></audio>

                                    <i class="el-icon-caret-right" style="font-size: 26px;color: rgb(51, 51, 51);"></i>
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
    // attachment-audio
    var audio_length = 0, audio_index = 0
    Vue.component('attachment-audio', {
        template: '#attachment-audio',
        props: {
            type: {
                type: String,
                default: 'audio'
            },
            op: {
                type: String
            }
        },
        // props: ['srcType'],
        data() {
            return {
                visible: false,
                atta_name: '',
                uploadParams: {
                    type_id: 0,
                    type: 2
                },
                // type_id:'',//分类ID
                total: 0,//默认数据总数
                pagesize: 10,//每页的数据条数
                currentPage: 1,//默认开始页面
                dialogVisible: false, //是否显示模态框
                groupItem: [], //分类组
                attachments: [],
                groupListLoading: true,
                lecturerId:0,
                rightLoading: true,
                new_type_name: '',
                fileList: [],//文件列表
                selectedArr: {},
                attachmentList: '12',
                // srcType:''
                // upload_url:'',
                uploadData:{
                    key: "",
                    token: ""
                },
                ali:{},
                tx: {},
                // 七牛云上传储存区域的上传域名（华东、华北、华南、北美、东南亚）
                upload_url: "",
                // 七牛云返回储存图片的子域名
                upload_addr: "",
                file:{
                    Url:'',
                    path:'',
                    name:'',
                    size:0
                },
                att_type:'',
            }
        },
        created() {
            _audio = this;
            _audio.init()

        },
        methods: {
            getQiniuToken: function () {
                const _audio = this;
                axios.get(appUrl + 'do=getQiniuToken')
                    .then(function (res) {
                        if (res.data) {
                            _audio.uploadData.token = res.data;
                            // console.log(res.data)
                        } else {
                            this.$message.error("七牛云配置错误");
                        }
                    })
            },
            init() {
                _audio = this;


                axios.get("{{route('zds.setting.storage')}}")
                    .then(function (res) {
                        if (res.data) {
                            var type = res.data.data.type
                            _audio.att_type = type
                            if (type == 1) {
                                // 本地服务器上传
                                _audio.upload_url = "{{route('file.upload')}}"
                                _audio.upload_addr = "{{env('APP_URL')}}/"
                            }
                            if (type == 2) {
                                // 腾讯云上传
                                _audio.tx =  JSON.parse(res.data.data.tengxun);
                                _audio.upload_addr = _audio.tx.tx_url
                            }
                            if (type == 4) {
                                // 阿里云
                                _audio.ali = res.data.data.aliyun
                                _audio.upload_addr = res.data.data.aliyun.al_url
                            }
                            if (type == 3) {
                                // 七牛上传
                                _audio.getQiniuToken();
                                _audio.upload_url = res.data.data.qiniu.qn_region
                                _audio.upload_addr = res.data.data.qiniu.qn_url
                            }
                        } else {
                        }
                    })
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
                //

            },
            updateTime(e) {
                this.currentTime = e.target.currentTime;  //获取audio当前播放时间
                console.log(this.currentTime)
            },


            getStyle(index) {
                var currentPage = _audio.currentPage, s = _audio.selectedArr[_audio.type == 'audio' ? 'danxuan' : 'item' + index + '' + currentPage]
                return s && s.page == currentPage && s.index == index ? 'border:1px solid #04ba8f;background:#e6f8f4' : ''
            },
            modalClose() {
                _audio.selectedArr = {}
            },
            dialogOpened() {
                this.getgroupItem();
                this.getAttachment_List();
            },
            beforeUpload: function (file) {

                var date = new Date();
                // filePath = 'yzd_edu/audio/' + date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getTime() + this.getRandom(111111, 999999) + '.' + file.name.split(".")[1]
                filePath = 'zds/audio/' + date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getTime() + this.getRandom(111111, 999999) + '.' + file.name.substring(file.name.lastIndexOf(".")+1)
                this.uploadData.key = filePath

                file_type = file.name.substring(file.name.lastIndexOf(".")+1)
                const isAudio =
                    file_type === 'mp3' || //mp3
                    file_type === 'm4a' || //m4a
                    file_type === 'wav' || //wav
                    file_type === 'aac' || //aac
                    file_type === 'MP3' || //mp3
                    file_type === 'M4A' || //m4a
                    file_type === 'WAV' || //wav
                    file_type === 'AAC' //aac
                // const isAudio =
                //     file.type === 'audio/mp3' ||
                //     file.type === 'audio/m4a' ||
                //     file.type === 'audio/wav' ||
                //     file.type === 'audio/aac'
                const isLt = file.size / 1024 / 1024 < 2048
                if (!isAudio) {
                    this.$message.error("文件只支持mp3、m4a、wav格式!");
                    return false;
                }
                if (!isLt) {
                    this.$message.error("文件大小不能超过 2GB!");
                    return false;
                }
                if(_audio.att_type == 3){
                    if (!_audio.client) _audio.client = new OSS({
                        region:_audio.ali.al_region,
                        accessKeyId: _audio.ali.al_accesskey,
                        accessKeySecret: _audio.ali.al_secretkey,
                        bucket:_audio.ali.al_bucket
                    });
                    _audio.client.multipartUpload(filePath, file).then(function (result) {
                        console.log(result)
                        _audio.handleSuccess({
                            key:result.name,
                        },{
                            name:file.name,
                            size:file.size
                        })
                    }).catch(function (err) {
                    });
                }
                if (_audio.att_type == 2) {
                    var imgtxurl = filePath
                    if (!_audio.cos) _audio.cos = new COS({
                        SecretId: _audio.tx.tx_secret_id,
                        SecretKey: _audio.tx.tx_secret_key,
                    });
                    _audio.cos.putObject({
                        Bucket: _audio.tx.tx_bucket,
                        Region: _audio.tx.tx_region,
                        Key: filePath,
                        StorageClass: 'STANDARD',
                        Body: file,
                        onProgress: function (progressData) {
                            // console.log(JSON.stringify(progressData));
                        }
                    }, function (err, data) {
                        _audio.handleSuccess({
                            key: imgtxurl,
                        }, {
                            name: file.name,
                            size: file.size
                        }, _audio.fileList)
                    });
                }
            },
            getRandom(start, end, fixed = 0) {
                let differ = end - start
                let random = Math.random()
                return (start + differ * random).toFixed(fixed)
            },
            handleSuccess: function (res, file, fileList) {
                if(_audio.att_type == 1){
                    this.file.Url = res.url
                    this.file.path = res.path;
                }else{
                    this.file.Url = this.upload_addr + '/' + res.key;
                    this.file.path = res.key;
                }
                this.file.name = file.name;
                this.file.size = file.size;
                this.addAttachment();//保存到数据库
                _audio.fileList = fileList.slice(1);
            },
            addAttachment() {
                axios.post("{{route('attachment.store')}}",
                    Qs.stringify({
                        attachment_group_id: this.uploadParams.type_id,
                        url: this.file.Url,
                        path: this.file.path,
                        title: this.file.name,
                        size: this.file.size,
                        type: 2,
                        lecturer_id: _audio.lecturerId
                    }))
                    .then(function (res) {
                        _audio.getAttachment_List()//刷新列表
                        _audio.$message.success("上传成功");
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            handProgress() {
                _audio.rightLoading = true
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
                _audio = this
                axios.get("{{route('attachment_group.list')}}?type=2")
                    .then(function (res) {
                        if (res.data.data) {
                            _audio.groupItem = res.data.data
                            _audio.groupListLoading = false
                            _audio.visible = false
                        } else {
                            _audio.groupListLoading = false
                        }
                    })
            },
            //获取附件列表
            getAttachment_List() {
                _audio.rightLoading = true
                axios.get("{{route('attachment.list')}}?type=2")
                    .then(function (res) {
                        if (res.data.data) {
                            _audio.attachments = res.data.data
                            audio_length = 0
                            audio_index = 0 //初始化当前页未初始化音频时间
                            for (let i = 0; i < _audio.attachments.length; i++) {
                                _audio.attachments[i].size = (_audio.attachments[i].size / 1024 / 1024).toFixed(2) + 'MB'
                            }
                            _audio.total = res.data.total
                            _audio.rightLoading = false

                        } else {
                            _audio.rightLoading = false
                        }
                    })
            },
            //新建分组
            addNewAttType() {
                _audio.groupListLoading = true
                axios.post("{{route('attachment_group.store')}}",
                    Qs.stringify({
                        title: _audio.new_type_name,
                        type: 2,
                        lecturer_id: _audio.lecturerId
                    }))
                    .then(function (res) {
                        _audio.visible = false
                        _this.$message({
                            message: res.data.message,
                            type: 'success',
                            duration: 500,
                            onClose: () => {
                                _audio.new_type_name = ''
                                _audio.getgroupItem()
                            },
                        })
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            search(atta_name) {
                _audio.currentPage = 1
                _audio.getAttachment_List();
            },
            //切换分页
            current_change(currentPage) {
                _audio.currentPage = currentPage;
                _audio.getAttachment_List();
            },
            //删除分类
            deleteType(id) {
                // console.log(id)
                _audio.groupListLoading = true
                axios.post("{{route('attachment_group.delete')}}",
                    Qs.stringify({
                        id: id
                    }))
                    .then(function (res) {
                        _audio.visible = false
                        _this.$message({
                            message: res.data.message,
                            type: 'success',
                            duration: 500,
                            onClose: () => {
                                _audio.getgroupItem()
                            },
                        })
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            //删除附件
            deleteItem(id,oss_type,path) {
                _audio.rightLoading = true
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
                                _audio.getAttachment_List()
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
                _audio.rightLoading = true
                _audio.uploadParams.type_id = id
                _audio.getAttachment_List();
            },
            selectedItem(index, url, title, time) {
                // console.log(url,title)
                var istype = _audio.type == 'audio', str = istype ? 'danxuan' : 'item' + index + '' + _audio.currentPage, s = _audio.selectedArr[str]
                if (s && s.index == index) {
                    _audio.$delete(_audio.selectedArr, str);
                } else {
                    if (istype) {
                        _audio.$delete(_audio.selectedArr, str);
                    }
                    _audio.$set(_audio.selectedArr, str, {
                        page: _audio.currentPage,
                        index,
                        url,
                        title,
                        time
                    })
                }
            },
            selectedItems() {
                _audio = this;
                var item = _audio.selectedArr, str = '', arr = [], audio = []
                for (const key in item) {
                    str += str ? ',' + item[key].url : item[key].url
                    arr.push(item[key].url)
                }
                audio.src = str
                audio.src_title = _audio.selectedArr.danxuan.title
                audio.src_time = _audio.selectedArr.danxuan.time
                // console.log(video)
                _audio.$emit('audio', {
                    audio,
                    arr
                })
                _audio.dialogVisible = false
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