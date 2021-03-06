# php-google-translator
Php wrapper for google javascript translator website plugin. It will create a dropdown option for languages.

![alt text](https://github.com/peterujah/php-google-translator/blob/df2497403282a8d3a9cd629649aa361d3100a503/assets/en.jpg)
![alt text](https://github.com/peterujah/php-google-translator/blob/df2497403282a8d3a9cd629649aa361d3100a503/assets/cn.jpg)


## Installation

Installation is super-easy via Composer:

```md
composer require peterujah/php-google-translator
```


# USAGES

```php 
use Peterujah\NanoBlock\GTranslator;
$translate = new GTranslator("en", "/assets/flags/");
```

set selector design provider, you can choose between `DEFAULT` style and `BOOTSTRAP.`
The `DEFAULT` is the default provider
```php 
$this->setProvider(GTranslator::DEFAULT || GTranslator::BOOTSTRAP);
```

Set the fag icon type, `PNG` or `SVG` to use icons download country flag icon and set the Relative or Absolute path

```php
$translate->setIconType(GTranslator::PNG || GTranslator::SVG);
$translate->setIconPath("https://foo.com/assets/flags/");
 ```
 Add additional langues
 
 ```php
 $translate->addLanguage("en", "English")->addLanguage("ig", "Igbo");
 ```
 
 Load languages 
 
 ```php
 $translate->setLanguages([
  "en" => "English",
  "ig" => "Igbo"
 ])
 ```
 
 Display select option for languages, it accepts optional width.
 ```php 
 $translate->button(optional width = "50%");
 ```
 
 Load supportes javascript plugin
 ```php 
 $translate->load();
 ```
 
 Full usage on website to translate webpage
 
 ```php
<?php 
 use Peterujah\NanoBlock\GTranslator;
 $translate = new GTranslator("en", "/assets/flags/");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>PHP Google Translator</title>
  </head>
  <body>
      <div class="button">
        <?php $translate->button();?>
      </div>
      <div class="content">
        <h2>We have a long history of service in the Bay Area</h2>

        <p>
          We were one of the first credit unions that operate world wide, founded in 1932 as City &amp; County Employees' Credit Union. 
          Membership is now open to anyone who lives, works, or attends school in 
          Alameda, Contra Costa, San Joaquin, Solano, Stanislaus, or Kings counties in California. 
          We believe in banking locally and hope you will too. 
        </p>
      </div>
      <?php $translate->load();?>
  </body>
</html>
```
 
