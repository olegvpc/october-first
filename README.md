### Первый проект на OctoberCMS:
#### 1: проект одностраничного сайта с использованием jQuery перенесен в OctoberCMS
 ![extjs](https://github.com/olegvpc/october-first/blob/main/themes/mogo/assets/images/theme-preview.png?raw=true)
#### 2: Pагружены и использованы plugins: blog & blog views & comments
#### 3: Plugins comments стилизован под проект
 ![extjs](https://github.com/olegvpc/october-first/blob/main/themes/mogo/assets/images/comments.png?raw=true)
#### 4: Внедрение прав доступа на страницу /order в соответствии с настройками session компонента user
 ![extjs](https://github.com/olegvpc/october-first/blob/main/themes/mogo/assets/images/user-session.png?raw=true)
 ![extjs](https://github.com/olegvpc/october-first/blob/main/themes/mogo/assets/images/order.png?raw=true)
 #### 5: Внедрение дополнительного контроля доступа на страницу /order в соответствии с настройками session компонента kurtjensen.passage
 Наличие Ключа доступа (как для конкретного юзера так и для всей группы) позволяет определить доступ к части информации на странице
 Twig functions:
 ```
 {% if can('secret_key') %}
<p>This will show only if the user belongs to a Rainlab.User Usergroup that includes the permision named "secret_key".</p>
{% else %}
<p>This will show if the user DOES NOT belong to a Rainlab.User Usergroup that include the permision named "secret_key".</p>
{% endif %}
 ```
 ![extjs](https://github.com/olegvpc/october-first/blob/main/themes/mogo/assets/images/session-key-approve.png?raw=true)
 ![extjs](https://github.com/olegvpc/october-first/blob/main/themes/mogo/assets/images/session-key-deny.png?raw=true)
#### -: DB - MySQL


### Установка проекта (запускается локально без Вocker)
* 1: clone repository
```
git clone https://github.com/olegvpc/october-first
```
* 2: cd to work-project

* 3: Install all modules
```
composer install
php artisan october:install
```
* 4: Create file .env & generate new key
```
php artisan key:generate
```

```
APP_NAME="October-Tkach"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
```


* 5: Check data of DB in config/database.php
```

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => 'storage/database.sqlite',
            'prefix' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'engine' => 'InnoDB',
            'host' => 'localhost',
            'port' => 3306,
            'database' => 'DB',
            'username' => 'root',
            'password' => 'admin',
        ]]
```

* 6: Запустить PHP Server (любой доступный): open project

* 7: Admin will by in ../backend


