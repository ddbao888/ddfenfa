
    <template id="component-wentiku" v-cloak>
        <el-dialog title="问题库" :visible.sync="dialogVisible" width="960px" @close='modalClose' @opened="dialogOpened">
            <div>
                <el-form label-width="80px" @submit.native.prevent  :inline="true" class="demo-form-inline">
                    <el-form-item>
                        <el-input placeholder="标题名称" suffix-icon="el-icon-search" v-model="title" size="small" @keyup.enter.native="search(title)" @input="search(title)"> </el-input>
                    </el-form-item>
                </el-form>
                <el-table :data="data" @selection-change="handleSelectionChange" stri v-loading="tableLoading">
                    <el-table-column label="板块" min-width="15%">
                        <template slot-scope="scope">
                            <div>@{{ scope.row.question_title }}</div>
                        </template>
                    </el-table-column>
                    <el-table-column label="题库标题" min-width="20%">
                        <template slot-scope="scope">
                            <div>@{{ scope.row.title }}</div>
                        </template>
                    </el-table-column>
                    <el-table-column label="类型" min-width="15%">
                        <template slot-scope="scope">
                            <el-tag type="primary" size="medium" v-if="scope.row.type ==1" title="点击查看">文字</el-tag>
                            <el-tag type="primary" size="medium" v-if="scope.row.type ==2" title="点击查看">图片</el-tag>
                            <el-tag type="primary" size="medium" v-if="scope.row.type ==3" title="点击收听">语音</el-tag>
                            <el-tag type="primary" size="medium" v-if="scope.row.type ==4" title="点击观看">视频</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column label="答案选项" prop="answer_items" min-width="15%">

                    </el-table-column>
                    <el-table-column label="答案" prop="answer" size="small" min-width="15%">
                    </el-table-column>
                    <el-table-column label="简易度"  size="small" min-width="15%">
                        <template slot-scope="scope">
                            <el-tag type="primary" size="medium" v-if="scope.row.easy == 1">简单</el-tag>
                            <el-tag type="warning" size="medium" v-if="scope.row.easy == 2">一般</el-tag>
                            <el-tag type="danger" size="medium" v-if="scope.row.easy == 3">复杂</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column label="状态" prop="status"  min-width="10%">
                        <template slot-scope="scope">
                            <el-tag type="warning" v-if="scope.row.status == 1 " size="small">待审</el-tag>
                            <el-tag type="primary" v-if="scope.row.status == 2 " size="small">通过</el-tag>
                            <el-tag type="danger" v-if="scope.row.status == -1 " size="small">未通过</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column label="总计" prop="view" min-width="10%">

                    </el-table-column>
                    <el-table-column label="创建时间" prop="created_at" min-width="15%">

                    </el-table-column>
                    <el-table-column min-width="20%" label="操作" align="center">
                        <template slot-scope="scope" >
                            <el-button type="danger" circle title="删除" @click="setStatus(scope.row.id,-1)" size='mini' icon="el-icon-delete"></el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <div class="btn-toolbar">
                    <el-row>
                        <el-col :span="8">
                            <el-button size="small" type="danger" :loading="btnLoading" @click="batchSetStatus(-1)":disabled="batchBtn">批量删除</el-button>
                        </el-col>
                        <el-col :span="16">
                            <el-pagination background layout="prev, pager, next" :total="total" :page-size="pageSize"
                                           @current-change="current_change" style="text-align:right">
                            </el-pagination>
                        </el-col>
                    </el-row>
                </div>
                <!-- 分页代码结束 -->
            </div>
            <span slot="footer" class="dialog-footer">
            <el-button @click="dialogVisible = false" size='small'>关 闭</el-button>
        </span>
        </el-dialog>
    </template>

