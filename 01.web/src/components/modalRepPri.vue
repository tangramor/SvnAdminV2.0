<template>
  <div>
    <!-- 对话框-仓库权限 -->
    <Modal
      v-model="modalRepPri"
      :title="titleModalRepPri"
      @on-visible-change="ChangeModalVisible"
      fullscreen
    >
      <Row type="flex" justify="center" :gutter="16">
        <Col span="11">
          <Scroll :height="550">
            <Tree
              :data="dataTreeRep"
              :load-data="ExpandRepTree"
              :render="renderContent"
              @on-select-change="ChangeSelectTreeNode"
              @on-contextmenu="handleContextMenu"
            >
              <template slot="contextMenu">
                <DropdownItem @click.native="modalCreateRepFolder = true"
                  >{{ $t('modalRepPri.createFolder') }}</DropdownItem
                >
              </template>
            </Tree>
            <Spin size="large" fix v-if="loadingRepTree"></Spin>
          </Scroll>
        </Col>
        <Col span="11">
          <Tooltip
            style="width: 100%"
            max-width="450"
            :content="currentRepPath"
            placement="bottom"
          >
            <Input v-model="currentRepPath">
              <span slot="prepend">{{ $t('modalRepPri.currentPath') }}</span>
            </Input>
          </Tooltip>
          <Card
            :bordered="true"
            :dis-hover="true"
            style="height: 500px; margin-top: 18px"
          >
            <Button
              icon="md-add"
              type="primary"
              ghost
              @click="modalSvnObject = true"
              >{{ $t('modalRepPri.addPathPermission') }}</Button
            >
            <Table
              border
              :height="410"
              size="small"
              :loading="loadingRepPathAllPri"
              :columns="tableColumnRepPathAllPri"
              :data="tableDataRepPathAllPri"
              style="margin-top: 20px"
            >
              <template slot-scope="{ row }" slot="objectType">
                <Tag
                  color="blue"
                  v-if="row.objectType == 'user'"
                  style="width: 90px; text-align: center"
                  >{{ $t('repositoryGroup.user') }}</Tag
                >
                <Tag
                  color="geekblue"
                  v-if="row.objectType == 'group'"
                  style="width: 90px; text-align: center"
                  >{{ $t('repositoryGroup.group') }}</Tag
                >
                <Tag
                  color="purple"
                  v-if="row.objectType == 'aliase'"
                  style="width: 90px; text-align: center"
                  >{{ $t('repositoryGroup.aliase') }}</Tag
                >
                <Tag
                  color="red"
                  v-if="row.objectType == '*'"
                  style="width: 90px; text-align: center"
                  >{{ $t('modalRepPri.allUsers') }}</Tag
                >
                <Tag
                  color="magenta"
                  v-if="row.objectType == '$authenticated'"
                  style="width: 90px; text-align: center"
                  >{{ $t('modalRepPri.authenticatedUsers') }}</Tag
                >
                <Tag
                  color="volcano"
                  v-if="row.objectType == '$anonymous'"
                  style="width: 90px; text-align: center"
                  >{{ $t('modalRepPri.anonymousUsers') }}</Tag
                >
              </template>
              <template slot-scope="{ row }" slot="objectPri">
                <RadioGroup
                  v-model="row.objectPri"
                  type="button"
                  size="small"
                  button-style="solid"
                  @on-change="
                    (objectPri) =>
                      ClickRepPathPri(
                        row.objectType,
                        row.invert,
                        row.objectName,
                        objectPri
                      )
                  "
                >
                  <Radio label="rw">{{ $t('modalRepPri.readWrite') }}</Radio>
                  <Radio label="r">{{ $t('modalRepPri.readOnly') }}</Radio>
                  <Radio label="no">{{ $t('modalRepPri.noAccess') }}</Radio>
                </RadioGroup>
              </template>
              <template slot-scope="{ row }" slot="invert">
                <Switch
                  v-if="row.objectType != '*'"
                  v-model="row.invert"
                  @on-change="
                    (invert) =>
                      ClickRepPathPri(
                        row.objectType,
                        invert,
                        row.objectName,
                        row.objectPri
                      )
                  "
                >
                  <Icon type="md-checkmark" slot="open"></Icon>
                  <Icon type="md-close" slot="close"></Icon>
                </Switch>
              </template>
              <template slot-scope="{ row }" slot="action">
                <Button
                  type="error"
                  size="small"
                  @click="DelRepPathPri(row.objectType, row.objectName)"
                  >{{ $t('delete') }}</Button
                >
              </template>
            </Table>
          </Card>
        </Col>
      </Row>
      <div slot="footer">
        <Button type="primary" ghost @click="CloseModalRepPri">{{ $t('cancel') }}</Button>
      </div>
    </Modal>
    <!-- SVN对象列表组件 -->
    <ModalSvnObject
      :propModalSvnObject="modalSvnObject"
      :propChangeParentModalObject="CloseModalObject"
      :propSendParentObject="CreateRepPathPri"
      :propSvnnUserPriPathId="svnn_user_pri_path_id"
      :propShowSvnAllTab="showModalSvnObjectTab"
      :propShowSvnAuthenticatedTab="showModalSvnObjectTab"
      :propShowSvnAnonymousTab="showModalSvnObjectTab"
    />
    <!-- 对话框-新建文件夹 -->
    <Modal v-model="modalCreateRepFolder" :draggable="true" :title="$t('modalRepPri.createFolder')">
      <Form :label-width="80">
        <FormItem :label="$t('modalRepPri.parentPath')">
          <Input v-model="currentRepPath" readonly></Input>
        </FormItem>
        <FormItem :label="$t('modalRepPri.folderName')">
          <Input v-model="folderName"></Input>
        </FormItem>
        <FormItem>
          <Button
            type="primary"
            :loading="loadingCreateRepFolder"
            @click="CreateRepFolder"
            >{{ $t('confirm') }}</Button
          >
        </FormItem>
      </Form>
      <div slot="footer">
        <Button type="primary" ghost @click="modalCreateRepFolder = false"
          >{{ $t('cancel') }}</Button
        >
      </div>
    </Modal>
  </div>
