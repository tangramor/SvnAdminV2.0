# About Authz

The default authorization mechanism in Subversion is based on access control lists (ACLs). An ACL is a set of access rules that specify which users or groups can perform which actions on a given resource (e.g. a repository, a directory, or a file).

In Subversion, an ACL is stored as a text file in the repository, mostly named `authz`. In Apache HTTPD, the `authz` file can be located in a directory specified by the `AuthzSVNAccessFile` directive or in each repository's directory, such as `conf/authz`.

refer to: https://svnbook.red-bean.com/nightly/en/svn.serverconfig.httpd.html#svn.serverconfig.httpd.authz.inrepo-authz

参考：https://svnbook.red-bean.com/nightly/zh/svn.serverconfig.httpd.html#svn.serverconfig.httpd.authz.inrepo-authz

本项目缺省使用单一 authz 文件，这样只要有管理权限或者子管理员权限的用户都可以修改 authz 文件。

但在实际生产过程中，Subversion 服务器往往有多个仓库，这些仓库属于不同的项目组，每个项目组有自己的管理人员，这样我们就需要为每个仓库单独配置 authz 文件。否则 A 项目组的管理人员可以修改 B 项目组的仓库，这显然是不合理的。

因此，本项目提供了多 authz 文件配置方案，每个仓库都对应一个 authz 文件，这样每个仓库的管理人员都可以单独管理自己的仓库权限。

多 authz 文件配置方案带来的影响：

- SVN 分组不再为全局定义，而是每个仓库单独定义。这样前端的 SVN 分组 管理界面必须置于每个仓库的管理页面之下，而不是全局管理页面。
- 子管理员的权限必须在每个仓库单独配置，否则 A 项目组的子管理员可以修改 B 项目组的仓库，这也是不合理的。
- 前端 系统配置 里的分组来源也需要根据每个仓库的 authz 文件来确定，而不是全局的 authz 文件。
- 使用 LDAP 认证时，同步用户组信息到每个仓库的 authz 文件需要额外的工作。

# About SSL

Secure Sockets Layer (SSL) is a protocol that provides encryption for data sent over the internet. It is used to establish a secure connection between a web server and a web browser, and to encrypt sensitive information that is transmitted between the two.
