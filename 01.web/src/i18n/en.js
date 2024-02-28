export default {
    en: 'English',
    cancel: 'Cancel',
    confirm: 'Confirm',
    save: 'Save',
    add: 'Add',
    edit: 'Edit',
    delete: 'Delete',
    username: 'Username',
    password: 'Password',
    newPassword: 'New Password',
    note: 'Note',
    serial: 'ID',
    status: 'Status',
    others: 'Others',
    operator: 'Operator',
    退出登录成功: 'Logout success',
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
        SVNAdmin: 'SVNAdmin',
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
        running: 'Running',
        waiting: 'Waiting',
        stopTask: 'Stop Task',
        cancelTask: 'Cancel Task',
        completed: 'Completed',
        cancelled: 'Cancelled',
        stopped: 'Stopped',
        viewLog: 'Log',
        taskLog: 'Task Log',
        taskName: 'Task Name',
        action: 'Action',
        endTime: 'End Time',
        stopConfirm: 'Confirm to stop task',
        stopConfirmContent: 'Are you sure to stop this task?<br/>Cannot garrantee the task will be stopped successfully!',
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
        monthDay: 'Day {0}',
        dayDay: ' days',
        hourHour: ' hours',
        minuteMinute: ' minutes',
        cycleDesc: 'Cycle Description',
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
    index: {
        loadStatus: 'Load Status',
        cpuLoad1Min: 'Last 1 minute average load:',
        cpuLoad5Min: 'Last 5 minute average load:',
        cpuLoad15Min: 'Last 15 minute average load:',
        cpuUsage: 'CPU Usage',
        memUsage: 'Memory Usage',
        cpuPhysical: ' Physical CPU',
        cpuCore: ' CPU Cores',
        cpuProcessor: ' Logical Processors',
        fileSystem: 'File System: ',
        fsSize: 'Size: ',
        fsUsed: 'Used: ',
        fsAvail: 'Available: ',
        fsPercent: 'Usage: ',
        mountOn: 'Mounted on: ',
        statistics: 'Statistics',
        svnRepo: 'SVN Repository',
        repoSize: 'Repository Size',
        repoBackup: 'Repository Backup',
        backupSize: 'Backup Size',
        logs: 'Logs',
        svnAlias: 'SVN Aliases',
        运行堵塞: 'Running Jammed',
        运行缓慢: 'Running Slow',
        运行正常: 'Running Normal',
        运行流畅: 'Running Quick',
        未知: 'Unknown',
    },
    logs: {
        clearLogs: 'Clear Logs',
        exportLogs: 'Export Logs',
        logName: 'SVNAdmin2-Logs',
        searchLogs: 'Search Logs',
        logType: 'Log Type',
        content: 'Content',
        addTime: 'Add Time',
    },
    personal: {
        changePassword: 'Change Password',
        adminAccount: 'Admin Account',
        adminPassword: 'Admin Password',
        subadminAccount: 'Sub-Admin Account',
        subadminPassword: 'Sub-Admin Password',
        modifyAdminAccount: 'Modify Admin Account',
        newAccount: 'New Account',
        modifyAdminPassword: 'Modify Admin Password',
        modifySubadminPassword: 'Modify Sub-Admin Password',
    },
    subadmin: {
        createSubadmin: 'Create Sub-Admin',
        searchByNameAndDesc: 'Search by username and description',
        priTree: 'Config',
        online: 'Online',
        offline: 'Offline',
        resetPassword: 'Reset',
        delete: 'Delete',
        permissionConfig: 'Config Permission of Sub-Admin',
        reAuth: 'Please refer to the old permission tree and re-authorize the sub-admin for the new permission nodes.',
        oldPriTree: 'Old Permission Tree',
        newPriTree: 'Permission Tree',
        lastLogin: 'Last Login',
        onlineStatus: 'Online Status',
        sysPermission: 'System Permission',
        deleteSubadmin: 'Delete Sub-Admin',
        confirmDeleteSubadmin: 'Are you sure to delete this sub-admin? <br/>This operation cannot be undone!',
    },
    repositoryInfo: {
        noDataNow: 'No data now',
    }
}