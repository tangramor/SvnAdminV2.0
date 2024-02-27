export default {
    en: 'English',
    cancel: 'Cancel',
    confirm: 'Confirm',
    save: 'Save',
    add: 'Add',
    loginPage: {
        inputUsername: 'Please enter username',
        inputPassword: 'Please enter password',
        inputCode: 'Please enter verification code',
        login: 'Login',
        usernameCannotBeEmpty: 'Username cannot be empty',
        passwordCannotBeEmpty: 'Password cannot be empty',
        codeCannotBeEmpty: 'Verification code cannot be empty',
        userAlreadyLogin: 'User already login，redirecting...',
        登陆成功: 'Login success',
        退出登录成功: 'Logout success',
        验证码错误: 'Login failed[Verification code error]',
        验证码失效: 'Login failed[Verification code expired]',
        验证码过期: 'Login failed[Verification code expired]',
        账号或密码错误: 'Login failed[Account or password error]',
        ldap账户未同步: 'Login failed[ldap account not synchronized]',
        ldap账户认证失败: 'Login failed[ldap account authentication failed]',
        ldap账户名不合法: 'Login failed[ldap account name is illegal]',
        用户已过期: 'Login failed[User has expired]',
        用户未同步: 'Login failed[User not synchronized]',
    },
    menus: {
        backendTasks: 'Backend Tasks',
        仓库: 'Repository',
        信息统计: 'Statics',
        SVN仓库: 'SVN Repository',
        SVN用户: 'SVN Users',
        SVN分组: 'SVN Groups',
        运维: 'Operations',
        系统日志: 'System Logs',
        任务计划: 'Cron Tasks',
        高级: 'Advanced',
        个人中心: 'Personal',
        子管理员: 'Sub-Admin',
        系统配置: 'System Config',
        logout: 'Logout',
    },
    roles: {
        管理员: 'Administrator',
        SVN用户: 'SVN User',
        子管理员: 'Sub-Admin',
        未知: 'Unknown',
    },
    backendTasks: {
        realtimeBackendTasks: 'Real-time Backend Tasks',
        currentTasks: 'Current Tasks',
        tasksInQueue: 'Tasks in Queue',
        historyTasks: 'History Tasks',
        noTasksRunning: 'Currently no background tasks running (if there is a backlog of tasks, please restart the daemon process to resolve the issue)',
    },
    errors: {
        contactAdmin: 'There was an error. Please contact the administrator.',
    },
    crond: {
        plsCheckCrondAtd: 'Please check if crond/atd is installed correctly and running',
        addCrond: 'Add Crond',
        searchByNameAndDesc: 'Search by task name and description',
        noNotice: 'No notice',
        successNotice: 'Only success notice',
        failureNotice: 'Only failure notice',
        allNotice: 'All notice',
        viewLog: 'Log',
        tipCheckByTrigger: 'Trigger the task manually to check the specific situation by analyzing the log',
        trigger: 'Trigger',
        type: 'Task Type',
        name: 'Task Name',
        cycleType: 'Task Cycle',
        changeRepo: 'Change Repository',
        notice: 'Notification',
        noticeSuccess: 'Success notification',
        noticeFailure: 'Failure notification',
        saveCount: 'Save Count',
        scriptContent: 'Script Content',
        inputScriptContent: 'Input script content',
        viewCrondLog: 'View Crond Log',
        logFile: 'Log File',
        dumpFull: 'Repository Backup [dump-full]',
        dumpDeltas: 'Repository Backup [dump-deltas]',
        hotcopyFull: 'Repository Backup [hotcopy-full]',
        hotcopyDeltas: 'Repository Backup [hotcopy-deltas]',
        allRepos: 'All Repositories',
        checkRepo: 'Check Repository',
        shellScript: 'Shell Script',
        syncSvnUser: 'Sync Svn User',
        syncSvnGroup: 'Sync Svn Group',
        syncSvnRepo: 'Sync Svn Repository',
        minute: 'Minute',
        minute_n:'Every N minutes',
        hour: 'Hourly',
        hour_n: 'Every N hours',
        day: 'Daily',
        day_n: 'Every N days',
        week: 'Weekly',
        month: 'Monthly',
        Monday: 'Monday',
        Tuesday: 'Tuesday',
        Wednesday: 'Wednesday',
        Thursday: 'Thursday',
        Friday: 'Friday',
        Saturday: 'Saturday',
        Sunday: 'Sunday',
        monthDay: 'Date {0}',
        dayDay: ' days',
        hourHour: ' hours',
        minuteMinute: ' minutes',
        serial: 'Serial',
        cycleDesc: 'Cycle Description',
        status: 'Status',
        lastExecTime: 'Last Execution Time',
        others: 'Others',
        time: 'Time',
        content: 'Content',
        editCrond: 'Edit Crontab',
        deleteCrond: 'Delete Crontab',
        confirmDelCrond: 'Are you sure to delete this crontab task? All tasks related to this record will be deleted!',
        triggerCrond: 'Trigger Crontab',
        confirmTriggerCrond: 'Are you sure to trigger this crontab task? This operation can be used to test the correctness of the crontab task configuration!',
    },
    repositoryInfo: {
        noDataNow: 'No data now',
    }
}