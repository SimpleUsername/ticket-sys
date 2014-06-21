#Проект билетооборота
##Установка
###Windows
**1)** Установка [XAMPP](https://www.apachefriends.org/ru/download.html). Настройки по умолчанию.

**2)** В php.ini установить `short_open_tag = On`

**3)** [phpMyAdmin](http://localhost/phpmyadmin/) создать базу `ticket-sys` и сделать импорт из файла `ticket-sys.sql`

**4)** Если используется [GitHub](https://windows.github.com/), то удобно будет создать соединение для каталога:
```
mklink /j C:\xampp\htdocs\ticket-sys.loc C:\Users\User\Documents\GitHub\ticket-sys
```
**5)** Добавить в `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
```
<VirtualHost 127.0.0.1:80>
  ServerName ticket-sys.loc
  ServerAlias www.ticket-sys.loc
  ServerAdmin admin@asd.ru
  DocumentRoot "C:\xampp\htdocs\ticket-sys.loc"
  ErrorLog "C:\xampp\htdocs\ticket-sys.loc\error.log"
  CustomLog "C:\xampp\htdocs\ticket-sys.loc\access.log" combined
<Directory "C:\xampp\htdocs\ticket-sys.loc">
  AllowOverride All
  Order allow,deny
  Allow from all
</Directory>
</VirtualHost>
```
**6)** Добавить в `C:\windows\system32\drivers\etc\hosts`
```
127.0.0.1	ticket-sys.loc
```
