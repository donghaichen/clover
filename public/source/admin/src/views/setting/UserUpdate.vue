<template>
    <div class="content">
        <Card :bordered="false">
            <p slot="title">
            </p>
            <Form ref="formItem" :model="formItem" :rules="ruleValidate" :label-width="80">
                <Alert type="error" show-icon  v-if="isUpdate === false">
                    您没有权限编辑此页面
                    <span slot="desc">只有超级用户 admin 有新建用户和编辑其他用户权限，其他用户只允许编辑自己资料</span>
                </Alert>
                <FormItem label="用户名" prop="username">
                    <Input v-model="formItem.username" placeholder="用户名用来登录，不能包含特殊字符和中文"></Input>
                </FormItem>
                <FormItem label="密码" prop="password">
                    <Input type="password" v-model="formItem.password" placeholder="请输入密码"></Input>
                    <p>创建用户，密码必填</p>
                    <p>编辑用户，密码不填则为不修改</p>
                </FormItem>
                <FormItem label="昵称" prop="nickname">
                    <Input v-model="formItem.nickname" placeholder="请输入昵称"></Input>
                </FormItem>
                <FormItem label="手机" prop="mobile">
                    <Input v-model="formItem.mobile" placeholder="请输入 手机号码"></Input>
                </FormItem>
                <FormItem label="Email" prop="email">
                    <Input v-model="formItem.email" placeholder="请输入 Email"></Input>
                </FormItem>
                <FormItem label="个人主页" prop="url">
                    <Input v-model="formItem.url" placeholder="请输入个人主页"></Input>
                </FormItem>
                <FormItem label="头像" prop="avatar">
                    <Input v-model="formItem.avatar" disabled placeholder="请上传头像"></Input>
                    <p class="desc">支持上传的文件格式为 jpg·jpeg·png·gif 且文件大小不超过 {{file_size}} M</p>
                    <Upload v-if="isUpdate"
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
                    <Button v-if="isUpdate" style="margin-left: 100px;margin-top: -68px;" @click="viewPic(formItem.avatar)">查看图片</Button>
                </FormItem>
                <FormItem label="个人简介" prop="desc">
                    <Input v-model="formItem.desc" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请输入个人简介"></Input>
                </FormItem>
                <FormItem label="状态" prop="status"  v-if="isAdmin">
                    <RadioGroup v-model="formItem.status">
                        <Radio label="1">启用</Radio>
                        <Radio label="2">禁用</Radio>
                    </RadioGroup>
                </FormItem>
                <FormItem v-if="isUpdate">
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
  export default {
    data () {
      return {
        file_size: JSON.parse(localStorage.getItem('config')).file_size,
        isUpdate: false,
        isAdmin: true,
        adminUid: 1,
        uid: 0,
        uploadUrl: this.api.common.upload,
        formItem: {
          // id: 0,
          username: '',
          mobile: '',
          password: '',
          nickname: '',
          email: '',
          is_admin: 1,
          url: '',
          avatar: '',
          desc: '',
          status: '1',

        },
        ruleValidate: {
          username: [
            { required: true, message: '用户名不能为空', trigger: 'blur' },
            { type: 'string', min: 2, message: '用户名必须大于 2 个字', trigger: 'blur' },
            { type: 'string', max: 50, message: '用户名必须小于 50 个字', trigger: 'blur' },
            { type: "string", pattern: /^[a-zA-Z0-9]+$/, message: '用户名不能包含特殊字符和中文', trigger: 'blur' }
          ],
          mobile: [
            { required: true, message: '手机号不能为空', trigger: 'blur' },
            { type: 'string', min: 11, message: '手机必须手机 11 个字', trigger: 'blur' },
            { type: 'string', max: 11, message: '手机必须手机 11 个字', trigger: 'blur' }
          ],
          nickname: [
            { type: 'string', min: 2, message: '昵称必须大于 2 个字', trigger: 'blur' },
            { type: 'string', max: 50, message: '昵称必须小于 50 个字', trigger: 'blur' }
          ],
          password: [
            // { required: true, message: '密码不能为空', trigger: 'blur' },
            { type: 'string', min: 5, message: '密码必须大于 5 个字', trigger: 'blur' },
            { type: 'string', max: 25, message: '密码必须小于 25 个字', trigger: 'blur' }
          ],
          email: [
            { type: 'email', message: '邮箱格式错误', trigger: 'blur' }
          ],
          url: [
            { type: 'url', message: '个人主页格式错误', trigger: 'blur' }
          ],
          desc: [
            { type: 'string', min: 2, message: '个人简介必须大于 2 个字', trigger: 'blur' }
          ]
        }
      }
    },
    created() {
      this.uploadUrl = this.uploadUrl + '?module=user'
      if (this.$route.query.uid == localStorage.getItem('uid'))
      {
        this.isUpdate = true
        this.isAdmin = false
      }
      if (localStorage.getItem('uid') == 1)
      {
        this.isUpdate = true
      }
      var url = this.api.user.view;
      if (this.$route.query.uid > 0)
      {
        var uid = this.$route.query.uid
        this.$axios.get(url + '/' + uid)
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
    mounted() {
      if (this.$route.query.uid > 0) {
        var uid = this.$route.query.uid
        this.uid = uid
      }
    },
    watch: {
      // //@todo 在编辑个人资料页面点击右上角个人资料uid不改变
      // uid:function(old,newd){
      //   window.console.log(old)
      //   window.console.log(newd)
      // }
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
        this.formItem.avatar = response.data.url
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
            var url = this.api.user.save;
            this.$axios.post(url, this.formItem)
              .then((response) => {
                if (response.data.code == 0)
                {
                  this.$Message.success('操作成功');
                  setTimeout(() => {
                    this.gotoPage('dashboard')
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