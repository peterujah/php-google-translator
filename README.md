# php-google-translator
Php wrapper for google translator plugin

![alt text](https://github.com/peterujah/Hierarchical/blob/c0fcb5bc6be51763ae3a04d04e56694d682b7ec5/Screen%20Shot%202021-10-01%20at%206.12.50%20AM.png)


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
 
