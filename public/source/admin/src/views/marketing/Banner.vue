<template>
    <div class="content">
        <Card :bordered="false">
            <p slot="title"></p>
            <Carousel v-model="value1" style="width: 1200px;height: 290px;margin: 0 0 50px 100px" :height="290" autoplay loop>
                <CarouselItem v-for="(item, index) in formDynamic.items" v-if="item.status" >
                    <div class="demo-carousel"><img width="1200px" height="290px" :src="static + item.src"/></div>
                </CarouselItem>
            </Carousel>
            <Form ref="formDynamic" :model="formDynamic" :label-width="80" style="100%">
                <FormItem
                        v-for="(item, index) in formDynamic.items"
                        :key="index"
                        :label="'Bannner ' + item.index"
                        :rules="{}"
                >
                    <Row>
                        <Col span="1" style="text-align: center">图片</Col>
                        <Col span="6">
                            <Input type="text" disabled v-model="item.src" placeholder="图片"></Input>
                        </Col>
                        <Col span="1" style="text-align: center">链接</Col>
                        <Col span="6">
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
                    </Row>
                    <p style="    padding: 10px;
    width: 200px;
    padding-left: 62px;">
                        <Upload
                                :data="{index:item.index - 1}"
                                :show-upload-list="false"
                                :on-success="handleSuccess"
                                :action="uploadUrl"
                        >
                            <Button icon="ios-cloud-upload-outline">上传</Button>
                        </Upload>
                        <Button style="margin-left: 100px;margin-top: -68px;" @click="viewPic(item.src)">查看图片</Button>
                    </p>
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
        static: this.api.static.baseUrl,
        value1: 0,
        type: 'banner',
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

      this.$axios.get(this.api.marketing.banner)
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
      viewPic (src) {
        if(src.length <= 0)
        {
          this.$Message.error('尚未上传图片，无法查看')
          return false
        }
        const title = '查看图片';
        const content = '<img style="width: 85%" src="' +this.api.static.baseUrl + src +'">';
        this.$Modal.info({
          title: title,
          content: content
        });
      },
      handleSuccess (response) {
        var index = response.data.additional.index

        this.formDynamic.items[index].src = response.data.url
        this.$Message.success('上传成功')
      },
      handleSubmit () {
        this.$axios.post(this.api.marketing.banner, {data: JSON.stringify(this.formDynamic.items)})
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
    },
    mounted () {
    }
  }
</script>

<style scoped>

</style>