<template>
    <div class="content dashboard">
        <div class="analysis">
            <Row>
                <Col :xl="6" :md="12" :lg="12" :xs="24">
                    <Card style="margin:0 12px">
                        <p slot="title">新闻量<Tag type="border" color="green">总计</Tag></p>
                        <p class="numeral"><span class="ivu-numeral">{{count.newsCount}}</span></p>
                        <p class="info"><span>获取时间：{{count.newsTime}}</span></p>
                    </Card>
                </Col>
                <Col :xl="6" :md="12" :lg="12" :xs="24">
                    <Card style="margin:0 12px">
                        <p slot="title">产品量<Tag type="border" color="blue">总计</Tag></p>
                        <p class="numeral"><span class="ivu-numeral">{{count.productCount}}</span></p>
                        <p class="info"><span>获取时间：{{count.productTime}}</span></p>
                    </Card>
                </Col>
                <Col :xl="6" :md="12" :lg="12" :xs="24">
                    <Card style="margin:0 12px">
                        <p slot="title">访问量<Tag type="border" color="red">月</Tag></p>
                        <div class="chart">
                            <ve-line :data="chartData" height="240px"
                                     :tooltip-visible="false"
                                     :legend-visible="false">
                            </ve-line>
                        </div>
                        <p class="info"><span>最后更新时间：2019年8月3日 03:27:16</span></p>
                    </Card>
                </Col>
                <Col :xl="6" :md="12" :lg="12" :xs="24">
                    <Card style="margin:0 12px">
                        <p slot="title">磁盘占用 <Tag type="border" color="purple">实时数据</Tag></p>
                        <p style="text-align: center">
                            <i-circle :percent="dashboard.disk">
                                <span class="demo-Circle-inner" style="font-size:24px">{{dashboard.disk}}%</span>
                            </i-circle>
                        </p>
                    </Card>
                </Col>
            </Row>
        </div>
        <div class="dashboard-console-grid">
            <Row>
                <Col :xl="3" :xs="12" :sm="12" :md="12" :lg="12">
                    <Col >
                        <Card>
                            <router-link :to="{ name: 'news' }">
                                <p><i class="ivu-icon ivu-icon-md-cart" style="color: rgb(255, 156, 110);"></i></p>
                                <p>新闻</p>
                            </router-link>
                        </Card>
                    </Col>
                </Col>
                <Col :xl="3" :xs="12" :sm="12" :md="12" :lg="12">
                    <Col >
                        <Card>
                            <router-link :to="{ name: 'product' }">
                                <p><i class="ivu-icon ivu-icon-md-clipboard" style="color: rgb(179, 127, 235);"></i>
                                <p>产品</p>
                            </router-link>
                        </Card>
                    </Col>
                </Col>
                <Col :xl="3" :xs="12" :sm="12" :md="12" :lg="12">
                    <Col >
                        <Card>
                            <router-link :to="{ name: 'video' }">
                                <p><i class="ivu-icon ivu-icon-md-switch" style="color: rgb(255, 192, 105);"></i></p>
                                <p>视频</p>
                            </router-link>
                        </Card>
                    </Col>
                </Col>
                <Col :xl="3" :xs="12" :sm="12" :md="12" :lg="12">
                    <Col >
                        <Card>
                            <router-link :to="{ name: 'download' }">
                                <p><i class="ivu-icon ivu-icon-md-podium" style="color: rgb(149, 222, 100);"></i></p>
                                <p>下载</p>
                            </router-link>
                        </Card>
                    </Col>
                </Col>
                <Col :xl="3" :xs="12" :sm="12" :md="12" :lg="12">
                    <Col >
                        <Card>
                            <router-link :to="{ name: 'case' }">
                                <p><i class="ivu-icon ivu-icon-md-card" style="color: rgb(255, 214, 102);"></i></p>
                                <p>案例</p>
                            </router-link>
                        </Card>
                    </Col>
                </Col><Col :xl="3" :xs="12" :sm="12" :md="12" :lg="12">
                <Col >
                    <Card>
                        <router-link :to="{ name: 'job' }">
                            <p><i class="ivu-icon ivu-icon-md-mail" style="color: rgb(92, 219, 211);"></i></p>
                            <p>招聘</p>
                        </router-link>
                    </Card>
                </Col>
            </Col><Col :xl="3" :xs="12" :sm="12" :md="12" :lg="12">
                <Col >
                    <Card>
                        <router-link :to="{ name: 'user' }">
                            <p><i class="ivu-icon ivu-icon-md-pricetags" style="color: rgb(255, 133, 192);"></i></p>
                            <p>用户</p>
                        </router-link>
                    </Card>
                </Col>
            </Col><Col :xl="3" :xs="12" :sm="12" :md="12" :lg="12">
                <Col >
                    <Card>
                        <router-link :to="{ name: 'setting' }">
                            <p><i class="ivu-icon ivu-icon-md-people" style="color: rgb(105, 192, 255);"></i></p>
                            <p>设置</p>
                        </router-link>
                    </Card>
                </Col>
            </Col>
            </Row>
        </div>
        <div class="visit">
            <Card :bordered="false">
                <p slot="title"><Icon type="ios-podium" size="24"></Icon>网站分析</p>
                <Row>
                    <Col :xl="16" :lg="24" style=" padding-left: 12px;padding-right: 12px;">
                        <h4>流量分析</h4>
                        <p><ve-histogram :data="visitData"></ve-histogram></p>
                    </Col>
                    <Col :xl="8" :lg="24" style="padding-left: 12px;padding-right: 12px;">
                        <h4>官方公告</h4>
                        <Timeline  style="margin: 30px 0px 0px 30px;">
                            <TimelineItem v-for="(item, index) in notice" :key="index"
                                          style="padding: 20px 0;cursor: pointer;"
                                          @click.native="noticeClick(item.href)"
                            >
                                <p class="time">{{item.created_at}}</p>
                                <p class="content">{{item.title}}</p>
                            </TimelineItem>
                        </Timeline>
                    </Col>
                </Row>
            </Card>
        </div>
        <div id="index" class="site">
            <Row>
                <Col :lg="24" :xl="12">
                    <Card :bordered="false">
                        <p slot="title">服务器信息</p>
                        <div class="siteInfo">
                            <ul>
                                <li>PHP 版本：{{dashboard.php_version}}</li>
                                <li>最大上传文件：{{dashboard.max_upload_size}}</li>
                                <li>脚本最大执行时间：{{dashboard.max_execution_time}}</li>
                                <li>PHP 运行模式：{{dashboard.sapi_name}}</li>
                                <li>Socket 支持：{{dashboard.sockets}}</li>
                                <li>curl 支持：{{dashboard.curl}}</li>
                                <li>ctype 支持：{{dashboard.ctype}}</li>
                                <li>pdo_mysql 支持：{{dashboard.pdo_mysql}}</li>
                                <li>openSSL 支持：{{dashboard.openSSL}}</li>
                            </ul>
                            <ul class="last long">
                                <li>服务器操作系统：{{dashboard.os}}</li>
                                <li>服务器时间：{{dashboard.time}}</li>
                                <li>服务器 CPU：{{dashboard.cpu}}</li>
                                <li>服务器硬盘：空闲 {{dashboard.disk_free_space}} / 总计 {{dashboard.disk_total_space}}</li>
                                <li>Web 服务器：{{dashboard.web}}</li>
                            </ul>
                        </div>
                    </Card>
                </Col>
                <Col :lg="24" :xl="12">
                    <Card :bordered="false">
                        <p slot="title">管理员操作记录</p>
                        <p><Table :columns="admin_log_columns" :border="false" :data="admin_log"></Table> </p>
                    </Card>
                </Col>
            </Row>
        </div>
    </div>
