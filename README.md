calc.sio  
========================
![](https://img.shields.io/badge/calc-systeme.io-navy.svg)

------------------------

## [Запуск](#installing-the-service):
Приложение поставляется в Docker образе, если на вашем компьютере еще нет Docker - самое время [установить Docker и Docker Compose](https://docs.docker.com/compose/gettingstarted/).

1. Клонируй репозиторий с исходным кодом приложения.
2. В терминале операционной системы перейди в директорию, содержащую этот файл и вызови:
    1. ```make install``` - для инициализации приложения.
    2. ```make up``` - для запуска приложения.

## [Настройка](#configuring-the-service):
Переменные окружения и рабочие порты для внутренних служб и сервисов приложения, можно переопределить или добавить в конфигурационных файлах:
- [./frankenphp/env/docker-compose.env](./frankenphp/env/docker-compose.env) - базовый шаблон безопасный для размещения в репозитории.
- ```frankenphp/env/docker-compose.override.env``` - актуальный конфигурационный файл созданный (см. [Makefile](./Makefile)) на основе базового шаблона.

## API спецификация:
Cпецификация API доступна после [запуска](#installing-the-service) приложения по адресу
[https://calc.sio.localhost:4600/api/doc](https://calc.sio.localhost:4600/api/doc)

## [Подключение к терминалу приложения](#how-to-connect-to-the-service-terminal):
В терминале операционной системы перейди в директорию, содержащую этот файл и вызови ```make php```.

## Консоль приложения:
Вызови ```console``` или ```c``` в [терминале приложения](#how-to-connect-to-the-service-terminal).

## Тестирование функционала:
Вызови ```phpunit``` или ```t``` в [терминале приложения](#how-to-connect-to-the-service-terminal).

Чтобы запустить некоторую часть тестов:
- `t [путь до файла или директории]` - Запустить тесты в указанной директории или конкретном файле.
- `t [путь до файла или директории]:[фильтр]` - Запустить тесты, название метода которых содержит указанный фильтр.

## Проверка стиля кода:
Вызови ```code-style``` в [терминале приложения](#how-to-connect-to-the-service-terminal).

## Автоматическая корректировка стиля кода:
Вызови ```code-style-fix``` в [терминале приложения](#how-to-connect-to-the-service-terminal).

## Аудит безопасности элементов приложения:
Вызови ```security-audit``` в [терминале приложения](#how-to-connect-to-the-service-terminal).

## Статический анализ кода:
Вызови ```static-analysis``` в [терминале приложения](#how-to-connect-to-the-service-terminal).

## Управление миграциями схем БД:
Вызови ```mig``` в [терминале приложения](#how-to-connect-to-the-service-terminal).
