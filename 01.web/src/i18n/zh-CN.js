module.exports = {
    zh: '中文',
    cancel: '取消',
    confirm: '确定',
    save: '保存',
    add: '添加',
    edit: '编辑',
    modify: '修改',
    confirmModify: '确认修改',
    delete: '删除',
    copy: '复制',
    action: '操作',
    apply: '应用',
    close: '关闭',
    reset: '重设',
    view: '浏览',
    config: '配置',
    advance: '高级',
    username: '用户名',
    password: '密码',
    newPassword: '新密码',
    confirmNewPassword: '确认新密码',
    note: '备注',
    serial: '序号',
    status: '状态',
    others: '其它',
    success: '成功',
    fail: '失败',
    createTime: '创建时间',
    noDataNow: '暂无数据',
    operator: '操作人',
    退出登录成功: '退出登录成功',
    roles: {
        管理员: '管理员',
        SVN用户: 'SVN用户',
        子管理员: '子管理员',
        未知: '未知',
    },
    backendTasks: {
        realtimeBackendTasks: '实时后台任务',
        currentTasks: '当前任务',
        tasksInQueue: '排队任务',
        historyTasks: '历史任务',
        noTasksRunning: '当前没有后台任务运行（如遇任务堆积不执行可重启守护进程解决）',
        running: '执行中',
        waiting: '待执行',
        stopTask: '中断执行',
        cancelTask: '取消排队',
        completed: '已完成',
        cancelled: '已取消',
        stopped: '已中断',
        viewLog: '日志',
        taskLog: '历史任务日志',
        taskName: '任务名称',
        endTime: '结束时间',
        stopConfirm: '中断进程确认',
        stopConfirmContent: '确定要中断执行吗？<br/>不保证该操作是否会产生无法清理的睡眠进程！',
    },
    errors: {
        contactAdmin: '出错了 请联系管理员！',
    },
    crond: {
        plsCheckCrondAtd: '请确保依赖的 crond atd 服务安装并正常运行',
        addCrond: '添加任务计划',
        searchByNameAndDesc: '通过任务名称和描述搜索...',
        noNotice: '通知关闭',
        successNotice: '仅成功通知',
        failureNotice: '仅失败通知',
        allNotice: '全部通知',
        viewLog: '日志',
        tipCheckByTrigger: '不确定任务是否配置成功可手动执行一次通过分析日志查看具体情况',
        trigger: '执行',
        type: '任务类型',
        name: '任务名称',
        cycleType: '执行周期',
        changeRepo: '仓库选择',
        notice: '消息通知',
        noticeSuccess: '成功通知',
        noticeFailure: '失败通知',
        saveCount: '保存数量',
        scriptContent: '脚本内容',
        inputScriptContent: '请输入脚本内容',
        viewCrondLog: '查看任务计划日志',
        logFile: '日志文件',
        dumpFull: '仓库备份[dump-全量]',
        dumpDeltas: '仓库备份[dump-增量-deltas]',
        hotcopyFull: '仓库热备份[hotcopy-全量]',
        hotcopyDeltas: '仓库热备份[hotcopy-增量]',
        allRepos: '所有仓库',
        checkRepo: '仓库检查',
        shellScript: 'Shell脚本',
        syncSvnUser: '同步SVN用户',
        syncSvnGroup: '同步SVN分组',
        syncSvnRepo: '同步SVN仓库',
        minute: '每分钟',
        minute_n:'每隔N分钟',
        hour: '每小时',
        hour_n: '每隔N小时',
        day: '每天',
        day_n: '每隔N天',
        week: '每周',
        month: '每月',
        Monday: '周一',
        Tuesday: '周二',
        Wednesday: '周三',
        Thursday: '周四',
        Friday: '周五',
        Saturday: '周六',
        Sunday: '周日',
        monthDay: '{0}日',
        dayDay: '天',
        hourHour: '小时',
        minuteMinute: '分钟',
        cycleDesc: '执行周期描述',
        lastExecTime: '上次执行时间',
        time: '时间',
        content: '内容',
        editCrond: '编辑计划任务',
        deleteCrond: '删除计划任务',
        confirmDelCrond: '确定要删除该记录吗？此操作不可逆！',
        triggerCrond: '执行计划任务',
        confirmTriggerCrond: '确定要立即执行该任务计划吗？该操作可用于测试任务计划配置的正确性！',
    },
    index: {
        loadStatus: '负载状态',
        cpuLoad1Min: '最近1分钟平均负载：',
        cpuLoad5Min: '最近5分钟平均负载：',
        cpuLoad15Min: '最近15分钟平均负载：',
        cpuUsage: 'CPU使用率',
        memUsage: '内存使用率',
        cpuPhysical: '个物理CPU',
        cpuCore: '个物理核心',
        cpuProcessor: '个逻辑核心/线程',
        fileSystem: '文件系统：',
        fsSize: '容量：',
        fsUsed: '已使用+系统占用：',
        fsAvail: '可使用：',
        fsPercent: '使用率：',
        mountOn: '挂载点：',
        statistics: '统计',
        svnRepo: 'SVN仓库',
        repoSize: '仓库占用',
        repoBackup: '仓库备份',
        backupSize: '备份占用',
        logs: '运行日志',
        svnAlias: 'SVN别名',
        运行堵塞: '运行堵塞',
        运行缓慢: '运行缓慢',
        运行正常: '运行正常',
        运行流畅: '运行流畅',
        未知: '未知',
    },
    layout: {
        SVNAdmin: 'SVNAdmin',
        backendTasks: '后台任务',
        仓库: '仓库',
        信息统计: '信息统计',
        SVN仓库: 'SVN仓库',
        SVN用户: 'SVN用户',
        SVN分组: 'SVN分组',
        运维: '运维',
        系统日志: '系统日志',
        任务计划: '任务计划',
        高级: '高级',
        个人中心: '个人中心',
        子管理员: '子管理员',
        系统配置: '系统配置',
        logout: '退出登录',
    },
    login: {
        inputUsername: '请输入用户名',
        inputPassword: '请输入密码',
        inputCode: '请输入验证码',
        login: '登录',
        usernameCannotBeEmpty: '用户名不能为空',
        passwordCannotBeEmpty: '密码不能为空',
        codeCannotBeEmpty: '验证码不能为空',
        userAlreadyLogin: '已有登录信息 自动跳转中...',
        登陆成功: '登录成功',
        验证码错误: '登录失败[验证码错误]',
        验证码失效: '登录失败[验证码失效]',
        验证码过期: '登录失败[验证码过期]',
        账号或密码错误: '登录失败[账号或密码错误]',
        ldap账户未同步: '登录失败[ldap账户未同步]',
        ldap账户认证失败: '登录失败[ldap账户认证失败]',
        ldap账户名不合法: '登录失败[ldap账户名不合法]',
        用户已过期: '登录失败[用户已过期]',
        用户未同步: '登录失败[用户未同步]',
    },
    logs: {
        clearLogs: '清空日志',
        exportLogs: '导出日志',
        logName: 'SVNAdmin2-系统日志',
        searchLogs: '通过所有信息搜索...',
        logType: '日志类型',
        content: '详细信息',
        addTime: '操作时间',
    },
    personal: {
        changePassword: '修改密码',
        adminAccount: '管理员账户',
        adminPassword: '管理员密码',
        subadminAccount: '子管理员账户',
        subadminPassword: '子管理员密码',
        modifyAdminAccount: '修改管理员账户',
        newAccount: '新账户',
        modifyAdminPassword: '修改管理员密码',
        modifySubadminPassword: '修改子管理员密码',
    },
    repositoryGroup: {
        createGroup: '新建SVN分组',
        syncGroupTip: '同步才可获取最新分组列表',
        syncGroupList: '同步列表',
        searchGroup: '通过SVN分组名、备注搜索...',
        groupMember: '成员',
        groupName: '分组名',
        groupNameTip: '分组名只能包含字母、数字、破折号、下划线、点。',
        addMember: '添加成员',
        searchMember: '通过对象名称搜索...',
        user: 'SVN用户',
        group: 'SVN分组',
        aliase: 'SVN别名',
        scanGroupTitle: '步骤一：分组识别',
        authzContent: `请粘贴 authz 文件内容

示例：  

[groups]
group1=user1,user2,@group2
group2=user3
group3=user4,&aliase1`,
        scanGroup: '识别',
        includeUserCount: '包含用户数',
        includeGroupCount: '包含分组数',
        includeAliaseCount: '包含别名用户数',
        objectType: '对象类型',
        objectName: '对象名称',
        editGroupName: '编辑SVN分组名',
        deleteGroup: '删除SVN分组',
        deleteGroupsConfirm: '确定要删除该分组吗？<br/>将会从所有仓库和分组下将该分组移除！<br/>该操作不可逆！',
        editGroupMember: '编辑分组成员信息',
    },
    repositoryInfo: {
        createRepo: '新建仓库',
        syncRepListTip: '该操作会扫描磁盘上的有效仓库列表',
        syncRepList: '同步仓库列表',
        syncRepListInfoTip: '该操作会扫描磁盘上的有效仓库列表，并批量读取每个仓库的体积和版本信息，为耗时操作',
        syncRepListInfo: '同步仓库信息',
        syncUserRepListTip: '同步才可获取最新权限列表',
        syncUserRepList: '同步列表',
        checkAuthzTip: `不经意的配置可能会导致 authz 配置文件失效
如 svnserve 1.10 版本中为空分组授权仓库可能会导致配置失效等
配置文件失效会导致用户端无法检出、浏览等正常操作
因此可通过此工具在线检测 authz 配置文件有无问题
此功能依赖 svnauthz-validate`,
        checkAuthz: 'authz检测',
        searchRepByNameDesc: '通过SVN仓库名、备注搜索...',
        searchRepByName: '通过SVN仓库名搜索...',
        viewRaw: '原生浏览',
        repoName: '仓库名称',
        repoNameTip: '仓库名称只能包含中文、字母、数字、破折号、下划线、点，不能以点开头或结尾',
        repoType: '仓库类型',
        emptyRepo: '空仓库',
        standardRepo: '指定结构的仓库(包含 "trunk" "branches" "tags" 文件夹)',
        repoHooksAlert: '如果SVN客户端正在触发相关的钩子，则更新动作可能会持续阻塞或失败，直至客户端结束相关进程',
        repoHooks: '仓库钩子',
        introduce: '介绍',
        recommendHooks: '常用钩子',
        recommendHooksAlert1: '如需将自己常用的钩子显示在此处',
        recommendHooksAlert2: '以新增 pre-commit 功能为例，操作步骤如下：',
        recommendHooksAlert3: '1、在 /home/svnadmin/hooks/ 目录下创建任意名称的文件夹',
        recommendHooksAlert4: '2、创建文件 hookDescription 并写入此钩子的主要功能描述',
        recommendHooksAlert5: '3、创建文件 hookName 并写入钩子的类型 pre-commit',
        recommendHooksAlert6: '4、创建文件 pre-commit 并写入钩子内容',
        hookFilePlaceHolder: '具体介绍和语法可看钩子介绍',
        repoAttribute: '仓库属性',
        repoBackup: '仓库备份',
        cannotUploadAlert: '当前环境PHP未开启文件上传功能',
        backupByCrondDump: '以svnadmin dump的方式加入后台任务进行备份',
        backupNow: '立即备份',
        uploadBackup: '上传备份',
        loadBackup: '恢复',
        downloadBackup: '下载',
        resetUUID: '重设仓库UUID',
        inputUUID: '不填写则自动生成全新UUID',
        authzCheckResult: 'authz检测结果',
        repoLoadError: '仓库导入错误',
        uploadBackupFile: '仓库备份文件上传',
        uploadFile: '上传文件',
        chooseFile: '选择文件',
        uploadProgress: '上传进度',
        filename: '文件名称',
        filesize: '文件大小',
        uploadStatus: '当前阶段',
        chunkSize: '分片大小',
        timeleft: '剩余时间',
        clearChunks: '分片清理',
        deleteOnMerge: '合并完成后服务器自动删除分片',
        keepOnMerge: '合并完成后服务器不自动删除分片',
        uploadControl: '上传控制',
        pause: '暂停',
        pauseTips: '暂停后需要重新选择文件-已上传分片依然有效',
        repoRev: '版本数',
        repoSize: '体积',
        repoScan: '仓库内容',
        repoPri: '仓库权限',
        pathFile: '路径/文件',
        secondPri: '二次授权',
        resourceType: '类型',
        resourceName: '文件',
        revAuthor: '作者',
        revNum: '版本',
        revTime: '时间',
        revLog: '日志',
        fileEditTime: '修改时间',
        userPri: '用户权限',
        groupName: '分组名',
        groupPri: '分组权限',
        repoInfo: '仓库信息',
        noDataTextRepCon: '由于svnserve服务未启动，SVN用户只能复制检出地址而不能进行仓库内容浏览',
        copySuccess: '复制成功',
        copyFailed: '复制失败，请手动复制',
        mergingChunks: '分片合并中',
        mergeSuccess: '分片合并完成（上传成功）',
        chunksUploading: '个分片上传中',
        hours: '时',
        minutes: '分',
        seconds: '秒',
        chunksMd5Calculating: '个分片md5计算中',
        deleteFile: '删除文件',
        deleteFileConfirm: '确定要删除该文件吗？<br/>该操作不可逆！',
        modifyRepoName: '修改仓库名称',
        deleteRepo: '删除仓库',
        deleteRepoConfirm: '确定要删除该仓库吗？<br/>该操作不可逆！<br/>如果该仓库有正在进行的网络传输，可能会删除失败，请注意提示信息！',
    },
    repositoryUser: {
        createUser: '新建SVN用户',
        userScan: '用户迁入',
        syncListTip: `1、同步才可获取最新用户列表 
2、手动写入passwd文件的用户需要同步才能登录系统`,
        syncList: '同步列表',
        searchUser: '通过SVN用户名、备注搜索...',
        online: '在线',
        offline: '离线',
        userNameAlert: '用户名只能包含字母、数字、破折号、下划线、点。',
        userRecogonize: '步骤一：用户识别',
        userPasswdTips: `请粘贴 passwd 文件内容

如果为 svn 协议检出(密码明文)，内容示例如下：  

[users]
user1=passwd1
user2=passwd2

如果为 http 协议检出(密码密文)，内容示例如下：  

user1:passwd1
user2:passwd2`,
        userScanResult: '步骤二：结果确认',
        enabled: '正常',
        disabled: '禁用',
        userImport: '导入',
        userImportResult: '步骤三：导入结果',
        secondPriObj: '二次授权对象',
        userPriPath: '有权路径',
        lastLogin: '上次登录',
        onlineStatus: '在线状态',
        secondPriStatus: '二次授权状态',
        secondPriTips: '二次授权可赋予普通SVN用户分配路径权限的能力',
        forExample: '举例来讲',
        secondPriTips1: 'projects 仓库包含众多项目 project1 project2 ...',
        secondPriTips2: 'user1 user2 user3 负责项目 project1',
        secondPriTips3: 'user1 为项目组长',
        secondPriTips4: 'user2 为研发同学',
        secondPriTips5: 'user2 为测试同学',
        secondPriTips6: '(1) 管理员为 user1 开启此路径二次授权开关',
        secondPriTips7: '(2) 管理员选择二次授权管理对象(此处为 user2 user3)',
        secondPriTips8: 'user1 可随意为管理对象授权而无需管理员介入',
        secondPriTips9: '关闭二次授权将会同步清理配置的二次授权对象',
        userStatus: '用户状态',
        importResult: '导入结果',
        reason: '原因',
        deleteUser: '删除SVN用户',
        deleteUserConfirm: '确定要删除该用户吗？<br/>将会从所有仓库和分组下将该用户移除！<br/>该操作不可逆！',
        userPriPathList: '用户有权限路径列表',
    },
    setting: {
        serverConfig: '主机配置',
        serverConfigDesc: '该信息主要用于仓库检出地址的拼接',
        serverNameIp: '宿主机IP/域名',
        serverNameIpTip: '此值仅通过数据库维护-不影响业务运行',
        info: '说明',
        hostSvnPort: '宿主机SVN端口',
        hostPortTip: '此值仅通过数据库维护-不影响业务运行-作用为当处于容器环境中通常会做端口映射导致容器内端口和宿主机端口不同-这个时候为了显示方便-可配置此值-当不处于容器环境时候此值与实际端口值一致即可',
        hostWebPort: '宿主机WEB端口',
        pathInfo: '路径信息',
        pathInfoDesc: '可在命令行模式下执行 server/install.php 进行目录更换操作',
        checkoutBySvnProtocol: 'SVN协议检出',
        protocolStatus: '协议启用状态',
        disable: '未启用',
        enabled: '启用中',
        protocolStatusTip: '为了仓库数据安全-svn协议检出和http协议检出不建议同时提供服务-因此同时只可启用一种服务',
        enable: '启用',
        svnserveInfo: 'svnserve服务信息',
        svnserveTip: '当使用svn协议检出时必须通过svnserve服务',
        runningStatus: '运行状态',
        runningStatusTip: '运行状态通过pid文件和pid数值进行判断-如有误判请检查svnserve程序的启动方式',
        notStart: '未启动',
        running: '运行中',
        start: '启动',
        stop: '停止',
        listeningPort: '监听端口',
        listeningPortTip: '若您的应用以容器方式部署，则无需修改此值，只需要修改宿主机到容器的端口映射即可',
        listeningAddress: '监听地址',
        listeningAddressTip: '（1）请注意，此值默认为 0.0.0.0 ，是 svnserve 服务器的实际的默认的绑定地址。如果无特殊原因无需修改此默认值。如果你要更换为公网IP地址，且你的机器为公网服务器且非弹性IP，则可能会绑定失败。原因与云服务器厂商分配公网IP给服务器的方式有关。（2）若您的应用以容器方式部署，则无需修改此值',
        passwordDb: '关联用户文件',
        passwordDbTip: 'svn协议检出默认情况下使用明文密码的用户认证文件',
        saslauthdService: 'saslauthd服务信息',
        saslauthdServiceTip: '当使用svn协议检出时如要接入第三方认证如ldap等必须通过额外的服务saslauthd',
        supportInfo: '支持信息',
        runningStatusTip: '运行状态通过pid文件和pid数值进行判断-如有误判请检查saslauthd程序的启动方式',
        userSource: '用户来源',
        svnUserSource: 'SVN用户来源',
        passwdFile: 'passwd文件',
        svnGroupSource: 'SVN分组来源',
        authzFile: 'authz文件',
        ldapSourceTip: '如果要设置SVN分组来源为LDAP  必须要先设置SVN用户来源为LDAP',
        ldapServer: 'LDAP 服务器',
        ldapPort: 'LDAP 端口',
        ldapServerAddress: 'LDAP 主机地址',
        ldapVersion: 'LDAP 协议版本',
        ldapBindDnTip: '如: CN=blue,CN=Users,DC=witersen,DC=com',
        ldapTest: '验证',
        ldapUser: 'LDAP 用户',
        ldapBaseDnTip: '如: CN=Users,DC=witersen,DC=com',
        ldapAttributesTip: '如: sAMAccountName（注意如果没有过滤到结果可切换本属性为全部小写进行测试）',
        ldapGroup: 'LDAP 分组',
        ldapGroupBaseDnTip: '如: DC=witersen,DC=com',
        ldapGroupSearchFilterTip: '如: (objectClass=group)',
        ldapGroupAttributesTip: '如:  sAMAccountName',
        ldapGroupsToUserAttributeTip: '表示分组中的一个多值属性，这个多值属性包含了0到多个用户',
        ldapGroupsToUserAttributeValueTip: '一般填写的是dn，即当遍历LDAP用户时，某用户的【Groups to user attribute value】属性对应的值与某组的【Groups to user attribute】对应的多项的某一项相等，该分组才包含该用户',
        checkoutByHttpProtocol: 'HTTP协议检出',
        httpProtocolTip: 'svn协议检出和http协议检出可同时提供服务-只是管理系统同一时间只建议管理一套用户数据-因此通过此按钮进行管理切换',
        apacheServiceInfo: 'apache服务信息',
        apacheServiceInfoTip: '当使用http协议检出时必须通过apache和mod_dav_svn模块',
        modulesInfo: 'svn相关模块',
        modulesPathInfo: '安装模块目录',
        apacheListeningPortTip: '（1）此值仅通过数据库维护-不影响业务运行-由于实际情况复杂不便监控和管理apache服务器的运行端口-因此当本系统apache实际配置何端口值此处则填写何端口值-填写不正确则会影响http协议检出模式下的本系统内用户在线仓库浏览功能（2）若您的应用以容器方式部署，则无需修改此值',
        httpRepoPrefix: '仓库访问前缀',
        httpRepoPrefixTip: '此值默认为 /svn 为使用http协议检出时访问仓库的路径-如果设置为 / 请注意与管理系统的地址冲突问题-可通过为本系统配置虚拟路径如/manage来达到仓库使用/前缀的目的',
        httpPasswordDbTip: 'http协议检出默认情况下使用密文密码的用户认证文件',
        httpPasswdFile: 'httpPasswd文件',
        emailSetting: '邮件服务',
        smtpServerInfo: 'SMTP主机',
        encryption: '加密',
        none: '无',
        ssl: 'SSL',
        tls: 'TLS',
        smtpEncryptionTip: '对于大多数服务器，建议使用TLS。 如果您的SMTP提供商同时提供SSL和TLS选项，我们建议您使用TLS。',
        smtpPort: 'SMTP端口',
        autoTls: '自动TLS',
        smtpAutoTlsTip: '默认情况下，如果服务器支持TLS加密，则会自动使用TLS加密（推荐）。在某些情况下，由于服务器配置错误可能会导致问题，则需要将其禁用。',
        auth: '认证',
        smtpUser: 'SMTP用户名',
        smtpUserTip: '如果使用QQ邮件服务，请注意对于@qq.com的邮件地址，仅输入@前面的部分，对于@vip.qq.com的邮件地址，可能需填入完整的地址',
        smtpPass: 'SMTP密码',
        fromEmailAddress: '发件人邮箱',
        fromEmailAddressTip: '默认与用户名相同，需要为邮件格式',
        toEmailAddress: '收件人邮箱',
        toEmailAddressTip: '收件人邮箱只有在触发消息推送选项且邮件服务启用的条件下才会收到邮件',
        testEmailAddress: '测试邮箱',
        testEmailAddressDesc: '测试邮箱不会被保存',
        testEmailAddressTip: '发送测试邮件会使用当前表单填写的配置信息而不是已经保存过的配置信息。全局默认的发送超时时间为10s，如有需要请自行修改。',
        send: '发送',
        smtpSendTimeout: '发邮超时时间',
        smtpStatus: '启用状态',
        pushSetting: '消息推送',
        pushSettingTip: '由于邮件发送没有使用异步任务<br /><br />因此开启了邮件推送模块的响应时间会有相应延迟<br /><br />如，用户点击登录 ~ 登录成功跳转的响应时间 = 正常处理时间 + 邮件发送时间',
        safeSetting: '安全设置',
        currentVersion: '当前版本',
        phpVersion: '支持PHP版本',
        supportedDatabase: '支持数据库',
        codeSource: '开源地址',
        checkUpdate: '检测更新',
        updateInfo: '最新版本信息',
        latestVersion: '最新版本',
        fixedContent: '修复内容',
        addContent: '新增内容',
        removeContent: '移除内容',
        releaseDownload: '完整程序包',
        node: '节点',
        download: '下载',
        updateDownload: '升级程序包',
        updateStep: '升级步骤',
        addToEmail: '添加收件人邮箱',
        systemUpdate: '系统更新',
        emailEmpty: '输入不能为空',
        emailRepeat: '邮件已存在',
        startSvnserveDaemon: '以daemon方式启动svnserve服务',
        startSvnserveConfirm: '确定要启动svnserve服务吗？',
        stopSvnserve: '停止svnserve服务',
        stopSvnserveConfirm: '确定要停止svnserve服务吗？',
        changeSvnservePort: '更换svnserve服务绑定端口',
        changeSvnservePortConfirm: '确定要更换svnserve服务绑定端口吗？此操作会使svnserve服务停止并重新启动！',
        changeSvnserveHost: '更换svnserve服务绑定主机',
        changeSvnserveHostConfirm: '确定要更换svnserve服务绑定主机吗？此操作会使svnserve服务停止并重新启动！',
        testLdapResult1: 'LDAP用户共 ',
        testLdapResult2: ' 个：成功 ',
        testLdapResult3: ' 个，失败 ',
        testLdapResult4: ' 个',
        testLdapResult5: 'LDAP分组共 ',
        warning: '警告',
        changeSvnUsersourceConfirm: '如果为切换到ldap服务器，请仔细阅读以下内容后做出选择:<br/>1、此操作会将数据库中的SVN用户信息清空,后续手动同步时会将ldap用户写入数据库。<br/>2、接入ldap不会修改本系统中的passwd文件。<br/>3、如果设置了分组来源为ldap，此操作会将数据库中的SVN分组信息清空但是不立刻清空authz的分组信息。后续手动同步时自动清空authz的分组信息之后进行到authz文件中分组和数据库的同步。<br/>4、此操作不会清理被清理分组和用户之前已配置的仓库路径权限',
        startSaslauthdDaemon: '以daomen方式启动saslauthd服务',
        startSaslauthdConfirm: '确定要启动saslauthd服务吗吗？',
        stopSaslauthd: '停止saslauthd服务',
        stopSaslauthdConfirm: '确定要停止saslauthd服务吗？',
        enableHttpProtocol: '启用 http 协议检出将会使用另外的用户密码文件、会清空数据库中目前的用户信息、会停止 svn 协议检出。是否继续？',
        waitingHttpdRestart: '等待httpd服务重启',
        enableSvnProtocol: '启用 svn 协议检出将会使用另外的用户密码文件、会清空数据库中目前的用户信息、会停止 http 协议检出。是否继续？',
        restartHttpdConfirm: '此操作将会重启httpd服务。是否继续？',
        主目录: '主目录',
        仓库父目录: '仓库父目录',
        仓库配置文件: '仓库配置文件',
        仓库权限文件: '仓库权限文件',
        用户账号文件: '用户账号文件',
        备份目录: '备份目录',
        svnserve环境变量文件: 'svnserve环境变量文件',
        用户登录: '用户登录',
        管理人员修改账户名: '管理人员修改账户名',
        管理人员修改密码: '管理人员修改密码',
        SVN用户修改密码: 'SVN用户修改密码',
        登录验证码: '登录验证码',
    },
    subadmin: {
        createSubadmin: '新建子管理员',
        searchByNameAndDesc: '通过用户名、备注信息搜索...',
        priTree: '配置',
        online: '在线',
        offline: '离线',
        resetPassword: '重置密码',
        delete: '删除',
        permissionConfig: '子管理员权限配置',
        reAuth: '由于版本升级-权限节点调整-请参考旧权限树-重新为子管理员授权',
        oldPriTree: '旧权限树',
        newPriTree: '权限树',
        lastLogin: '上次登录',
        onlineStatus: '在线状态',
        sysPermission: '系统权限',
        deleteSubadmin: '删除子管理员',
        confirmDeleteSubadmin: '确定要删除该子管理员吗？<br/>该操作不可逆！',
    },
    modalRepPri: {
        createFolder: '新建文件夹',
        currentPath: '当前路径：',
        addPathPermission: '路径授权',
        allUsers: '所有人',
        authenticatedUsers: '所有已认证者',
        anonymousUsers: '所有匿名者',
        readWrite: '读写',
        readOnly: '只读',
        noAccess: '禁止',
        parentPath: '父目录',
        folderName: '文件夹',
        authType: '授权类型',
        objectName: '对象名称',
        rwPermission: '读写权限',
        invertPermission: '权限反转',
        permissionDesc1: '不熟练的用户请慎用此功能！',
        permissionDesc2: '从 Subversion 1.5 开始',
        permissionDesc3: '$authenticated 表示所有已认证的用户',
        permissionDesc4: '$anonymous 表示所有未认证的用户',
        permissionDesc5: '~ 即权限反转表示排除某些用户 如在用户名、别名、用户组、认证类别前加上 ~ 表示将访问权限授予给与规则不匹配的用户',
        permissionDesc6: '如：',
        permissionDesc7: '虽然下面的配置容易让人产生困惑，但它和上面的例子是等效的：',
        permissionDesc8: '下面是一个更恰当的使用 ~ 的例子：',
        permissionDesc9: '# calc 项目的开发人员信息',
        permissionDesc10: '# calc 项目的管理人员信息',
        permissionDesc11: '# calc 项目的所有参与人信息',
        permissionDesc12: '# 所有的 calc 项目参与成员有该项目的读权限',
        permissionDesc13: '# 只有项目管理员有 calc 项目的发行版标签操作权限',
    },
    modalSvnObject: {
        objectList: '对象列表',
        syncUserTip: '同步才可获取最新用户列表',
        searchByUserName: '通过用户名搜索...',
        select: '选择',
        searchByGroupName: '通过分组名搜索...',
        searchByAliase: '通过别名搜索...',
        searchBySymbol: '通过符号搜索',
        defaultPermissionTip: '授权的对象权限默认为读写',
        aliase: '别名',
        aliaseCon: '别名内容',
        groupMemberInfo: '分组成员信息',
    }
}