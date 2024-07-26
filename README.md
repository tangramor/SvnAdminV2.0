## [中文](README.zh-CN.md)

# SVNAdmin2 - A Web-based SVN Management System

## 1. Introduction

- SVNAdmin2 is a web program for managing SVN repositories on the server through a graphical interface.

- Under normal circumstances, the configuration of the SVN repository's personnel permissions requires logging into the server to manually modify the `authz` and `passwd` files. When the structure of the repository and the personnel permissions have expanded to a certain scale, manual management becomes very prone to errors. This system can recognize personnel and permissions and provide management and expansion functionalities.

- SVNAdmin2 supports SVN protocol checkout, HTTP protocol checkout, and also supports the switch between the two protocols. It supports Docker deployment or source code deployment.

- SVNAdmin2 supports the integration of LDAP, thereby achieving the goal of using the existing personnel structure and grouping rules.

- [GitHub地址](https://github.com/witersen/SvnAdminV2.0)   [Gitee地址](https://gitee.com/witersen/SvnAdminV2.0)

- Problem help, feature suggestions, update plans, SVN technical discussions, you can join QQ group: 633108141

- Project demonstration address: [http://svnadmin.witersen.com (administrators/admin/admin)](http://svnadmin.witersen.com%20(administrators/admin/admin))

- System Screenshots

<img src="00.static/demo.jpg" alt="" width="100%" height="100%" />


## 2. Compatibility

**Docker > CentOS7 > CentOS8 > Rocky > Ubuntu > ......**

If needed on Windows, a Docker version can be used.

PHP Version: [php5.5, php8.2] (Developed based on php7.4, so it is recommended to use php7.4)

Database: SQLite, MySQL

Subversion: 1.8+


## 3. Docker Installation

Docker deployment instructions for NAS users are here.

### 3.1 Suitable for: Quick deployment to see the effect

Note: The image is default hosted on Dockerhub. If the speed is not good, you can choose the domestic route (`docker pull registry.cn-hangzhou.aliyuncs.com/witersencom/svnadmin:[Image Version Number]`)

This method allows you to quickly deploy the program and experience the effect. Data is not stored on the host machine. For production use, please see section 3.2.

```shell
docker run -d --name svnadmintemp -p 80:80 -p 3690:3690 --privileged witersencom/svnadmin:2.5.9
```

### 3.2 Suitable for: New users for official use

- Start a temporary container to copy the configuration file out.

```shell
docker run -d --name svnadmintemp --privileged witersencom/svnadmin:2.5.9 /usr/sbin/init
```

- Copy the configuration files to the local machine's `/home/svnadmin` directory

```shell
cd /home/
docker cp svnadmintemp:/home/svnadmin ./
docker cp svnadmintemp:/etc/httpd/conf.d ./svnadmin/
docker cp svnadmintemp:/etc/sasl2 ./svnadmin/
```

- Delete the temporary container

```shell
docker stop svnadmintemp && docker rm svnadmintemp
```

- Start the official container

```shell
docker run -d -p 80:80 -p 3690:3690 \
-v /home/svnadmin/:/home/svnadmin/ \
-v /home/svnadmin/conf.d/:/etc/httpd/conf.d/ \
-v /home/svnadmin/sasl2/:/etc/sasl2/ \
--privileged \
--name svnadmin \
witersencom/svnadmin:2.5.9
```

- Enter the container for file authorization

```shell
docker exec -it svnadmin bash
chown -R apache:apache /home/svnadmin
```

### 3.3 Suitable for: Upgrade for old users

Note: Users of 2.4.3 and before need to pay attention to the directory mounting of `conf.d sasl2`. Copy it out in advance before upgrading.

#### Upgrade from 2.3.x and 2.4.x to 2.5.9 (for users who can connect to the internet)

- Enter the container

```shell
yum install -y unzip
cd /var/www/html/server && php install.php
```

- Exit the container

- Stop the old container, pull the new container, and mount the local data directory to the new version container

#### Upgrade from 2.3.x and 2.4.x to 2.5.9 (for users who cannot connect to the internet)

- Download the upgrade package in an environment with network access, note to download `update.tar.gz` instead of `update.zip`

- Copy the upgrade package to the container's `/var/www/html/server/` directory in advance

```shell
cd /var/www/html/server/
tar -zxvf update.tar.gz

php update/index.php
```

- Exit the container

- Stop the old container, pull the new container, and mount the local data directory to the new version container


## 4. Source Code Installation

`svnadmin` = web system + background process, so pay attention to the installation

### 4.1 Suitable for: CentOS7, Rocky, etc.

- Install tools such as decompression

```shell
yum install -y zip unzip wget vim which
```

- Install sasl-related dependencies (svn protocol check-out configuration sasl authentication such as ldap is needed)

```shell
yum install -y cyrus-sasl cyrus-sasl-lib cyrus-sasl-plain
```

- Install PHP and related extensions (CentOS7 provides PHP version 5.4 by default, and we need 5.5+, so use the remi repository)

```shell
yum install -y epel-release yum-utils
rpm -Uvh https://mirrors.aliyun.com/remi/enterprise/remi-release-7.rpm
yum-config-manager --enable remi-php74

yum install -y php php-common php-cli php-fpm php-mysqlnd php-mysql php-pdo php-process php-json php-gd php-bcmath php-ldap php-mbstring
```

- Install the web server (apache is recommended for http protocol check-out)

```shell
yum install -y httpd mod_dav_svn mod_ldap
systemctl start httpd
systemctl enable httpd
```

- Install the task scheduling component (task scheduling function is used)

```shell
yum install -y cronie at

Start atd (if `ps aux | grep -v 'grep' | grep atd` result is empty, execute)

atd

Start crond (if `ps aux | grep -v 'grep' | grep crond` result is empty, execute)

crond
```

- Download and unzip the code package

```shell
cd /var/www/html/ && wget https://gitee.com/witersen/SvnAdminV2.0/releases/download/2.5.9/2.5.9.zip

unzip 2.5.9.zip
```

- Install Subversion (if you have installed Subversion, skip this step) (note that Subversion >= 1.8 is required)

```shell
cd /var/www/html/server/
chown -R apache:apache /var/www/html/

Option 1

php install.php
```

- Modify Subversion's configuration to support management by this system (if you have installed Subversion, this step must be executed)

```shell
cd /var/www/html/server

Option 1 or Option 2

php install.php
```

- Authorize the owner and group for the data directory. The PHP script's web call is executed as the apache identity, so the apache user needs rights to the data directory.

- If you use another web server such as nginx or Tomcat, you can obtain the owner and group by accessing your machine IP/server/own.php through the browser.

```shell
chown -R apache:apache /home/svnadmin
```

- Manually start the background process (start method 1)

```shell
# pwd
# /var/www/html/server/

# Background running
nohup php svnadmind.php start >/dev/null 2>&1 &

# After running in the background, enter exit to ensure stable background operation

exit

# Stop the background
php svnadmind.php stop

# Debug mode
php svnadmind.php console
```

- Start the background process through system management (start method 2)

- Create a system service file svnserve.service (CentOS is generally `/usr/lib/systemd/system/svnadmind.service`, Ubuntu is generally `/lib/systemd/system/svnadmind.service`)

- Write the following content (note to adjust according to your own code deployment path)

```shell
[Unit]
Description=SVNAdmin
After=syslog.target network.target

[Service]
Type=simple
ExecStart=/usr/bin/php /var/www/html/server/svnadmind.php start

[Install]
WantedBy=multi-user.target
```

```shell
# Start
systemctl daemon-reload
systemctl start svnadmind

# Check status
systemctl status svnadmind

# Add to startup on boot
systemctl enable svnadmind
```

### 4.2 Suitable for: Baota panel

- The installation method is similar to manual deployment, but Baota has a lot of visual operations that are very convenient.

- Refer to the video: SVNAdmin V2.2.1 System Deployment and Usage Demo Video [for Baota panel]( https://www.bilibili.com/video/BV1XR4y1H7p3?share_source=copy_web&vd_source=f4620db503611c42618f1afd9c8afecd)

### 4.3 Suitable for: Ubuntu18

- The steps are the same as 1 (Note that the server/install.php and server/svnadmind.php need to be executed as the root user)

- The software package name in Ubuntu is different from the CentOS series, which requires the user to handle it themselves

```shell
sudo apt-get update

sudo apt-get install -y apache2
sudo apt-get install -y php
sudo apt-get install -y php-cli
sudo apt-get install -y php-fpm

sudo a2enmod proxy_fcgi setenvif
sudo systemctl restart apache2
sudo a2enconf php7.2-fpm
sudo systemctl reload apache2

sudo apt-get install -y php-json

sudo apt-get install -y php7.2-mysql
sudo apt-get install -y php-mysql

sudo apt-get install -y sqlite3

sudo apt-get install -y php7.2-sqlite

sudo apt-get install -y php-gd

sudo systemctl restart apache2

sudo apt-get install -y subversion subversion-tools

cd /var/www/html

wget xxx.zip

unzip xxx.zip

Option 2

sudo server/install.php

chown -R apache:apache /home/svnadmin/

su root

nohup php server/svnadmind.php start &

```

### 4.4 Suitable for: Upgrade for old users

- 2.3.x and 2.4.x and 2.5.x upgrade to 2.5.9

```shell
yum install -y unzip

cd /var/www/html/server && php install.php
```

## 5. Frequently Asked Questions

### 5.1 How to use this system to manage a repository previously managed by other means?

Confirm the version of the previous SVN repository. If it is 1.8+, there is no need to worry. If it is below 1.8, a simple upgrade of the repository is required.

Install this system

Execute `php server/install.php` to use the built-in function to reconfigure your Subversion

Move one or more existing SVN repositories to the `/home/svnadmin/rep/` directory

In the navigation SVN repository, execute the sync list to recognize the SVN repository

Note: If you originally had a set of configuration files for each repository, you will also need to adjust your configuration files slightly according to the screenshot. Because now it is a management method of multiple repositories with a set of configuration files.

### 5.2 How to switch the database to MySQL?

Create a database named `svnadmin`

Import the `svnadmind.sql` file from the installation package's `templete/database/mysql/` directory into the database

Modify `config/database.php` to comment out the SQLite part and configure your MySQL

Note: If the PHP version is too low and the MySQL version is >= 8.0, it will prompt: The server requested authentication method unknown to the client. You only need to upgrade the PHP version or modify the MySQL database configuration information.

### 5.3 Why does it only support managing Subversion 1.8+?

Because the current method is to read a set of configuration files for multiple repositories, and this method is only supported by Subversion 1.8+

It is expected to support managing Subversion 1.5+ in the 2.5.x version

### 5.4 Why does it currently only support Linux operating systems?

The system uses some multi-process schemes, which would take more time to implement on Windows

There are no plans to support Windows deployment in the short term

For Windows users, you can use the Docker version

### 5.5 Repository initialization structure template?

We can choose to create a repository with a specified content structure when creating a repository, such as a structure that includes "trunk", "branches", and "tags" folders. This structure is optional and adjustable. We can manually adjust the directory structure under `/home/svnadmin/templete/initStruct/01/`.

### 5.6 Recommended common hooks?

We can add our commonly used hooks in the directory `/home/svnadmin/hooks/`.

Create a folder xx under `/home/svnadmin/hooks/`, the name is arbitrary.

Create a file named `hookDescription` under xx to write a description of the hook.

Create a file named `hookName` under xx to write the hook type, such as `post-commit`.

Create a file named after the hook type under xx, such as `post-commit`, and then write the specific hook content.

### 5.7 Administrator password recovery

Using the default SQLite database

```shell
yum install -y sqlite-devel

cd /home/svnadmin

sqlite3 svnadmin.db

.header on

.mode column

select * from admin_users;
```

Using the MySQL database

Use a visual tool to log in to the database and view the information in the `admin_users` table.

### 5.8 Issues with downloading large files being interrupted

When downloading files of 1G or more, the download may be interrupted because the file download does not use an HTTP file direct link for security reasons, but reads the file stream through PHP verification. Therefore, there is a problem with the maximum execution time of php-fpm. You can set the `request_terminate_timeout` in the `php-fpm.conf` configuration file to 0 to cancel the timeout limit.

### 5.9 If multiple repository templates are configured, how to specify a particular repository template when creating a repository?

- For example:

    Configure the first repository structure template under `/home/svnadmin/templete/initStruct/01/`.
    Configure the second repository structure template under `/home/svnadmin/templete/initStruct/02/`.

    How to use the default `/home/svnadmin/templete/initStruct/02/` repository structure template when creating in the web?

- Solution

    Due to time constraints, this feature was not developed in detail during development, so only the configuration file level modification method was reserved. The repository template function will be added to the web configuration later, without the need for manual command line management.

    You can modify the `templete_init_struct_01` value in `config/svn.php` to change it.

### 5.10 The custom repository template is not effective when creating a repository

Note the location of the custom repository template

The usual location is under `/home/svnadmin/templete/initStruct/01/`

Not in the project code-related location

### 5.11 Data length exceeds 8192, please adjust the parameter: SOCKET_READ_LENGTH upwards

- Reason for the problem

    The number of SVN users and permission configurations has increased, exceeding the default value

- Solution

    Modify the parameter in the `config/daemon.php` file.

### 5.12 Unable to connect to the LDAP server


Make sure of the following two points:

1. Your ldap server address and port are truly valid.
2. The machine where svnadmin2 is installed can indeed communicate with your ldap server through the ldap port.

Then check the selinux configuration of the machine where svnadmin2 is located. Usually, `selinux` will prohibit http from connecting to ldap.

Execute:

```shell
getsebool -a | grep ldap
```

If you get the following result:

```shell
httpd_can_connect_ldap --> off
```
It proves that we need to manually turn on the `httpd_can_connect_ldap` option.

Execute the following command to allow the connection:

```shell
setsebool -P httpd_can_connect_ldap=1
```

The above situation may occur when selinux is enabled. After selinux is turned off, the above configuration no longer takes effect.


### 5.13 LDAP is enabled, but users have permissions but no rights to browse the repository

This situation is usually because the source code installation process is missing some modules or dependencies related to ldap. It is recommended to read the document in detail.

### 5.14 LDAP is enabled, and the user list is synchronized successfully, but login is not possible (a problem before version 2.4.x)

In this case, it is usually because of the configuration of your Base DN.

Assume

Your base dn is filled in as: `dc=witersen,dc=com`

Your Attributes are filled in as: `cn`

Then you filter out the user: `blue`

So when the user blue logs in to the system as an SVN user, the system will use `cn=blue,dc=witersen,dc=com` as the complete dn of the user blue and combine it with the password entered by the user to request the ldap server for verification. So if the real dn of the blue user is `cn=blue,ou=devGroup,dc=witersen,dc=com`, it will cause the synchronization to succeed but the login to fail.

### 5.15 LDAP can obtain users and groups, but cannot synchronize users as members of the group

Refer to the article: [https://www.witersen.com/?p=1844](https://www.witersen.com/?p=1844)

A detailed explanation of how to connect to LDAP is provided.


