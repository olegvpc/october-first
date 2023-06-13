### Первый проект на OctoberCMS:
#### 1: проект одностраничного сайта с использованием jQuery перенесен в OctoberCMS
 ![extjs](https://github.com/olegvpc/october-first/blob/main/themes/mogo/assets/images/theme-preview.png?raw=true)
#### 2: Pагружены и использованы Plagins blog & blog views
#### 3: DB - MySQL




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
* 4: Create file .env
```
APP_NAME="October-Tkach"
APP_ENV=local
APP_KEY=base64:nDtGjVtRLQeHQroQuIXaeaeY7KJC4ENI2HQkI0CCkOU=
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