<script>
    Vue.component('component-wentiku', {
        template: '#component-wentiku',
        props: {
            id: {
                type: Number,
                default: '0'
            },
        },
        data() {
            return {
                visible: false,
                btnLoading:false,
                tableLoading:false,
                title:'',
                tableDatas:[],
                batchBtn:true,
                page:1,
                pageSize:15,
                total:0,
                data:[],
                dialogVisible: false, //是否显示模态框
            }
        },
        created() {
            _wt = this;
            _wt.init();
        },
        methods: {
            init() {
                _wt.tableLoading = true;
                axios.get("{{route("question_item.list")}}", {
                    params: {
                        page: _wt.page,
                        title: _wt.title,
                        question_id: _wt.id,
                        status:2
                    }
                })
                    .then(function (res) {
                        if (res.data) {

                            // console.log(res.data.courseList)
                            _wt.data = res.data.data
                            _wt.total = res.data.total
                            _wt.tableLoading = false

                        } else {
                            _good.tableLoading = false
                        }
                    })
            },
            modalClose() {

            },
            dialogOpened() {
                _wt.init();
            },
            handleSelectionChange(val)
            {
                console.log(val);
                let _wt = this;
                if(val.length>0)
                {
                    _wt.batchBtn = false;
                } else {
                    _wt.batchBtn = true;
                }
                _wt.tableDatas = val;

            },
            //切换分页
            current_change: function (currentPage) {
                _wt.page = currentPage;
                _wt.init();
            },
            //搜索
            search() {
                _wt.page = 1;
                _wt.cardLoading = true
                _wt.init();
            },
            selectedItems() {

            },
            batchSetStatus(status) {
                _wt.btnLoading = true;
                var ids = [];
                _wt.tableDatas.forEach((row)=>{
                    ids.push(row.id);
                })
                
                axios.post("{{route('question_item.status')}}",Qs.stringify({
                    id:ids,
                    status:status
                })).then(function(resp){
                    if(resp.data.status == 'success'){
                        _wt.$message({
                            message:'操作成功!',
                            type:'success',
                            duration:1000,
                        })
                    } else {
                        _wt.$message({
                            message:resp.data.msg,
                            type:'warning',
                            duration:1000
                        })
                    }
                    _wt.btnLoading = false;
                    _wt.init();
                    //_wt.batchBtn = false;
                }).catch(()=>{
                    _wt.$message({
                        message:'系统错误',
                        type:'error',
                        duration:1000
                    })
                })
            },
            setStatus(id, status)
            {
                _wt.$confirm("确认要删除吗?", "提示").then(()=>{
                    axios.post("{{route('question_item.status')}}",
                        Qs.stringify({id:id,status:status}))
                        .then((resp)=>{
                            if(resp.data.status == 'success'){
                                _wt.$message.success('删除成功!')
                            } else {
                                _wt.$message.warning(resp.data.msg)
                            }
                            _wt.init();
                        }).catch(()=>{
                        _wt.$message.error('系统错误');
                    })
                })
            },
            remove(id)
            {
                _wt.$confirm("确认要彻底删除吗?","提示").then(()=>{
                    axios.post("{{route('question_item.delete')}}", {id:id}).then((resp)=>{
                        if(resp.data.status == 'success') {
                            _wt.$message.success('删除成功!');
                            _wt.init();
                        } else {
                            _wt.$message.warning(resp.data.msg);
                        }
                    }).catch(()=>{
                        _wt.$message.error('系统错误!')
                    })
                })
            },
            batchDelete()
            {
                _wt.btnLoading = true;
                var ids = [];
                _wt.tableDatas.forEach((row)=>{
                    ids.push(row.id);
                })
                _wt.$confirm('确认要删除吗？', '提示!').then(()=>{
                    axios.post("{{route('question_item.delete')}}",Qs.stringify({
                        id:ids
                    })).then(function(resp){
                        if(resp.data.status == 'success'){
                            _wt.$message({
                                message:'删除成功!',
                                type:'success',
                                duration:1000,
                            })
                        } else {
                            _wt.$message({
                                message:resp.data.msg,
                                type:'warning',
                                duration:1000
                            })
                        }
                        _wt.btnLoading=false;
                        _wt.batchBtn = false;
                        _wt.init();
                    }).catch(()=>{
                        _wt.$message({
                            message:'系统错误',
                            type:'error',
                            duration:1000
                        })
                    })
                })

            }
        },
    })
</script>
<style>
    input[type=file] {
        display: none;
    }
    .good_img{
        background-size: cover;
        background-position: center center;
        width: 60px;
        height: 60px;
        border-radius: 0%
    }
</style>