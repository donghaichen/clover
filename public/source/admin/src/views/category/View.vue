<template>
    <div class="content">
        <Card :bordered="false">
            <p slot="title"></p>
            <Form ref="formItem" :model="formItem" :rules="ruleValidate" :label-width="80">
                <FormItem label="上级栏目" prop="parent_id">
                    <Cascader :data="cascader" :disabled="disabled" v-model="formItem.parent_id" :filterable="true" change-on-select></Cascader>
                    <p v-if="disabled">该栏目为系统核心数据 ，父级栏目不允不许修改</p>
                    <p v-if="disabled == false">上级栏目每个节点都可以选择，请注意正确选择合理的上级栏目, 默认和不选为无上级，创建顶级栏目</p>
                </FormItem>
                <FormItem label="标题" prop="name">
                    <Input v-model="formItem.name" placeholder=""></Input>
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
                <FormItem label="启用" prop="is_show">
                    <i-switch v-model="formItem.is_show" size="large">
                        <span slot="true">启用</span>
                        <span slot="false">禁用</span>
                    </i-switch>
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
  export default {
    data () {
      return {
        disabled: false,
        cascader: [],
        formItem: {
          id: 0,
          name: '',
          meta_title: '',
          keywords: '',
          description: '',
          sort: 0,
          is_show: true,
          parent_id: [0],
        },
        ruleValidate: {
          name: [
            { required: true, message: '标题不能为空', trigger: 'blur' },
            { type: 'string', min: 2, message: '标题必须大于 2 个字', trigger: 'blur' },
            { type: 'string', max: 200, message: '标题必须小于200 个字', trigger: 'blur' },
          ],
          // sort: [
          //   { type: 'number', message: '排序必须为整数', trigger: 'blur' },
          // ]
        }
      }
    },
    created() {
      var url = this.api.category.cascader;
      this.$axios.get(url)
        .then((response) => {
          if (response.data.code == 0)
          {
            this.cascader = response.data.data
          }else{
            this.$Message.error('数据获取失败，请检查');
          }
        })
      if (this.$route.query.id > 0)
      {
        this.$axios.get(this.api.category.view + '/' + this.$route.query.id)
          .then((response) => {
            if (response.data.code == 0)
            {
              this.formItem = response.data.data
              if (response.data.data.id > 0 && response.data.data.id <= 6)
              {
                this.disabled = true
              }
            }else{
              this.$Message.error('数据获取失败，请检查');
            }
          })
      }
    },
    methods: {
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
      getData() {
        this.$axios.get(this.api.category.list)
          .then((response) => {
            this.tableData = response.data.data
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
            var url = this.api.category.save;
            this.$axios.post(url, this.formItem)
              .then((response) => {
                if (response.data.code == 0)
                {
                  this.$Message.success('操作成功');
                  setTimeout(() => {
                    this.gotoPage('category')
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