
@include('web.common.attachment-img')

<div class="web_content" id="app">
    <el-card class="box-card" style="margin-top:10px">

        <el-form label-width="150px" ref="form" :model="form">
            <el-form-item label="站点名称">
                <el-col :span="12">
                    <el-input type="text" v-model="form.site_name" size="small" suffix-icon="el-icon-coin"></el-input>
                </el-col>
            </el-form-item>
            <el-form-item label="站点图标">
                <el-col :span="12">
                    <el-image src="" @click="choiceImg"></el-image>
                </el-col>
            </el-form-item>
            <el-form-item label="网站icp">
                <el-col :span="12">
                    <el-switch v-model="form.icp"></el-switch>
                </el-col>
            </el-form-item>
            <el-form-item label="关键词">
                <el-col :span="12">
                    <el-input type="textarea" v-model="form.keyword" rows="4"></el-input>
                </el-col>
            </el-form-item>
            <el-form-item label="前端LOGO">
                <template slot='label'>
                    <span>前端LOGO</span>
                </template>
                <el-col :span="12">
                    <el-input type="text" size="small" v-model="form.basic.word_filter"  placeholder="每个词用|进行分割"></el-input>
                </el-col>
            </el-form-item>

            <el-form-item label="">
                <el-button type="primary" @click="submit()" :loading="btnLoading">保存</el-button>
            </el-form-item>
        </el-form>
    </el-card>
    <attachment-img ref="img" type="img" @attachment="selectImgOk"></attachment-img>

<script>
    new Vue({
        el:'#app',
        data(){
            return{
                color:null,
                btnLoading:false,
                activeName:'base',
                form:{
                    type:'1',
                    storage_type:'1',
                    basic:{
                        open_notice:0
                    },
                    wx_mp:{
                        share_img:'/static/img/banner.npg'
                    },
                    baidu_mp:{
                        share_img:'',

                    },
                    douyin_mp:{
                        share_img:'',
                    },
                    storage:{},
                    wx_pay:{},
                    ali_pay:{},
                    third_pay:{}
                },
                rules:{
                    tx_secret_id:[{required:true, message: 'secretId不能为空', trigger:'blur'}],
                    tx_secret_key:[{required:true, message: 'secretkey不能为空', trigger:'blur'}],
                    tx_bucket:[{required:true, message: 'tx_bucket不能为空', trigger:'blur'}],
                    tx_region:[{required:true, message: '区域不能为空', trigger:'blur'}],
                    tx_url:[{required:true, message: 'tx_url不能为空', trigger:'blur'}],
                    mchid:[{required:true, message: '商户号不能为空', trigger:'blur'}],
                    signkey:[{required:true, message:'秘钥不能为空', trigger:'blur'}]
                },
                imgName:''
            }
        },
        created(){
            this.init();
        },
        methods:{
            chooseImg(name){
                this.imgName = name;
                this.$refs['img'].dialogVisible = true;
            },
            selectImgOk(e){
                console.log('imgName='+this.imgName);
                if(this.imgName == "wx_share_img") {
                    this.form.wx_mp.share_img = e.str;
                    console.log(this.form.wx_mp);
                }
                if(this.imgName == "baidu_share_img") {
                    this.form.baidu_mp.share_img = e.str;
                }
                if(this.imgName == "douyin_share_img") {
                    this.form.douyin.share_img = e.str;
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
            openAuthor(e){

            },
            init() {
                let _this = this;
                axios.get("{{route('zds.setting')}}")
                    .then(function (res){
                        console.log(res.data);
                        if(res.data.status == "success")
                        {
                            let data = res.data.data;
                            _this.form.basic = JSON.parse(data.basic);
                            _this.form.wx_mp = JSON.parse(data.wx_mp);
                            _this.form.baidu_mp = JSON.parse(data.baidu_mp);
                            _this.form.douyin_mp = JSON.parse(data.douyin_mp);
                            _this.form.wx_pay = JSON.parse(data.wx_pay);
                            _this.form.ali_pay = JSON.parse(data.ali_pay);
                            _this.form.third_pay = JSON.parse(data.third_pay);
                            _this.form.storage_type = data.storage_type;
                            _this.form.storage = JSON.parse(data.storage);
                        }

                    })
            },
            submit(){
                var _this = this;
                _this.$refs.form.validate((valid) => {
                    if(valid) {
                        _this.btnLoading = true;
                        axios.post('/zds/basic/setting', Qs.stringify({
                            basic: JSON.stringify(_this.form.basic),
                            wx_mp: JSON.stringify(_this.form.wx_mp),
                            baidu_mp: JSON.stringify(_this.form.baidu_mp),
                            douyin_mp:JSON.stringify(_this.form.douyin_mp),
                            storage_type: _this.form.storage_type,
                            storage: JSON.stringify(_this.form.storage),
                            wx_pay: JSON.stringify(_this.form.wx_pay),
                            ali_pay:JSON.stringify(_this.form.ali_pay),
                            third_pay:JSON.stringify(_this.form.third_pay)
                        })).then(function(res) {
                            console.log(res);
                            _this.$message({
                                message:'保存成功',
                                type:'success',
                                duration:1000,
                                onClose:()=> {
                                    _this.btnLoading = false;

                                }

                            })
                        }).catch(function(error) {
                            console.log(error);
                        })
                    } else {
                        _this.$message({
                            message:'请按要求填写',
                            type:'error'
                        });
                    }
                })
            }
        }
    })
</script>
<style>
    .img-box{
        width:126px;
        height:70px;
        border:1px solid #eee;
        border-radius:5px;
        background-size:cover;
        background-position:center center;
        cursor:pointer;
    }
</style>