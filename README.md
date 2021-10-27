## Разворачивание проекта

```
    git clone https://github.com/karlygash9810/Paddock.git
    cp .env.example .env
    composer install
```

## Настройка БД
В файле .env поставить значения
- DB_HOST
- DB_PORT
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD

Запустить команды

```
    php artisan key:generate
    php artisan migrate 
    php artisan db:seed
```

## Vue JS
####[Скачать NodeJS](https://nodejs.org)

Скачать все библиотеки
```
    npm install
```

Для компиляции js файлов в директории public запустить

```
    npm run dev
```

Для того чтобы не компилировать после каждого изменения можно поставить watcher

```
    npm run watch
```

Для запуска используем

```
    php artisan serve
```

Если добавить нужный домен в файл hosts, можно запустить команду. Port = 80 для того чтобы можно было перейти на ссылку без указания порта. Сайт будет доступен по адресу http://domain.dev
```
    php artisan serve --host=domain.dev --port=80 
```
