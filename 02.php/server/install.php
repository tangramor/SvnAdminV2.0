<?php
/*
 * @Author: witersen
 * 
 * @LastEditors: witersen
 * 
 * @Description: QQ:1801168257
 */

/**
 * 安装和升级程序
 */

/**
 * 将工作模式限制在cli模式
 */
if (!preg_match('/cli/i', php_sapi_name())) {
    exit('require php-cli mode');
}

define('BASE_PATH', __DIR__);

auto_require(BASE_PATH . '/../app/util/Config.php');

auto_require(BASE_PATH . '/../config/');

auto_require(BASE_PATH . '/../app/function/');

auto_require(BASE_PATH . '/../extension/Philipp15b/i18n.class.php');

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

class Install
{

    private $configDb;
    private $configReg;
    private $configSvn;
    private $configUpdate;
    private $configVersion;
    private $configBin;

    private $scripts = [
        [
            'index' => 1,
            'note' => \L::help_install_config_svn   //'帮我安装并配置Subversion'
        ],
        [
            'index' => 2,
            'note' => \L::initial_svn_for_this_system   //'按照本系统的要求初始化Subversion（针对以其它方式安装的Subversion）'
        ],
        [
            'index' => 3,
            'note' => \L::check_new_version   //'检测SVNAdmin的新版本'
        ],
        [
            'index' => 4,
            'note' => \L::change_data_storage_dir   //'修改当前的数据存储主目录'
        ]
    ];

    function __construct()
    {
        Config::load(BASE_PATH . '/../config/');

        $this->configDb = Config::get('database');
        $this->configReg = Config::get('reg');
        $this->configSvn = Config::get('svn');
        $this->configUpdate = Config::get('update');
        $this->configVersion = Config::get('version');
        $this->configBin = Config::get('bin');
    }

