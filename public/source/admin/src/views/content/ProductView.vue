<template>
    <div class="content">
        <Card :bordered="false">
            <Tabs value="name1">
                <TabPane label="基本信息" name="name1">
                    <p slot="title"></p>
                    <Form ref="formItem" :model="formItem" :rules="ruleValidate" :label-width="80">
                        <FormItem label="所属栏目" prop="category_id">
                            <Cascader :data="cascader" v-model="formItem.category_id" :filterable="true" change-on-select></Cascader>
                            <p>所属栏目必选，发布到顶级栏目可以选择无上级</p>
                        </FormItem>
                        <FormItem label="标题" prop="title">
                            <Input v-model="formItem.title" placeholder=""></Input>
                        </FormItem>
<!--                        <FormItem label="产品类别" prop="category">-->
<!--                            <Select v-model="formItem.category" style="width:100%">-->
<!--                                <Option v-for="(value, key, index) in searchCriteria.category" :value="key" :key="key">{{ value }}</Option>-->
<!--                            </Select>-->
<!--                        </FormItem>-->
<!--                        <FormItem label="产品行数" prop="row">-->
<!--                            <Select v-model="formItem.row" style="width:100%">-->
<!--                                <Option v-for="(value, key, index) in searchCriteria.row" :value="key" :key="key">{{ value }}</Option>-->
<!--                            </Select>-->
<!--                        </FormItem>-->
<!--                        <FormItem label="产品间距（MM)" prop="pitch">-->
<!--                            <Select v-model="formItem.pitch" style="width:100%">-->
<!--                                <Option v-for="(value, key, index) in searchCriteria.pitch" :value="key" :key="key">{{ value }}</Option>-->
<!--                            </Select>-->
<!--                        </FormItem>-->
<!--                        <FormItem label="额定电流（最大值）" prop="electric_current">-->
<!--                            <Select v-model="formItem.electric_current" style="width:100%">-->
<!--                                <Option v-for="(value, key, index) in searchCriteria.electric_current" :value="key" :key="key">{{ value }}</Option>-->
<!--                            </Select>-->
<!--                        </FormItem>-->
<!--                        <FormItem label="安装类型" prop="installation_method">-->
<!--                            <Select v-model="formItem.installation_method" style="width:100%">-->
<!--                                <Option v-for="(value, key, index) in searchCriteria.installation_method" :value="key" :key="key">{{ value }}</Option>-->
<!--                            </Select>-->
<!--                        </FormItem>-->
                        <FormItem label="文档" prop="doc">
                            <p>请在本地修改好文件名再上传，不支持在线修改文件名</p>
                            <p>允许的文件格式为['jpg','jpeg','png', 'gif', 'rar','zip','tar.gz','exe','txt','doc','docx','xls','xlsx','ppt','pdf'] 且文件大小不超过 {{file_size}} M</p>
                            <Upload
                                    multiple
                                    type="drag"
                                    :on-success="handleSuccessDoc"
                                    :on-error="handleError"
                                    :max-size="file_size * 1024"
                                    :on-format-error="handleFormatError"
                                    :on-exceeded-size="handleMaxSize"
                                    :format="['jpg','jpeg','png', 'gif', 'rar','zip','tar.gz','exe','txt','doc','docx','xls','xlsx','ppt','pdf']"
                                    :action="uploadDocUrl">
                                <div style="padding: 20px 0">
                                    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                    <p>单击或拖动文件以上传</p>
                                </div>
                            </Upload>
                            <List
                                    v-if="this.$route.query.id > 0 && doc.length > 0"
                                    header="已上传成功的文档，如重新上传文档，会覆盖之该产品前上传的所有文档，请谨慎操作，如需修改文档，请上传该产品所有文档"
                                    size="small"
                            >
                                <ul class="ivu-upload-list">
                                    <li v-for="(item, index) in doc" class="ivu-upload-list-file ivu-upload-list-file-finish" :key="index">
                                        <span><i class="ivu-icon ivu-icon-ios-image"></i> {{item.name}} </span>
                                        <Progress :percent="100" :stroke-width="2" />
                                    </li>
                                </ul>
                            </List>


                        </FormItem>
                        <FormItem label="Mates With/Use With" prop="mates">
                            <tinymce-editor ref="editor"
                                            v-model="formItem.extend_mates"

                            >
                            </tinymce-editor>
                        </FormItem>
                        <FormItem label="产物环境符合" prop="environmental">
                            <tinymce-editor ref="editor"
                                            v-model="formItem.extend_environmental"
                            >
                            </tinymce-editor>
                        </FormItem>
                        <h3 id="ZXS">Part Details</h3>

                        <FormItem label="一般" prop="general">
                            <tinymce-editor ref="editor"
                                            v-model="formItem.extend_general"
                            >
                            </tinymce-editor>
                        </FormItem>
                        <FormItem label="物理" prop="physical">
                            <tinymce-editor ref="editor"
                                            v-model="formItem.extend_physical"
                            >
                            </tinymce-editor>
                        </FormItem>
                        <FormItem label="电气" prop="electric">
                            <tinymce-editor ref="editor"
                                            v-model="formItem.extend_electric"
                            >
                            </tinymce-editor>
                        </FormItem>
                        <FormItem label="机构认证" prop="certification">
                            <tinymce-editor ref="editor"
                                            v-model="formItem.extend_certification"
                            >
                            </tinymce-editor>
                        </FormItem>
                        <FormItem label="焊接处理数据" prop="welding">
                            <tinymce-editor ref="editor"
                                            v-model="formItem.extend_welding"
                            >
                            </tinymce-editor>
                        </FormItem>
                        <FormItem label="材料信息" prop="material">
                            <tinymce-editor ref="editor"
                                            v-model="formItem.extend_material"
                            >
                            </tinymce-editor>
                        </FormItem>
                        <FormItem label="参考：图纸编号" prop="drawing">
                            <tinymce-editor ref="editor"
                                            v-model="formItem.extend_drawing"
                            >
                            </tinymce-editor>
                        </FormItem>
                        <FormItem label="价格" prop="price">
                            <Input v-model="formItem.price" placeholder=""></Input>
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
                        <FormItem label="是否推荐">
                            <i-switch v-model="formItem.is_recommend" size="large">
                                <span slot="true">开启</span>
                                <span slot="false">关闭</span>
                            </i-switch>
                        </FormItem>
                        <!--                        <FormItem label="是否特价">-->
                        <!--                            <i-switch v-model="formItem.is_offer" size="large">-->
                        <!--                                <span slot="true">开启</span>-->
                        <!--                                <span slot="false">关闭</span>-->
                        <!--                            </i-switch>-->
                        <!--                        </FormItem>-->
                        <FormItem label="简介" prop="content">
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
                </TabPane>
                                <TabPane label="属性" name="name2">
                                    <div class="ivu-form ivu-form-label-right">
                                        <div style="margin: 10px 0 20px" v-for="(item, index) in attribute" :key="index">
                                            <label class="ivu-form-item-label" style="width: 80px;">{{item.name }}</label>
                                            <div class="ivu-form-item-content" style="margin-left: 80px;">
                                                <div data-v-2a320a54="" class="ivu-input-wrapper ivu-input-wrapper-default ivu-input-type">
                                                    <input v-model="item['value']" class="ivu-input ivu-input-default">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </TabPane>
