### Установка и запуск проекта

1. Склонируйте проект командой git clone
2. Запустите команду `make build` для сборки docker контейнеров и всех зависимостей
3. Запустите команду `make run` для запуска контейнера
4. Войдите в контейнер командой `make input` для запуска команд бизнес логики

### Фикстуры

Для работы с приложением запустите выгрузку фикстур командой

```
php bin/console doctrine:fixtures:load
```

### Команды бизнес логики

Получить список клиентов
```
bin/console client:list
```

Получить информацию о клиенте и его адресах доставки
```
php bin/console client:get [uuid]
```

Пример:
```
php bin/console client:get f30c871c-3f8a-44bf-9a3a-43268929a453
```

Команда добавления адреса доставки
```
php bin/console client:add-shipping-address --id=[uuid] --country=[country] --city=[city] --street=[street] --zipCode=[zipcode]
```

Пример 
```
php bin/console client:add-shipping-address --id=f30c871c-3f8a-44bf-9a3a-43268929a453 --country="Hungary" --city=Patriciaton --street="Norbert Gardens" --zipCode=53897
```

Команда удаления адреса доставки
```
php bin/console client:delete-shipping-address --id=[uuid] --country=[country] --city=[city] --street=[street] --zipCode=[zipcode]
```

Пример 
```
php bin/console client:delete-shipping-address --id=f30c871c-3f8a-44bf-9a3a-43268929a453 --country="Hungary" --city=Patriciaton --street="Norbert Gardens" --zipCode=53897
```

Команда обновления адреса доставки
```
php bin/console client:update-shipping-address --id=[uuid] --country=[old-country] --city=[old-city] --street=[old-street] --zipCode=[old-zipcode] --new-country=[new-country] --new-city=[new-city] --new-street=[new-street] --new-zipCode=[new-zipcode]
```

Пример 
```
php bin/console client:update-shipping-address --id=cb4666dd-ab44-492d-a9c1-27b17d7310c4 --country="Qatar" --city="McCulloughhaven" --street="Olaf Passage" --zipCode=76312 --new-country=Qatar2 --new-city=McCulloughhaven2 --new-street="Olaf Passage 2" --new-zipCode=76313
```

Команда смены адреса доставки по умолчанию
```
php bin/console client:set-default-shipping-address --id=[uuid] --country=[country] --city=[city] --street=[street] --zipCode=[zipcode]
```

Пример 
```
php bin/console client:set-default-shipping-address --id=f30c871c-3f8a-44bf-9a3a-43268929a453 --country="Hungary" --city=Patriciaton --street="Norbert Gardens" --zipCode=53897
```

### Тесты

Для тестирования используется symfony/phpunit-bridge, для запуска используйте

```
php bin/phpunit
```

### Статический анализ

Для статического анализа используется PHPStan, для запуска используйте

```
php vendor/bin/phpstan analyse -l 8 -c phpstan.neon src
```

### Единый code-style

Для поддержки единого стиля используется PHP CodeSniffer && PHP Beautify and Fixer
```
php vendor/bin/phpcs src --standard=PSR12
php vendor/bin/phpcbf src --standard=PSR12
```