    /**
     * 检测SVNAdmin的新版本并选择更新
     */
    function DetectUpdate()
    {
        foreach ($this->configUpdate['update_server'] as $key1 => $value1) {

            $result = funCurlRequest(sprintf($value1['url'], $this->configVersion['version']));

            if (empty($result)) {
                echo sprintf(\L::timeout_on_access_node, $value1['nodeName'], PHP_EOL); //'节点[%s]访问超时-切换下一节点%s'
                echo '===============================================' . PHP_EOL;
                continue;
            }

            //json => array
            $result = json_decode($result, true);

            if (!isset($result['code'])) {
                echo sprintf(\L::response_error_from_node, $value1['nodeName'], PHP_EOL);   //'节点[%s]返回信息错误-切换下一节点%s'
                echo '===============================================' . PHP_EOL;
                continue;
            }

            if ($result['code'] != 200) {
                echo sprintf(\L::error_code_return_from_node, $value1['nodeName'], $result['status'], $result['message'], $result['code'], PHP_EOL);    //'节点[%s]返回状态码[%s]状态[%s]错误信息[%s]-切换下一节点%s'
                echo '===============================================' . PHP_EOL;
                continue;
            }

            if (empty($result['data'])) {
                echo sprintf(\L::is_newest_version, $this->configVersion['version'], PHP_EOL);  //'当前为最新版[%s]%s'
                echo '===============================================' . PHP_EOL;
                exit();
            }

            echo sprintf(\L::new_version_available, $result['data']['version'], PHP_EOL);   //'有新版本[%s]%s'

            echo sprintf(\L::fixed_bugs, PHP_EOL);  //'修复内容如下:%s'
            foreach ($result['data']['fixd']['con'] as $cons) {
                echo sprintf('    [%s] %s%s', $cons['title'], $cons['content'], PHP_EOL);
                // echo '    [' . $cons['title'] . ']' . ' ' . $cons['content'] . PHP_EOL;
            }

            echo sprintf(\L::added_features, PHP_EOL);  //'新增内容如下:%s'
            foreach ($result['data']['add']['con'] as $cons) {
                echo sprintf('    [%s] %s%s', $cons['title'], $cons['content'], PHP_EOL);
                // echo '    [' . $cons['title'] . ']' . ' ' . $cons['content'] . PHP_EOL;
            }

            echo sprintf(\L::removed_features, PHP_EOL);    //'移除内容如下:%s'
            foreach ($result['data']['remove']['con'] as $cons) {
                echo sprintf('    [%s] %s%s', $cons['title'], $cons['content'], PHP_EOL);
                // echo '    [' . $cons['title'] . ']' . ' ' . $cons['content'] . PHP_EOL;
            }

            echo sprintf(\L::confirm_upgrade, $result['data']['version']);  //'确定要升级到[%s]版本吗[y/n]: '

            $answer = strtolower(trim(fgets(STDIN)));

            if (!in_array($answer, ['y', 'n'])) {
                echo sprintf(\L::wrong_answer, PHP_EOL);    //'不正确的选项%s'
                echo '===============================================' . PHP_EOL;
                exit();
            }

            if ($answer == 'n') {
                echo sprintf(\L::cancelled, PHP_EOL);   //'已取消%s'
                echo '===============================================' . PHP_EOL;
                exit();
            }

            //下载并执行升级脚本
            echo sprintf(\L::start_download_upgrade_package, PHP_EOL);  //'开始下载升级包%s'
            echo '===============================================' . PHP_EOL;
            $packages = isset($result['data']['update']['download'][$key1]['packages']) ? $result['data']['update']['download'][$key1]['packages'] : [];
            $forList = array_column($packages, 'for');
            $current = [
                'source' => $this->configVersion['version'],
                'dest' => $result['data']['version']
            ];
            if (!in_array($current, $forList)) {
                echo sprintf(\L::no_upgrade_package_available, PHP_EOL);    //'没有合适的升级包-请尝试直接手动安装最新版%s'
                echo '===============================================' . PHP_EOL;
                exit();
            }
            $index = array_search($current, $forList);
            $update_download_url = $packages[$index]['url'];
            $update_zip = funCurlRequest($update_download_url);
            if ($update_zip == null) {
                echo sprintf(\L::timeout_on_download_upgrade_package_from_node, $value1['nodeName'], PHP_EOL);  //'从节点[%s]下载升级包超时-切换下一节点%s'
                echo '===============================================' . PHP_EOL;
                continue;
            }
            file_put_contents(BASE_PATH . '/update.zip', $update_zip);
            echo sprintf(\L::upgrade_package_downloaded, PHP_EOL);  //'升级包下载完成%s'
            echo '===============================================' . PHP_EOL;

            echo sprintf(\L::decompress_upgrade_package, PHP_EOL);  //'开始解压升级包[覆盖解压]%s'
            echo '===============================================' . PHP_EOL;
            passthru('unzip -o ' . BASE_PATH . '/update.zip');
            if (!is_dir(BASE_PATH . '/update')) {
                echo sprintf(\L::error_on_decompress_upgrade_package, PHP_EOL); //'解压升级包出错-请尝试手动解压并执行升级程序[php update/index.php]%s'
                echo '===============================================' . PHP_EOL;
                exit();
            }
            echo sprintf(\L::upgrade_package_decompressed, PHP_EOL);    //'升级包解压完成%s'
            echo '===============================================' . PHP_EOL;

            echo sprintf(\L::confirm_exec_upgrade); //'确定要执行升级程序吗[y/n]: '

            $answer = strtolower(trim(fgets(STDIN)));

            if (!in_array($answer, ['y', 'n'])) {
                echo sprintf(\L::wrong_answer, PHP_EOL);    //'不正确的选项%s'
                echo '===============================================' . PHP_EOL;
                exit();
            }

            if ($answer == 'n') {
                echo sprintf(\L::cancelled, PHP_EOL);   //'已取消%s'
                echo '===============================================' . PHP_EOL;
                exit();
            }

            echo sprintf(\L::upgrading, PHP_EOL);   //'正在执行升级程序%s'
            echo '===============================================' . PHP_EOL;

            passthru('php ' . BASE_PATH . '/update/index.php');

            passthru(sprintf("cd '%s' && rm -rf ./update && rm -f update.zip", BASE_PATH));

            echo '===============================================' . PHP_EOL;

            echo sprintf(\L::upgrade_success, PHP_EOL); //'升级成功-请重启守护进程使部分配置文件生效%s'
            echo '===============================================' . PHP_EOL;
            exit();
        }
    }

    /**
     * 将SVNAdmin加入到开机自启动
     */
    function InitlSVNAdmin()
    {
    }

    /**
     * 将SVNAdmin取消开机自启动
     */
    function UninitSVNAdmin()
    {
    }

    /**
     * 将SVNAdmin加入监控 如果检测到异常退出则自动重启
     */
    function Monitor()
    {
    }

    /**
     * 获取Linux操作系统类型
     * 
     * /etc/redhat-release      redhat 或 centos 或 rocky
     * /etc/debian_version      debian 或 ubuntu
     * /etc/slackware_version   Slackware
     * /etc/lsb-release         ubuntu
     */
    private function GetOS()
    {
        if (PHP_OS != 'Linux') {
            return false;
        }
        if (file_exists('/etc/redhat-release')) {
            $readhat_release = file_get_contents('/etc/redhat-release');
            $readhat_release = strtolower($readhat_release);
            if (strstr($readhat_release, 'centos')) {
                if (strstr($readhat_release, '8.')) {
                    return 'centos 8';
                } elseif (strstr($readhat_release, '7.')) {
                    return 'centos 7';
                } else {
                    return false;
                }
            } elseif (strstr($readhat_release, 'rocky')) {
                return 'rocky';
            } else {
                return false;
            }
        } elseif (file_exists('/etc/lsb-release')) {
            return 'ubuntu';
        } else {
            return false;
        }
    }

