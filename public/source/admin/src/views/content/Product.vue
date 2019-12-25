<template>
    <div class="content">
        <Card :bordered="false" style="margin-bottom: 20px">
            <Form ref="formItem" :model="formItem" :label-width="80">
                <Row>
                    <Col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
                        <FormItem label="栏目" prop="category_id">
                            <Cascader :data="cascader" v-model="formItem.category_id" :filterable="true" change-on-select></Cascader>
                        </FormItem>
                    </Col>
                    <Col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
                        <FormItem label="标题" prop="title">
                            <Input v-model="formItem.title" placeholder="输入标题"></Input>
                        </FormItem>
                    </Col>
                    <Col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
                        <FormItem label="添加日期" prop="date">
                            <DatePicker v-model="formItem.date" show-week-numbers type="daterange" split-panels placeholder="选择日期" style="width: 100%;padding-right: 20px"></DatePicker>
                        </FormItem>
                    </Col>
                    <span v-if="drop">
                     <Col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
                        <FormItem label="推荐" prop="is_recommend">
                            <Select v-model="formItem.is_recommend">
                                <Option value="1">是</Option>
                                <Option value="0">否</Option>
                            </Select>
                        </FormItem>
                      </Col>
                    <Col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
                        <FormItem label="状态" prop="status">
                            <Select v-model="formItem.status" disabled>
                                <Option value="publish">已发布</Option>
                                <Option value="draft">草稿</Option>
                                <Option value="pending">等待复审</Option>
                            </Select>
                        </FormItem>
                    </Col>
                    </span>

                    <Col :xs="24" :sm="24" :md="12" :lg="24" :xl="24">
                        <FormItem style="float: right;margin-right: 15px;">
                            <Button @click="handleSearch" type="primary" icon="ios-search">查询</Button>
                            <Button @click="handleReset('formItem')" style="margin-left: 8px">重置</Button>
                            <a class="ivu-ml-8 drop-down" @click="dropDown" style="font-size: 14px;">
                                {{dropDownContent}}
                                <Icon :type="dropDownIcon"></Icon>
                            </a>
                        </FormItem>
                    </Col>
                    <Col :xs="24" :sm="24" :md="12" :lg="24" :xl="24" style="margin-left: 42px;" class="operation">
                        <Button @click="add" type="primary">新建</Button>
                        <Button @click="delAll" icon="md-trash">批量删除</Button>
                        <Button @click="recommendAll" icon="md-trash">批量推荐</Button>
                        <Button @click="cancelRecommendAll" icon="md-trash">批量取消推荐</Button>
                        <Dropdown @on-click="handleDropdown">
                            <Button>
                                更多操作
                                <Icon type="md-arrow-dropdown" />
                            </Button>
                            <DropdownMenu slot="list">
                                <DropdownItem name="refresh">刷新</DropdownItem>
                                <!--                                <DropdownItem name="reset">重置用户密码</DropdownItem>-->
                                <!--                                <DropdownItem disable name="exportData">导出所选数据</DropdownItem>-->
                                <!--                                <DropdownItem name="exportAll">导出全部数据</DropdownItem>-->
                                <!--                                <DropdownItem name="importData">导入数据(付费)</DropdownItem>-->
                            </DropdownMenu>
                        </Dropdown>
                    </Col>
                </Row>
            </Form>
            <Table
                    :loading = "loading"
                    :border="true"
                    :highlight-row="true"
                    :stripe="showStripe"
                    :show-header="showHeader"
                    :height="fixedHeader ? 250 : ''"
                    :size="tableSize"
                    :data="tableData"
                    :columns="tableColumns"
                    @on-selection-change="showSelect"
            >
            </Table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                    <Page :total="total" :current="current" :page-size="pageSize" show-total show-elevator @on-change="changePage"></Page>
                </div>
            </div>
        </Card>
    </div>
</template>

