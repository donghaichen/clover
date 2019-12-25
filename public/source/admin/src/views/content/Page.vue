<template>
    <div class="content">
        <h1><router-link :to="{name: 'pageView'}"><Button type="primary">新建</Button></router-link></h1>
        <Row>
            <Col :xs="24" :sm="12" :md="6" :lg="8" :xl="4" style="text-align: center" v-for="(item, index) in list" :key="index">
                <Card :bordered="false" style="margin: 10px" :class="'page page'+item.level">
                    <Badge type="normal" :count="item.level+1" style="position: absolute;top: 0;left: 0;"></Badge>
                    <Divider>{{item.title}}</Divider>
                    <p style="margin: 10px 0">别名：{{item.slug}}</p>
                    <p style="margin: 10px 0">
                        <Button @click.native="userOperate('edit', item.id)" type="primary" ghost>编辑</Button>
                        <Button @click.native="userOperate('delete', item.id)"  style="margin-left: 10px" type="error" ghost>删除</Button>
                    </p>

                </Card>
            </Col>
        </Row>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        list: [],
      }
    },
    created() {
       this.getData()
      },
    methods: {
      getData: function()
      {
        this.$axios.get(this.api.content.page)
          .then((response) => {
            if (response.data.code == 0)
            {
              this.list = response.data.data
            }else{
              this.$Message.error('数据获取失败，请检查');
            }
          })
      },
      gotoPage: function(name, params = {}){
        this.$router.push({  //核心语句
          name:name,   //跳转的路径
          query:params,   //跳转的参数
        })
      },
      // 用户操作
      userOperate(name, id) {
        switch(name) {
          case 'delete':
            var url = this.api.content.pageDelete
            this.$axios.delete(url + '/' + id)
              .then((response) => {
                if (response.data.code == 0)
                {
                  this.getData()
                  this.$Message.success('删除成功');
                }else{
                  this.$Message.error(response.data.msg);
                }
              })
            break
          default:
            this.gotoPage('pageView',{
              id:id
            })
            break
        }
      },
    }
  }
</script>

<style scoped>
.badge{

}
.page{

}
.page0{
    background: rgba(255,255,255,1);
}
.page1{

    background: #e0e0e0;
}

.page2{
    background: rgba(255,255,255,.4);
}
.page3{
    background: rgba(255,255,255,.3);
}
.page4{
    background: white;
}

</style>
