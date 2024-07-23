<?php
/*
 * @Author: witersen
 * 
 * @LastEditors: witersen
 * 
 * @Description: QQ:1801168257
 */

class Check
{
    private $configReg;
    private $L;

    function __construct($configReg)
    {
        $i18n = new i18n();
        $i18n->setCachePath('/tmp/langcache');
        $i18n->setFilePath(BASE_PATH . '/app/lang/{LANGUAGE}.ini'); // language file path
        $i18n->setFallbackLang('en-US');
        $i18n->setSectionSeparator('_');
        $i18n->setMergeFallback(false); // make keys available from the fallback language
        $i18n->init();

        $this->L = LangManager::getInstance($i18n->getAppliedLang());
        
        $this->configReg = $configReg;
    }

    /**
     * 检查SVN仓库名称
     */
    public function CheckRepName($repName)
    {
        if (preg_match($this->configReg['REG_SVN_REP_NAME'], $repName) != 1) {
            return ['code' => 200, 'status' => 0, 'message' => $this->L->translate('svn_repo_name_limit'), 'data' => []];  //'SVN仓库名称只能包含字母、数字、破折号、下划线、点，不能以点开头或结尾'
        }
        return ['code' => 200, 'status' => 1, 'message' => '', 'data' => []];
    }

    /**
     * 检查SVN用户名称
     */
    public function CheckRepUser($repUserName)
    {
        if (preg_match($this->configReg['REG_SVN_USER_NAME'], $repUserName) != 1) {
            return ['code' => 200, 'status' => 0, 'message' => $this->L->translate('svn_user_name_limit'), 'data' => []];  //'SVN用户名只能包含字母、数字、破折号、下划线、点'
        }
        return ['code' => 200, 'status' => 1, 'message' => '', 'data' => []];
    }

    /**
     * 检查SVN用户组名称
     */
    public function CheckRepGroup($repGroupName)
    {
        if (preg_match($this->configReg['REG_SVN_GROUP_NAME'], $repGroupName) != 1) {
            return ['code' => 200, 'status' => 0, 'message' => $this->L->translate('svn_group_name_limit'), 'data' => []]; //'SVN分组名只能包含字母、数字、破折号、下划线、点'
        }
        return ['code' => 200, 'status' => 1, 'message' => '', 'data' => []];
    }

    /**
     * 邮箱检查
     */
    public function CheckMail($mail)
    {
        if (preg_match_all($this->configReg['REG_MAIL'], $mail) == 1) {
            return ['code' => 200, 'status' => 0, 'message' => $this->L->translate('email_error'), 'data' => []];  //'邮箱错误'
        }
        return ['code' => 200, 'status' => 1, 'message' => '', 'data' => []];
    }
}