</template>

<script>
  export default {
    data: function () {
      return {
        dashboard: [],
        notice: [],
        count: [],
        chartData: {
          columns: ['日期', '注册用户'],
          rows: [
            { '日期': '1/1', '注册用户': 13 },
            { '日期': '1/2', '注册用户': 35 },
            { '日期': '1/3', '注册用户': 29},

          ]
        },
        visitData: {
          columns: ['日期', '访问用户'],
          rows: [
            { '日期': '1/1', '访问用户': 1393},
            { '日期': '1/2', '访问用户': 3530},
            { '日期': '1/3', '访问用户': 2923},
            { '日期': '1/4', '访问用户': 1723},
            { '日期': '1/5', '访问用户': 3792},
            { '日期': '1/7', '访问用户': 5593},
            { '日期': '1/8', '访问用户': 2593},
            { '日期': '1/9', '访问用户': 6593},
            { '日期': '1/10', '访问用户': 2593},
            { '日期': '1/10', '访问用户': 5593},
            { '日期': '1/10', '访问用户': 3393},
            { '日期': '1/10', '访问用户': 5293},
            { '日期': '1/10', '访问用户': 493},
            { '日期': '1/10', '访问用户': 3193},
            { '日期': '1/10', '访问用户': 1593},
          ]
        },
        admin_log_columns: [
          {
            title: '用户名',
            key: 'username',
            tooltip: true
          },
          {
            title: '内容',
            key: 'info',
            tooltip: true
          },
          {
            title: '操作时间',
            key: 'created_at',
            tooltip: true
          }
        ],
        admin_log: [],
      }
    },
    created: function () {
      this.$axios.get(this.api.common.dashboard)
        .then((response) => {
          this.dashboard = response.data.data.dashboard
          this.count = response.data.data.count
          this.admin_log = response.data.data.admin_log
          this.notice = response.data.data.notice
        })
    },
    methods: {
      noticeClick(name) {
        window.console.log('11111111')
       window.open(name)
      },
    },
    computed: {
      color () {
        let color = '#2db7f5';
        if (this.percent == 100) {
          color = '#5cb85c';
        }
        return color;
      }
    },
  }
