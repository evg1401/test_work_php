## Структура проекта

```text
        test_work_php/                           # Корень PHP-приложения
    ├── app/
    │   └── src/                                 # Исходный код
    │       └── Application.php                  # Запуск маршрутизации
    ├── bootstrap/
    │   ├── app.php                              # Общая начальная загрузка
    │   ├── config.php                           # Чтение переменных окружения
    │   └── container.php                        # Сборка DI
    ├── configs/                                 # Файлы переменных окружения
    ├── dev/
    │   ├── bin/seed.php                         # сидинг категорий и статей
    ├── logs/                                    # Локальные журналы приложения
    ├── migrations/                              # Миграции БД
    ├── public/                                  # root веб-сервера
    │   ├── assets/
    │   │   ├── css/app.css                      # Скомпилированный CSS
    │   ├── index.php                            # Точка входа
    ├── resources/                               # Исходники
    │   ├── styles/
    │   │   ├── app.scss                         # Точка сборки SCSS
    │   │   └── components/                      # Стили страниц и компонентов
    │   └── views/                               # Шаблоны страниц
    ├── routing/                                 # Маргрутизатор
    │   ├── routes/                              # Маршруты по контроллерам
    │   ├── RouteDispatcher.php                  # Создание запроса
    │   ├── RouteRegistrar.php                   # Регистрация маршрутов
    │   └── web.php                              # Массив маршрутов
    └── cli-config.php                           # Конфигурация миграций Doctrine
```

## Структура типового модуля

```text
app/src/Module/
├── Application/
│   ├── DTO/
│   │   ├── Input/                               # Входные данные сценариев
│   │   └── Output/                              # Результаты прикладного слоя
│   └── ModuleService.php                        # Координация репозиториев и DTO
├── Http/
│   ├── ViewModel/                               # Данные для шаблона или ответа
│   └── ModuleController.php                     # HTTP-запрос → сервис → ViewModel
└── Infrastructure/
    ├── DI/
    │   └── ModuleServiceProvider.php            # Регистрация зависимостей
    └── Persistence/Doctrine/
        ├── Entity/                              # Doctrine-сущности
        └── Repository/                          # Запросы к базе данных

routing/routes/Module.route.php                  # Маршруты контроллеров модулей
```

## Запуск

```bash
docker compose up -d
```

После запуска контейнеров создать БД.

## Миграции, сидинг категорий и статей

Если папка migrations пуста, то сначала создать миграцию:

```bash
docker compose exec app php vendor/bin/doctrine-migrations migrations:diff
```

Применить миграцию:

```bash
docker compose exec app php vendor/bin/doctrine-migrations migrations:migrate --no-interaction
```

Очистить таблицы приложения и загрузить dev-данные:

```bash
docker compose exec app php dev/bin/seed.php --purge
```

## Сборка SCSS
```bash
cd test_work_php
npm ci
npm run css:build
```

Пересборка:

```bash
npm run css:watch
```

Исходники находятся в `resources/styles`, результат - в `public/assets/css/app.css`.