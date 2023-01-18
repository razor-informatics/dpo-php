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

### Verify Transaction using Transaction Token

How to verify token

```php
use RazorInformatics\DPOPhp;

$companyToken  = 'YOUR_COMPANY_TOKEN';
$serviceType = 5525; //SERVICE TYPE

$dpo = new DPOPhp($companyToken,$serviceType);

$transactionToken = 'TRANSACTION_TOKEN_GIVEN';

$results = $$dpo->token()->verify($transactionToken)

print_r($results);
```
