<template id="bjz-area">
    <el-form-item label="区域">
    <el-select v-model="prov" style="width:167px;margin-right: 25px;">
        <el-option v-for="option in arr" :value="option.name" @change="

">
            @{{ option.name }}
        </el-option>
    </el-select>
    <el-select v-model="city" style="width:167px;margin-right: 25px;">
        <el-option v-for="option in cityArr" :value="option.name">
            @{{ option.name }}
        </el-option>
    </el-select>
    <el-select v-model="district" v-if="district" @change="districtChange" style="width:167px;">
        <el-option v-for="option in districtArr" :value="option.name">
            @{{ option.name }}
        </el-option>
    </el-select>
    </el-form-item>
</template>
<script src="/js/area.js" />
<script>
    Vue.component('bjz-area', {
        template:'#bjz-area',
        props:{
            bprov:String,
            bcity:String,
            bdistrict:String
        },
        data(){
            return {
                arr: arrAll,
                prov: this.bprov,
                city: this.bcity,
                district: this.bdistrict,
                cityArr: [],
                districtArr: [],
            }
        },
        methods: {
            updateCity: function() {
                for (var i in this.arr) {
                    var obj = this.arr[i];
                    if (obj.name) {
                        if (obj.name == this.prov) {
                            this.cityArr = obj.sub;
                            break;
                        }
                    }
                }
                this.city = this.cityArr[1].name;
            },
            updateDistrict: function() {
                for (var i in this.cityArr) {
                    var obj = this.cityArr[i];
                    if (obj.name == this.city) {
                        this.districtArr = obj.sub;
                        break;
                    }
                }
                if (this.districtArr && this.districtArr.length > 0 && this.districtArr[1].name) {
                    this.district = this.districtArr[1].name;
                } else {
                    this.district = '';
                }
                this.$emit('area', {
                    prov: this.prov,
                    city: this.city,
                    district: this.district
                })
            },
            districtChange:function() {
                this.$emit('area', {
                    prov: this.prov,
                    city: this.city,
                    district: this.district
                })
            }
        },
        beforeMount() {
            this.updateCity();
            this.updateDistrict();

        },
        watch: {
            prov: function() {
                this.updateCity();
                this.updateDistrict();
            },
            city: function() {
                this.updateDistrict();
            }
        }
    })
</script>