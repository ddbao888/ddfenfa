@include('web.common.attachment-img')
@include('web.common.attachment-video')

<template id="baidu-ueditor">
    <div class="editor-box">
        <!-- <script :id="editor_id" type="text/plain"></script> -->
        <textarea  :id="editor_id"></textarea>
        <attachment-video ref="video" type="video" @video='chooseVideoOK' ></attachment-video>
        <attachment-img ref="imgs" type="imgs" @attachment='chooseImgsOK' ></attachment-img>

        <!-- </attachment-video> -->
    </div>
</template>

<script>
    Vue.component('baidu-ueditor', {
        template: '#baidu-ueditor',
        props: {
            value: null,
        },
        name: 'UE',
        data () {
            return {
                editor: null,//编辑器
                tempContent: this.value,//临时内容
                isInputChange: false,//是否改变内容
                editor_id: 'baidu-editor-' + (Math.floor((Math.random() * 10000) + 1)),
                newVal:'',
                oldVal:''
            }
        },
        watch: {
            value(newVal, oldVal) {
                this.newVal = newVal
                this.oldVal = oldVal
                // console.log(111,newVal, 222,oldVal)
                // 监听值变化
                if (!this.isInputChange && newVal) {
                    if (this.editor && this.editor.isReady === 1) {
                        this.editor.setContent(newVal);
                    } else {
                        this.tempContent = newVal;
                    }
                }
                if (this.isInputChange) {
                    this.isInputChange = false;
                }
            },
        },
        mounted() {
            // console.log(999)
            this.loadUe();
        },
        methods: {
            loadUe() {
                _ue = this;
                // console.log(_ue.editor_id)
                this.editor = UE.getEditor(_ue.editor_id);
                this.editor.addListener('ready', editor => {
                    if (this.tempContent) {
                        this.editor.setContent(this.tempContent);
                    }
                });
                this.editor.addListener('keyup', editor => {
                    this.isInputChange = true;
                    this.$emit('input', this.editor.getContent());
                });
                this.editor.addListener('contentChange', editor => {
                    this.isInputChange = true;
                    this.$emit('input', this.editor.getContent());
                });
                let self = this;
                UE.registerUI('appinsertimage', (editor, uiName) => {
                    return new UE.ui.Button({
                        name: uiName,
                        title: '插入图片',
                        //添加额外样式，指定icon图标，这里默认使用一个重复的icon
                        cssRules: 'background-position: -381px 0px;',
                        onclick() {
                            self.ue = editor
                            _ue.$refs['imgs'].dialogVisible = true
                        },
                    });
                });
                UE.registerUI('appinsertvideo', (editor, uiName) => {
                    return new UE.ui.Button({
                        name: uiName,
                        title: '插入视频',
                        className:'edui-for-insertvideo',
                        //添加额外样式，指定icon图标，这里默认使用一个重复的icon
                        cssRules: 'background-position: -381px 0px;background-image: url(../images/icons.png);',
                        onclick() {
                            self.ue = editor
                            _ue.$refs['video'].dialogVisible = true
                            // _ue.$refs['video'].dialogVisible = true
                        },
                    });
                });
            },
            chooseImgsOK(e){
                for(i = 0; i < e.arr.length; i++){
                    var src ='<img src="' + e.arr[i] + '" style="max-width: 100%;">';
                    _ue.editor.execCommand('inserthtml',src)
                }
            },
            chooseVideoOK(e){
                for(i = 0; i < e.arr.length; i++){
                    var src ='<video src="' + e.arr[i] + '" style="width: 100%;">1 </video>';
                    _ue.editor.execCommand('inserthtml',src)
                }
            },
            reload(){
                this.$forceUpdate()
                this.isInputChange = false;
            }
        },
        destroyed() {
            _ue.editor.destroy();
        }
    })
</script>
<style>

</style>