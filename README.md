# DPO Group PHP SDK

>DPO Group Payment gateway wrapper. using api payment option.

## Documentation
To get the depth details of the api check [API docs here](https://notifier.razorinformatics.co.ke).

## Install

You can install the PHP SDK via composer or by downloading the source

#### Via Composer

The recommended way to install the SDK is with [Composer](http://getcomposer.org/).

```bash
composer require razor-informatics/dpo-php
```


### Fetch Account Balance

```php
use RazorInformatics\DPOPhp;

$companyToken  = 'YOUR_COMPANY_TOKEN';
$serviceType = 5525; //SERVICE TYPE

$dpo = new DPOPhp($companyToken,$serviceType);

$results = $dpo->account()->balance('USD')

print_r($results);
```

### Card Payment Example
details of a previous sent message.

```php
use RazorInformatics\RiNotifierPhp;

$apiKey  = 'YOUR_API_KEY';
$razor = new RiNotifierPhp\Notifier($apiKey);


$results = $razor->message()->fetchMessage('MESSAGE ID');

print_r($results);
```
### Get Account Details Example

The data available is project details & current account balance

```php
use RazorInformatics\RiNotifierPhp;

$apiKey  = 'YOUR_API_KEY';
$razor = new RiNotifierPhp\Notifier($apiKey);


$results = $razor->account()->getDetails();

print_r($results);
```