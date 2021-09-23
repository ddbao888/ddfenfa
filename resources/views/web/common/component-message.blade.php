@verbatim
    <template id="component-members" v-cloak>
        <el-dialog title="选择会员" :visible.sync="dialogVisible" width="960px" @close='memberModalClose' @opened="memberDialogOpened">
            <div>
                <el-form label-width="80px" @submit.native.prevent>
                    <el-form-item label="标题">
                        <el-input placeholder="标题" suffix-icon="el-icon-search" v-model="nickName" size="small" @keyup.enter.native="search(title)" @input="search(title)"> </el-input>
                    </el-form-item>
                    <el-form-item label="描述">
                        <el-input type="textarea" :rows="2"></el-input>
                    </el-form-item>
                    <el-form-item label="接收会员">

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