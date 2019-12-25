<template>
    <div class="content">
            <Card :bordered="false">
                <p slot="title">系统设置</p>
                <Tabs value="name1">
                    <TabPane label="基本设置" name="name1">
                        <Form ref="formItem" :model="formItem" :rules="ruleValidate" :label-width="80">
                            <FormItem label="站点名称" >
                                <Input v-model="formItem.title" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="站点域名">
                                <Input v-model="formItem.url" placeholder=""><span slot="prepend">http://</span></Input>
                            </FormItem>
                            <FormItem label="站点标题">
                                <Input v-model="formItem.seo_title" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="站点关键词">
                                <Input v-model="formItem.keywords" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="首页简介">
                                <Input v-model="formItem.home_title" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="站点描述">
                                <Input v-model="formItem.description" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="站点 LOGO">
                                <Input v-model="formItem.logo" disabled placeholder="" :value="uploadList[0].url"></Input>
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
                                <Button style="margin-left: 100px;margin-top: -68px;" @click="viewPic(formItem.logo)">查看图片</Button>
                            </FormItem>
                            <FormItem label="微信公众号二维码">
                                <Input v-model="formItem.qrcode" disabled placeholder="" :value="uploadList[0].url"></Input>
                                <p class="desc">支持上传的文件格式为 jpg·jpeg·png·gif 且文件大小不超过 {{file_size}} M</p>
                                <Upload
                                        ref="upload"
                                        :show-upload-list="false"
                                        :on-success="qrCodeHandleSuccess"
                                        :on-error="handleError"
                                        :max-size="file_size * 1024"
                                        :on-format-error="handleFormatError"
                                        :on-exceeded-size="handleMaxSize"
                                        :format="['jpg','jpeg','png', 'gif']"
                                        :action="uploadUrl">
                                    <Button icon="ios-cloud-upload-outline">上传</Button>
                                </Upload>
                                <Button style="margin-left: 100px;margin-top: -68px;" @click="viewPic(formItem.qrcode)">查看图片</Button>
                            </FormItem>
                            <FormItem label="公司名称">
                                <Input v-model="formItem.company_name" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="公司地址">
                                <Input v-model="formItem.company_address" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="客服邮箱">
                                <Input v-model="formItem.contact_email" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="客服 QQ">
                                <Input v-model="formItem.qq" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="联系人">
                                <Input v-model="formItem.contact_name" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="客服电话">
                                <Input v-model="formItem.contact_tel" placeholder=""></Input>
                                <p>客服电话在网站前台展示，请正确填写</p>
                            </FormItem>
                            <FormItem label="ICP 备案号">
                                <Input v-model="formItem.icp" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="统计代码">
                                <Input v-model="formItem.code" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder=""></Input>
                            </FormItem>
                            <FormItem>
                                <Button type="primary" @click="handleSubmit('formItem')">保存</Button>
                            </FormItem>
                        </Form>
                    </TabPane>
                    <TabPane label="安全设置" name="name3">
                        <Form ref="formItem" :model="formItem" :rules="ruleValidate" :label-width="80">
                            <FormItem label="站点状态">
                                <i-switch v-model="formItem.status" size="large">
                                    <span slot="true">开启</span>
                                    <span slot="false">关闭</span>
                                </i-switch>
                                <p>（站点维护，突发紧急状况），请关闭此选项。正常运营期间严禁关闭网站</p>
                            </FormItem>
                            <FormItem label="开放注册">
                                <i-switch v-model="formItem.isRegister" size="large">
                                    <span slot="true">开启</span>
                                    <span slot="false">关闭</span>
                                </i-switch>
                                <p>如果关闭注册，将不能注册新会员，已注册会员可以登录和其他操作不受影响。</p>
                            </FormItem>
                            <FormItem label="最大上传限制">
                                <Input v-model="formItem.file_size" placeholder=""></Input>
                                <p>（单位 MB 1G=1024MB），上传最大限制受服务器设置影响，如您不清楚，可以联系您的服务器供应商</p>
                            </FormItem>
                            <FormItem label="上传格式白名单">
                                <Input v-model="formItem.file_ext" placeholder=""></Input>
                                <p>因系统安全需要，禁止上传(.php, .js, .sql)类型的文件，后缀之间用英文逗号隔开，不在格式白名单的文件将被禁止上传</p>
                            </FormItem>
                            <FormItem label="URL 重写">
                                <i-switch v-model="formItem.rewrite" size="large">
                                    <span slot="true">开启</span>
                                    <span slot="false">关闭</span>
                                </i-switch>
                                <p>需要 Rewrite 支持，启用前请重命名根目录 .htaccess.txt 文件为 .htaccess</p>
                            </FormItem>
                            <FormItem label="登录验证">
                                <i-switch v-model="formItem.verify_code" size="large">
                                    <span slot="true">开启</span>
                                    <span slot="false">关闭</span>
                                </i-switch>
                                <p>设置（会员）登录验证，后台登录验证为永久开启状态，不提供关闭功能</p>
                            </FormItem>
                            <FormItem>
                                <Button type="primary" @click="handleSubmit('formItem')">保存</Button>
                            </FormItem>
                        </Form>
                    </TabPane>
                    <TabPane label="显示设置" name="name2">
                        <Form ref="formItem" :model="formItem" :rules="ruleValidate" :label-width="80">
                            <FormItem label="站点语言">
                                <Select v-model="formItem.lang">
                                    <Option value="zh_cn">中文简体</Option>
                                    <Option value="en_us">英文</Option>
                                    <Option value="zh_tw">中文繁体</Option>
                                </Select>
                            </FormItem>
                            <FormItem label="图片水印">
                                <i-switch v-model="formItem.watermark" size="large">
                                    <span slot="true">开启</span>
                                    <span slot="false">关闭</span>
                                </i-switch>
                            </FormItem>
                            <FormItem label="水印 LOGO">
                                <Input v-model="formItem.watermarkLogo" disabled placeholder=""></Input>
                                <p class="desc">支持上传的文件格式为 jpg·jpeg·png·gif 且文件大小不超过 {{file_size}} M</p>
                                <Upload
                                        ref="upload"
                                        :show-upload-list="false"
                                        :on-success="watermarkHandleSuccess"
                                        :on-error="handleError"
                                        :max-size="file_size * 1024"
                                        :on-format-error="handleFormatError"
                                        :on-exceeded-size="handleMaxSize"
                                        :format="['jpg','jpeg','png', 'gif']"
                                        :action="uploadUrl">
                                    <Button icon="ios-cloud-upload-outline">上传</Button>
                                </Upload>
                                <Button style="margin-left: 100px;margin-top: -68px;" @click="viewPic(formItem.watermarkLogo)">查看图片</Button>
                            </FormItem>
                            <FormItem label="水印位置">
                                <RadioGroup v-model="formItem.watermark_location">
                                    <Radio label="top">上</Radio>
                                    <Radio label="bottom">下</Radio>
                                    <Radio label="left">左</Radio>
                                    <Radio label="right">右</Radio>
                                    <Radio label="middle">中</Radio>
                                    <Radio label="random">随机</Radio>
                                </RadioGroup>
                            </FormItem>
                            <FormItem label="产品列表数量">
                                <Input v-model="formItem.product_list_num" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="产品属性">
                                <Input v-model="formItem.product_attribute" placeholder=""></Input>
                                <p>如"颜色,尺寸,型号"中间以英文逗号隔开</p>
                            </FormItem>
                            <FormItem label="新闻列表数量">
                                <Input v-model="formItem.news_list_num" placeholder=""></Input>
                            </FormItem>
                            <FormItem>
                                <Button type="primary" @click="handleSubmit('formItem')">保存</Button>
                            </FormItem>
                        </Form>
                    </TabPane>
                    <TabPane label="邮件服务器" name="name4">
                        <Form ref="formItem" :model="formItem" :rules="ruleValidate" :label-width="80">
                            <FormItem label="使用SMTP">
                                <i-switch v-model="formItem.smtp" size="large">
                                    <span slot="true">开启</span>
                                    <span slot="false">关闭</span>
                                </i-switch>
                            </FormItem>
                            <FormItem label="服务器">
                                <Input v-model="formItem.smtp_server" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="端口">
                                <Input v-model="formItem.smtp_port" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="开启SSL">
                                <i-switch v-model="formItem.smtp_ssl" size="large">
                                    <span slot="true">开启</span>
                                    <span slot="false">关闭</span>
                                </i-switch>
                            </FormItem>
                            <FormItem label="邮箱">
                                <Input v-model="formItem.smtp_mail" placeholder=""></Input>
                            </FormItem>
                            <FormItem label="密码"  prop="smtp_passwd">
                                <Input type="password" v-model="formItem.smtp_passwd" placeholder=""></Input>
                                <p>部分邮箱是授权码，如果您不清楚，请联系您的邮箱供应商</p>
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
          title: '',
          url: '',
          seo_title: '',
          keywords: '',
          description: '',
          home_title: '',
          logo: '',
          qrcode: '',
          company_name: '',
          company_address: '',
          contact_tel: '',
          contact_email: '',
          qq: '',
          contact_name:'',
          icp: '',
          code: '',
          status: true,
          isRegister:false,
          file_size:'',
          file_ext:'',
          rewrite: true,
          verify_code: false,
          lang: 'zh_cn',
          watermark: true,
          watermarkLogo: '',
          watermark_location: 'bottom',
          product_list_num: 10,
          product_attribute: '',
          news_list_num: 10,
          smtp: true,
          smtp_server: '',
          smtp_port: '',
          smtp_ssl: true,
          smtp_mail: '',
          smtp_passwd: '',
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
      this.getData()
    },
    mounted () {
    },
    methods: {
      getData()
      {
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
        this.formItem.logo = response.data.url
        this.$Message.success('上传成功')
      },
      qrCodeHandleSuccess (response) {
        this.formItem.qrcode = response.data.url
        this.$Message.success('上传成功')
      },
      watermarkHandleSuccess (response) {
        this.formItem.watermarkLogo = response.data.url
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
      query(data) {
        var url = this.api.user.view11;
        this.$axios.post(url, data)
          .then((response) => {
            if (response.data.code == 0)
            {
              this.$Message.success('操作成功');
            }else{
              this.$Message.error(response.data.msg);
            }
          })
      },
      handleSubmit (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            var url = this.api.common.setting;
            this.$axios.post(url, this.formItem)
              .then((response) => {
                if (response.data.code == 0)
                {
                  this.$Message.success('操作成功');
                  // 更新本地配置
                  this.$axios.get(this.api.common.localConfig)
                    .then((response) => {
                      if (response.data.code == 0)
                      {
                        localStorage.setItem('config', JSON.stringify(response.data.data))
                      }else{
                        // 更新本地配置不成功
                        this.$Message.error('数据同步到本地异常，请退出登录重新登录');
                      }
                    })
                }else{
                  this.$Message.error(response.data.msg);
                }
              })
          } else {
            this.$Message.error('请正确输入表单内容');
          }
        })
      },
      handleReset (name) {
        this.$refs[name].resetFields();
      }
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