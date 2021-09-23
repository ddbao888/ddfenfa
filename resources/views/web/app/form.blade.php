<el-dialog title="应用编辑" :visible.sync="dialogVisible" width="40%">
    <el-form label-width="100px" :model="form" ref="form" :rules="formRules">
        <el-form-item label="应用名称">
            <el-col :span="12">
                <el-input type="text" v-model="inForm.in_name"></el-input>
            </el-col>
        </el-form-item>
        <el-form-item label="Logo" prop="pic">

            <el-col :span="6" >
                <el-upload
                        class="avatar-uploader"
                        action="/web/img/upload"
                        :show-file-list="false"
                        ref="logo"
                        accept="image/png"
                        :before-upload="logoBeforeUpload"
                        :on-success="logoHandleSuccess">
                    <img v-if="logoUrl.length > 10" :src="logoUrl" class="avatar">
                    <i v-show="logoUrl =='' && logoFlag == false" class="el-icon-plus avatar-uploader-icon"></i>
                    <el-progress v-show="logoFlag == true" type="circle" :percentage="percent" style="margin-top: 20px"></el-progress>
                </el-upload>
            </el-col>
        </el-form-item>

    </el-form>
    <span slot="footer" class="dialog-footer">
    <el-button @click="dialogVisible = false">取 消</el-button>
    <el-button type="primary" @click="update" :loading="btnLoading" v-if="inForm.id">保 存</el-button>
  </span>
</el-dialog>