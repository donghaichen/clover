<template>
    <div class="content">
        <Card :bordered="false">
            <Table border :columns="columns1" :data="data1"></Table>
<!--            <p style="margin: 20px 50px;">标题： {{data.title}}</p>-->
<!--            <p style="margin: 20px 50px;">用户： {{data.nickname}}</p>-->
<!--            <p style="margin: 20px 50px;">联系方式： {{data.contact}}</p>-->
<!--            <p style="margin: 20px 50px;">内容： {{data.content}}</p>-->
<!--            <p style="margin: 20px 50px;">时间： {{data.created_at}}</p>-->
<!--            <p style="margin: 20px 50px;">IP ： {{data.created_ip}}</p>-->
        </Card>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        columns1: [
          {
            title: '标题：',
            key: 'name'
          },
          {
            title: '用户：',
            key: 'age'
          },
          {
            title: 'Address',
            key: 'address'
          }
        ],
        data1: [
          {
            name: 'John Brown',
            age: 18,
            address: 'New York No. 1 Lake Park',
            date: '2016-10-03'
          },
          {
            name: 'Jim Green',
            age: 24,
            address: 'London No. 1 Lake Park',
            date: '2016-10-01'
          },
          {
            name: 'Joe Black',
            age: 30,
            address: 'Sydney No. 1 Lake Park',
            date: '2016-10-02'
          },
          {
            name: 'Jon Snow',
            age: 26,
            address: 'Ottawa No. 2 Lake Park',
            date: '2016-10-04'
          }
        ]
      }
    },
    created: function () {
      this.tableData = this.getData(1)
    },
    methods: {
      getData() {
          let id = this.$route.query.id
        if (!id > 0)
        {
          this.$Message.error('请不要刷新该页面，从列表进入');
        }
          this.$axios.get(this.api.common.msg + '/' + id)
            .then((response) => {
              if (response.data.code == 0)
              {
                this.data = response.data.data
              }else{
                this.$Message.error('数据获取失败，请检查');
              }
            })
      },
    },
  }
</script>

<style scoped>

</style>