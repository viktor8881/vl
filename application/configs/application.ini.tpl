[production]
phpSettings.display_startup_errors = 0
phpSettings.display_errors = 0
includePaths.library = APPLICATION_PATH "/../library"
bootstrap.path = APPLICATION_PATH "/Bootstrap.php"
bootstrap.class = "Bootstrap"
appnamespace = "Application"
autoloaderNamespaces[] = "Core"
resources.frontController.controllerDirectory = APPLICATION_PATH "/controllers"
resources.frontController.params.displayExceptions = 0
resources.frontController.defaultmodule = "default"
resources.frontController.moduleDirectory = APPLICATION_PATH "/modules"

resources.layout.layoutPath = APPLICATION_PATH "/layouts/scripts/"
resources.layout.layout = "layout"
resources.view[] =

resources.modules[] =

resources.view.doctype = "XHTML1_STRICT"
resources.view.encoding = "UTF-8"
resources.view.contentType = "text/html; charset=utf-8"
resources.view.pragmaNoCache = "On"

; путь для логов системы
path.log.system = APPLICATION_PATH "/../data/log/"
path.temp = APPLICATION_PATH "/../data/tmp/"
path.cache = APPLICATION_PATH "/../data/cache/"
path.public_img_pchart = "img/pchart/"
path.public_fonts = "fonts/"

%%params%%

; resources.db.adapter = PDO_MYSQL
; resources.db.params.host = %db_host%
; resources.db.params.username = %db_username%
; resources.db.params.password = %db_password%
; resources.db.params.dbname = %db_name%
; resources.db.params.charset = utf8
; resources.db.isDefaultTableAdapter = true

; smtp.host = %smtp_host%
; smtp.port = %smtp_port%
; smtp.auth = login
; smtp.username = %smtp_username%
; smtp.password = %smtp_password%

; mail.siteEmail = %mail_domen%
; mail.adminEmail = %mail_admin%

[staging : production]

[testing : production]
phpSettings.display_startup_errors = 1
phpSettings.display_errors = 1

[development : production]
phpSettings.display_startup_errors = 1
phpSettings.display_errors = 1
resources.frontController.params.displayExceptions = 1
