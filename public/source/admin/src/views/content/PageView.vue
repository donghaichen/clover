<template>
    <div class="content">
        <Card :bordered="false">
            <p slot="title"></p>
            <Form ref="formItem" :model="formItem" :rules="ruleValidate" :label-width="80">
                <FormItem label="上级页面" prop="parent_id">
                    <Select v-model="formItem.parent_id" filterable>
                        <Option :value="0" :key="0">无上级 </Option>
                        <Option v-for="item in parentList" :value="item.id" :key="item.id">{{ item.mark + item.title}} </Option>
                    </Select>
                </FormItem>
                <FormItem label="标题" prop="title">
                    <Input v-model="formItem.title" placeholder=""></Input>
                </FormItem>
                <FormItem label="别名" prop="slug">
                    <Input v-model="formItem.slug" placeholder=""></Input>
                    <p>别名必须为英文，多个词用采用（中划线，下划线，大小写）组合 例如（test-category, test_category, testCategory）</p>
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
                <FormItem label="SEO 标题" prop="meta_title">
                    <Input v-model="formItem.meta_title" placeholder=""></Input>
                </FormItem>
                <FormItem label="SEO 关键词" prop="keywords">
                    <Input v-model="formItem.keywords" placeholder=""></Input>
                </FormItem>
                <FormItem label="SEO 描述" prop="description">
                    <Input v-model="formItem.description" placeholder=""></Input>
                </FormItem>
                <FormItem label="排序" prop="sort">
                    <InputNumber :min="0" :max="100000000" v-model="formItem.sort" placeholder=""
                                 style="width: 100%"
                    ></InputNumber >
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
  // string: Must be of type string. This is the default type.
  //   number: Must be of type number.
  //   boolean: Must be of type boolean.
  //   method: Must be of type function.
  // regexp: Must be an instance of RegExp or a string that does not generate an exception when creating a new RegExp.
  // integer: Must be of type number and an integer.
  //   float: Must be of type number and a floating point number.
  //   array: Must be an array as determined by Array.isArray.
  //   object: Must be of type object and not Array.isArray.
  //   enum: Value must exist in the enum.
  // date: Value must be valid as determined by Date
  // url: Must be of type url.
  //   hex: Must be of type hex.
  //   email: Must be of type email
  export default {
    components: {
      TinymceEditor
    },
    data () {
      return {
        file_size: JSON.parse(localStorage.getItem('config')).file_size,
        uploadUrl: this.api.common.upload,
        contentType: 'page',
        parentList: [],
        formItem: {
          id: 0,
          title: '',
          slug: '',
          image: '',
          meta_title: '',
          keywords: '',
          description: '',
          sort: 0,
          parent_id: 0,
        },
        ruleValidate: {
          title: [
            { required: true, message: '标题不能为空', trigger: 'blur' },
            { type: 'string', min: 2, message: '标题必须大于 2 个字', trigger: 'blur' },
            { type: 'string', max: 200, message: '标题必须小于200 个字', trigger: 'blur' },
          ],
          slug: [
            { required: true, message: '别名不能为空', trigger: 'blur' },
            { type: 'string', min: 2, message: '别名必须大于 2 个字', trigger: 'blur' },
            { type: 'string', max: 50, message: '别名称必须小于 50 个字', trigger: 'blur' },
            { type: "string", pattern: /^[a-zA-Z0-9_-]+$/, message: '别名不能包含特殊字符和中文（英文下划线 中划线 - _ 除外）', trigger: 'blur' }
          ],
          content: [
            { required: true, message: '内容不能为空', trigger: 'blur' },
          ],
        }
      }
    },
    created() {
      this.uploadUrl = this.uploadUrl + '?module=' + this.contentType
      //option 下拉框
      this.$axios.get(this.api.content.page)
        .then((response) => {
          if (response.data.code == 0)
          {
            this.parentList = response.data.data
          }else{
            this.$Message.error('数据获取失败，请检查');
          }
        })
      //表单内容
      if (this.$route.query.id > 0)
      {
        this.$axios.get(this.api.content.pageView + '/' + this.$route.query.id)
          .then((response) => {
            if (response.data.code == 0)
            {
              this.formItem = response.data.data
              this.parent_id = response.data.data.parent_id
              window.console.log(this.parent_id)
            }else{
              this.$Message.error('数据获取失败，请检查');
            }
          })
      }
    },
    methods: {
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
      imgUrl(src)
      {
        window.console.log(src.length)
        if(src.length <= 0)
        {
          this.$Message.error('尚未上传图片，无法查看')
          return false
        }
        if(src.substr(0,4).toLowerCase() != "http")
        {
          src = this.api.static.baseUrl + src;
        }
        return src
      },
      viewPic (src) {
        if(src.length <= 0)
        {
          this.$Message.error('尚未上传图片，无法查看')
          return false
        }
        src = this.imgUrl(src)
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
      gotoPage: function(name, params = {}){
        this.$router.push({  //核心语句
          name:name,   //跳转的路径
          query:params,   //跳转的参数
        })
      },
      handleSubmit (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            var url = this.api.content.pageSave;
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