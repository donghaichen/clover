<template>
    <div class="content">
        <Card :bordered="false">
            <p slot="title"></p>
            <Form ref="formDynamic" :model="formDynamic" :label-width="80" style="100%">
                <FormItem
                        v-for="(item, index) in formDynamic.items"
                        :key="index"
                        :label="'链接 ' + item.index"
                        :rules="{}"
                >
                    <Row>
                        <Col span="1" style="text-align: center">名称</Col>
                        <Col span="4">
                            <Input type="text" v-model="item.title" placeholder="名称"></Input>
                        </Col>
                        <Col span="1" style="text-align: center">图片</Col>
                        <Col span="4">
                            <Input type="text" v-model="item.src" placeholder="图片"></Input>
                        </Col>
                        <Col span="1" style="text-align: center">链接</Col>
                        <Col span="4">
                            <Input type="text" v-model="item.href" placeholder="链接"></Input>
                        </Col>
                        <Col span="1" style="text-align: center">排序</Col>
                        <Col span="1">
                            <Input type="text" v-model="item.sort" placeholder="排序"></Input>
                        </Col>
                        <Col span="1" style="text-align: center">状态</Col>
                        <Col span="1">
                            <i-switch v-model="item.status" size="large">
                                <span slot="open">开启</span>
                                <span slot="close">关闭</span>
                            </i-switch>
                        </Col>
                        <div style="display: none"><Input v-model="type" type="text"></Input></div>
<!--                        <Col span="2" offset="1">-->
<!--                            <Button @click="handleRemove(index)">删除</Button>-->
<!--                        </Col>-->
                    </Row>
<!--                    <p style="    padding: 10px;-->
<!--    width: 200px;-->
<!--    padding-left: 62px;">-->
<!--                        <Upload-->
<!--                                :data="{index:item.index - 1}"-->
<!--                                :show-upload-list="false"-->
<!--                                :on-success="handleSuccess"-->
<!--                                :action="uploadUrl"-->
<!--                        >-->
<!--                            <Button icon="ios-cloud-upload-outline">上传</Button>-->
<!--                        </Upload>-->
<!--                    </p>-->
                </FormItem>
                <FormItem>
                    <Row>
                        <Col span="12">
                            <Button type="dashed" long @click="handleAdd" icon="md-add">添加更多</Button>
                        </Col>
                    </Row>
                </FormItem>
                <FormItem>
                    <Button type="primary" @click="handleSubmit('formDynamic')">提交</Button>
                    <Button @click="handleReset('formDynamic')" style="margin-left: 8px">重置</Button>
                </FormItem>
            </Form>
        </Card>
    </div>
</template>

<script>
  export default {
    data () {
      return {
        type: 'link',
        uploadUrl: this.api.common.upload,
        index: 1,
        formDynamic: {
          items: [
          ]
        }
      }
    },
    created() {
      this.uploadUrl = this.uploadUrl + '?module=banner'
      this.$axios.get(this.api.marketing.link)
        .then((response) => {
          if (response.data.code == 0)
          {
            this.formDynamic.items = response.data.data
          }else{
            this.$Message.error('数据获取失败，请检查');
          }
        })
    },
    methods: {
      // handleSuccess (response) {
      //   var index = response.data.additional.index
      //
      //   this.formDynamic.items[index].src = response.data.url
      //   this.$Message.success('上传成功')
      // },
      handleSubmit () {
        this.$axios.post(this.api.marketing.link, {data: JSON.stringify(this.formDynamic.items)})
          .then((response) => {
            if (response.data.code == 0)
            {
              this.$Message.success('提交成功');
            }else{
              this.$Message.error('提交失败，请重试');
            }
          })
      },
      handleReset (name) {
        this.$refs[name].resetFields();
      },
      handleAdd () {
        this.index++;
        this.formDynamic.items.push({
          value: '',
          index: this.index,
          status: 1
        });
      },
      handleRemove (index) {
        this.formDynamic.items[index].status = 0;
      }
    },
    mounted () {
    }
  }
</script>

<style scoped>

</style>