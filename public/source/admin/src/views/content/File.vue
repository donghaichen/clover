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
        this.$axios.get(this.api.common.file + '?page=' + page)
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
      }
    },
    computed: {
      tableColumns () {
        let columns = [];
        columns.push({
          title: '文件ID',
          key: 'id',
          width: 120,
          sortable: true
        });
        columns.push({
          title: '原始文件名',
          key: 'name',
          tooltip: true

        });
        columns.push({
          title: '地址',
          key: 'src',
          tooltip: true
        });
        columns.push({
          title: '类型',
          key: 'type',
          width: 120,
          sortable: true,
          tooltip: true
        });
        columns.push({
          title: '大小',
          key: 'size',
          align: 'center',
          tooltip: true,
          width: 150,
          render: (h, params) => {
            const row = params.row;
            const color = 'success';
            const text = (row.size / 1024 / 1024).toFixed(4) + ' M';
            return h('Tag', {
              props: {
                type: 'dot',
                color: color
              }
            }, text);
          }
        });
        columns.push({
          title: '业务模块',
          key: 'module',
          width: 120,
        });
        columns.push({
          title: '操作 IP',
          key: 'created_ip',
          tooltip: true,
        });
        columns.push({
          title: '操作时间',
          key: 'created_at',
          sortable: true,
          tooltip: true,
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
                  size: 'small'
                },
                style: {
                  marginRight: '5px'
                },
                on: {
                  click: () => {
                    window.open(this.api.static.baseUrl + params.row.src,'_blank')
                  }
                }
              }, '查看'),
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