<script>
  export default {
    data () {
      return {
        search:{},
        contentType: 'product',
        cascader: [],
        loading: true,
        total: 0,
        current: 0,
        pageSize: 0,
        selectCount: 0,
        selectList: [],
        exportData: [],
        drop: false,
        dropDownContent: "展开",
        dropDownIcon: "ios-arrow-down",
        formItem:{
          category_id: [0],
          title: '',
          price: '',
          date: '',
          is_recommend: '',
          status:'',
        },
        tableData: [],
        showBorder: true,
        showStripe: true,
        showHeader: true,
        showIndex: false,
        showCheckbox: true,
        fixedHeader: false,
        tableSize: 'default'
      }
    },
    created: function () {
      var url = this.api.category.cascaderContent+this.contentType;
      this.$axios.get(url)
        .then((response) => {
          if (response.data.code == 0)
          {
            this.cascader = response.data.data
          }else{
            this.$Message.error('数据获取失败，请检查');
          }
        })
      this.getData()
    },
    methods: {
      add() {
        this.gotoPage(this.contentType + 'View',{
          id: 0
        })
      },
      handleSearch() {
        let params = {
          category_id: this.formItem.category_id,
          title: this.formItem.title,
          date: this.formItem.date,
          is_recommend: this.formItem.is_recommend,
          status: this.formItem.status,
        }
        this.search = params

        this.getData(0, params)
        window.console.log('search')
      },
      handleReset (name) {
        this.$refs[name].resetFields();
        this.getData();
      },
      gotoPage: function(name, params = {}){
        this.$router.push({  //核心语句
          name:name,   //跳转的路径
          query:params,   //跳转的参数
        })
      },
      getData(page = 0, params = {}) {
        this.loading = true;
        page = page > 0 ? page : 0;

        this.$axios.get(this.api.content.list + this.contentType + '?page=' + page, {params:params})
          .then((response) => {
            this.loading = false;
            this.tableData = response.data.data.data
            this.current = response.data.data.current_page
            this.total = response.data.data.total
            this.pageSize = response.data.data.per_page
            return this.tableData
          })
      },
      dropDown() {
        if (this.drop) {
          this.dropDownContent = "展开";
          this.dropDownIcon = "ios-arrow-down";
        } else {
          this.dropDownContent = "收起";
          this.dropDownIcon = "ios-arrow-up";
        }
        this.drop = !this.drop;
      },
      show (index) {
        window.console.log(index)
      },
      remove (params) {
        this.delete(params.row.id)
      },
      changePage (index) {
        this.tableData = this.getData(index, this.search);
      },
      delete(id){
        this.$axios.post(this.api.content.contentDelete, {
          id: id,
          type: this.contentType
        })
          .then((response) => {
            this.loading = false;
            if (response.data.code == 0)
            {
              this.$Modal.remove();
              this.$Message.success("删除成功");
            }else{
              this.$Message.error(response.data.msg);
            }
            this.getData()
          })
      },
      delAll() {
        if (this.selectCount <= 0) {
          this.$Message.warning("您还未选择要删除的数据");
          return;
        }
        this.$Modal.confirm({
          title: "确认删除",
          content: "您确认要删除所选的 " + this.selectCount + " 条数据?",
          loading: true,
          onOk: () => {
            let ids = "";
            this.selectList.forEach(function(e) {
              ids += e.id + ",";
            });
            ids = ids.substring(0, ids.length - 1);
            this.delete(ids)
          }
        });
      },
      recommend(id, isRecommend = 0){
        this.$axios.post(this.api.content.contentRecommend, {
          id: id,
          type: this.contentType,
          recommend: isRecommend
        })
          .then((response) => {
            this.loading = false;
            if (response.data.code == 0)
            {
              this.$Modal.remove();
              let msg = isRecommend === 1 ? "推荐成功" : '取消推荐成功'
              this.$Message.success(msg);
            }else{
              this.$Modal.remove();
              this.$Message.error(response.data.msg);
            }
            this.getData()
          })
      },
      recommendAll() {
        if (this.selectCount <= 0) {
          this.$Message.warning("您还未选择要操作的数据");
          return;
        }
        this.$Modal.confirm({
          title: "确认推荐",
          content: "您确认要推荐所选的 " + this.selectCount + " 条数据?",
          loading: true,
          onOk: () => {
            let ids = "";
            this.selectList.forEach(function(e) {
              ids += e.id + ",";
            });
            ids = ids.substring(0, ids.length - 1);
            this.recommend(ids, 1)
          }
        });
      },
      cancelRecommendAll() {
        if (this.selectCount <= 0) {
          this.$Message.warning("您还未选择要操作的数据");
          return;
        }
        this.$Modal.confirm({
          title: "取消推荐",
          content: "您确认要取消推荐所选的 " + this.selectCount + " 条数据?",
          loading: true,
          onOk: () => {
            let ids = "";
            this.selectList.forEach(function(e) {
              ids += e.id + ",";
            });
            ids = ids.substring(0, ids.length - 1);
            this.recommend(ids, 0)
          }
        });
      },
      showSelect(e) {
        // window.console.log(e)
        this.exportData = e;
        this.selectList = e;
        this.selectCount = e.length;
      },
      handleDropdown(name) {
        switch (name) {
          case 'refresh':
            this.getData()
            break
          default:
        }
      },
    },
    computed: {
      tableColumns () {
        let columns = [];
        if (this.showCheckbox) {
          columns.push({
            type: 'selection',
            width: 60,
            align: 'center'
          })
        }
        columns.push({
          title: 'ID',
          width: 80,
          key: 'id',
        });
        columns.push({
          title: '所属栏目',
          key: 'category_name',
          sortable: true,
          width: 120,
          tooltip: true
        });
        columns.push({
          title: '标题',
          key: 'title',
          width: 300,
          tooltip: true
        });
        columns.push({
          title: '价格',
          key: 'price',
          width: 120,
          tooltip: true
        });
        columns.push({
          title: '状态',
          key: 'status_cn',
          align: 'center',
          width: 150,
          render: (h, params) => {
            const row = params.row;
            // $status_cn = ['draft' => '草稿', 'pending' => '等待复审', 'publish' => '已发布'];
            const color = row.status === 'publish' ? 'primary' : row.status === 'draft' ? 'success' : 'error';
            const text = row.status_cn;
            return h('Tag', {
              props: {
                type: 'dot',
                color: color
              }
            }, text);
          }
        });
        columns.push({
          title: '推荐',
          key: 'is_recommend',
          align: 'center',
          width: 70,
          render: (h, params) => {
            const row = params.row;
            const color = row.is_recommend === 1 ? 'success' : 'default';
            const text = row.is_recommend === 1 ? '是' : '否';
            const recommend = row.is_recommend === 1 ? 0 : 1;
            return h('Button', {
              props: {
                type: color,
                size: 'small'
              },
              on: {
                click: () => {
                  this.recommend(params.row.id, recommend)
                }
              }
            }, text);
          }
        });
        // columns.push({
        //   title: '特价',
        //   key: 'is_offer',
        //   align: 'center',
        //   width: 70,
        //   render: (h, params) => {
        //     const row = params.row;
        //     const color = row.is_offer === 1 ? 'success' : 'default';
        //     const text = row.is_offer === 1 ? '是' : '否';
        //     return h('Tag', {
        //       props: {
        //         color: color
        //       }
        //     }, text);
        //   }
        // });
        columns.push({
          title: '点击量',
          key: 'click_count',
          align: 'center',
          sortable: true,
          width: 100,
        });
        columns.push({
          title: '缩略图',
          key: 'image',
          render: (h, params) => {
            let image = params.row.image
            if(image.length > 0 && image.substr(0,4).toLowerCase() != "http")
            {
              image = this.api.static.baseUrl + image;
            }
            return h('Avatar', {
              props: {
                icon: 'ios-image',
                src: image,
                shape: 'square',
                // size: 'small',
              }
            });
          }
        });
        columns.push({
          title: '添加时间',
          key: 'created_at',
          sortable: true,
          tooltip: true
        });
        columns.push({
          title: '更新时间',
          key: 'updated_at',
          sortable: true,
          tooltip: true
        });
        // columns.push({
        //   title: '发布时间',
        //   key: 'published_at',
        //   sortable: true,
        // });
        columns.push({
          title: '操作',
          key: 'action',
          width: 150,
          align: 'center',
          render: (h, params) => {
            return h('div', [
              h('Button', {
                props: {
                  type: 'primary',
                  size: 'small'
                },
                style: {
                  marginRight: '5px'
                },
                on: {
                  click: () => {
                    this.gotoPage(this.contentType + 'View',{
                      id: params.row.id
                    })
                  }
                }
              }, '详情'),
              h('Button', {
                props: {
                  type: 'error',
                  size: 'small'
                },
                on: {
                  click: () => {
                    this.remove(params)
                  }
                }
              }, '删除')
            ]);
          }
        });
        return columns;
      }
    }
  }
</script>

<style scoped>
    .ivu-ml-8 {
        margin-left: 8px!important;
    }
    .ivu-form {
        margin: 20px 0!important;
    }
    /*.ivu-row{*/
    /*    margin-left: -42px!important;*/
    /*    margin-right: -12px!important;*/
    /*}*/
    .operation button {
        margin-right: 10px;
    }

</style>