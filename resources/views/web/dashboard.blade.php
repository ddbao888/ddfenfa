
<div id="app" class="web_content">
    <div class="el-card box-card is-always-shadow">

        <div class="el-card__header">
            <div class="clearfix">
                <span>概况</span>
            </div>
        </div>
        {{--<div class="el-card__body">
            <div class=''>
                <div class="index_top_content el-row">
                    <div class="el-col el-col-6">
                        <div class="index_top_left">
                            <div class="iconb icon1" style="background-color:rgb(119,131,234)">
                                <i class="icon iconfont icon- color-f"></i>
                            </div>
                        </div>
                        <div class="index_top_right">
                            <div class="h2 rel">
                                昨日新增app
                                <i class="el-tootip icontoo el-icon-info ab item" aria-descripbedby="el-tooltip-9443" tbindex="0" style="left:150px;top:3px"></i>
                            </div>
                            <div class="h1">0</div>
                            <div class="p">昨日:0</div>
                        </div>
                    </div>
                    <div class="el-col el-col-6">
                        <div class="index_top_left">
                            <div class="iconb iconc1" style="background-color:rgb(225,84,123)">
                                <i class="icon iconfint color_f"></i>
                            </div>
                        </div>
                        <div class="index_top_right">
                            <div class="h2 rel">
                                今日新增app
                                <i class="el-tootip icontoo el-icon-info ab item" aria-descripbedby="el-tooltip-9443" tbindex="0" style="left:150px;top:3px"></i>
                            </div>
                            <div class="h1">0</div>
                            <div class="p">昨日:0</div>
                        </div>
                    </div>
                    <div class="el-col el-col-6">
                        <div class="index_top_left">
                            <div class="iconb iconc2">
                                <i class="icon iconfint color_f"></i>
                            </div>
                        </div>
                        <div class="index_top_right">
                            <div class="h2 rel">
                                昨日下载量
                                <i class="el-tootip icontoo el-icon-info ab item" aria-descripbedby="el-tooltip-9443" tbindex="0" style="left:150px;top:3px"></i>
                            </div>
                            <div class="h1">0</div>
                            <div class="p">昨日:0</div>
                        </div>
                    </div>
                    <div class="el-col el-col-6">
                        <div class="index_top_left">
                            <div class="iconb iconc3">
                                <i class="icon iconfint color_f"></i>
                            </div>
                        </div>
                        <div class="index_top_right">
                            <div class="h2 rel">
                                昨日下载量
                                <i class="el-tootip icontoo el-icon-info ab item" aria-descripbedby="el-tooltip-9443" tbindex="0" style="left:150px;top:3px"></i>
                            </div>
                            <div class="h1">0</div>
                            <div class="p">昨日:0</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>--}}
    </div>
    <el-card class="box-card" style="padding-top:10px">
        <div>
            <div class="el-row">
                <div class="el-col-24">
                    <div id="orderChart" style="width:100%;height:300px">

                    </div>
                </div>
            </div>
        </div>
    </el-card>
    <el-card class="box-card">
        <div>
            <div class="el-row">
                <div class="el-col-24">
                    <div id="orderChart" style="width:100%;height:300px">

                    </div>
                </div>
            </div>
        </div>
    </el-card>
</div>

<script>
    new Vue({
        el:'#app',
        methods:{
            open() {
                this.$message('这是一条消息提示');
            },
        }
    })
</script>
<script type="text/javascript">
    /*var orderChart = echarts.init(document.getElementById('orderChart'));
    var orderOption = {
        title:{
            text:'30天下载量'
        },
        tooltip:{
            trigger:'axis',
            axisPointer:{
                type:'line'
            }
        },
        legend:{
            data:['用户数']
        },
        color:['#FF7D57', '#04BA8F'],
        grid:{
            left:'3%',
            right:'4%',
            bottom:'3%',
            containLabel: true
        },
        xAxis:{
            type:'category',
            boundaryGap:false,
            data:[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30],
            axisLine:{
                lineStyle:{color:'#FFA500', width:2}
            },
            axisLabel:{
                show:true,
                textStyle:{color:'#333'}
            }
        },
        yAxis:{
            type:'value'
        },
        series:[
            {name:'用户数', type:'line', data:[]},
        ],
    }
    orderChart.setOption(orderOption);*/

</script>
