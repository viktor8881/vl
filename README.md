# vl

Анализатор изменения основных мировых валют и металлов.
 - Отслеживает изменения котировок, отправляет письма при изменениях за указанный период;
 - Ведет учет всех денежных вложений;
 - Прогнозирует изменения котировок.
 Котировки берутся с сайта Центробанка `http://www.cbr.ru/`.
 

## REQUIREMENTS

 - php v5.3 >
 - ZendFramework 1.12.3
 - Phing 2.7.0 >
 - MySql 5.0 >

## INSTALLATION

Установите проект в web-доступную директорию.
Перед первым запуском, проект необходимо настроить и собрать.

#### Settings

В директории `/data/dump/` расположены: 
 - файлы структуры БД `structure.gz`;
 - файлы структуры БД с данными `structure+data.gz`;
 Создайте БД и выполните импорт файла `structure+data.gz`.
 
В директории `/application/db/` создайте файл с именем `options.dat`
и содержанием:

    resources.db.adapter = PDO_MYSQL
    resources.db.params.host = %db_host%
    resources.db.params.username = %db_username%
    resources.db.params.password = %db_password%
    resources.db.params.dbname = %db_name%
    resources.db.params.charset = utf8
    resources.db.isDefaultTableAdapter = true

    smtp.host = %smtp_host%
    smtp.port = %smtp_port%
    smtp.auth = login
    smtp.username = %smtp_username%
    smtp.password = %smtp_password%

    mail.siteEmail = %mail_domen%
    mail.adminEmail = %mail_admin%

 - `mail.siteEmail` - email адрес от кого будут приходить письма.
 - `mail.adminEmail` - email адрес куда посылать нотификации при изменениях.

#### Builder

Сборка осуществляется с помощью Phing.
Процесс сборки см. `/build.xml`

Настройте выполнение крона на ежедневный сбор котировок.
 - `http://domen.ru/cron/receive-quotation/currency` - для валюты. Рекомендуется установить выполнение данных скриптов раз в три часа (данные обновляются иногда с опозданием).
 - `http://domen.ru/cron/receive-quotation/currency` - для металлов. Рекомендуется установить выполнение данных скриптов раз в три часа (данные обновляются иногда с опозданием).
 - `http://domen.ru/cron` - выполнение задач. Рекомендуется установить выполнение задач после получения котировок. 

Выполняет последовательно: 
 - анализ по установленным заданиям (http://domen.ru/tasks/index/list); 
 - отправку писем по результатам анализа.




