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
        this.$axios.get(this.api.log.admin + '?page=' + page)
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
        if (this.showCheckbox) {
          columns.push({
            type: 'selection',
            width: 60,
            align: 'center'
          })
        }
        columns.push({
          title: '日志ID',
          key: 'id',
          sortable: true
        });
        columns.push({
          title: '用户名',
          key: 'username',
          tooltip: true,
        });
        columns.push({
          title: '内容',
          key: 'info',
          width: 380,
          tooltip: true,
          render: (h, params) => {
              return h("div", [
                h(
                  "Tag",
                  {
                    props: {
                      color: "blue"
                    }
                  },
                  params.row.info
                )
              ]);
          }
        });
        columns.push({
          title: '请求类型',
          key: 'method',
          sortable: true,
          filters: [
          {
            label: "GET",
            value: "GET"
          },
          {
            label: "POST",
            value: "POST"
          },
          {
            label: "PUT",
            value: "PUT"
          },
          {
            label: "DELETE",
            value: "DELETE"
          }
        ],
          filterMultiple: false,
          filterMethod(value, row) {
          if (value == "GET") {
            return row.method == "GET";
          } else if (value == "POST") {
            return row.method == "POST";
          } else if (value == "PUT") {
            return row.method == "PUT";
          } else if (value == "DELETE") {
            return row.method == "DELETE";
          }
        }
        });
        columns.push({
          title: '控制器',
          key: 'controller'
        });
        columns.push({
          title: '方法',
          key: 'action'
        });
        columns.push({
          title: '操作 IP',
          key: 'created_ip',
        });
        // columns.push({
        //   title: 'IP 地址',
        //   key: 'ipInfo',
        // });
        columns.push({
          title: '操作时间',
          key: 'created_at',
          sortable: true,
        });
        return columns;
      }
    }
  }
</script>

<style scoped>

</style>