    /**
     * 检测目标路径是否为空
     *
     * @return bool
     */
    private function IsDirEmpty($path)
    {
        clearstatcache();

        $filename = scandir($path);

        foreach ($filename as $key => $value) {
            if ($value != '.' && $value != '..') {
                return false;
            }
        }

        return true;
    }

    /**
     * 修改已经安装的Subversion配置以适合SVNAdmin的管理
     */
    function ConfigSubversion()
    {
        /**
         * 1、检测 which 工具是否安装
         */
        if (trim(shell_exec('which which 2>/dev/null')) == '') {
            echo \L::which_is_not_installed . PHP_EOL;  //'当前环境没有安装 which 工具 不会自动检测软件安装位置！'
            echo '===============================================' . PHP_EOL;
        }

        echo PHP_EOL . '===============================================' . PHP_EOL;
        echo \L::confirm_config_svn;    //'确定要开始配置Subversion程序吗[y/n]：'
        $continue = strtolower(trim(fgets(STDIN)));

        if (!in_array($continue, ['y', 'n'])) {
            echo \L::wrong_answer2 . PHP_EOL;   //'不正确的选项！'
            echo '===============================================' . PHP_EOL;
            exit();
        }

        if ($continue == 'n') {
            echo \L::cancelled2 . PHP_EOL;  //'已取消！'
            echo '===============================================' . PHP_EOL;
            exit();
        }

        /**
         * 2、检测Subversion的安装情况
         */
        //检测是否有正在运行的进程
        if (shell_exec('ps auxf|grep -v "grep"|grep svnserve') != '') {
            echo \L::need_stop_svnserve_manually . PHP_EOL; //'请先手动停止正在运行的 svnserve 程序后重试！'
            echo '===============================================' . PHP_EOL;
            exit();
        }

        /**
         * 3、令用户手动选择配置程序的路径
         */
        $needBin = [
            'svn' => '',
            'svnadmin' => '',
            'svnlook' => '',
            'svnserve' => '',
            'svnversion' => '',
            'svnsync' => '',
            'svnrdump' => '',
            'svndumpfilter' => '',
            'svnmucc' => '',
            'svnauthz-validate' => '',
            'saslauthd' => '',
            'httpd' => '',
            'htpasswd' => ''
        ];

        echo '===============================================' . PHP_EOL;
        echo \L::start_config_svn . PHP_EOL;    //'开始配置Subversion程序！'
        echo '===============================================' . PHP_EOL;

        foreach ($needBin as $key => $value) {
            // echo "请输入[$key]程序位置：" . PHP_EOL;
            echo sprintf(\L::input_program_location, $key) . PHP_EOL;    //"请输入[%s]程序位置："
            if ($key == 'svnauthz-validate') {
                echo \L::centos_svnauthz_validate_location . PHP_EOL;   //'CentOS 下 svnauthz-validate 的位置通常为 /usr/bin/svn-tools/svnauthz-validate'
            }
            echo \L::auto_detected_location . PHP_EOL;  //'自动检测到以下程序路径：'
            passthru("which $key 2>/dev/null");
            echo \L::input_enter_or_manual_input;   //'请输入回车使用默认检测路径或手动输入：'
            $binPath = fgets(STDIN);
            if ($binPath == '') {
                echo \L::input_cannot_be_empty . PHP_EOL;   //'输入不能为空！'
                echo '===============================================' . PHP_EOL;
                exit();
            }
            if ($binPath == "\n") {
                $binPath = trim(shell_exec("which $key 2>/dev/null"));
                if ($binPath == '') {
                    if (in_array($key, [
                        'svnmucc',
                        'svnauthz-validate',
                        'saslauthd',
                        'httpd',
                        'htpasswd'
                    ])) {
                        echo sprintf(\L::need_input_program_path_manually, $key) . PHP_EOL;  //"未检测到 $key ，请手动输入程序路径！"
                        echo sprintf(\L::this_isnt_necessary_can_ignore, $key) . PHP_EOL;  //"由于 $key 在当前版本非必要，因此无安装可忽略"
                        echo '===============================================' . PHP_EOL;
                    } else {
                        echo sprintf(\L::need_input_program_path_manually, $key) . PHP_EOL;  //"未检测到 $key ，请手动输入程序路径！"
                        echo '===============================================' . PHP_EOL;
                        exit();
                    }
                }
            } else {
                $binPath = trim($binPath);
            }
            echo sprintf(\L::program_location_is, $key, $binPath) . PHP_EOL;    //"$key 程序位置：$binPath"
            echo '===============================================' . PHP_EOL;
            $needBin[$key] = $binPath;
        }

        $binCon = <<<CON
        <?php
        
        return [
            'svn' => '{$needBin['svn']}',
            'svnadmin' => '{$needBin['svnadmin']}',
            'svnlook' => '{$needBin['svnlook']}',
            'svnserve' => '{$needBin['svnserve']}',
            'svnversion' => '{$needBin['svnversion']}',
            'svnsync' => '{$needBin['svnsync']}',
            'svnrdump' => '{$needBin['svnrdump']}',
            'svndumpfilter' => '{$needBin['svndumpfilter']}',
            'svnmucc' => '{$needBin['svnmucc']}',
            'svnauthz-validate' => '{$needBin['svnauthz-validate']}',
            'saslauthd' => '{$needBin['saslauthd']}',
            'httpd' => '{$needBin['httpd']}',
            'htpasswd' => '{$needBin['htpasswd']}'
        ];
CON;

        file_put_contents(BASE_PATH . '/../config/bin.php', $binCon);

        /**
         * 4、相关文件配置
         */
        $templete_path = BASE_PATH . '/../templete/';

        echo \L::create_related_folders . PHP_EOL;  //'创建相关目录'

        clearstatcache();

        //创建SVNAdmin软件配置信息的主目录
        is_dir($this->configSvn['home_path']) ? '' : mkdir($this->configSvn['home_path'], 0754, true);

        //创建SVN仓库父目录
        is_dir($this->configSvn['rep_base_path']) ? '' : mkdir($this->configSvn['rep_base_path'], 0754, true);

        //创建推荐钩子目录
        is_dir($this->configSvn['recommend_hook_path']) ? '' : mkdir($this->configSvn['recommend_hook_path'], 0754, true);
        shell_exec(sprintf("cp -r '%s' '%s'", $templete_path . 'hooks', $this->configSvn['home_path']));

        //创建备份目录
        is_dir($this->configSvn['backup_base_path']) ? '' : mkdir($this->configSvn['backup_base_path'], 0754, true);

        //创建日志目录
        is_dir($this->configSvn['log_base_path']) ? '' : mkdir($this->configSvn['log_base_path'], 0754, true);

        //创建仓库结构模板目录
        is_dir($this->configSvn['templete_base_path'] . 'initStruct/01/branches') ? '' : mkdir($this->configSvn['templete_base_path'].'initStruct/01/branches', 0754, true);
        is_dir($this->configSvn['templete_base_path'] . 'initStruct/01/tags') ? '' : mkdir($this->configSvn['templete_base_path'].'initStruct/01/tags', 0754, true);
        is_dir($this->configSvn['templete_base_path'] . 'initStruct/01/trunk') ? '' : mkdir($this->configSvn['templete_base_path'].'initStruct/01/trunk', 0754, true);

        //创建sasl目录
        is_dir($this->configSvn['sasl_home']) ? '' : mkdir($this->configSvn['sasl_home'], 0754, true);

        //创建ldap目录
        is_dir($this->configSvn['ldap_home']) ? '' : mkdir($this->configSvn['ldap_home'], 0754, true);

        //创建crond目录
        is_dir($this->configSvn['crond_base_path']) ? '' : mkdir($this->configSvn['crond_base_path'], 0754, true);

        echo '===============================================' . PHP_EOL;

        echo \L::create_related_files . PHP_EOL;    //'创建相关文件'

        //写入svnserve环境变量文件
        $con_svnserve_env_file = file_get_contents($templete_path . 'svnserve/svnserve');
        $con_svnserve_env_file = sprintf($con_svnserve_env_file, $this->configSvn['rep_base_path'], $this->configSvn['svn_conf_file'], $this->configSvn['svnserve_log_file']);
        file_put_contents($this->configSvn['svnserve_env_file'], $con_svnserve_env_file);

        //写入SVN仓库权限配置文件
        $con_svn_conf_file = file_get_contents($templete_path . 'svnserve/svnserve.conf');
        file_put_contents($this->configSvn['svn_conf_file'], $con_svn_conf_file);

        //ldap服务器配置文件
        file_put_contents($this->configSvn['ldap_config_file'], '');

        //写入authz文件
        $con_svn_authz_file = file_get_contents($templete_path . 'svnserve/authz');
        if (file_exists($this->configSvn['svn_authz_file'])) {
            echo PHP_EOL . '===============================================' . PHP_EOL;
            echo \L::confirm_overwrite_authz;   //'要覆盖原有的权限配置文件 authz 吗？[y/n]：'
            $continue = strtolower(trim(fgets(STDIN)));

            if (!in_array($continue, ['y', 'n'])) {
                echo \L::wrong_answer2 . PHP_EOL;   //'不正确的选项！'
                echo '===============================================' . PHP_EOL;
                exit();
            }

            if ($continue == 'y') {
                //备份
                copy($this->configSvn['svn_authz_file'], $this->configSvn['home_path'] . time() . 'authz');
                //操作
                file_put_contents($this->configSvn['svn_authz_file'], $con_svn_authz_file);
            }
        } else {
            file_put_contents($this->configSvn['svn_authz_file'], $con_svn_authz_file);
        }

        //写入httpPasswd文件
        if (!file_exists($this->configSvn['http_passwd_file'])) {
            file_put_contents($this->configSvn['http_passwd_file'], '');
        }

        //写入passwd文件
        $con_svn_passwd_file = file_get_contents($templete_path . 'svnserve/passwd');
        if (file_exists($this->configSvn['svn_passwd_file'])) {
            echo PHP_EOL . '===============================================' . PHP_EOL;
            echo \L::confirm_overwrite_passwd;  //'要覆盖原有的权限配置文件 passwd 吗？[y/n]：'
            $continue = strtolower(trim(fgets(STDIN)));

            if (!in_array($continue, ['y', 'n'])) {
                echo \L::wrong_answer2 . PHP_EOL;   //'不正确的选项！'
                echo '===============================================' . PHP_EOL;
                exit();
            }

            if ($continue == 'y') {
                //备份
                copy($this->configSvn['svn_passwd_file'], $this->configSvn['home_path'] . time() . 'passwd');
                //操作
                file_put_contents($this->configSvn['svn_passwd_file'], $con_svn_passwd_file);
            }
        } else {
            file_put_contents($this->configSvn['svn_passwd_file'], $con_svn_passwd_file);
        }

        //创建svnserve运行日志文件
        file_put_contents($this->configSvn['svnserve_log_file'], '');

        //创建pid文件
        file_put_contents($this->configSvn['svnserve_pid_file'], '');

        echo '===============================================' . PHP_EOL;

        /**
         * 5、关闭selinux 
         * 包括临时关闭和永久关闭
         */
        echo \L::disable_selinux . PHP_EOL; //'临时关闭并永久关闭seliux'

        //临时关闭selinux
        shell_exec('setenforce 0');

        //永久关闭selinux
        shell_exec("sed -i 's/SELINUX=enforcing/SELINUX=disabled/g' /etc/selinux/config");

        echo '===============================================' . PHP_EOL;

        /**
         * 6、配置SQLite数据库文件
         */
        echo \L::config_and_use_sqlite . PHP_EOL;   //'配置并启用SQLite数据库'

        if (file_exists($this->configSvn['home_path'] . 'svnadmin.db')) {
            echo PHP_EOL . '===============================================' . PHP_EOL;
            echo \L::confirm_overwrite_svnadmin_db; //'要覆盖原有的SQLite数据库文件 svnadmin.db 吗？[y/n]：'
            $continue = strtolower(trim(fgets(STDIN)));

            if (!in_array($continue, ['y', 'n'])) {
                echo \L::wrong_answer2 . PHP_EOL;   //'不正确的选项！'
                echo '===============================================' . PHP_EOL;
                exit();
            }

            if ($continue == 'y') {
                //备份
                copy($this->configSvn['home_path'] . 'svnadmin.db', $this->configSvn['home_path'] . time() . 'svnadmin.db');
                //操作
                copy($templete_path . 'database/sqlite/svnadmin.db', $this->configSvn['home_path'] . 'svnadmin.db');
            }
        } else {
            copy($templete_path . 'database/sqlite/svnadmin.db', $this->configSvn['home_path'] . 'svnadmin.db');
        }

        echo '===============================================' . PHP_EOL;

        /**
         * 8、将svnserve注册为系统服务
         */
        echo \L::clean_old_svnserve . PHP_EOL;  //'清理之前注册的svnserve服务'

        passthru('systemctl stop svnserve.service');
        passthru('systemctl disable svnserve.service');
        passthru('systemctl daemon-reload');

        echo '===============================================' . PHP_EOL;

        echo \L::register_new_svnserve . PHP_EOL;   //'注册新的svnserve服务'

        $os = $this->GetOS();
        $con_svnserve_service_file = file_get_contents($templete_path . 'svnserve/svnserve.service');
        $con_svnserve_service_file = sprintf($con_svnserve_service_file, $this->configSvn['svnserve_env_file'], $needBin['svnserve'], $this->configSvn['svnserve_pid_file']);
        if ($os == 'centos 7' || $os == 'centos 8') {
            file_put_contents($this->configSvn['svnserve_service_file']['centos'], $con_svnserve_service_file);
        } elseif ($os == 'ubuntu') {
            file_put_contents($this->configSvn['svnserve_service_file']['ubuntu'], $con_svnserve_service_file);
        } elseif ($os == 'rocky') {
            file_put_contents($this->configSvn['svnserve_service_file']['centos'], $con_svnserve_service_file);
        } else {
            file_put_contents($this->configSvn['svnserve_service_file']['centos'], $con_svnserve_service_file);
            echo '===============================================' . PHP_EOL;
            echo \L::warn_about_untested_os_version . PHP_EOL;  //'警告！当前操作系统版本未测试，使用过程中可能会遇到问题！'
            echo '===============================================' . PHP_EOL;
        }

        echo '===============================================' . PHP_EOL;

        //启动
        echo \L::start_svnserve . PHP_EOL;  //'开始启动svnserve服务'

        passthru('systemctl daemon-reload');
        passthru('systemctl start svnserve');

        echo '===============================================' . PHP_EOL;

        //开机自启动
        echo \L::auto_start_svnserve . PHP_EOL; //'将svnserve服务加入到开机自启动'

        passthru('systemctl enable svnserve');

        echo '===============================================' . PHP_EOL;

        //查看状态
        echo \L::svnserve_running_status . PHP_EOL; //'svnserve安装成功，打印运行状态：'

        passthru('systemctl status svnserve');

        echo '===============================================' . PHP_EOL;
    }