</script>

<style scoped>
    .dashboard {
        margin: 0 -10px;
        padding-right: 0;
    }

    .ivu-card-head p, .ivu-card-head-inner {
        height: 26px;
        line-height: 30px;
    }

    .analysis .ivu-card-body {
        height: 158px;
    }
    .analysis .ve-line {
        height: 145px!important;
        overflow: hidden;
    }
    .analysis .chart {
        height: 113px!important;
        overflow: hidden;
    }
    .analysis p.numeral {
        margin-left: 10px;
        margin-top: -5px;
        font-size: 30px;
        margin-bottom: 73px;
    }
    .analysis p.info {
        margin-left: 10px;
        margin-top: -5px;
        font-size: 12px;
        color: rgb(158, 167, 180);
    }

    p .ivu-tag-border {
        float: right;
    }

    .ve-line {
        margin-top: -50px;
    }

    .dashboard-console-grid {
        text-align: center;
        /*margin:0 0 12px;*/
    }

    .dashboard-console-grid a {
        display: block;
        color: inherit;
        padding: 16px;
    }

    .dashboard-console-grid p {
        margin-top: 8px;
    }

    .dashboard-console-grid .ivu-card-body {
    }

    .dashboard-console-grid .ivu-col {
        padding: 6px;
    }

    .dashboard-console-grid i {
        font-size: 32px;
    }

    .visit {
        margin: 12px;
    }

    .visit i {
        margin-right: 10px;
        margin-top: -5px;
    }

    .ivu-timeline {
        margin: 30px 20px;
    }

    .ivu-timeline a {
        color: #515a6e;
        font-size: 14px;
    }

    .ivu-timeline a:hover {
        color: #2d8cf0;
    }

    .ivu-timeline .time {
        font-size: 14px;
        float: right;
    }

    .ivu-timeline .content {
        padding-left: 5px;
    }

    .site {
        margin: 25px 0;
    }

    .site .ivu-col {
        padding: 0 12px;
    }

    .site .ivu-table th {
        background: transparent;
        border: none;
    }

    .site .ivu-table-wrapper, .site .ivu-table td, .site tr.ivu-table-row-hover td {
        border: none;
    }
    /*- siteInfo -*/
    #index .siteInfo {
        font-size: 12px;
    }

    #index .siteInfo ul {
        border-bottom: 1px solid #e8eaec;
        margin-bottom: 10px;
        padding-bottom: 12px;
        zoom: 1;
        overflow: hidden;
    }

    #index .siteInfo ul.last {
        border-bottom: 0;
        padding-bottom: 0;
        margin-bottom: 0;
    }

    #index .siteInfo ul li {
        float: left;
        width: 33%;
        margin-bottom: 8px;
    }

    #index .siteInfo ul.long li {
        float: none;
        width: 100%;
    }
</style>
<style>
#index .ivu-card-body {
    min-height: 263px!important;
}
.analysis .ivu-col{
    margin-bottom: 16px!important;
}
</style>
