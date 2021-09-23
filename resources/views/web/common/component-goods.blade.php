@verbatim
<template id="component-goods" v-cloak>
    <el-dialog title="选择商品" :visible.sync="dialogVisible" width="960px" @close='goodModalClose' @opened="goodDialogOpened">
        <div>
            <el-form label-width="80px" @submit.native.prevent  :inline="true" class="demo-form-inline">
                <el-form-item>
                    <el-input placeholder="商品名称" suffix-icon="el-icon-search" v-model="title" size="small" @keyup.enter.native="search(title)" @input="search(title)"> </el-input>
                </el-form-item>
            </el-form>
            <el-table :data="tableData" style="width: 100%" fit @selection-change="handleSelectionChange" v-loading="cardLoading">
                <el-table-column
                        type="selection"
                        width="55">
                </el-table-column>
                <el-table-column prop='id' label="ID" min-width='5%'>
                </el-table-column>
                <el-table-column label="商品信息" min-width='25%'>
                    <template slot-scope="scope">
                        <div style="display: flex;">
                            <div style="padding-right: 10px;">
                                <div class="good_img" :style="{ backgroundImage:'url('+ scope.row.pic +')' }">
                                </div>
                            </div>
                            <div style="line-height: 30px;">
                                <div>{{scope.row.good_name}} ({{ scope.row.type_name }})</div>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <!-- <el-table-column prop='lecturer_name' align="center" label="讲师" min-width='10%'>
                </el-table-column> -->
                <el-table-column label="金额" align="center" min-width='10%'>
                    <template slot-scope="scope">
                        <span style="color: rgb(245, 108, 108)">{{scope.row.money}} 元</span>
                    </template>
                </el-table-column>
                <el-table-column prop='sales' align="center" label="获奖人数" min-width='10%'>
                </el-table-column>
            </el-table>
            <!-- 分页代码开始 -->
            <el-pagination background layout="prev, pager, next" :total="total" :page-size="pagesize"
                           @current-change="current_change">
            </el-pagination>
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
    Vue.component('component-goods', {
        template: '#component-goods',
        props: {
            type: {
                type: String,
                default: 'good'
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
                title: '',//关键词
                tableDatas:[],
                dialogVisible: false, //是否显示模态框
            }
        },
        created() {
            _good = this;
            _good.init();
        },
        methods: {
            init() {
                axios.get("{{route("good.list")}}", {
                        page: _good.currentPage,
                        title: _good.title,
                })
                    .then(function (res) {
                        if (res.data) {
                            // console.log(res.data.courseList)
                            _good.tableData = res.data.data
                            _good.total = res.data.total
                            _good.cardLoading = false
                        } else {
                            _good.cardLoading = false
                        }
                    })
            },
            goodModalClose() {
                _good.list = []
            },
            goodDialogOpened() {
                _good.init();
            },
            handleSelectionChange(val) {
                console.log(val.length);
                if(val.length > 1) {
                    return _this.$message.error('最多只可以选择一个物品');
                }
                _good.tableDatas = val;
            },
            //切换分页
            current_change: function (currentPage) {
                _good.currentPage = currentPage;
                _good.init();
            },
            //搜索
            search() {
                _good.currentPage = 1;
                _good.cardLoading = true
                _good.init();
            },
            selectedItems() {
                list = _good.tableDatas
                _good.$emit('good', {
                    list
                })
                _good.dialogVisible = false
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