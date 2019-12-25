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
        this.$axios.get(this.api.developer.runningLog + '?page=' + page)
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
          title: '内容',
          key: 'content',
          tooltip: true,
        });
        return columns;
      }
    }
  }
</script>

<style scoped>

</style>