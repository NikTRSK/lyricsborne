# lyricsborne
310 yungPimpz Project Repo

##Backend setup
* Navigate to `Code` and run `composer install`
* Run `php composer.phar update`

##To run
* Navigate to `Code\src`
* Run `php -S localhost:8888`

##Tests
* Follow the download instructions here: https://phpunit.de/getting-started.html
* `sudo apt-get install php-xml`
* Install XDebug: `sudo apt-get install php-xdebug` (http://www.dieuwe.com/blog/xdebug-ubuntu-1604-php7)
* Run tests:
	* Navigate to `/var/lyricsborne/tests/lyricsborne/Backend/phpunit.xml.dist`
	* Run `./vendor/phpunit/phpunit/phpunit.php -c phpunit.xml.dist`