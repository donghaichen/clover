<template>
    <div class="content">
        <Table
                :loading="tableLoading"
                :border="showBorder"
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
  export default {
    data () {
      return {
        tableLoading: true,
        tableData: [
        ],
        total: 0,
        current: 0,
        pageSize: 0,
        showBorder: true,
        showStripe: true,
        showHeader: true,
        showIndex: false,
        showCheckbox: false,
        fixedHeader: false,
        tableSize: 'default'
      }
    },
    created: function () {
      this.tableData = this.getData(1)
    },
    methods: {
      getData(page) {
        this.tableLoading = true
        page = page > 0 ? page : 0;
        this.$axios.get(this.api.common.msg + '?page=' + page)
          .then((response) => {
            this.tableData = response.data.data.data
            this.current = response.data.data.current_page
            this.total = response.data.data.total
            this.pageSize = response.data.data.per_page
            return this.tableData
          })
        this.tableLoading = false
      },
      changePage (index) {
        this.getData(index)
      },
      gotoPage: function(name, params = {}){
        this.$router.push({  //核心语句
          name:name,   //跳转的路径
          query:params,   //跳转的参数
        })
      },
    },
    computed: {
      tableColumns () {
        let columns = [];
        columns.push({
          title: 'ID',
          key: 'id',
          tooltip: true,
        });
        columns.push({
          title: '用户',
          key: 'nickname',
          tooltip: true,
        });
        columns.push({
          title: '联系方式',
          key: 'contact',
          tooltip: true,
        });
        columns.push({
          title: '内容',
          key: 'content',
          width: 500,
          tooltip: true,
        });
        columns.push({
          title: '时间',
          key: 'created_at',
          tooltip: true,
        });
        columns.push({
          title: 'IP',
          key: 'created_ip',
          tooltip: true,
        });
        // columns.push({
        //   title: '操作',
        //   key: 'action',
        //   width: 150,
        //   align: 'center',
        //   render: (h, params) => {
        //     return h('div', [
        //       h('Button', {
        //         props: {
        //           type: 'primary',
        //           size: 'small',
        //         },
        //         style: {
        //           marginRight: '5px'
        //         },
        //         on: {
        //           click: () => {
        //             this.gotoPage('msgView',{
        //               id:params.row.id
        //             })
        //           }
        //         }
        //       }, '详情'),
        //     ]);
        //   }
        // });
        return columns;
      }
    }
  }
</script>

<style scoped>

</style>