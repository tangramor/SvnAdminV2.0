<?php
/*
 * @Author: witersen
 * @Date: 2022-04-27 23:19:24
 * @LastEditors: witersen
 * @LastEditTime: 2022-05-09 16:36:56
 * @Description: QQ:1801168257
 * @copyright: https://github.com/witersen/
 */

namespace SVNAdmin\SVN;

class Core
{
    /**
     * authz文件内容
     *
     * @var string
     */
    public $authzFileContent;

    /**
     * passwd文件内容
     *
     * @var string
     */
    public $passwdFileContent;

    /**
     * 禁用SVN用户前缀
     *
     * @var string
     */
    protected $REG_SVN_USER_DISABLED = '#disabled#';

    /**
     * 匹配authz文件中的用户权限
     *
     * @var string
     */
    protected $REG_AUTHZ_USER_PRI = "/^([A-Za-z0-9-_.\s]*[^\s])\s*=(.*)/m";

    /**
     * 匹配authz文件中的用户组权限
     *
     * @var string
     */
    protected $REG_AUTHZ_GROUP_PRI = "/^@([A-Za-z0-9-_.\s]*[^\s])\s*=(.*)/m";

    /**
     * 匹配authz文件中[groups]下的分组以及成员
     * 
     * [groups]
     * group1=u1,@group2
     * group1=u2
     *
     * @var string
     */
    protected $REG_AUTHZ_GROUP_KEY_VALUE = "/^([A-Za-z0-9-_.\s]*[^\s])\s*=(.*)/m";

    /**
     * 匹配authz文件中的[groups]及其内容
     *
     * 如
     * [groups]
     * g1=u1,u2
     * g2=u1,u3
     * 
     * @var string
     */
    protected $REG_AUTHZ_GROUP_WITH_CON = "/^\[groups\]([\s\S][^\[]*)/m";

    /**
     * 匹配passwd文件中的[users]及其内容
     *
     * [users]
     * u1=password
     * u2=password
     * 
     * @var string
     */
    protected $REG_PASSWD_USER_WITH_CON = "/^\[users\]([\s\S][^\[]*)/m";

    /**
     * 匹配passwd文件中的用户以及密码
     *
     * [users]
     * u1=password
     * u2=password
     * 中的后两行
     * 
     * @var string
     */
    protected $REG_PASSWD_USER_PASSWD = "/^((%s)*[A-Za-z0-9-_.]+)\s*=(.*)/m";


    /**
     * 匹配authz配置文件中某个分组有权限的仓库列表
     * 
     * %s => $group
     *
     * @var string
     */
    protected $REG_AUTHZ_GROUP_PRI_REPS = "/^\[(.*?):(.*?)\][A-za-z0-9_=@*\s]*?@%s[\s]*=[\s]*([rw]+)$\n/m";

    /**
     * 匹配authz配置文件中某个用户有权限的仓库列表
     * 
     * %s => $user
     *
     * @var string
     */
    protected $REG_AUTHZ_USER_PRI_REPS = "/^\[(.*?):(.*?)\][A-za-z0-9_=@*\s]*?%s[\s]*=[\s]*([rw]+)$\n/m";


    /**
     * 匹配authz配置文件中所有用户有权限的仓库列表
     * 
     * *=r、*=rw
     *
     * @var string
     */
    protected $REG_AUTHZ_ALL_HAVE_PRI_REPS = "/^\[(.*?):(.*?)\][A-za-z0-9_=@*\s]*?\*[\s]*=[\s]*([rw]+)$\n/m";


    /**
     * 匹配authz配置文件中所有用户无权限的仓库列表
     * 
     * *=
     *
     * @var string
     */
    protected $REG_AUTHZ_ALL_NO_PRI_REPS = "/^\[(.*?):(.*?)\][A-za-z0-9_=@*\s]*?\*[\s]*=[\s]*$\n/m";

    /**
     * 匹配通过魔力符号配置的相关信息
     * 
     * 包含 ~ $等前缀
     */
    //todo

    /**
     * 匹配authz配置文件中指定仓库的指定路径 包含内容
     * 
     * [rep1:/floder]
     * u1=r
     * @g1=rw
     * 
     * %s => $repName
     * %s => $repPath
     *
     * @var string
     */
    protected $REG_AUTHZ_REP_SPECIAL_PATH_WITH_CON = "/^\[%s:%s\]([\s\S][^\[]*)/m";

    /**
     * 匹配authz配置文件中指定仓库的指定路径 不包含内容
     * 
     * 如
     * [rep1:/]
     * 或
     * [rep2:/floder]
     * 等
     * 
     * %s => $repName
     * %s => $repPaht str_replace('/', '\/', $repPath)
     *
     * @var string
     */
    protected $REG_AUTHZ_REP_SPECIAL_PATH_WITHOUT_CON = "/^\[%s:%s\]/m";

    /**
     * 匹配authz配置文件中指定仓库的所有路径 不包含内容
     * 
     * %s => $repName
     *
     * @var string
     */
    protected $REG_AUTHZ_REP_ALL_PATH_WITHOUT_CON = "/^\[%s:(.*?)\]/m";

    /**
     * 匹配authz配置文件中指定仓库的所有路径以及包含的内容
     * 
     * %s => $repName
     *
     * @var string
     */
    protected $REG_AUTHZ_REP_ALL_PATH_WITH_CON = "/^\[%s:.*\][\s\S][^\[]*/m";

    /**
     * 匹配authz配置文件中的所有仓库名称
     * 
     * 不匹配这些仓库的内容和具体路径
     *
     * @var string
     */
    protected $REG_AUTHZ_ALL_REP_WITHOUT_PATH_AND_CON = "/^\[(.*?):.*?\]/m";

    /**
     * 将 svnadmin info $repPaht 的结果匹配为 key => value 形式
     *
     * @var string
     */
    protected $REG_REP_INFO = "/(.*):[\S]*(.*)/m";

    /**
     * 配置文件svn内容
     *
     * @var array
     */
    protected $config_svn;

    /**
     * 配置文件bin内容
     *
     * @var array
     */
    protected $config_bin;


    function __construct($authzFileContent, $passwdFileContent, $config_svn, $config_bin)
    {
        $this->authzFileContent = $authzFileContent;
        $this->passwdFileContent = $passwdFileContent;
        $this->config_svn = $config_svn;
        $this->config_bin = $config_bin;
    }
}