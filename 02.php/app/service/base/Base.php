<?php
/*
 * @Author: witersen
 * 
 * @LastEditors: witersen
 * 
 * @Description: QQ:1801168257
 */

namespace app\service;

//require config
auto_require(BASE_PATH . '/config/');

//require function
auto_require(BASE_PATH . '/app/function/');

//require util
auto_require(BASE_PATH . '/app/util/');

//require service
auto_require(BASE_PATH . '/app/service/');

//require extension
auto_require(BASE_PATH . '/extension/Medoo-1.7.10/src/Medoo.php');

auto_require(BASE_PATH . '/extension/PHPMailer-6.6.0/src/Exception.php');
auto_require(BASE_PATH . '/extension/PHPMailer-6.6.0/src/PHPMailer.php');
auto_require(BASE_PATH . '/extension/PHPMailer-6.6.0/src/SMTP.php');
auto_require(BASE_PATH . '/extension/PHPMailer-6.6.0/language/phpmailer.lang-zh_cn.php');

auto_require(BASE_PATH . '/extension/Verifycode/Verifycode.php');

auto_require(BASE_PATH . '/extension/Witersen/SVNAdmin.php');

auto_require(BASE_PATH . '/extension/Witersen/File/Upload.php');

auto_require(BASE_PATH . '/extension/Philipp15b/i18n.class.php');

function auto_require($path, $recursively = false)
{
    if (is_file($path)) {
        if (substr($path, -4) == '.php') {
            require_once $path;
        }
    } else {
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                if (is_dir($path . '/' . $file)) {
                    $recursively ? auto_require($path . '/' . $file, true) : '';
                } else {
                    if (substr($file, -4) == '.php') {
                        require_once $path . '/' . $file;
                    }
                }
            }
        }
    }
}

use Check;

use Config;
use Medoo\Medoo;
use Witersen\SVNAdmin;
use Witersen\Upload;
use i18n;

$i18n = new i18n();
$i18n->setCachePath('/tmp/langcache');
$i18n->setFilePath(BASE_PATH . '/app/lang/{LANGUAGE}.ini'); // language file path
$i18n->setLangVariantEnabled(false); // trim region variant in language codes (e.g. en-us -> en)
$i18n->setFallbackLang('en-US');
$i18n->setSectionSeparator('_');
$i18n->setMergeFallback(false); // make keys available from the fallback language
$i18n->init();

class Base
{
    public $token;

    //根据token得到的用户信息
    public $userName;
    public $userRoleId;

    //svn配置文件
    public $authzPath;
    public $authzContent;
    public $passwdContent;
    public $httpPasswdContent;
    public $svnserveContent;

    //medoo
    public $database;

    //配置信息
    public $configBin;
    public $configSvn;
    public $configReg;
    public $configSign;

    //payload
    public $payload;

    //SVNAdmin
    public $SVNAdmin;
    public $SVNAdminGroup;
    public $SVNAdminInfo;
    public $SVNAdminRep;
    public $SVNAdminUser;

    //检查
    public $checkService;

    //宿主机
    public $dockerHost = '127.0.0.1';
    public $dockerSvnPort = 3690;
    public $dockerHttpPort = 80;

    //本地
    public $localSvnHost = '127.0.0.1';
    public $localSvnPort = 3690;
    public $localHttpHost = '127.0.0.1';
    public $localHttpPort = 80;
    public $localHttpProtocol = 'http';

    //启用协议
    public $enableCheckout = '';

    //数据源
    public $svnDataSource;
    public $httpDataSource;

    //http
    public $httpPrefix = '';

    //i18n
    public $i18n;

