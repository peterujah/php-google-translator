# PHP Google Translator

A lightweight PHP wrapper for the Google Translate JavaScript plugin.

This library allows you to easily integrate a language selector into your website, supporting:

* **Dropdown or image/button selectors** for multiple languages.
* **Automatic browser language detection** and preferred language settings.
* **Customizable UI** with options for default, select, or Bootstrap-styled dropdowns.
* **Flexible icon support** with PNG or SVG flags from a local or remote path.
* **Full control over CSS classes** for containers and items.

Designed to simplify adding multilingual support to your site while keeping integration straightforward.

---


![alt text](https://github.com/peterujah/php-google-translator/blob/df2497403282a8d3a9cd629649aa361d3100a503/assets/en.jpg)
![alt text](https://github.com/peterujah/php-google-translator/blob/df2497403282a8d3a9cd629649aa361d3100a503/assets/cn.jpg)


## Installation

Installation is super-easy via Composer:

```md
composer require peterujah/php-google-translator
```

---


## Usage

### Initialize the translator

With a base path for flag icons:

```php
use Peterujah\NanoBlock\GTranslator;

$translate = new GTranslator("en", "/assets/flags/");
```

Without specifying an icon path:

```php
$translate = new GTranslator("en");
```

---

### Set the selector provider

Choose between:

* `GTranslator::DEFAULT` (default HTML dropdown or image button)
* `GTranslator::SELECT` (HTML select dropdown)
* `GTranslator::BOOTSTRAP` (Bootstrap dropdown)

```php
$translate->setProvider(GTranslator::DEFAULT);
// or
$translate->setProvider(GTranslator::SELECT);
// or
$translate->setProvider(GTranslator::BOOTSTRAP);
```

---

### Configure icon path and type

Set the relative or absolute path for your flag icons and choose between `GTranslator::PNG` or `GTranslator::SVG`:

```php
$translate->setIconPath("/assets/flags/")->setIconType(GTranslator::PNG);
```

Or using an external URL:

```php
$translate->setIconPath("https://example.com/assets/flags/")->setIconType(GTranslator::PNG);
```

---

### Manage languages

Add additional languages individually:

```php
$translate->addLanguage("en", "English")
    ->addLanguage("ig", "Igbo");
```

Or override the default language map:

```php
$translate->setLanguages([
    "en" => "English",
    "ig" => "Igbo",
]);
```

---

### Display the language selector

Render the selector with an optional width (default `"170px"`):

```php
$translate->button("50%");
```

Behavior depends on the provider:

* **`GTranslator::SELECT`** → returns a `<select>` element.
* **`GTranslator::BOOTSTRAP`** → returns a bootstrap dropdown `<li>` element.
* **`GTranslator::DEFAULT`** → returns an image/button interface:

Image JS trigger button uses `GTranslator::DEFAULT`

```php
$translate->setProvider(GTranslator::DEFAULT);
$translate->jsButton(true);
```

---

### Load the translator engine

Include the Google Translate JS and CSS:

```php
$translate->load();
```

---

### Customize classes

Set the container class:

```php
$translate->setContainerClass("my-translator");
```

Set individual item classes:

```php
$translate->setItemsClass("my-translator");
```

---

### Control language behavior

Set a preferred language (must be after `load()`):

```php
$translate->preferredLanguage("ms");
```

Automatically detect the browser language (must be after `load()`):

```php
$translate->autoTranslate();
```

---

### Full example

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
    <?php $translate->button(); ?>
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

<?php
$translate->load();
$translate->preferredLanguage("ms");
?>

</body>
</html>
```
