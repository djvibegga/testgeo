0. настройка проекта:
- выполнить в консоли psql все команды из файла dump.sql
- если параметры подключения к БД у вас другие то подредактировать файл env.php
- для 3,4,5 предварительно запустить импорт из файлов,
  которые я предварительно сгенерировал: 
  php import_cadastr_samples_to_postgis.php

далее по каждому пункту тестового:
1   - php kml2_postgis.php
2   - php print_centers.php 
3,4 - php search_inoutbound_polygons_using_postgis.php
5   - php -S localhost:8000

и далее открыть для тестирования API следующие адреса в браузере:
http://localhost:8000/api?cadastr=1420988400:01:001:0005
http://localhost:8000/api?cadastr=
http://localhost:8000/api?cadastr=123
http://localhost:8000/api?cadastr=abc
http://localhost:8000/api?cadastr=1420988400:01:001:0003