    /**
     * 修改当前的数据存储主目录
     */
    function MoveHome()
    {
        //检查是否停止了svnserve
        if (shell_exec('ps auxf|grep -v "grep"|grep svnserve') != '') {
            echo \L::need_stop_svnserve_manually . PHP_EOL; //'请先手动停止正在运行的 svnserve 程序后重试！'
            echo '===============================================' . PHP_EOL;
            exit();
        }

        //输入路径
        echo \L::input_full_path_of_target_directory;   //'请输入目标目录的绝对路径：'
        $newHomePath = trim(fgets(STDIN));
        if ($newHomePath == '') {
            echo '===============================================' . PHP_EOL;
            echo \L::input_cannot_be_empty . PHP_EOL;   //'输入不能为空！'
            echo '===============================================' . PHP_EOL;
            exit();
        }

        //检查要修改的目标路径是否存在
        clearstatcache();
        if (!is_dir($newHomePath)) {
            echo '===============================================' . PHP_EOL;
            echo \L::target_directory_not_exists . PHP_EOL; //'目标目录不存在！'
            echo '===============================================' . PHP_EOL;
            exit();
        }

        //路径是否相同
        if ($newHomePath == $this->configSvn['home_path']) {
            echo '===============================================' . PHP_EOL;
            echo \L::no_change_on_path . PHP_EOL;   //'路径无变化！'
            echo '===============================================' . PHP_EOL;
            exit();
        }

        //检查目标路径是否为空
        if (!$this->IsDirEmpty($newHomePath)) {
            echo '===============================================' . PHP_EOL;
            echo \L::target_directory_should_be_empty . PHP_EOL;    //'目标目录需要为空！'
            echo '===============================================' . PHP_EOL;
            exit();
        }

        echo '===============================================' . PHP_EOL;
        echo \L::notice_this_step_for_config_initialized . PHP_EOL; //'提醒！该步骤适用于您之前执行过 [1] 或 [2] 步骤进行过初始化配置的情况'

        echo '===============================================' . PHP_EOL;
        echo \L::notice_not_suggest_to_move_data_storage_to_root . PHP_EOL; //'提醒！不建议将数据存储主目录移动到 root 目录下，因为这会导致读取权限出现问题（除非将 root 目录设置 777 ，但是也不是好主意）'

        //对输入的路径规范化，如果末尾没有带有 / 则自动补全
        if (substr($newHomePath, -1) != '/') {
            $newHomePath .= '/';
        }

        //再次确认
        echo '===============================================' . PHP_EOL;
        echo sprintf(\L::change_data_storage_dir_to, $this->configSvn['home_path'], $newHomePath) . PHP_EOL;    //'将数据存储主目录从 %s 修改为 %s'
        echo '===============================================' . PHP_EOL;
        echo \L::confirm_continue;  //'确定要继续操作吗[y/n]：'
        $continue = strtolower(trim(fgets(STDIN)));
        echo '===============================================' . PHP_EOL;

        if (!in_array($continue, ['y', 'n'])) {
            echo \L::wrong_answer2 . PHP_EOL;   //'不正确的选项！'
            echo '===============================================' . PHP_EOL;
            exit();
        }

        if ($continue == 'n') {
            echo \L::cancelled2 . PHP_EOL;  //'已取消！'
            echo '===============================================' . PHP_EOL;
            exit();
        }

        //旧内容
        $oldConfigSvn = $this->configSvn;

        //修改配置文件 svn.php
        $con = file_get_contents(BASE_PATH . '/../config/svn.php');
        $con  = preg_replace("/\\\$home[\s]*=[\s]*(['\"])(.*)\\1[;]/", sprintf("\$home = '%s';", $newHomePath), $con);
        //判断是否匹配成功
        file_put_contents(BASE_PATH . '/../config/svn.php', $con);

        //新内容
        $newConfigSvn = Config::get('svn');

        //修改svnserve文件中的仓库路径、配置文件路径、日志文件路径
        echo \L::modify_env_file_of_svnserve . PHP_EOL; //'修改svnserve环境变量文件'

        $templete_path = BASE_PATH . '/../templete/';
        $con_svnserve_env_file = file_get_contents($templete_path . 'svnserve/svnserve');
        $con_svnserve_env_file = sprintf($con_svnserve_env_file, $newConfigSvn['rep_base_path'], $newConfigSvn['svn_conf_file'], $newConfigSvn['svnserve_log_file']);
        file_put_contents($oldConfigSvn['svnserve_env_file'], $con_svnserve_env_file);

        echo '===============================================' . PHP_EOL;

        //开始移动主目录
        echo \L::start_move_home_dir . PHP_EOL; //'开始移动主目录'

        passthru(sprintf("mv %s* %s", $oldConfigSvn['home_path'], $newConfigSvn['home_path']));

        echo '===============================================' . PHP_EOL;

        echo \L::clean_old_svnserve . PHP_EOL;  //'清理之前注册的svnserve服务'

        passthru('systemctl stop svnserve.service');
        passthru('systemctl disable svnserve.service');
        passthru('systemctl daemon-reload');

        echo '===============================================' . PHP_EOL;

        echo \L::register_new_svnserve . PHP_EOL;   //'注册新的svnserve服务'

        $os = $this->GetOS();
        $con_svnserve_service_file = file_get_contents($templete_path . 'svnserve/svnserve.service');
        $con_svnserve_service_file = sprintf($con_svnserve_service_file, $newConfigSvn['svnserve_env_file'], $this->configBin['svnserve'], $newConfigSvn['svnserve_pid_file']);
        if ($os == 'centos 7' || $os == 'centos 8') {
            file_put_contents($newConfigSvn['svnserve_service_file']['centos'], $con_svnserve_service_file);
        } elseif ($os == 'ubuntu') {
            file_put_contents($newConfigSvn['svnserve_service_file']['ubuntu'], $con_svnserve_service_file);
        } elseif ($os == 'rocky') {
            file_put_contents($newConfigSvn['svnserve_service_file']['centos'], $con_svnserve_service_file);
        } else {
            file_put_contents($newConfigSvn['svnserve_service_file']['centos'], $con_svnserve_service_file);
            echo '===============================================' . PHP_EOL;
            echo \L::warn_about_untested_os_version . PHP_EOL;  //'警告！当前操作系统版本未测试，使用过程中可能会遇到问题！'
            echo '===============================================' . PHP_EOL;
        }

        echo '===============================================' . PHP_EOL;

        //启动
        echo \L::start_svnserve . PHP_EOL;  //'开始启动svnserve服务'

        passthru('systemctl daemon-reload');
        passthru('systemctl start svnserve');

        echo '===============================================' . PHP_EOL;

        //开机自启动
        echo \L::auto_start_svnserve . PHP_EOL; //'将svnserve服务加入到开机自启动'

        passthru('systemctl enable svnserve');

        echo '===============================================' . PHP_EOL;

        //查看状态
        echo \L::reconfigured_svnserve_and_print_status . PHP_EOL;  //'svnserve重新配置成功，打印运行状态：'

        passthru('systemctl status svnserve');

        echo '===============================================' . PHP_EOL;

        //重启守护进程
        echo \L::need_restart_daemon_manually . PHP_EOL;    //'请运行 svnadmind.php 程序手动重启后台程序！'

        passthru('php svnadmind.php stop');

        echo '===============================================' . PHP_EOL;

        //sqlite数据库
    }

