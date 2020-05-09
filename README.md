Last Topics from any forum's page
# Last Topics from any forum's page

## Installation

Copy the extension to phpBB/ext/alg/lasttopicsajax

Go to "ACP" > "Customise" > "Extensions" and enable the "Last Topics from any forum's page".

## Tests and Continuous Integration

We use Travis-CI as a continuous integration server and phpunit for our unit testing. See more information on the [phpBB development wiki](https://wiki.phpbb.com/Unit_Tests).
To run the tests locally, you need to install phpBB from its Git repository. Afterwards run the following command from the phpBB Git repository's root:

Windows:

    phpBB\vendor\bin\phpunit.bat -c phpBB\ext\alg\lasttopicsajax\phpunit.xml.dist

others:

    phpBB/vendor/bin/phpunit -c phpBB/ext/alg/lasttopicsajax/phpunit.xml.dist

### Лицензия
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)

Расширение выводит последние темы вверху главной страницы или с любого места, как pop-up окно

В настройках можно детально задать условия вывода тем по колонкам и пр.


Репозиторий: https://github.com/alg5/lasttopicsajax
Инсталляция:
Скопируйте всё содержимое репозитория в папку ext/alg/lasttopicsajax/

Перейдите в Панель администратора: АСР-> Персонализация-> Управление расширениями 
Включите расширение "Last Topics from any forum's page".
Поддерживаемые языки:
- Английский (TODО)
 Русский

### Лицензия
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)

[![Build Status](https://travis-ci.org/alg5/lasttopicsajax.svg?branch=master)](https://travis-ci.org/alg5/lasttopicsajax)


