# DPO Group :tm: Payment API PHP SDK

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](https://travis-ci.org/joemccann/dillinger)

> DPO Group Payment gateway wrapper. using api payment option Easily use with API without web or redirects

## Development Support

If you need support in development, or you need a development team
kindly [contact us](https://razorinformatics.co.ke/contact).

## Documentation

To get the depth details of the api check [API docs here](https://docs.dpopay.com/api/index.html).

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

$results = $dpo->token()->verify($transactionToken)

print_r($results);
```

### Card Payment

make a direct card payment.

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

$results = $dpo->payment()->card($reference,$serviceType,$paymentAmount,$cardNumber,$cardExpiry,$cardCvv, $customerFirstName,$customerLastName,$customerPhone,$customerEmail,$currency,$description)

print_r($results);
```

### Mpesa Payment

Make a direct mpesa payment, Currency is in KES (Kenya Shillings). In the results there us the transaction token, use it
to verfiy payment went through.

```php
use RazorInformatics\DPOPhp;

$companyToken  = 'YOUR_COMPANY_TOKEN';
$serviceType = 5525; //SERVICE TYPE
$reference ="INV-1000";
$paymentAmount = 500;
$customerFirstName='John';
$customerLastName = 'Doe';
$customerPhone = 2547100100100;
$customerEmail = '';
$description = 'Flight booking for 5th January 2032'

$dpo = new DPOPhp($companyToken);

$results = $dpo->payment()->chargeMpesa($reference,$serviceType,$paymentAmount, $customerFirstName,$customerLastName,$customerPhone,$customerEmail,$description)

print_r($results);
```

Remember to verify the transaction code to confirm if payment was successfully.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).
