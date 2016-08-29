yii2-users-module
=================

Users and RBAC module for Yii2

Extension provides basic set of Users, Roles and Permissions

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist devgroup/yii2-users-module "*"
```

or add

```
"devgroup/yii2-users-module": "*"
```

After it you should execute migrations:

```bash
/usr/bin/php yii migrate --migrationPath=@DevGroup/Users/migrations
```

After performing the previous steps `admin` super user will be available in the system with following credentials:
 
 - login : admin
 - password: admin (password is temporary)

## Base Routes

### Backend RBAC Management
- list: `/users/rbac-manage`
- edit: `/users/rbac-manage/edit?params`
- delete: `/users/rbac-manage/delete?params`

### Backend Users Management
- list: `/users/users-manage`
- edit: `/users/users-manage/edit?params`
- delete: `/users/users-manage/delete?params`


## Inspiration sources

https://github.com/dektrium/yii2-user