    /**
     * 程序入口
     */
    function Run()
    {
        echo '===============SVNAdmin==================' . PHP_EOL;

        foreach ($this->scripts as $value) {
            echo '[' . $value['index'] . '] ' . $value['note'] . PHP_EOL;
        }

        echo '===============================================' . PHP_EOL;

        echo \L::input_command_number;  //'请输入命令编号：'

        $answer = trim(fgets(STDIN));

        echo '===============================================' . PHP_EOL;

        if (!in_array($answer, array_column($this->scripts, 'index'))) {
            exit(\L::wrong_command_number . PHP_EOL);   //'错误的命令编号：'
        }

        if ($answer == 1) {
            //帮我安装并配置Subversion

            $shellPath = BASE_PATH . '/../templete/install/WANdisco/';

            if (!is_dir($shellPath)) {
                exit(\L::install_script_dir_not_exists . PHP_EOL);  //'安装脚本目录不存在！'
            }

            $shell = scandir($shellPath);

            echo \L::desc_of_svn_install_script_1 . PHP_EOL;    //'| Subversion安装脚本来自 WANdiso'

            echo \L::desc_of_svn_install_script_2 . PHP_EOL;    //'| 当前提供的安装脚本不一定适配所有操作系统！如部分的ubuntu和rokcy等'

            echo \L::desc_of_svn_install_script_3 . PHP_EOL;    //'| 如果当前操作系统平台提供的Subversion版本较低（<1.8）才推荐使用此方法安装Subversion！'

            echo \L::desc_of_svn_install_script_4 . PHP_EOL;    //'| 如果由于网络延迟原因安装失败，可手动停止后多尝试几次'

            echo \L::desc_of_svn_install_script_5 . PHP_EOL;    //'| 在通过脚本安装Subversion的过程中，请注意信息交互！'

            echo '===============================================' . PHP_EOL;

            echo \L::svn_versions . PHP_EOL;    //'可选择的Subversion版本如下：'

            echo '===============================================' . PHP_EOL;

            $noShell = true;
            foreach ($shell as $value) {
                if ($value == '.' || $value == '..') {
                    continue;
                }
                $noShell = false;
                echo $value . PHP_EOL;
            }

            if ($noShell) {
                exit(\L::no_install_script . PHP_EOL);  //'没有可选的安装脚本！'
            }

            echo '===============================================' . PHP_EOL;

            echo \L::notice_supported_versions . PHP_EOL;   //'请注意SVNAdmin支持管理的Subversion版本为1.8+！'

            echo '===============================================' . PHP_EOL;

            echo \L::input_version_to_install;  //'请输入要安装的Subversion版本（推荐Subversion-1.10）：'

            $answer = trim(fgets(STDIN));

            echo '===============================================' . PHP_EOL;

            if (!file_exists($shellPath . 'subversion_installer_' . $answer . '.sh')) {
                exit(\L::need_correct_version . PHP_EOL);   //'请选择正确的版本！'
            }

            echo \L::start_exec_script . 'subversion_installer_' . $answer . '.sh' . PHP_EOL;   //'现在开始执行脚本：'

            echo '===============================================' . PHP_EOL;

            passthru('sh ' . $shellPath . 'subversion_installer_' . $answer . '.sh');

            $this->ConfigSubversion();
        } elseif ($answer == 2) {
            //按照本系统的要求初始化Subversion（针对以其它方式安装的Subversion）
            $this->ConfigSubversion();
        } elseif ($answer == 3) {
            //检测SVNAdmin的新版本
            $this->DetectUpdate();
        } elseif ($answer == 4) {
            //修改当前的数据存储主目录
            $this->MoveHome();
        }
    }
}

