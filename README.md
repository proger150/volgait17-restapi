Первое задание олимпиады
============================

Данный репозиторий содержит полный перечень файлов, необходимый для развертывания проекта


Требования
------------

PHP >= 5.4 и MySQL >= 5.7

Установка
------------

	-Для установки проекта необходимо скопировать его в корневую дирректорию веб сервера
	-Загрузить дамп базы (rest_api.sql) 
	-Указать параметры соединения с БД в config/db.php


Что сделано?
-------

В результате работы были реализованы следующие методы:

	-Метод добавления данных (users/set?key=[ключ]&val=[Значение])
	-Метод получения данных (users/set?key=[ключ])
	-Кэширования данных пользователя
	-Метод получение времени последнего запроса (users/last-query)
	
	
Очень важно!!!
------------

В проекте не использовался mod_rewrite, поэтому все запросы к серверу должны быть вида http://domain.ru/web/?r=users/set&key=test_key&val=test_val

Как запустить?
--------------

При каждом вызове метода необходимо передавать параметр token. Он находится в таблице users, в поле auth_key

Для запуска метода из контроллера необходимо перейти по адресу [Ваш домен]/web/?r=users/set&key=test_key&val=test_val&token=123 .
В переменной r передается название метода api, например r=users/list. Далее через указываем параметры




 
	

