<template>
    <div class="content">
        <Card :bordered="false">
            <Tabs value="name1">
                <TabPane label="基本设置" name="name1">
                    <Form ref="formItem" :model="formItem" :rules="ruleValidate" :label-width="80">
                        <FormItem label="站点标题">
                            <Input v-model="formItem.h5_seo_title" placeholder=""></Input>
                        </FormItem>
                        <FormItem label="站点关键词">
                            <Input v-model="formItem.h5_keywords" placeholder=""></Input>
                        </FormItem>
                        <FormItem label="站点描述">
                            <Input v-model="formItem.h5_description" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder=""></Input>
                        </FormItem>
                        <FormItem label="站点 LOGO">
                            <Input v-model="formItem.h5_logo" disabled placeholder="" :value="uploadList[0].url"></Input>
                            <p class="desc">支持上传的文件格式为 jpg·jpeg·png·gif 且文件大小不超过 {{file_size}} M</p>
                            <Upload
                                    ref="upload"
                                    :show-upload-list="false"
                                    :on-success="handleSuccess"
                                    :on-error="handleError"
                                    :max-size="file_size * 1024"
                                    :on-format-error="handleFormatError"
                                    :on-exceeded-size="handleMaxSize"
                                    :format="['jpg','jpeg','png', 'gif']"
                                    :action="uploadUrl">
                                <Button icon="ios-cloud-upload-outline">上传</Button>
                            </Upload>
                            <Button style="margin-left: 100px;margin-top: -68px;" @click="viewPic(formItem.h5_logo)">查看图片</Button>
                        </FormItem>
                        <FormItem label="产品列表数量">
                            <Input v-model="formItem.h5_products_list_num" placeholder=""></Input>
                        </FormItem>
                        <FormItem label="新闻列表数量">
                            <Input v-model="formItem.h5_news_list_num" placeholder=""></Input>
                        </FormItem>
                        <FormItem>
                            <Button type="primary" @click="handleSubmit('formItem')">保存</Button>
                        </FormItem>
                    </Form>
                </TabPane>

            </Tabs>
        </Card>
    </div>
</template>

<script>
  export default {
    data () {
      return {
        file_size: JSON.parse(localStorage.getItem('config')).file_size,
        uploadUrl: this.api.common.upload,
        formItem: {
          h5_seo_title: '',
          h5_keywords: '',
          h5_description: '',
          h5_logo: '',
          h5_products_list_num: 10,
          h5_news_list_num: 10,
        },
        ruleValidate: {

        },
        uploadList: [
          {
            url: ''
          }
        ],
      }
    },
    created() {
      this.uploadUrl = this.uploadUrl + '?module=setting'
      var url = this.api.common.setting;
      this.$axios.get(url)
        .then((response) => {
          if (response.data.code == 0)
          {
            this.formItem = response.data.data
          }else{
            this.$Message.error('数据获取失败，请检查');
          }
        })
    },
    mounted () {
    },
    methods: {
      viewPic (src) {
        if(src.length <= 0)
        {
          this.$Message.error('尚未上传图片，无法查看')
          return false
        }
        const title = '查看图片';
        const content = '<img style="width: 88%" src="' +this.api.static.baseUrl + src +'">';
        this.$Modal.info({
          title: title,
          content: content
        });
      },
      handleSuccess (response) {
        this.formItem.h5_logo = response.data.url
        this.$Message.success('上传成功')
      },
      handleError (event) {
        window.console.log(event)
        this.$Message.error('上传错误，请重新上传')

      },
      handleFormatError (file) {
        this.$Notice.warning({
          title: '文件格式错误',
          desc: '文件： ' + file.name + ' 格式错误, 请上传允许的文件.'
        });
      },
      handleMaxSize (file) {
        this.$Notice.warning({
          title: '文件大小超过限制',
          desc: '文件：  ' + file.name + ' 不超过' +  this.file_size + 'M'
        });
      },
      handleSubmit (name) {
        var url = this.api.common.setting;
        this.$axios.post(url, this.formItem)
          .then((response) => {
            if (response.data.code == 0)
            {
              this.$Message.success('操作成功');
              // 更新本地配置
              let config = JSON.stringify(this.formItem)
              localStorage.setItem('config', config)
            }else{
              this.$Message.error(response.data.msg);
            }
          })
      },
    }
  }
</script>

<style>
    .ivu-form
    {
        padding: 20px;
    }
    .ivu-form .ivu-form-item-label
    {
        text-align: left;
        width: 120px!important;
    }
    .ivu-form .ivu-form-item-content{
        margin-left: 130px!important;
    }
</style>