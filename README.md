# Yapay #

Yapay is a library for Yapay Payment Integration.

## Installation ##

Clone this repository inside your lib folder. 
For example: 

```
\vendor
```

Add a new reference to Yapay\App.php: 

````
<?php 
use Yapay\App;
````

## Set up ##

First, create a new instance of .\App.php, where $environment is **'HOMOLOGATION'** or **'PRODUCTION'**:
    
```
$yapay = new \Yapay\App($environment); 
```

Then, pass your token account in a position of the array data to be send, like this: 

```
$data["token_account"] = $token;
```

Call the Yapay endpoing with _POST_ request type: 
```
return $yapay->callAPI('POST', $data);
```

# Usage #
The object to be send to Yapay API is a array containing all of the following objects, described below:

## Entities ##
### Customer ###
| Name | Field | Type | Min Length | Max Length | Required? (Y/N) | Description |
| - | - | -  | - | - | - | - |
| Name | name | String | - | 100 | Y | Name of Customer |
| Email | email | String | 14 | - | Y | Email of Customer |
| CPF | cpf | String | - | 100 | Y | CPF of Customer (Only numbers) |
| Birth Date | birth_date | DateTime | - | 10  | Y | YYYY-MM-DD Format |
| Trade Name | trade_name | String | - |  100| N | Buyer Fantasy Name of Company  |
| Company Name | company_name | String |  100 | - | N | Buyer Company Name |
| CNPJ  | cnpj | String | - |  18 | N | CNPJ of Company (Only numbers) |
| Municipal Inscrition  | municipal_inscrition | String | - | 20  | N | Municipal Inscrition (Only numbers) |
| Addresses | addresses | Address[] | -| - | - | Array List containing all Addresses of Customer |
| Contacts | contacts | Contacts[] | -| - | - | Array List containing all Contacts of Customer |

### Contact ###