//检测禁用函数
$require_functions = ['shell_exec', 'passthru'];
$disable_functions = explode(',', ini_get('disable_functions'));
foreach ($disable_functions as $disable) {
    if (in_array(trim($disable), $require_functions)) {
        echo sprintf(\L::function_disabled, $disable) . PHP_EOL;   //"需要的 $disable 函数被禁用"
        exit();
    }
}

function getTranslations()
{
    $i18nPath = BASE_PATH . '/../app/lang/';
    $translations = [];
    $i18nFiles = array_diff(scandir($i18nPath), [".", ".."]);
    foreach ($i18nFiles as $key => $value) {
        $translations[] = pathinfo($value, PATHINFO_FILENAME);
    }
    return $translations;
}

echo '================Language=================' . PHP_EOL;

$languages = ['zh-CN' => '中文', 'en-US' => 'English'];

$translations = getTranslations();
foreach ($translations as $i => $lang) {
    echo '[' . $i . '] ' . $languages[$lang] . PHP_EOL;
}

echo "Choose language: ";

$answer = trim(fgets(STDIN));
if (!in_array($answer, array_keys($translations))) {
    exit("Wrong option number!" . PHP_EOL);
} else {
    $i18n = null;
    $i18n = new i18n();
    $i18n->setForcedLang($translations[intval($answer)]);
    $i18n->setCachePath('/tmp/langcache');
    $i18n->setFilePath(BASE_PATH . '/../app/lang/{LANGUAGE}.ini'); // language file path
    $i18n->setLangVariantEnabled(false); // trim region variant in language codes (e.g. en-us -> en)
    $i18n->setFallbackLang('en-US');
    $i18n->setSectionSeparator('_');
    $i18n->setMergeFallback(false); // make keys available from the fallback language
    $i18n->init();

    (new Install())->Run();
}