    /**
     * 子管理员权限树
     *
     * @var array
     */
    public $subadminTree = [
        [
            'title' => \L::backend_tasks,    // '后台任务'
            'expand' => false,
            'checked' => false,
            'disabled' => false,
            'necessary_functions' => [],
            'children' => [
                [
                    'title' => \L::current_tasks,    // '当前任务'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [],
                    'children' => [
                        [
                            'title' => \L::get_real_time_task_log,  //'获取后台任务实时日志'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => false,
                            'necessary_functions' => [
                                'Tasks/GetTaskRun',
                            ],
                            'children' => []
                        ],
                    ]
                ],
                [
                    'title' => \L::task_queue,    // '排队任务'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [],
                    'children' => [
                        [
                            'title' => \L::get_task_queue,  //'获取后台任务队列'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => false,
                            'necessary_functions' => [
                                'Tasks/GetTaskQueue',
                            ],
                            'children' => [
                                [
                                    'title' => \L::stop_task,   //'停止后台任务'
                                    'expand' => false,
                                    'checked' => false,
                                    'disabled' => false,
                                    'necessary_functions' => [
                                        'Tasks/UpdTaskStop',
                                    ],
                                    'children' => []
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    'title' => \L::task_history,    // '历史任务'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [],
                    'children' => [
                        [
                            'title' => \L::get_task_history,  //'获取后台任务执行历史'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => false,
                            'necessary_functions' => [
                                'Tasks/GetTaskHistory',
                            ],
                            'children' => [
                                [
                                    'title' => \L::get_task_history_log,  //'获取历史任务日志'
                                    'expand' => false,
                                    'checked' => false,
                                    'disabled' => false,
                                    'necessary_functions' => [
                                        'Tasks/GetTaskHistoryLog',
                                    ],
                                    'children' => []
                                ],
                                [
                                    'title' => \L::delete_task_history,  //'删除历史执行任务'
                                    'expand' => false,
                                    'checked' => false,
                                    'disabled' => true,
                                    'necessary_functions' => [
                                        'Tasks/DelTaskHistory',
                                    ],
                                    'children' => []
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        [
            'title' => \L::statistics_info,    // '信息统计'
            'expand' => false,
            'checked' => false,
            'disabled' => false,
            'router_name' => 'index',
            'necessary_functions' => [
                'Statistics/GetLoadInfo',
                'Statistics/GetDiskInfo',
                'Statistics/GetStatisticsInfo',
            ],
            'children' => []
        ],
        [
            'title' => \L::svn_repository,    // 'SVN仓库'
            'expand' => false,
            'checked' => false,
            'disabled' => false,
            'router_name' => 'repositoryInfo',
            'necessary_functions' => [
                'Svnrep/GetRepList',
                'Svnrep/GetSvnserveStatus',
                'Svnrep/SyncRepSize',
                'Svnrep/SyncRepRev',
            ],
            'children' => [
                [
                    'title' => \L::create_repository,    // '新建仓库'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [
                        'Svnrep/CreateRep',
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::authz_check,    // 'authz检测'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [
                        'Svnrep/CheckAuthz',
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::update_repository_note,    // '备注信息修改'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [
                        'Svnrep/UpdRepNote',
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::repository_content,    // '仓库内容浏览'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [
                        'Svnrep/GetCheckout',
                        'Svnrep/GetRepCon',
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::backup_management,    // '仓库备份管理'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [],
                    'children' => [
                        [
                            'title' => \L::get_backup_list,    // '获取备份文件列表'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => true,
                            'necessary_functions' => [
                                'Svnrep/GetBackupList',
                            ],
                            'children' => []
                        ],
                        // [
                        //     'title' => '生成仓库备份文件(svnadmin dump)',
                        //     'expand' => false,
                        //     'checked' => false,
                        //     'disabled' => true,
                        //     'necessary_functions' => [
                        //         'Svnrep/SvnadminDump',
                        //     ],
                        //     'children' => []
                        // ],
                        [
                            'title' => \L::delete_backup_file,    // '删除仓库备份文件'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => true,
                            'necessary_functions' => [
                                'Svnrep/DelRepBackup',
                            ],
                            'children' => []
                        ],
                    ]
                ],
                [
                    'title' => \L::repository_permission,    // '仓库权限配置'
                    'expand' => false,
                    'checked' => true,
                    'disabled' => false,
                    'necessary_functions' => [
                        'Svnrep/GetRepTree',
                        'Svnrep/GetRepPathAllPri',
                        'Svnrep/DelRepBackup',
                    ],
                    'children' => [
                        [
                            'title' => \L::repository_authorization_component,    //'仓库授权组件'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => true,
                            'necessary_functions' => [],
                            'children' => [
                                [
                                    'title' => \L::repository_directory_tree_browsing_left,    //'仓库目录树浏览(左侧)'
                                    'expand' => false,
                                    'checked' => false,
                                    'disabled' => true,
                                    'necessary_functions' => [],
                                    'children' => [
                                        [
                                            'title' => \L::get_repository_directory_tree,    //'获取仓库目录树'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [
                                                'Svnrep/GetRepTree',
                                            ],
                                            'children' => []
                                        ],
                                        [
                                            'title' => \L::create_repository_folder,    //'在线创建文件夹'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [
                                                'Svnrep/CreateRepFolder',
                                            ],
                                            'children' => []
                                        ]
                                    ]
                                ],
                                [
                                    'title' => \L::repository_path_authorization_right,    //'仓库路径授权(右侧)'
                                    'expand' => false,
                                    'checked' => false,
                                    'disabled' => true,
                                    'necessary_functions' => [],
                                    'children' => [
                                        [
                                            'title' => \L::add_permission_under_a_repository_path,    //'增加某个仓库路径下的权限'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [
                                                'Svnrep/CreateRepPathPri',
                                            ],
                                            'children' => []
                                        ],
                                        [
                                            'title' => \L::get_permission_under_a_repository_path,    //'获取某个仓库路径下的权限'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [
                                                'Svnrep/GetRepPathAllPri',
                                            ],
                                            'children' => []
                                        ],
                                        [
                                            'title' => \L::modify_permission_under_a_repository_path,    //'修改某个仓库路径下的权限'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [
                                                'Svnrep/UpdRepPathPri',
                                            ],
                                            'children' => []
                                        ],
                                        [
                                            'title' => \L::delete_permission_under_a_repository_path,    //'删除某个仓库路径下的权限'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [
                                                'Svnrep/DelRepPathPri',
                                            ],
                                            'children' => []
                                        ],
                                    ]
                                ],
                            ]
                        ],
                        [
                            'title' => \L::object_list_component,    //'对象列表组件'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => true,
                            'necessary_functions' => [],
                            'children' => [
                                [
                                    'title' => \L::get_svn_user_list,   //'获取SVN用户列表'
                                    'expand' => false,
                                    'checked' => false,
                                    'disabled' => true,
                                    'necessary_functions' => [
                                        'Svnuser/GetUserList',
                                    ],
                                    'children' => []
                                ],
                                [
                                    'title' => \L::get_svn_group_list,   //'获取SVN分组列表'
                                    'expand' => false,
                                    'checked' => false,
                                    'disabled' => true,
                                    'necessary_functions' => [
                                        'Svngroup/GetGroupList',
                                    ],
                                    'children' => [
                                        [
                                            'title' => \L::get_svn_group_member,   //'获取SVN分组成员'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [
                                                'Svngroup/GetGroupMember',
                                            ],
                                            'children' => []
                                        ],
                                    ]
                                ],
                                [
                                    'title' => \L::get_svn_alias_list,   //'获取SVN别名列表'
                                    'expand' => false,
                                    'checked' => false,
                                    'disabled' => true,
                                    'necessary_functions' => [
                                        'Svnaliase/GetAliaseList',
                                    ],
                                    'children' => []
                                ],
                            ]
                        ]
                    ]
                ],
                [
                    'title' => \L::edit_repository_hooks,   //'仓库钩子编辑'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [],
                    'children' => [
                        [
                            'title' => \L::get_hooks_list,  //'获取仓库钩子列表'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => true,
                            'necessary_functions' => [
                                'Svnrep/GetRepHooks'
                            ],
                            'children' => []
                        ],
                        [
                            'title' => \L::get_commonly_used_hooks,    //'获取常用钩子列表'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => true,
                            'necessary_functions' => [
                                'Svnrep/GetRecommendHooks'
                            ],
                            'children' => []
                        ],
                        [
                            'title' => \L::modify_repository_hook_content,  //'修改仓库钩子内容'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => true,
                            'necessary_functions' => [
                                'Svnrep/UpdRepHook'
                            ],
                            'children' => []
                        ],
                        [
                            'title' => \L::clear_repository_hook_content,   //'清空仓库钩子内容'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => true,
                            'necessary_functions' => [
                                'Svnrep/DelRepHook'
                            ],
                            'children' => []
                        ],
                    ]
                ],
                [
                    'title' => \L::other,  //'其它'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [],
                    'children' => [
                        [
                            'title' => \L::advanced,    //'高级'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => false,
                            'necessary_functions' => [],
                            'children' => [
                                [
                                    'title' => \L::repository_properties,  //'仓库属性'
                                    'expand' => false,
                                    'checked' => false,
                                    'disabled' => true,
                                    'necessary_functions' => [
                                        'Svnrep/GetRepDetail'
                                    ],
                                    'children' => []
                                ],
                                [
                                    'title' => \L::repository_backup,   //'仓库备份'
                                    'expand' => false,
                                    'checked' => false,
                                    'disabled' => true,
                                    'necessary_functions' => [
                                        'Svnrep/GetRepHooks'
                                    ],
                                    'children' => [
                                        [
                                            'title' => \L::immediate_backup_repository,  //'立即备份仓库'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [
                                                'Svnrep/SvnadminDump'
                                            ],
                                            'children' => []
                                        ],
                                        [
                                            'title' => \L::get_php_file_upload_related_parameters,  //'获取php文件上传相关参数'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [
                                                'Svnrep/GetUploadInfo'
                                            ],
                                            'children' => []
                                        ],
                                        [
                                            'title' => \L::get_backup_file_list,   //'获取备份文件列表'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [
                                                'Svnrep/GetBackupList'
                                            ],
                                            'children' => []
                                        ],
                                        [
                                            'title' => \L::upload_file_upload_backup_file_to_server,    //'上传文件(上传备份文件到服务器)'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [
                                                'Svnrep/UploadBackup'
                                            ],
                                            'children' => []
                                        ],
                                        [
                                            'title' => \L::import_repository_backup,    //导入仓库备份(svnadmin load)'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [
                                                'Svnrep/SvnadminLoad'
                                            ],
                                            'children' => []
                                        ],
                                    ]
                                ],
                            ]
                        ],
                        [
                            'title' => \L::modify_repository_name,   //'修改(修改仓库名称)'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => false,
                            'necessary_functions' => [
                                'Svnrep/UpdRepName'
                            ],
                            'children' => []
                        ],
                        [
                            'title' => \L::delete_repository,    //'删除(删除仓库)'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => false,
                            'necessary_functions' => [
                                'Svnrep/DelRep'
                            ],
                            'children' => []
                        ],
                    ]
                ],
            ]
        ],
        [
            'title' => \L::svn_user,    // 'SVN用户'
            'expand' => false,
            'checked' => false,
            'disabled' => false,
            'router_name' => 'repositoryUser',
            'necessary_functions' => [
                'Svnuser/GetUserList',
            ],
            'children' => [
                [
                    'title' => \L::create_svn_user, //'新建SVN用户'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [
                        'Svnuser/CreateUser',
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::user_migration,  //'用户迁入'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [],
                    'children' => [
                        [
                            'title' => \L::user_identification, //'用户识别'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => true,
                            'necessary_functions' => [
                                'Svnuser/UserScan',
                            ],
                            'children' => []
                        ],
                        [
                            'title' => \L::confirm_import,  //'确认导入'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => true,
                            'necessary_functions' => [
                                'Svnuser/UserImport',
                            ],
                            'children' => []
                        ],
                    ]
                ],
                [
                    'title' => \L::disable_svn_user,    //'弃用或禁用SVN用户'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [
                        'Svnuser/UpdUserStatus',
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::modify_user_note,    //'修改SVN用户备注信息'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [
                        'Svnuser/UpdUserNote',
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::authorized_path,    //'有权路径'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [],
                    'children' => [
                        [
                            'title' => \L::view,    //'查看'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => false,
                            'necessary_functions' => [
                                'Svnrep/GetSvnUserRepList2',
                            ],
                            'children' => []
                        ],
                        [
                            'title' => \L::secondary_authorization, //'二次授权'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => false,
                            'necessary_functions' => [],
                            'children' => [
                                [
                                    'title' => \L::secondary_authorization_status, //'二次授权状态'
                                    'expand' => false,
                                    'checked' => false,
                                    'disabled' => true,
                                    'necessary_functions' => [
                                        'Secondpri/UpdSecondpri',
                                    ],
                                    'children' => []
                                ],
                                [
                                    'title' => \L::secondary_authorization_objects, //'二次授权对象'
                                    'expand' => false,
                                    'checked' => false,
                                    'disabled' => true,
                                    'necessary_functions' => [
                                        'Secondpri/GetSecondpriObjectList',
                                    ],
                                    'children' => [
                                        [
                                            'title' => \L::create_second_pri_object,    //'添加成员'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [
                                                'Secondpri/CreateSecondpriObject',
                                            ],
                                            'children' => []
                                        ],
                                        [
                                            'title' => \L::remove_second_pri_object,    //'移除成员'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [
                                                'Secondpri/DelSecondpriObject',
                                            ],
                                            'children' => []
                                        ],
                                        [
                                            'title' => \L::object_list_component,   //'对象列表组件'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [],
                                            'children' => [
                                                [
                                                    'title' => \L::get_svn_user_list,   //'获取SVN用户列表'
                                                    'expand' => false,
                                                    'checked' => false,
                                                    'disabled' => true,
                                                    'necessary_functions' => [
                                                        'Svnuser/GetUserList',
                                                    ],
                                                    'children' => []
                                                ],
                                                [
                                                    'title' => \L::get_svn_group_list,   //'获取SVN分组列表'
                                                    'expand' => false,
                                                    'checked' => false,
                                                    'disabled' => true,
                                                    'necessary_functions' => [
                                                        'Svngroup/GetGroupList',
                                                    ],
                                                    'children' => [
                                                        [
                                                            'title' => \L::get_svn_group_member,    //'获取SVN分组成员'
                                                            'expand' => false,
                                                            'checked' => false,
                                                            'disabled' => true,
                                                            'necessary_functions' => [
                                                                'Svngroup/GetGroupMember',
                                                            ],
                                                            'children' => []
                                                        ],
                                                    ]
                                                ],
                                                [
                                                    'title' => \L::get_svn_alias_list,  //'获取SVN别名列表'
                                                    'expand' => false,
                                                    'checked' => false,
                                                    'disabled' => true,
                                                    'necessary_functions' => [
                                                        'Svnaliase/GetAliaseList',
                                                    ],
                                                    'children' => []
                                                ],
                                            ]
                                        ]
                                    ]
                                ],
                            ]
                        ],
                    ]
                ],
                [
                    'title' => \L::modify_svn_user_password,    //'修改SVN用户密码'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [
                        'Svnuser/UpdUserPass',
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::delete_svn_user, //'删除SVN用户'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [
                        'Svnuser/DelUser',
                    ],
                    'children' => []
                ],
            ]
        ],
        [
            'title' => \L::svn_group,    // 'SVN分组'
            'expand' => false,
            'checked' => false,
            'disabled' => false,
            'router_name' => 'repositoryGroup',
            'necessary_functions' => [
                'Svngroup/GetGroupList'
            ],
            'children' => [
                [
                    'title' => \L::create_svn_group,    //'新建SVN分组'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [
                        'Svngroup/CreateGroup',
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::group_note,  //'备注信息'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [
                        'Svngroup/UpdGroupNote',
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::group_members,   //'成员'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [
                        'Svngroup/CreateGroup',
                    ],
                    'children' => [
                        [
                            'title' => \L::get_group_member_list,   //'获取分组成员列表'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => true,
                            'necessary_functions' => [
                                'Svngroup/CreateGroup',
                            ],
                            'children' => []
                        ],
                        [
                            'title' => \L::add_or_delete_group_member,  //'添加或删除分组成员'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => true,
                            'necessary_functions' => [
                                'Svngroup/UpdGroupMember',
                            ],
                            'children' => []
                        ],
                        [
                            'title' => \L::object_list_component,   //'对象列表组件'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => true,
                            'necessary_functions' => [],
                            'children' => [
                                [
                                    'title' => \L::get_svn_user_list,   //'获取SVN用户列表'
                                    'expand' => false,
                                    'checked' => false,
                                    'disabled' => true,
                                    'necessary_functions' => [
                                        'Svnuser/GetUserList',
                                    ],
                                    'children' => []
                                ],
                                [
                                    'title' => \L::get_svn_group_list,   //'获取SVN分组列表'
                                    'expand' => false,
                                    'checked' => false,
                                    'disabled' => true,
                                    'necessary_functions' => [
                                        'Svngroup/GetGroupList',
                                    ],
                                    'children' => [
                                        [
                                            'title' => \L::get_svn_group_member,   //'获取SVN分组成员'
                                            'expand' => false,
                                            'checked' => false,
                                            'disabled' => true,
                                            'necessary_functions' => [
                                                'Svngroup/GetGroupMember',
                                            ],
                                            'children' => []
                                        ],
                                    ]
                                ],
                                [
                                    'title' => \L::get_svn_alias_list,  //'获取SVN别名列表'
                                    'expand' => false,
                                    'checked' => false,
                                    'disabled' => true,
                                    'necessary_functions' => [
                                        'Svnaliase/GetAliaseList',
                                    ],
                                    'children' => []
                                ],
                            ]
                        ]
                    ]
                ],
                [
                    'title' => \L::edit_group_name, //'编辑'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [
                        'Svngroup/UpdGroupName',
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::delete_group,    //'删除'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'necessary_functions' => [
                        'Svngroup/DelGroup',
                    ],
                    'children' => []
                ],
            ]
        ],
        [
            'title' => \L::system_logs,    // '系统日志'
            'expand' => false,
            'checked' => false,
            'disabled' => false,
            'router_name' => 'logs',
            'necessary_functions' => [],
            'children' => [
                [
                    'title' => \L::get_log_list,    // '获取日志列表'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'router_name' => 'Logs',
                    'necessary_functions' => [
                        'Logs/GetLogList',
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::clear_logs,    // '清空日志'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'router_name' => 'Logs',
                    'necessary_functions' => [
                        'Logs/DelLogs',
                    ],
                    'children' => []
                ],
            ]
        ],
        [
            'title' => \L::task_scheduling,    // '任务计划'
            'expand' => false,
            'checked' => false,
            'disabled' => false,
            'router_name' => 'crond',
            'necessary_functions' => [
                'Crond/GetCronStatus',
                'Crond/GetCrontabList',
                'Crond/GetRepList'
            ],
            'children' => [
                [
                    'title' => \L::add_task_schedule,    // '添加任务计划'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'router_name' => 'Crond',
                    'necessary_functions' => [
                        'Crond/CreateCrontab',
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::enable_or_disable_schedule,    // '启用或禁用任务计划'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'router_name' => 'Crond',
                    'necessary_functions' => [
                        'Crond/UpdCrontabStatus',
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::other,   //'其它'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => false,
                    'router_name' => 'Crond',
                    'necessary_functions' => [],
                    'children' => [
                        [
                            'title' => \L::view_schedule_log,   //'日志(查看任务计划执行日志)'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => false,
                            'router_name' => 'Crond',
                            'necessary_functions' => [
                                'Crond/GetCrontabLog',
                            ],
                            'children' => []
                        ],
                        [
                            'title' => \L::edit_schedule,   //'编辑(编辑任务计划)'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => false,
                            'router_name' => 'Crond',
                            'necessary_functions' => [
                                'Crond/UpdCrontab',
                            ],
                            'children' => []
                        ],
                        [
                            'title' => \L::delete_schedule, //'删除(删除任务计划)'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => false,
                            'router_name' => 'Crond',
                            'necessary_functions' => [
                                'Crond/DelCrontab',
                            ],
                            'children' => []
                        ],
                        [
                            'title' => \L::execute_schedule,    //'执行(立即执行一次任务计划)'
                            'expand' => false,
                            'checked' => false,
                            'disabled' => false,
                            'router_name' => 'Crond',
                            'necessary_functions' => [
                                'Crond/TriggerCrontab',
                            ],
                            'children' => []
                        ],
                    ]
                ],
            ]
        ],
        [
            'title' => \L::personal_center,    // '个人中心'
            'expand' => false,
            'checked' => true,
            'disabled' => true,
            'router_name' => 'personal',
            'necessary_functions' => [
                'Personal/UpdSubadminUserPass',
                'Setting/CheckUpdate',
                'Common/Logout'
            ],
            'children' => []
        ],
        [
            'title' => \L::system_settings,    // '系统配置'
            'expand' => false,
            'checked' => false,
            'disabled' => false,
            'router_name' => 'setting',
            'necessary_functions' => [],
            'children' => [
                [
                    'title' => \L::host_configuration,    // '主机配置'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => true,
                    'necessary_functions' => [
                        'Setting/GetDcokerHostInfo',
                        'Setting/UpdDockerHostInfo',
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::path_info,    // '路径信息'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => true,
                    'necessary_functions' => [
                        'Setting/GetDirInfo',
                        'Setting/UpdSvnAuthzSingle',
                        'Setting/UpdSvnservePort'
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::svn_protocol_checkout,    // 'svn协议检出'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => true,
                    'necessary_functions' => [
                        'Setting/GetSvnInfo',

                        'Setting/UpdSvnEnable',

                        'Setting/UpdSvnserveStatusStop',
                        'Setting/UpdSvnserveStatusStart',

                        'Setting/UpdSvnservePort',
                        'Setting/UpdSvnserveHost',

                        'Setting/UpdSaslStatusStart',
                        'Setting/UpdSaslStatusStop',

                        'Setting/LdapTest',
                        'Setting/UpdSvnUsersource'
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::http_protocol_checkout,    // 'http协议检出'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => true,
                    'necessary_functions' => [
                        'Setting/GetApacheInfo',

                        'Setting/UpdSubversionEnable',

                        'Setting/UpdHttpPort',
                        'Setting/UpdHttpPrefix',

                        'Setting/LdapTest',
                        'Setting/UpdHttpUsersource'
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::mail_service,    // '邮件服务'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => true,
                    'necessary_functions' => [
                        'Setting/GetMailInfo',
                        'Setting/GetMailPushInfo',
                        'Setting/SendMailTest',
                        'Setting/UpdMailInfo'
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::message_push,    // '消息推送'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => true,
                    'necessary_functions' => [
                        'Setting/GetMailPushInfo',
                        'Setting/UpdPushInfo'
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::security_configuration,    // '安全配置'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => true,
                    'necessary_functions' => [
                        'Setting/GetSafeInfo',
                        'Setting/UpdSafeInfo'
                    ],
                    'children' => []
                ],
                [
                    'title' => \L::system_update,    // '系统更新'
                    'expand' => false,
                    'checked' => false,
                    'disabled' => true,
                    'necessary_functions' => [
                        'Setting/CheckUpdate'
                    ],
                    'children' => []
                ],
            ]
        ],
    ];

    /**
     * 所有角色路由
     *
     * @var array
     */
    public $route = [
        'name' => 'manage',
        'path' => '/',
        'redirect' => [
            'name' => 'login'
        ],
        'meta' => [
            'title' => 'SVNAdmin',
            'requireAuth' => false,
        ],
        'component' => 'layout/basicLayout/index.vue',
        'children' => [
            [
                'name' => 'index',
                'path' => '/index',
                'meta' => [
                    'title' => \L::statistics_info,    // '信息统计'
                    'icon' => 'ios-stats',
                    'requireAuth' => true,
                    'user_role_id' => [1, 3],
                    'group' => [
                        'name' => \L::repository,    //'仓库'
                        'num' => 1
                    ],
                    'id' => 1001
                ],
                'component' => 'index/index.vue'
            ],
            [
                'name' => 'repositoryInfo',
                'path' => '/repositoryInfo',
                'meta' => [
                    'title' => \L::svn_repository,  //'SVN仓库'
                    'icon' => 'logo-buffer',
                    'requireAuth' => true,
                    'user_role_id' => [1, 2, 3],
                    'group' => [
                        'name' => \L::repository,    //'仓库'
                        'num' => 1
                    ],
                    'id' => 1002
                ],
                'component' => 'repositoryInfo/index.vue'
            ],
            [
                'name' => 'repositoryUser',
                'path' => '/repositoryUser',
                'meta' => [
                    'title' => \L::svn_user,    //'SVN用户'
                    'icon' => 'md-person',
                    'requireAuth' => true,
                    'user_role_id' => [1, 3],
                    'group' => [
                        'name' => \L::repository,    //'仓库'
                        'num' => 1
                    ],
                    'id' => 1003
                ],
                'component' => 'repositoryUser/index.vue'
            ],
            [
                'name' => 'repositoryGroup',
                'path' => '/repositoryGroup',
                'meta' => [
                    'title' => \L::svn_group,    //'SVN分组'
                    'icon' => 'md-people',
                    'requireAuth' => true,
                    'user_role_id' => [1, 3],
                    'group' => [
                        'name' => \L::repository,    //'仓库'
                        'num' => 1
                    ],
                    'id' => 1004
                ],
                'component' => 'repositoryGroup/index.vue'
            ],
            [
                'name' => 'logs',
                'path' => '/logs',
                'meta' => [
                    'title' => \L::system_logs, //'系统日志'
                    'icon' => 'md-bug',
                    'requireAuth' => true,
                    'user_role_id' => [1, 3],
                    'group' => [
                        'name' => \L::operation_and_maintenance,    //'运维'
                        'num' => 2
                    ],
                    'id' => 1005
                ],
                'component' => 'logs/index.vue'
            ],
            [
                'name' => 'crond',
                'path' => '/crond',
                'meta' => [
                    'title' => \L::task_scheduling,    //'任务计划'
                    'icon' => 'ios-alarm',
                    'requireAuth' => true,
                    'user_role_id' => [1, 3],
                    'group' => [
                        'name' => \L::operation_and_maintenance,    //'运维'
                        'num' => 2
                    ],
                    'id' => 1006
                ],
                'component' => 'crond/index.vue'
            ],
            [
                'name' => 'personal',
                'path' => '/personal',
                'meta' => [
                    'title' => \L::personal_center,    //'个人中心'
                    'icon' => 'md-cube',
                    'requireAuth' => true,
                    'user_role_id' => [1, 2, 3],
                    'group' => [
                        'name' => \L::advanced, //'高级'
                        'num' => 3
                    ],
                    'id' => 1007
                ],
                'component' => 'personal/index.vue'
            ],
            [
                'name' => 'subadmin',
                'path' => '/subadmin',
                'meta' => [
                    'title' => \L::subadmin, //'子管理员'
                    'icon' => 'md-hand',
                    'requireAuth' => true,
                    'user_role_id' => [1],
                    'group' => [
                        'name' => \L::advanced, //'高级'
                        'num' => 3
                    ],
                    'id' => 1008
                ],
                'component' => 'subadmin/index.vue'
            ],
            [
                'name' => 'setting',
                'path' => '/setting',
                'meta' => [
                    'title' => \L::system_settings, //'系统配置'
                    'icon' => 'md-settings',
                    'requireAuth' => true,
                    'user_role_id' => [1, 3],
                    'group' => [
                        'name' => \L::advanced, //'高级'
                        'num' => 3
                    ],
                    'id' => 1009
                ],
                'component' => 'setting/index.vue'
            ],
        ]
    ];

    function __construct($parm)
    {
        //配置信息
        $this->configBin =  Config::get('bin');                       //可执行文件路径
        $this->configSvn = Config::get('svn');                        //仓库
        $this->configReg = Config::get('reg');                        //正则
        $this->configSign = Config::get('sign');                      //密钥

        $this->token = isset($parm['token']) ? $parm['token'] : '';

        global $database;
        if ($database) {
            $this->database = $database;
        } else {
            $configDatabase = Config::get('database');
            $configSvn = Config::get('svn');
            if (array_key_exists('database_file', $configDatabase)) {
                $configDatabase['database_file'] = sprintf($configDatabase['database_file'], $configSvn['home_path']);
            }
            try {
                $this->database = new Medoo($configDatabase);
            } catch (\Exception $e) {
                json1(200, 0, $e->getMessage());
            }
        }

        /**
         * 1、用户信息获取
         */
        if (empty($this->token)) {
            $this->userRoleId = isset($parm['payload']['userRoleId']) ? $parm['payload']['userRoleId'] : 0;
            $this->userName = isset($parm['payload']['userName']) ? $parm['payload']['userName'] : 0;
        } else {
            $array = explode($this->configSign['signSeparator'], $this->token);
            $this->userRoleId = $array[0];
            $this->userName = $array[1];
        }

        /**
         * 2、获取authz和passwd的配置文件信息
         */
        if ($this->configSvn['svn_single_authz']) {    //使用单一authz文件

            $this->authzContent = file_get_contents($this->configSvn['svn_authz_file']);
            $this->authzPath = $this->configSvn['svn_authz_file'];

        } else {    //仓库使用各自的 authz 文件

            //检查输入参数包含svn仓库名
            if (isset($this->payload['rep_name'])) {
                //获取仓库 authz 文件内容
                $this->authzPath = $this->configSvn['rep_base_path'] . $this->payload['rep_name'] . '/' . $this->configSvn['svn_standalone_authz_file'];
                $this->authzContent = file_get_contents($this->authzPath);

            } else {
                $this->authzContent = '';
                $this->authzPath = '';
            }  
        }

        $this->passwdContent = file_get_contents($this->configSvn['svn_passwd_file']);
        $this->httpPasswdContent = file_get_contents($this->configSvn['http_passwd_file']);
        $this->svnserveContent = file_get_contents($this->configSvn['svn_conf_file']);

        /**
         * 3、获取payload
         */
        $this->payload = isset($parm['payload']) ? $parm['payload'] : [];

        /**
         * 4、svnadmin对象
         */
        $this->SVNAdmin = new SVNAdmin();

        /**
         * 5、检查对象
         */
        $this->checkService = new Check($this->configReg);

        /**
         * 6、宿主机信息
         */
        $dockerHost = $this->database->get('options', [
            'option_id',
            'option_value'
        ], [
            'option_name' => '24_docker_host'
        ]);
        if (empty($dockerHost)) {
            $this->database->insert('options', [
                'option_value' => serialize([
                    'docker_host' => '127.0.0.1',
                    'docker_svn_port' => 3690,
                    'docker_http_port' => 80
                ]),
                'option_name' => '24_docker_host',
            ]);

            $this->dockerHost = '127.0.0.1';
            $this->dockerSvnPort = 3690;
            $this->dockerHttpPort = 80;
        } else {
            $dockerHost = unserialize($dockerHost['option_value']);

            $this->dockerHost = $dockerHost['docker_host'];
            $this->dockerSvnPort = $dockerHost['docker_svn_port'];
            $this->dockerHttpPort = $dockerHost['docker_http_port'];
        }

        /**
         * 7、本地信息
         */
        if (preg_match('/--listen-port[\s]+([0-9]+)/', file_get_contents($this->configSvn['svnserve_env_file']), $portMatchs)) {
            $this->localSvnPort = (int)trim($portMatchs[1]);
        }

        if (preg_match('/--listen-host[\s]+([\S]+)\b/', file_get_contents($this->configSvn['svnserve_env_file']), $hostMatchs)) {
            $this->localSvnHost = trim($hostMatchs[1]);
        }

        $this->localHttpHost = '127.0.0.1';

        $localHost = $this->database->get('options', [
            'option_id',
            'option_value'
        ], [
            'option_name' => '24_local_host'
        ]);
        if (empty($localHost)) {
            $this->database->insert('options', [
                'option_value' => serialize([
                    'local_http_port' => 80
                ]),
                'option_name' => '24_local_host',
            ]);

            $this->localHttpPort = 80;
        } else {
            $localHost = unserialize($localHost['option_value']);

            $this->localHttpPort = $localHost['local_http_port'];
        }

        /**
         * 8、当前启用协议
         */
        $this->enableCheckout = $this->database->get('options', [
            'option_id',
            'option_value'
        ], [
            'option_name' => '24_enable_checkout'
        ]);
        if (empty($this->enableCheckout)) {
            $this->database->insert('options', [
                'option_value' => 'svn',
                'option_name' => '24_enable_checkout',
            ]);

            $this->enableCheckout = 'svn';
        } else {
            $this->enableCheckout = $this->enableCheckout['option_value'];
        }


        /**
         * 9、数据源
         */
        //svn
        $result = $this->database->get('options', [
            'option_id',
            'option_value'
        ], [
            'option_name' => '24_svn_datasource'
        ]);
        if (empty($result)) {
            $this->svnDataSource = [
                'user_source' => 'passwd',
                'group_source' => 'authz',
                'ldap' => [
                    //ldap服务器
                    'ldap_host' => 'ldap://127.0.0.1/',
                    'ldap_port' => 389,
                    'ldap_version' => 3,
                    'ldap_bind_dn' => '',
                    'ldap_bind_password' => '',

                    //用户相关
                    'user_base_dn' => '',
                    'user_search_filter' => '',
                    'user_attributes' => '',

                    //分组相关
                    'group_base_dn' => '',
                    'group_search_filter' => '',
                    'group_attributes' => '',
                    'groups_to_user_attribute' => '',
                    'groups_to_user_attribute_value' => ''
                ]
            ];
            $this->database->insert('options', [
                'option_name' => '24_svn_datasource',
                'option_value' => serialize($this->svnDataSource)
            ]);
        } else {
            $this->svnDataSource = unserialize($result['option_value']);
        }

        //http
        $result = $this->database->get('options', [
            'option_id',
            'option_value'
        ], [
            'option_name' => '24_http_datasource'
        ]);
        if (empty($result)) {
            $this->httpDataSource = [
                'user_source' => 'httpPasswd',
                'group_source' => 'authz',
                'ldap' => [
                    //ldap服务器
                    'ldap_host' => 'ldap://127.0.0.1/',
                    'ldap_port' => 389,
                    'ldap_version' => 3,
                    'ldap_bind_dn' => '',
                    'ldap_bind_password' => '',

                    //用户相关
                    'user_base_dn' => '',
                    'user_search_filter' => '',
                    'user_attributes' => '',

                    //分组相关
                    'group_base_dn' => '',
                    'group_search_filter' => '',
                    'group_attributes' => '',
                    'groups_to_user_attribute' => '',
                    'groups_to_user_attribute_value' => ''
                ]
            ];
            $this->database->insert('options', [
                'option_name' => '24_http_datasource',
                'option_value' => serialize($this->httpDataSource)
            ]);
        } else {
            $this->httpDataSource = unserialize($result['option_value']);
        }

        /**
         * 10、http访问仓库前缀
         */
        $this->httpPrefix = $this->database->get('options', [
            'option_id',
            'option_value'
        ], [
            'option_name' => '24_http_prefix'
        ]);
        if (empty($this->httpPrefix)) {
            $this->database->insert('options', [
                'option_value' => '/svn',
                'option_name' => '24_http_prefix',
            ]);

            $this->httpPrefix = '/svn';
        } else {
            $this->httpPrefix = $this->httpPrefix['option_value'];
        }
    }

    /**
     * 获取动态路由
     */
    public function GetDynamicRouting($userName, $userRole)
    {
        $route = $this->route;

        $functions = [];

        //根据权限树过滤路由(子管理员)
        if ($userRole == 3) {
            $routerNames = array_column($route['children'], 'name');
            $subadminTree = $this->database->get('subadmin', 'subadmin_tree', [
                'subadmin_name' => $userName,
            ]);
            $subadminTree = json_decode($subadminTree, true);
            $subadminTree = empty($subadminTree) ? [] : $subadminTree;
            foreach ($subadminTree as $node) {
                $temp1 = [];
                //父节点完全勾选
                if ($node['checked']) {
                    $temp1 = array_merge($temp1, $node['necessary_functions']);
                    $temp1 = array_merge($temp1, $this->GetPriFunctions($node['children']));
                } else {
                    //父节点未勾选但是子节点部分勾选
                    $temp2 = $this->GetPriFunctions($node['children']);
                    if (!empty($temp2)) {
                        $temp1 = array_merge($temp1, $node['necessary_functions']);
                        $temp1 = array_merge($temp1, $temp2);
                    }
                }

                if (empty($temp1)) {
                    if (($index = array_search($node['router_name'], $routerNames)) !== false) {
                        unset($route['children'][$index]);
                    }
                    continue;
                }
                $functions = array_merge($functions, $temp1);
            }
        }

        //根据meta值过滤路由(SVN用户)
        foreach ($route['children'] as $key => $value) {
            if (!in_array($userRole, $value['meta']['user_role_id'])) {
                unset($route['children'][$key]);
            }
        }

        $route['children'] = array_values($route['children']);

        if ($userRole == 1) {
            $functions = $this->GetPriFunctions($this->subadminTree);
        }

        return [
            'route' => $route,
            'functions' => $functions
        ];
    }

    /**
     * 获取有权函数
     * 
     * 子节点有值则合并父节点的值
     */
    public function GetPriFunctions($tree)
    {
        if (empty($tree)) {
            return [];
        }

        $functions = [];
        foreach ($tree as $node) {
            //父节点完全勾选
            if ($node['checked']) {
                $functions = array_merge($functions, $node['necessary_functions']);
                $functions = array_merge($functions, $this->GetPriFunctions($node['children']));
            } else {
                //父节点未勾选但是子节点部分勾选
                $temp = $this->GetPriFunctions($node['children']);
                if (!empty($temp)) {
                    $functions = array_merge($functions, $node['necessary_functions']);
                    $functions = array_merge($functions, $temp);
                }
            }
        }

        return $functions;
    }

    /**
     * 重读authz的值
     *
     * @return void
     */
    public function RereadAuthz()
    {
        $this->configSvn = Config::get('svn');

        if ($this->configSvn['svn_single_authz']) {    //使用单一authz文件

            $this->authzContent = file_get_contents($this->configSvn['svn_authz_file']);

        } else {    //仓库使用各自的 authz 文件

            logger("service.base.RereadAuthz - repName: ".$this->payload['rep_name'], 'DEBUG', $this->configSvn['log_base_path']);

            //检查输入参数包含svn仓库名
            if (!isset($this->payload['rep_name'])) {
                return message(200, 0, '缺少svn仓库名');
            }
            $repName = $this->payload['rep_name'];

            //获取仓库 authz 文件内容
            $this->authzPath = $this->configSvn['rep_base_path'] . $repName . '/' . $this->configSvn['svn_standalone_authz_file'];
            $this->authzContent = file_get_contents($this->authzPath);

        }
    }

    /**
     * 重读passwd的值
     *
     * @return void
     */
    public function RereadPasswd()
    {
        $this->passwdContent = file_get_contents($this->configSvn['svn_passwd_file']);
    }

    /**
     * 重读httPpasswd的值
     *
     * @return void
     */
    public function RereadHttpPasswd()
    {
        $this->httpPasswdContent = file_get_contents($this->configSvn['http_passwd_file']);
    }

    /**
     * 重读svnserve环境变量文件
     *
     * @return void
     */
    public function RereadSvnserve()
    {
        if (preg_match('/--listen-port[\s]+([0-9]+)/', file_get_contents($this->configSvn['svnserve_env_file']), $portMatchs)) {
            $this->localSvnPort = (int)trim($portMatchs[1]);
        }
        if (preg_match('/--listen-host[\s]+([\S]+)\b/', file_get_contents($this->configSvn['svnserve_env_file']), $hostMatchs)) {
            $this->localSvnHost = trim($hostMatchs[1]);
        }
    }

    /**
     * 重读数据源
     *
     * @return void
     */
    public function ReloadDatasource()
    {
        //svn
        $result = $this->database->get('options', [
            'option_id',
            'option_value'
        ], [
            'option_name' => '24_svn_datasource'
        ]);

        $this->svnDataSource = unserialize($result['option_value']);

        //http
        $result = $this->database->get('options', [
            'option_id',
            'option_value'
        ], [
            'option_name' => '24_http_datasource'
        ]);

        $this->httpDataSource = unserialize($result['option_value']);
    }

    /**
     * 获取用户有权限的仓库
     *
     * @return array
     */
    public function GetUserRepoList() {
        if (empty($this->userName)) {
            return [];
        }
        
        //如果是管理员，返回所有仓库
        $admin = $this->database->select('admin_users', 'admin_user_id', 
            [
                'admin_user_name' => $this->userName
            ]
        );
        if (!empty($admin)) {
            $repList = $this->database->select('svn_reps', [
                'rep_name'
            ]);

            $userRepList = [];
            foreach ($repList as $rep) {
                $userRepList[] = $rep['rep_name'];
            }
            return $userRepList;
        }

        //如果是子管理员，返回其有权限管理的仓库
        $repList = $this->database->select('subadmin', [
            'rep_name'
        ], [
            'subadmin_name' => $this->userName
        ]);
        if (!empty($repList)) {
            $userRepList = explode(',', $repList[0]['rep_name']);
            return $userRepList;
        }

        //如果是普通用户，返回用户有权限访问的仓库
        $repList = $this->database->select('svn_user_pri_paths', [
            'rep_name'
        ], [
            'svn_user_name' => $this->userName
        ]);
        if (!empty($repList)) {
            $userRepList = [];
            foreach ($repList as $rep) {
                $userRepList[] = $rep['rep_name'];
            }
            return $userRepList;
        }

        return [];
    }
}
