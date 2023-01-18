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

$dpo = new DPOPhp($companyToken);

$results = $dpo->account()->balance('USD')

print_r($results);
```

### Verify Transaction using Transaction Token

How to verify token

```php
use RazorInformatics\DPOPhp;

$companyToken  = 'YOUR_COMPANY_TOKEN';

$dpo = new DPOPhp($companyToken);

$transactionToken = 'TRANSACTION_TOKEN_GIVEN';

$results = $$dpo->token()->verify($transactionToken)

print_r($results);
```

### Card Payment

How to verify token

```php
use RazorInformatics\DPOPhp;

$companyToken  = 'YOUR_COMPANY_TOKEN';
$serviceType = 5525; //SERVICE TYPE
$paymentAmount = 500;
$reference ="INV-1000";
$cardNumber = 5436886269848367
$cardExpiry = 1224;// format My example 0123 i.e. January 2023
$cardCvv = 123;
$customerFirstName='John';
$customerLastName = 'Doe';
$customerPhone ='';
$customerEmail = '';
$currency = 'USD';
$description = 'Flight booking for 5th January 2032'

$dpo = new DPOPhp($companyToken);

$transactionToken = 'TRANSACTION_TOKEN_GIVEN';

$results = $dpo->payment()->card($reference,$serviceType,$paymentAmount,$cardNumber,$cardExpiry,$cardCvv, $customerFirstName,$customerLastName,$customerPhone,$customerEmail,$currency,$description)

print_r($results);
```
