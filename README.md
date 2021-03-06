Задание: Напишите форму обратной связи.

Поля формы:
звездочкой (*) отмечены обязательные:
имя (*) - не более 80 знаков
email (*) - валидный email
текст обращения (*) - не более 4000 знаков
защита от ботов (*) - просьба решить небольшое математическое выражение в формате натуральное число меньше 30 плюс/минус натуральное число меньше 30, например 23+12

Функциональность:

1. Валидация. Нельзя отправить форму с неверным email, слишком длинным именем, пустым текстом и т.п.;
2. Форма сохраняется в базу данных MySQL. Структуру данных разработайте с учетом того, что в дальнейшем по этой базе должна быть возможность поиска по дате, по пользователю. Пользователь - отдельная сущность в базе данных, уникально идентифицируется по email;
3. Форма отправляется на email rabota@awardwallet.com и дублируется пользователю на email. Email должен быть в формате html. В email, который отправляется на rabota@awardwallet.com, добавьте ссылку на страницу, которая отобразит все сообщения, которые написал этот пользователь;
4. Страница отображения всей истории переписки с пользователем (смотри выше);
5. С одного ip нельзя отправлять более чем 2 формы в минуту - давать ошибку "Слишком много запросов с вашего адреса, пожалуйста, подождите минуту". Учитывайте, что в системе более чем один сервер;
6. Нельзя отправлять более чем 2 формы в минуту на один email;

Критерии оценки:

1. Реализация требуемого функционала;
2. Безопасность кода;
3. Использование Symfony. Другой фреймворк или отсутствие фреймворка тоже допускается;
4. Бизнес-модель: пишите код так, как будто это часть большого проекта и вашим кодом будет пользоваться кто-то еще;
5. Модель базы данных;
6. Качество и поддерживаемость кода.