<!--                                <TabPane label="相册" name="name3">-->
<!--                &lt;!&ndash;                    <li v-for="n in 3">{{n}}</li><br>&ndash;&gt;-->
<!--                &lt;!&ndash;                    <li v-for="(n,index) in 3">{{n}}&#45;&#45;{{index}}</li>&ndash;&gt;-->
<!--                                    <Row style="margin: 20px">-->
<!--                                        <Col :lg="8" :md="24">-->
<!--                                           <div style="width: 300px">-->
<!--                                               <Carousel loop v-if="album.length > 0">-->
<!--                                                   <CarouselItem v-for="(item, index) in album"  :key="index">-->
<!--                                                       <div class="demo-carousel"><img :src="item.src"></div>-->
<!--                                                   </CarouselItem>-->
<!--                                               </Carousel>-->
<!--                                           </div>-->
<!--                                            <p style="margin: 15px 0;" class="desc">最多可以上传 <Tag color="warning">{{albumCount}}</Tag> 个图片, 您已上传 <Tag color="green">{{album.length}}</Tag>个</p>-->
<!--                                            <p style="margin: 15px 0;" class="desc">支持上传的文件格式为 <Tag>jpg·jpeg·png·gif</Tag> 且文件大小不超过 <Tag>{{file_size}}</Tag> M</p>-->
<!--                                            <p style="margin: 15px 0;" class="desc" v-if="album.length > 0">-->
<!--                                                已上传 <Tag color="success">{{album.length}}</Tag> 张图片，-->
<!--                                                <Button type="error" @click.native="resetAlbum" style="margin: 10px 0" icon="md-close">-->
<!--                                                清空相册继续上传图片-->
<!--                                            </Button>-->
<!--                                            </p>-->
<!--                                            <Upload v-show="albumUpload"-->
<!--                                                    type="drag"-->
<!--                                                    ref="upload"-->
<!--                                                    :show-upload-list="false"-->
<!--                                                    :on-success="handleSuccessAlbum"-->
<!--                                                    :on-error="handleError"-->
<!--                                                    :max-size="file_size * 1024"-->
<!--                                                    :on-format-error="handleFormatError"-->
<!--                                                    :on-exceeded-size="handleMaxSize"-->
<!--                                                    :format="['jpg','jpeg','png', 'gif']"-->
<!--                                                    :action="uploadUrl">-->
<!--                                                <div style="padding: 30px 0">-->
<!--                                                <Icon type="ios-cloud-upload" size="200" style="color: #3399ff"></Icon>-->
<!--                                                <p>点击选择或拖动文件到此处上传</p>-->
<!--                                            </div>-->
<!--                                            </Upload>-->
<!--                                        </Col>-->
<!--                                    </Row>-->

