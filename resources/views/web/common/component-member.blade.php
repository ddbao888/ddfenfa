@verbatim
    <template id="component-members" v-cloak>
        <el-dialog title="选择会员" :visible.sync="dialogVisible" width="960px" @close='memberModalClose' @opened="memberDialogOpened">
            <div>
                <el-form label-width="80px" @submit.native.prevent  :inline="true" class="demo-form-inline">
                    <el-form-item>
                        <el-input placeholder="会员昵称" suffix-icon="el-icon-search" v-model="nickName" size="small" @keyup.enter.native="search(title)" @input="search(title)"> </el-input>
                    </el-form-item>
                </el-form>
                <el-table :data="tableData" style="width: 100%" fit @selection-change="handleSelectionChange" v-loading="cardLoading">
                    <el-table-column
                            type="selection"
                            width="55">
                    </el-table-column>
                    <el-table-column prop='id' label="ID" min-width='5%'>
                    </el-table-column>
                    <el-table-column label="会员信息" min-width='25%'>
                        <template slot-scope="scope">
                            <div style="display: flex;">
                                <div style="padding-right: 10px;">
                                    <div class="good_img" :style="{ backgroundImage:'url('+ scope.row.avatar +')' }">
                                    </div>
                                </div>
                                <div style="line-height: 30px;">
                                    <div>{{scope.row.nick_name}}</div>
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                    <!-- <el-table-column prop='lecturer_name' align="center" label="讲师" min-width='10%'>
                    </el-table-column> -->
                </el-table>
                <!-- 分页代码开始 -->
                <div @click="currentChange">加载更多</div>
                <!-- 分页代码结束 -->
            </div>
            <span slot="footer" class="dialog-footer">
            <el-button @click="dialogVisible = false" size='small'>取 消</el-button>
            <el-button type="primary" @click="selectedItems()" size='small'>确 定</el-button>
        </span>
        </el-dialog>
    </template>
@endverbatim
<script>
    Vue.component('component-members', {
        template: '#component-members',
        props: {
            type: {
                type: String,
                default: 'member'
            },
        },
        data() {
            return {
                visible: false,
                cardLoading: true,//loading
                tableData: [],//表格数据
                total: 0,//默认数据总数
                pagesize: 7,//每页的数据条数
                currentPage: 1,//默认开始页面
                nickName: '',//关键词
                tableDatas:[],
                dialogVisible: false, //是否显示模态框
            }
        },
        created() {
            _member = this;
            __member.init();
        },
        methods: {
            init() {
                axios.get("{{route("member.list")}}", {
                    page: _member.currentPage,
                    is_subscribe: 1,
                    nick_name:_member.nick_name
                })
                    .then(function (res) {
                        if (res.data) {
                            // console.log(res.data.courseList)
                            _member.tableData = res.data.data
                            _member.total = res.data.total
                            _member.cardLoading = false
                        } else {
                            _member.cardLoading = false
                        }
                    })
            },
            memberModalClose() {
                _member.list = []
            },
            memberDialogOpened() {
                _member.init();
            },
            handleSelectionChange(val) {
                console.log(val.length);
                if(val.length > 1) {
                    return _member.$message.error('最多只可以选择一个物品');
                }
                _member.tableDatas = val;
            },
            //切换分页
            currentChange: function (currentPage) {
                _member.currentPage = _member.currentPage +1;
                _member.init();
            },
            //搜索
            search() {
                _member.currentPage = 1;
                _member.cardLoading = true
                _member.init();
            },
            selectedItems() {
                list = _member.tableDatas
                _member.$emit('member', {
                    list
                })
                _member.dialogVisible = false
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