| Name | Field | Type | Min Length | Max Length | Required? (Y/N) | Description |
| - | - | -  | - | - | - | - |
| Type Contact | type_contact | char  | - | 1 |  Y | [Type Contact](https://intermediador.dev.yapay.com.br/#/tabelas?id=-tabela-1-contato) |
| Number Contact | number_contact | String  | 10 | 11 | Y | Buyer Phone Number |

### Address

| Name | Field | Type | Min Length | Max Length | Required? (Y/N) | Description |
| - | - | -  | - | - | - | - |
| Type Address | type_address | Char  | - | 1 | Y | [Address Type](https://intermediador.dev.yapay.com.br/#/tabelas?id=tabela-2-tipos-de-endere%C3%A7o) |
| Postal Code | address_postal_code | String | - |  8 | Y | Postal Code |
| Street Name |  address_street |  String | - |  120 | Y | Address Street Name |
| Number |  address_number |  String | - |  120 | Y | Address Street Name |
| Neighborhood | address_neighborhood |  String | - |  100 | Y | Address Neighborhood |
| Complement | address_complement |  String | - |  100 | N | Address Complement |
| City | address_city |  String | - |  120 | Y | Address City |
| State | address_state | String  | - | 2 | Y | Address State |

### Transaction

| Name | Field | Type | Min Length | Max Length | Required? (Y/N) | Description |
| - | - | -  | - | - | - | - |
| Available Payment Methods | - | -  | - | - | - | [Available Payment Methods](https://intermediador.dev.yapay.com.br/#/tabelas?id=tabela-3-formas-de-pagamento) <br /> **Note: Not being transfered in Request** |
| Order Number | order_number | String | - | 20 | N | Order Number (Should not repeat) |
| Customer IP | - | String | - | 15 | Y | Buyer IP (Aquired by Server, not send in request to BackEnd) |
| Shipping Type | shipping_type | String | - | 100 | N | Shipping Type (Sedex, Jadlog...) |
| Shipping Price | shipping_price | Float | - | 11 | N | Shipping Price (X.99 Format) |
| Price Discount | price_discount | Float | - | 11 | N | Price Discount (X.99 Format) |
| Price Additional | price_additional | Float | - | 11 | N | Price Discount (X.99 Format) |
| URL Notification | url_notification | String | - | 255 | N | URL Notification of Purchase |
| Free | free | String | - | 200 | N | Free field |
| Sub-Store | sub_store | String | - | 20 | N | Sub Store|
| Max Split Transaction | max_split_transaction | Int | - | 10 | N | Maximum Split Transaction|

### Transaction Product

| Name | Field | Type | Min Length | Max Length | Required? (Y/N) | Description |
| - | - | -  | - | - | - | - |
| Description | product_description | String  | - | 100 | Y | Product name |
| Quantity | product_quantity | Int  | - | 3 | Y | Product quantity |
| Price Unit | price_unit | Float | - | 11 | Y | Unit Value. (X.99 Format) |
| Code | product_code | String | - | 11 | N | Product Code |
| Product SKU Code | product_sku_code | String | - | 50 | N | Product SKU Code |
| Extra | product_extra | String | - | 100 | N | Product Free Field |

### Payment

| Name | Field | Type | Min Length | Max Length | Required? (Y/N) | Description |
| - | - | -  | - | - | - | - |
| Payment Method Id | - | String  | - | 2 | Y | Payment Method (Not Send in Request today, it's calculated in backend) |
| Payment Mode | payment_mode | bool  | - | 1 | Y | Payment Mode (Credit Card = true / Billet = false) |
| Split | split | String  | - | 2 | Y | Payment Splits (maximum 12 splits) |
| Card Token | card_token | String  | - | 100 | N | Card Token in Safe Store (Yapay Safe) |
| Card Name | card_name | String  | - | 100 | N | Name of Customer in Credit Card|
| Card Number | card_number | Int  | - | 20 | N | Number of Credit Card |
| Card Expiration Date | card_expdate | Datetime  | - | 7 | N | MM/YYYY Credit Card Format |
| Card Verification Value | card_cvv | Int  | - | 3 | N | Card Verification Value (Localized in the back of the card) |
| Billet Date Expiration | billet_date_expiration | Datetime  | - | 10 | N | Billet Date Expiration (YYYY-MM-DD Format) |

### Affiliates 

| Name | Field | Type | Min Length | Max Length | Required? (Y/N) | Description |
| - | - | -  | - | - | - | - |
| Account E-mail | affiliate_account_email | String  | - | 100 | N | Account E-mail of Affiliate |
| Affiliate Percentage  | affiliate_percentage | Int  | - | 3 | N | Pass-through percentage to affiliate |
| Affiliate Comission Ammount  | affiliate_comission_ammount | Float  | - | 11 | N | Affiliate Comission (Minimum value: 1 / Maximum: 100) |
| Type Affiliate  | type_affiliate | String  | - | 100 | N | Affiliate Type1 |

### Other fields 

| Name | Field | Type | Min Length | Max Length | Required? (Y/N) | Description |
| - | - | -  | - | - | - | - |
| Token Account | - | String  | - | 20 | Y | Seller Identification Token <br /> **Warning**: Utilize this field only in backend, <br />because this is your key to make transfers. |
| Reseller Token | - | String  | - | - | N | Resseler Token |
| Payment Tax Code | payment_tax_code | String  | - | - | N | Payment Tax Code |
| Finger Print | finger_print | String  | - | 100 | Y | Fingerprint token automatically generated |

## Transaction 
To send the data to Yapay, the object must be send with the following structure:

| Field | Description |
| - | - |
| 'customer' | Customer Entity. Inside of Customer, should have a 'addresses' list and a 'contacts' list of Entities|
| 'transaction' | Transaction Entity |
| 'transaction_product' | Transaction Product Entity |
| 'payment' | Payment Entity |
| 'affiliates' | List of Affiliates Entities |
| 'finger_print' | Finger Print field |
| 'reseller_token' | Resseler Token Field |
| 'payment_tax_code' | Payment Tax Code field |
| 'token_account' | Token Account Field |

* This library only works with Credit Card or Billet type of Payment, i didn't tested other payment methods. If the payment is with Credit Card, don't pass the parameter **_payment_method_id_** in the constructor, it will calculated based on the credit card. 
* However, if the payment method is Billet, you need to inform this parameter with value 6 *(last checked: 2020-12-18)*.

## Validation 

I've added a __validate__ method in all entities, so... before you send the data to Yapay API, check if there's any problem with the data to submit. Create a array and check/add all validate returns from any Yapay entity to send, then check if there's any error in array. 

```
<?php 
$errors[] = $customer->validate();
$errors[] = $transaction->validate();
$errors[] = $payment->validate();

foreach ($transactionProductDataList as $transactionProductItem) {
    $errors[] = $transactionProductItem->validate();
}

foreach ($affiliatesDataList as $affiliateItem) {
    $errors[] = $affiliateItem->validate();
}

$errors = array_filter($errors, fn ($error) => !is_null($error));

if (count($errors) > 0) {
    // Throw your exception with all errors listed
}
```

## Dependencies ##
PHP 7.4, with the following packages:
* php-common                 
* php7.4                     
* php7.4-cli                 
* php7.4-common              
* php7.4-curl                
* php7.4-json                

## License ##

[MIT](https://choosealicense.com/licenses/mit/)

## Version ##

    1.0.0

# Who do I talk to?

## Repo Owner or admin

* Admin/Developer: Gustavo Souza Gonçalves (gustavosouzagoncalves at gmail.com)
* Owner: Fábio gomes (fabiogomesds at gmail.com)