<!--                                </TabPane>-->
            </Tabs>
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
        albumCount: 5,
        albumUpload: true,
        file_size: JSON.parse(localStorage.getItem('config')).file_size,
        uploadUrl: this.api.common.upload,
        uploadDocUrl: this.api.common.upload,
        contentType: 'product',
        cascader: [],
        data:[],
        attribute:[],
        album: [],
        doc: [],
        formItem: {
          id: 0,
          title: '',
          is_offer: false,
          is_recommend: false,
          price: '0.00',
          image: '',
          content: '',
          category_id: [0],
          doc:[],
          extend_mates: '',
          extend_environmental: '',
          extend_general: '',
          extend_physical: '',
          extend_electric: '',
          extend_certification: '',
          extend_welding: '',
          extend_material: '',
          extend_drawing: '',
          // searchCriteria: [
          // ],
        },
        searchCriteria: [
        ],
        ruleValidate: {
          title: [
            { required: true, message: '标题不能为空', trigger: 'blur' },
            { type: 'string', min: 2, message: '标题必须大于 2 个字', trigger: 'blur' },
            { type: 'string', max: 200, message: '标题必须小于200 个字', trigger: 'blur' },
          ],
          // category: [
          //   { required: true, message: '类别不能为空', type: 'number', trigger: 'change' },
          // ],
          // row: [
          //   { required: true, message: '行数不能为空', type: 'number', trigger: 'change'},
          // ],
          // pitch: [
          //   { required: true, message: '间距不能为空', type: 'number', trigger: 'change'},
          // ],
          // electric_current: [
          //   { required: true, message: '额定电流不能为空', type: 'number', trigger: 'change'},
          // ],
          // installation_method: [
          //   { required: true, message: '安装类型不能为空', type: 'number', trigger: 'change'},
          // ],
          content : [
            { required: true, message: '简介不能为空', trigger: 'blur' },
          ],
          image : [
            { required: true, message: '缩略图不能为空', trigger: 'blur' },
          ],
        }
      }
    },
    created() {
      this.uploadUrl = this.uploadUrl + '?module=' + this.contentType
      this.uploadDocUrl = this.api.common.upload + '?module=doc'
      this.attribute = JSON.parse(localStorage.getItem('config'))['product_attribute']


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

      this.$axios.get(this.api.content.searchCriteria)
        .then((response) => {
          this.loading = false;
          console.log(response.data.data)
          this.searchCriteria = response.data.data
        })

      //表单内容
      if (this.$route.query.id > 0)
      {
        this.$axios.get(this.api.content.view + this.contentType + '/' + this.$route.query.id)
          .then((response) => {
            if (response.data.code == 0)
            {
              this.formItem = response.data.data
              this.formItem.doc = []
              this.doc = response.data.data.file
              if (response.data.data.attribute != null && JSON.parse(response.data.data.attribute).length > 0)
              {
                let attribute = JSON.parse(response.data.data.attribute)
                for (let i = 0; i < attribute.length; i++) {
                  this.attribute[i] = attribute[i]
                }
              }
              if (response.data.data.album != null)
              {
                let album = JSON.parse(response.data.data.album)
                this.album = album
                this.albumUpload = album.length >= this.albumCount ? false : true
              }
            }else{
              this.$Message.error('数据获取失败，请检查');
            }
          })
      }
    },
    methods: {
      resetAlbum () {
        this.albumUpload = true
        this.album = []
      },
      imgUrl(src)
      {
        console.log(src.length)
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
      handleSuccessAlbum (response) {
        this.album.push({
          src: this.imgUrl(response.data.url)
        })
        if(this.album.length > this.albumCount - 1)
        {
          this.albumUpload = false
        }
        this.$Message.success('上传成功')
      },
      handleSuccessDoc (response) {
        this.formItem.doc.push({
          name: response.data.name,
          src: this.imgUrl(response.data.url)
        })
        this.$Message.success('上传成功')
      },
      handleSuccess (response) {
        this.formItem.image = response.data.url
        this.$Message.success('上传成功')
      },
      handleVideoSuccess (response) {
        this.formItem.video = response.data.url
        this.$Message.success('上传成功')
      },
      handleError (event) {
        console.log(event)
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
      // searchCriteria: function()
      // {
      //   this.$axios.get(this.api.content.searchCriteria, {params:params})
      //     .then((response) => {
      //       this.loading = false;
      //       console.log(response.data.searchCriteria)
      //       this.searchCriteria = response.data.searchCriteria
      //     })
      // },
      handleSubmit (name) {
        this.$refs[name].validate((valid) => {
          if (valid) {
            let data = this.formItem
            let attribute = JSON.stringify(this.attribute)
            let album = JSON.stringify(this.album)
            data.attribute = attribute
            data.album = album
            data.doc = JSON.stringify(data.doc)
            var url = this.api.content.save + this.contentType + '/' + this.formItem.id;
            this.$axios.post(url, data)
              .then((response) => {
                if (response.data.code == 0)
                {
                  this.$Message.success('操作成功');
                  setTimeout(() => {
                    this.gotoPage('product')
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
    .demo-carousel
    {
        height: 300px;
        width: 300px;
        line-height: 200px;
        text-align: center;
        color: #fff;
        font-size: 20px;
        background: #506b9e;
    }
    .demo-carousel img{
        width: 100%;
        height: 100%;
    }
</style>