</template>

<script>
//SVN对象列表组件
import ModalSvnObject from "./modalSvnObject.vue";
import i18n from "@/i18n";
export default {
  props: {
    //父组件控制子组件显示
    propModalRepPri: {
      type: Boolean,
      default: false,
    },
    propCurrentRepPath: {
      type: String,
      default: "",
    },
    propCurrentRepName: {
      type: String,
      default: "",
    },
    propTitleModalRepPri: {
      type: String,
      default: "",
    },
    propSvnnUserPriPathId: {
      type: Number,
      default: -1,
    },
    //向父组件发送对话框状态变量
    propChangeParentModalVisible: {
      type: Function,
    },
    //向父组件发送仓库路径
    propChangeParentCurrentRepPath: {
      type: Function,
    },
  },
  data() {
    return {
      //配置仓库权限
      titleModalRepPri: "仓库权限",
      //临时
      contextData: null,
      //新建文件夹名称
      folderName: "",

      /**
       * 对话框
       */
      //仓库权限对话框
      modalRepPri: this.propModalRepPri,
      //SVN对象列表组件
      modalSvnObject: false,
      //创建文件夹
      modalCreateRepFolder: false,

      /**
       * 组件
       */
      showModalSvnObjectTab:
        sessionStorage.user_role_id == 1 || sessionStorage.user_role_id == 3
          ? true
          : false,

      /**
       * 加载
       */
      //仓库目录树
      loadingRepTree: true,
      //某个仓库路径的所有对象的权限列表
      loadingRepPathAllPri: true,
      //新建文件夹
      loadingCreateRepFolder: false,

      /**
       * 临时变量
       */
      //当前仓库路径
      currentRepPath: this.propCurrentRepPath,
      //当前仓库名称
      currentRepName: this.propCurrentRepName,
      //当前权限路径id
      svnn_user_pri_path_id: this.propSvnnUserPriPathId,

      /**
       * 对话框标题
       */
      

      /**
       * 表格
       */
      //仓库目录树
      dataTreeRep: [],
      //某节点的权限信息
      tableDataRepPathAllPri: [],
      
    };
  },
  components: {
    ModalSvnObject,
  },
  computed: {
    tableColumnRepPathAllPri() {
      return [
        {
          title: i18n.t('modalRepPri.authType'),    //"授权类型",
          slot: "objectType",
          width: 125,
        },
        {
          title: i18n.t('modalRepPri.objectName'),    //"对象名称",
          key: "objectName",
          tooltip: true,
          width: 115,
        },
        {
          title: i18n.t('modalRepPri.rwPermission'),    //"读写权限",
          slot: "objectPri",
          width: 200,
        },
        {
          slot: "invert",
          width: 100,
          renderHeader(h, params) {
            return h(
              "tooltip",
              {
                props: {
                  transfer: true,
                  placement: "left",
                  "max-width": "400",
                },
              },
              [
                h("span", [
                  h("span", i18n.t('modalRepPri.invertPermission')),
                  h("Icon", {
                    props: {
                      type: "ios-help-circle-outline",
                      size: "15",
                    },
                    class: { iconClass: true },
                  }),
                ]),
                h(
                  "div",
                  {
                    slot: "content",
                    style: {
                      fontSize: "10px",
                    },
                  },
                  [
                    h(
                      "p",
                      {
                        style: {
                          color: "#479af1",
                          fontSize: "15px",
                        },
                      },
                      i18n.t('modalRepPri.permissionDesc1')   //'不熟练的用户请慎用此功能！'
                    ),
                    h("p", " "),
                    h("p", i18n.t('modalRepPri.permissionDesc2')),   //"从 Subversion 1.5 开始"),
                    h("p", i18n.t('modalRepPri.permissionDesc3')),   //"$authenticated 表示所有已认证的用户"),
                    h("p", i18n.t('modalRepPri.permissionDesc4')),   //"$anonymous 表示所有未认证的用户"),
                    h(
                      "p",
                      i18n.t('modalRepPri.permissionDesc5')   //"~ 即权限反转表示排除某些用户 如在用户名、别名、用户组、认证类别前加上 ~ 表示将访问权限授予给与规则不匹配的用户"
                    ),
                    h("p", " "),
                    h("p", i18n.t('modalRepPri.permissionDesc6')),   //"如："),
                    h("p", "[calendar:/projects/calendar]"),
                    h("p", "$anonymous = r"),
                    h("p", "$authenticated = rw"),
                    h("p", " "),
                    h(
                      "p",
                      i18n.t('modalRepPri.permissionDesc7')   //"虽然下面的配置容易让人产生困惑,，但它和上面的例子是等效的："
                    ),
                    h("p", " "),
                    h("p", "[calendar:/projects/calendar]"),
                    h("p", "~$authenticated = r"),
                    h("p", "~$anonymous = rw"),
                    h("p", " "),
                    h("p", i18n.t('modalRepPri.permissionDesc8')),   //"下面是一个更恰当的使用 ~ 的例子："),
                    h("p", " "),
                    h("p", "[groups]"),
                    h("p", i18n.t('modalRepPri.permissionDesc9')),   //"# calc 项目的开发人员信息"),
                    h("p", "calc-developers = &harry, &sally, &joe"),
                    h("p", " "),
                    h("p", i18n.t('modalRepPri.permissionDesc10')),   //"# calc 项目的管理人员信息"),
                    h("p", "calc-owners = &hewlett, &packard"),
                    h("p", " "),
                    h("p", i18n.t('modalRepPri.permissionDesc11')),   //"# calc 项目的所有参与人信息"),
                    h("p", "calc = @calc-developers, @calc-owners"),
                    h("p", " "),
                    h("p", i18n.t('modalRepPri.permissionDesc12')),   //"# 所有的 calc 项目参与成员有该项目的读权限"),
                    h("p", "[calc:/projects/calc]"),
                    h("p", "@calc = rw"),
                    h("p", " "),
                    h("p", i18n.t('modalRepPri.permissionDesc13')),   //"# 只有项目管理员有 calc 项目的发行版标签操作权限"),
                    h("p", "[calc:/projects/calc/tags]"),
                    h("p", "~@calc-owners = r"),
                  ]
                ),
              ]
            );
          },
        },
        {
          title: i18n.t('action'),    //"操作",
          slot: "action",
        },
      ]},
    //   //配置仓库权限
    //   titleModalRepPri() {
    //     return i18n.t('repositoryInfo.repoPri');    //"仓库权限";
    //   }
  },
  created() {},
  mounted() {},
  watch: {
    //监控有序
    //仓库路径
    propCurrentRepPath: function (value) {
      this.currentRepPath = value;
    },
    //仓库名称
    propCurrentRepName: function (value) {
      this.currentRepName = value;
    },
    //SVN用户权限路径id
    propSvnnUserPriPathId: function (value) {
      this.svnn_user_pri_path_id = value;
    },
    //对话框状态
    propModalRepPri: function (value) {
      var that = this;
      that.modalRepPri = value;
      if (value) {
        //标题
        that.titleModalRepPri = i18n.t('repositoryInfo.repoPri') + " - " + that.currentRepName;
        //显示加载动画
        that.loadingRepTree = true;
        //清空数据
        that.dataTreeRep = [];
        if (
          sessionStorage.user_role_id == 1 ||
          sessionStorage.user_role_id == 3
        ) {
          //请求目录树
          that.GetRepTree().then(function (response) {
            that.loadingRepTree = false;
            var result = response.data;
            if (result.status == 1) {
              that.dataTreeRep = result.data;
            } else {
              that.$Message.error({ content: result.message, duration: 2 });
            }
          });
        } else if (sessionStorage.user_role_id == 2) {
          //请求目录树
          that.GetRepTree2(true).then(function (response) {
            that.loadingRepTree = false;
            var result = response.data;
            if (result.status == 1) {
              that.dataTreeRep = result.data;
            } else {
              that.$Message.error({ content: result.message, duration: 2 });
            }
          });
        } else {
          return;
        }
        //获取仓库根路径的所有对象的权限列表
        that.GetRepPathAllPri();
      }
    },
  },
  methods: {
    /**
     * ModalSvnObject 子组件传递变量给父组件
     */
    CloseModalObject() {
      this.modalSvnObject = false;
    },
    /**
     * 本组件 关闭对话框触发事件
     */
    CloseModalRepPri() {
      //本组件内对话框状态
      this.modalRepPri = false;
      //将对话框状态从本组件内传递给父组件
      this.propChangeParentModalVisible();
    },
    /**
     * 本组件 Modal右上角叉号触发父组件修改变量状态
     */
    ChangeModalVisible(value) {
      if (!value) {
        //本组件对话框右上角的叉号被触发也会将对话框关闭状态从本组件内传递给父组件
        this.propChangeParentModalVisible();
      }
    },
    /**
     * 渲染目录树 给文件夹和文件设置对应的图标
     */
    renderContent(h, { root, node, data }) {
      return h("span", [
        h("Icon", {
          props: {
            type:
              data.resourceType == "1"
                ? "ios-document-outline"
                : "ios-folder-open",
          },
          style: {
            marginRight: "8px",
          },
        }),
        h("span", data.title),
      ]);
    },
    //目录树右键
    handleContextMenu(data, event, position) {
      this.ChangeSelectTreeNode([], data);
    },
    CreateRepFolder() {
      var that = this;
      that.loadingCreateRepFolder = true;
      var data = {
        rep_name: that.currentRepName,
        path: that.currentRepPath,
        folder_name: that.folderName,
      };
      that.$axios
        .post("api.php?c=Svnrep&a=CreateRepFolder&t=web", data)
        .then(function (response) {
          that.loadingCreateRepFolder = false;
          var result = response.data;
          if (result.status == 1) {
            that.$Message.success(result.message);
            //重新请求目录树 todo
            that.modalCreateRepFolder = false;
          } else {
            that.$Message.error({ content: result.message, duration: 2 });
          }
        })
        .catch(function (error) {
          that.loadingCreateRepFolder = false;
          console.log(error);
          that.$Message.error(i18n.t('errors.contactAdmin'));
        });
    },
    /**
     * 创建目录
     */
    CreateFolder() {},
    /**
     * 管理员获取目录树
     */
    GetRepTree() {
      var that = this;
      var data = {
        rep_name: that.currentRepName,
        path: that.currentRepPath,
      };
      return new Promise(function (resolve, reject) {
        that.$axios
          .post("api.php?c=Svnrep&a=GetRepTree&t=web", data)
          .then(function (response) {
            resolve(response);
          })
          .catch(function (error) {
            console.log(error);
            that.$Message.error(i18n.t('errors.contactAdmin'));
            reject(error);
          });
      });
    },
    /**
     * SVN用户获取目录树
     */
    GetRepTree2(first = false) {
      var that = this;
      var data = {
        rep_name: that.currentRepName,
        path: that.currentRepPath,
        first: first,
      };
      return new Promise(function (resolve, reject) {
        that.$axios
          .post("api.php?c=Svnrep&a=GetRepTree2&t=web", data)
          .then(function (response) {
            resolve(response);
          })
          .catch(function (error) {
            console.log(error);
            that.$Message.error(i18n.t('errors.contactAdmin'));
            reject(error);
          });
      });
    },
    /**
     * 目录树展开触发
     * 异步加载目录下的内容
     */
    ExpandRepTree(item, callback) {
      var that = this;
      var data = [];
      that.currentRepPath = item.fullPath;
      that.propChangeParentCurrentRepPath(item.fullPath);
      that.GetRepPathAllPri();
      if (
        sessionStorage.user_role_id == 1 ||
        sessionStorage.user_role_id == 3
      ) {
        that.GetRepTree().then(function (response) {
          var result = response.data;
          if (result.status == 1) {
            data = result.data;
            if (data.length > 0) {
              if (data[0].fullPath != "/") {
                callback(data);
              } else {
                callback([]);
                //根目录下没有内容时 直接覆盖掉
                that.dataTreeRep = [
                  {
                    resourceType: 2,
                    title: that.currentRepName + "/",
                    fullPath: "/",
                  },
                ];
              }
            } else {
              callback([]);
            }
          } else {
            that.$Message.error({ content: result.message, duration: 2 });
            callback(data);
          }
        });
      } else if (sessionStorage.user_role_id == 2) {
        that.GetRepTree2().then(function (response) {
          var result = response.data;
          if (result.status == 1) {
            data = result.data;
            if (data.length > 0) {
              if (data[0].fullPath != "/") {
                callback(data);
              } else {
                callback([]);
                //根目录下没有内容时 直接覆盖掉
                that.dataTreeRep = [
                  {
                    resourceType: 2,
                    title: that.currentRepName + "/",
                    fullPath: "/",
                  },
                ];
              }
            } else {
              callback([]);
            }
          } else {
            that.$Message.error({ content: result.message, duration: 2 });
            callback(data);
          }
        });
      } else {
        return;
      }
    },
    /**
     * 点击目录树节点触发
     * 获取节点的权限
     */
    ChangeSelectTreeNode(selectArray, currentItem) {
      this.currentRepPath = currentItem.fullPath;
      this.propChangeParentCurrentRepPath(currentItem.fullPath);
      this.GetRepPathAllPri();
    },
    /**
     * 获取某个仓库路径的所有对象的权限列表
     */
    GetRepPathAllPri() {
      var that = this;
      //清空上次表格数据
      that.tableDataRepPathAllPri = [];
      //开始加载动画
      that.loadingRepPathAllPri = true;
      var data = {
        rep_name: that.currentRepName,
        path: that.currentRepPath,
        svnn_user_pri_path_id: that.svnn_user_pri_path_id,
      };
      that.$axios
        .post("api.php?c=Svnrep&a=GetRepPathAllPri&t=web", data)
        .then(function (response) {
          that.loadingRepPathAllPri = false;
          var result = response.data;
          if (result.status == 1) {
            that.tableDataRepPathAllPri = result.data;
          } else {
            that.$Message.error({ content: result.message, duration: 2 });
          }
        })
        .catch(function (error) {
          that.loadingRepPathAllPri = false;
          console.log(error);
          that.$Message.error(i18n.t('errors.contactAdmin'));
        });
    },
    /**
     * 为某仓库路径下增加权限
     */
    CreateRepPathPri(objectType, objectName) {
      var that = this;
      var data = {
        rep_name: that.currentRepName,
        path: that.currentRepPath,
        objectType: objectType,
        objectPri: "rw",
        objectName: objectName,
        svnn_user_pri_path_id: that.svnn_user_pri_path_id,
      };
      that.$axios
        .post("api.php?c=Svnrep&a=CreateRepPathPri&t=web", data)
        .then(function (response) {
          var result = response.data;
          if (result.status == 1) {
            // that.modalSvnObject = false;
            that.$Message.success(result.message);
            that.GetRepPathAllPri();
          } else {
            that.$Message.error({ content: result.message, duration: 2 });
          }
        })
        .catch(function (error) {
          // that.modalSvnObject = false;
          console.log(error);
          that.$Message.error(i18n.t('errors.contactAdmin'));
        });
    },
    /**
     * 修改某个仓库下的权限
     */
    ClickRepPathPri(objectType, invert, objectName, objectPri) {
      var that = this;
      var data = {
        rep_name: that.currentRepName,
        path: that.currentRepPath,
        objectType: objectType,
        invert: invert,
        objectName: objectName,
        objectPri: objectPri,
        svnn_user_pri_path_id: that.svnn_user_pri_path_id,
      };
      that.$axios
        .post("api.php?c=Svnrep&a=UpdRepPathPri&t=web", data)
        .then(function (response) {
          var result = response.data;
          if (result.status == 1) {
            that.$Message.success(result.message);
            that.GetRepPathAllPri();
          } else {
            that.$Message.error({ content: result.message, duration: 2 });
          }
        })
        .catch(function (error) {
          console.log(error);
          that.$Message.error(i18n.t('errors.contactAdmin'));
        });
    },
    /**
     * 删除某个仓库下的权限
     */
    DelRepPathPri(objectType, objectName) {
      var that = this;
      var data = {
        rep_name: that.currentRepName,
        path: that.currentRepPath,
        objectType: objectType,
        objectName: objectName,
        svnn_user_pri_path_id: that.svnn_user_pri_path_id,
      };
      that.$axios
        .post("api.php?c=Svnrep&a=DelRepPathPri&t=web", data)
        .then(function (response) {
          var result = response.data;
          if (result.status == 1) {
            that.$Message.success(result.message);
            that.GetRepPathAllPri();
          } else {
            that.$Message.error({ content: result.message, duration: 2 });
          }
        })
        .catch(function (error) {
          console.log(error);
          that.$Message.error(i18n.t('errors.contactAdmin'));
        });
    },
  },
};
</script>

<style>
</style>