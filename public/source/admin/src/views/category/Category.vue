<template>
    <div class="content">
        <Card :bordered="false" style="margin-bottom: 20px">
            <h1><router-link :to="{name: 'categoryView'}"><Button type="primary">新建</Button></router-link></h1>
            <Alert v-if="is_tip === true" closable type="success" @on-close="close">排序按照阿拉伯数字 ASC 升序排序，数字越小排序越靠前</Alert>
            <Table :border="showBorder"
                   :stripe="showStripe"
                   :show-header="showHeader"
                   :height="fixedHeader ? 250 : ''"
                   :size="tableSize"
                   :data="tableData"
                   :columns="tableColumns"
            >
            </Table>
            <Modal v-model="modal2" width="360">
                <p slot="header" style="color:#f60;text-align:center">
                    <Icon type="ios-information-circle"></Icon>
                    <span>确认对话框</span>
                </p>
                <div style="text-align:center">
                    <p>栏目（ ID ：{{categoryName}} ） 删除之后无法恢复。</p>
                    <p>是否确认删除？</p>
                </div>
                <div slot="footer">
                    <Button type="error" size="large" long :loading="modal_loading" @click="del">确认</Button>
                </div>
            </Modal>
        </Card>
    </div>
</template>
<script>
  export default {
    data () {
      return {
        is_tip: true,
        categoryName: '',
        index: '',
        modal2: false,
        modal_loading: false,
        tableData: [
        ],
        total: 0,
        current: 0,
        pageSize: 0,
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
      this.getData()
      window.console.log(localStorage.getItem('is_tip'))
      this.is_tip = localStorage.getItem('is_tip') === null ? true : localStorage.getItem('is_tip')
      window.console.log(this.is_tip)
    },
    methods: {
      sort(id, sort) {
        this.$axios.put(this.api.category.sort + '/' + id,
        {
          sort: sort
        })
          .then((response) => {
            if (response.data.code == 0)
            {
              this.$Message.success('排序成功');
              this.getData()
            }else{
              this.$Message.error(response.data.msg);
            }
          })
      },
      close () {
        alert('关闭提示信息后会在以后很长一段时间不再提示您')
        this.is_tip = false
        localStorage.setItem('is_tip', false)
      },
      handleSubmit (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            this.$Message.success('Success!');
          } else {
            this.$Message.error('Fail!');
          }
        })
      },
      handleReset (name) {
        this.$refs[name].resetFields();
      },
      getData() {
        this.$axios.get(this.api.category.list)
          .then((response) => {
            this.tableData = response.data.data
          })
      },
      gotoPage: function(name, params = {}){
        this.$router.push({  //核心语句
          name:name,   //跳转的路径
          query:params,   //跳转的参数
        })
      },
      del () {
        this.modal_loading = true;
        setTimeout(() => {
          this.modal_loading = false;
          this.modal2 = false;
        }, 1000);
        this.$axios.delete(this.api.category.remove + '/' + this.index.row.id)
          .then((response) => {
            if (response.data.code == 0)
            {
              this.$Message.success('删除成功');
              this.getData()
            }else{
              this.$Message.error(response.data.msg);
            }
          })
      },
      remove (index) {
        this.modal2 = true;
        this.categoryName = index.row.id
        this.index = index
      },
      changePage (index) {
        this.tableData = this.getData(index);
      }
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
          title: '栏目 ID',
          key: 'id',
        });
        columns.push({
          title: '名称',
          key: 'name',
          tooltip: true
        });
        columns.push({
          title: '排序',
          key: 'sort',
          width: 150,
          tooltip: true,
          render: (h, params) => {
            let row = params.row;
            let sort = row.sort
            return h('Input', {
              props: {
                type: 'number',
                value: sort,
                // icon: "ios-clock-outline"
              },
              on: {
                "on-blur": (e) => {
                  this.sort(row.id, e.target.value)
                }
              }
            });
          }
        });
        columns.push({
          title: '状态',
          key: 'is_show',
          render: (h, params) => {
            const row = params.row;
            const color = row.is_show === 1 ? 'success' : 'error';
            const text = row.is_show === 1 ? '启用' : '禁用';

            return h('Tag', {
              props: {
                type: 'dot',
                color: color
              }
            }, text);
          }
        });
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
                  size: 'small',
                },
                style: {
                  marginRight: '5px'
                },
                on: {
                  click: () => {
                    this.gotoPage('categoryView',{
                      id:params.row.id
                    })
                  }
                }
              }, '详情'),
              h('Button', {
                props: {
                  type: 'error',
                  size: 'small',
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

</style>