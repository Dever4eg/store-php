# Учебний проект интернет магазин на php

## Установка

1. установка зависисмостей

        $ composer install
   
2. Создать файл конфигураций и заполнить нужными данными

        $ cp App/configs/app.php.example App/configs/app.php
    
3. Виполнить миграции

        $ composer phinx migrate
        
4. Заполнение бд начальными данными

        $ composer phinx seed:run
        
