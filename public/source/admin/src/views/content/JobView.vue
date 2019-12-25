<template>
    <div class="content">
        <Card :bordered="false">
            <p slot="title"></p>
            <Form ref="formItem" :model="formItem" :rules="ruleValidate" :label-width="80">
                <FormItem label="所属栏目" prop="category_id">
                    <Cascader :data="cascader" v-model="formItem.category_id" :filterable="true" change-on-select></Cascader>
                    <p>所属栏目必选，发布到顶级栏目可以选择无上级</p>
                </FormItem>
                <FormItem label="标题" prop="title">
                    <Input v-model="formItem.title" placeholder=""></Input>
                </FormItem>
                <FormItem label="缩略图" prop="image">
                    <Input v-model="formItem.image" disabled placeholder="请上传缩略图"></Input>
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
                    <Button style="margin-left: 100px;margin-top: -68px;" @click="viewPic(formItem.image)">查看图片</Button>
                </FormItem>
                <FormItem label="内容" prop="content">
                    <tinymce-editor ref="editor"
                                    v-model="formItem.content"
                    >
                    </tinymce-editor>
                </FormItem>

                <FormItem>
                    <FormItem>
                        <Button type="primary" @click="handleSubmit('formItem')">保存</Button>
                        <Button @click="handleReset('formItem')" style="margin-left: 8px">重置</Button>
                    </FormItem>
                </FormItem>
            </Form>
        </Card>
    </div>
</template>

<script>
  import TinymceEditor from '../../components/Tinymce'
  export default {
    components: {
      TinymceEditor
    },
    data () {
      return {
        file_size: JSON.parse(localStorage.getItem('config')).file_size,
        uploadUrl: this.api.common.upload,
        contentType: 'job',
        cascader: [],
        formItem: {
          id: 0,
          title: '',
          image: '',
          content: '',
          category_id: [0],
        },
        ruleValidate: {
          title: [
            { required: true, message: '标题不能为空', trigger: 'blur' },
            { type: 'string', min: 2, message: '标题必须大于 2 个字', trigger: 'blur' },
            { type: 'string', max: 200, message: '标题必须小于200 个字', trigger: 'blur' },
          ],
          // category_id: [
          //   { required: true, message: '所属栏目必选', trigger: 'blur' },
          // ],
        }
      }
    },
    created() {
      this.uploadUrl = this.uploadUrl + '?module=' + this.contentType

      // 所属栏目
      var url = this.api.category.cascaderContent + this.contentType;
      this.$axios.get(url)
        .then((response) => {
          if (response.data.code == 0)
          {
            this.cascader = response.data.data
          }else{
            this.$Message.error('数据获取失败，请检查');
          }
        })
      //表单内容
      if (this.$route.query.id > 0)
      {
        this.$axios.get(this.api.content.view + this.contentType + '/' + this.$route.query.id)
          .then((response) => {
            if (response.data.code == 0)
            {
              this.formItem = response.data.data
            }else{
              this.$Message.error('数据获取失败，请检查');
            }
          })
      }
    },
    methods: {
      viewPic (src) {
        if(src.length <= 0)
        {
          this.$Message.error('尚未上传图片，无法查看')
          return false
        }
        if(src.substr(0,4).toLowerCase() != "http")
        {
          src = this.api.static.baseUrl + src;
        }
        const title = '查看图片';
        const content = '<img style="width: 88%" src="' + src +'">';
        this.$Modal.info({
          title: title,
          content: content
        });
      },
      handleSuccess (response) {
        this.formItem.image = response.data.url
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
      // 清空内容
      clear () {
        this.$refs.editor.clear()
      },
      format (labels, selectedData) {
        const index = labels.length - 1;
        const data = selectedData[index] || false;
        if (data && data.code) {
          return labels[index] + ' - ' + data.code;
        }
        return labels[index];
      },
      query(data) {
        var url = this.api.user.view;
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
      gotoPage: function(name, params = {}){
        this.$router.push({  //核心语句
          name:name,   //跳转的路径
          query:params,   //跳转的参数
        })
      },
      handleSubmit (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            var url = this.api.content.save + this.contentType + '/' + this.formItem.id;
            this.$axios.post(url, this.formItem)
              .then((response) => {
                if (response.data.code == 0)
                {
                  this.$Message.success('操作成功');
                  setTimeout(() => {
                    this.gotoPage(this.contentType)
                  }, 1000)
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

<style scoped>

</style>