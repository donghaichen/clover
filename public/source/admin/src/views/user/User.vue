<template>
    <div class="content">
        <Table :border="showBorder"
               :stripe="showStripe"
               :show-header="showHeader"
               :height="fixedHeader ? 250 : ''"
               :size="tableSize"
               :data="tableData"
               :columns="tableColumns"
        >
        </Table>
        <div style="margin: 10px;overflow: hidden">
            <div style="float: right;">
                <Page :total="total" :current="current" :page-size="pageSize" show-total show-elevator @on-change="changePage"></Page>
            </div>
        </div>
    </div>
</template>

<script>
import avatar from '@/assets/img/avatar.png'
  export default {
    data () {
      return {
        avatar:avatar,
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
      this.getData(1)
    },
    methods: {
      gotoPage: function(name, params = {}){
        this.$router.push({  //核心语句
          name:name,   //跳转的路径
          query:params,   //跳转的参数
        })
      },
      getData(page) {
        page = page > 0 ? page : 0;
        this.$axios.get(this.api.user.list + '?page=' + page)
          .then((response) => {
            this.tableData = response.data.data.data
            this.current = response.data.data.current_page
            this.total = response.data.data.total
            this.pageSize = response.data.data.per_page
            return this.tableData
          })
      },
      show (index) {

      },
      remove (index) {
        // this.data6.splice(index, 1);
      },
      changePage (index) {
        this.tableData = this.getData(index);
      }
    },
    computed: {
      tableColumns () {
        let columns = [];
        // if (this.showCheckbox) {
        //   columns.push({
        //     type: 'selection',
        //     width: 60,
        //     align: 'center'
        //   })
        // }
        columns.push({
          title: '用户 ID',
          key: 'id',
          sortable: true
        });
        columns.push({
          title: '用户名',
          key: 'username',
          tooltip: true
        });
        columns.push({
          title: '邮箱',
          key: 'email',
          tooltip: true
        });
        columns.push({
          title: '手机',
          key: 'mobile',
          tooltip: true
        });
        columns.push({
          title: '昵称',
          key: 'nickname',
          tooltip: true
        });
        columns.push({
          title: '头像',
          key: 'avatar',
          render: (h, params) => {
            const row = params.row;
            var avatar
            if (row.avatar.length > 0 && row.avatar.substr(0,4).toLowerCase() == "http" )
            {
              avatar = row.avatar;
            }else if(row.avatar.length > 0 && row.avatar.substr(0,4).toLowerCase() != "http")
            {
              avatar = this.api.static.baseUrl + row.avatar;
            }else
            {
              avatar = this.avatar
            }

            return h('Avatar', {
              props: {
                icon: 'ios-person',
                src: avatar,
                shape: 'square',
                // size: 'small',
              }
            });
          }
        });
        columns.push({
          title: '状态',
          key: 'status',
          render: (h, params) => {
            const row = params.row;
            const color = row.status === 1 ? 'success' : row.status === 2 ? 'warning' : 'error';
            const text = row.status === 1 ? '正常' : row.status === 2 ? '禁用' : '未知';

            return h('Tag', {
              props: {
                type: 'dot',
                color: color
              }
            }, text);
          }
        });
        columns.push({
          title: '注册IP',
          key: 'created_ip',
        });
        // columns.push({
        //   title: 'IP 地址',
        //   key: 'ipInfo',
        // });
        columns.push({
          title: '注册时间',
          key: 'created_at',
          sortable: true,
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
                    this.gotoPage('adminUserView',{
                      uid:params.row.id
                    })
                  }
                }
              }, '详情'),
              // h('Button', {
              //   props: {
              //     type: 'error',
              //     size: 'small',
              //     disabled: 'disabled'
              //   },
              //   on: {
              //     click: () => {
              //     }
              //   }
              // }, '删除')
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