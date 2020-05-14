# Shopify API

An object-oriented approach towards using the Shopify API.

> Please note: the old version (v0.9) using Guzzle 3.9 is [maintained here](https://github.com/danrichards/shopify-api)

## Supported Objects / Endpoints:

* [Asset](https://help.shopify.com/en/api/reference/online-store/asset)
* [Customer](https://help.shopify.com/en/api/reference/customers/customer)
* [Dispute](https://help.shopify.com/en/api/reference/shopify_payments/dispute)
* [Fulfillment](https://help.shopify.com/en/api/reference/shipping-and-fulfillment/fulfillment)
* [FulfillmentService](https://help.shopify.com/en/api/reference/shipping-and-fulfillment/fulfillmentservice)
* [Image](https://help.shopify.com/en/api/reference/products/product-image)
* [Metafield](https://shopify.dev/docs/admin-api/rest/reference/metafield?api)
* [Order](https://help.shopify.com/api/reference/orders)
* [Product](https://help.shopify.com/api/reference/products)
* [Risk](https://help.shopify.com/en/api/reference/orders/order-risk)
* [Theme](https://help.shopify.com/en/api/reference/online-store/theme)
* [Variant](https://help.shopify.com/en/api/reference/products/product-variant)
* [Webhook](https://help.shopify.com/en/api/reference/events/webhook)

## Composer

    $ composer require dan/shopify-api v2.*

### Metafields!

There are multiple endpoints in the Shopify API that have support for metafields.  
In effort to support them all, this API has been updated to allow chaining `->metafields` from any endpoint.  
This won't always work as not every endpoint supports metafields, and any endpoint that doesn't support metafields will result in a `404`.  
Below are examples of all the endpoints that support metafields.
```php
// Get our API
$api = Dan\Shopify\Shopify::make($shop, $token);

// Store metafields
$api->metafields->get();

// Metafields on an Order
$api->orders($order_id)->metafields->get();

// Metafields on a Product
$api->products($product_id)->metafields->get();

// Metafields on a Variant
$api->products($product_id)->variants($variant_id)->metafields->get();

// Metafields on a Customer
$api->customers($customer_id)->metafields->get();

// Metafields can also be updated like all other endpoints
$api->products($product_id)->metafields($metafield_id)->put($data);
``` 
    
## Updated to work with cursors!

As of the `2019-10` API version, Shopify has removed per page pagination on their busiest endpoints.  
With the deprecation of the per page pagination comes a new cursor based pagination.  
You can use the `next` method to get paged responses.  
Example usage:
``` php
$api = Dan\Shopify\Shopify::make($shop, $token);
// First call to next can have all the usual query params you might want.
$api->orders->next(['limit' => 100, 'status' => 'closed');
// Further calls will have all query params preset except for limit.
$api->orders->next(['limit' => 100]);
```

## Usage without Laravel

``` php
// Assumes setup of client with access token.
$api = Dan\Shopify\Shopify::make($shop, $token);
$api->orders->find($order_id = 123);              // returns Dan\Shopify/Models/Order

// Alternatively, we may call methods on the API object.
$api->orders->get([], $order_id = 123);           // returns array

See Facade usages for other methods available.
```

## Usage with Laravel

### Single Store App

In your `config/app.php`

### Add the following to your `providers` array:

    Dan\Shopify\Integrations\Laravel\ShopifyServiceProvider::class,
    
### Add the following to your `aliases` array:

    'Shopify' => Dan\Shopify\Integrations\Laravel\ShopifyFacade::class,
    
### Replace following variables in your `.env`
    
``` php
SHOPIFY_DOMAIN=your-shop-name.myshopify.com
SHOPIFY_TOKEN=your-token-here
```

### Optionally replace following variables in your `.env`

Empty or `admin` defaults to oldest legacy, [learn more](https://help.shopify.com/en/api/versioning)

``` php
SHOPIFY_API_BASE="admin/api/2019-10"
```

### Using the Facade gives you `Dan\Shopify\Shopify`

> It will be instantiated with your shop and token you setup in `config/shopify.php`

```

```

#### Examples of saving data.

##### Creating a product using a model

```

```

##### Updating a product using a model

```

```

##### Add a product to a collection

```

```

or

```

```

## Embedded Apps

#### Get a token for a redirect response.

``` php
Shopify::getAppInstallResponse(
    'your_app_client_id', 
    'your_app_client_secret',
    'shop_from_request',
    'code_from_request'
);

// returns (object) ['access_token' => '...', 'scopes' => '...']
```

#### Verify App Hmac (works for callback or redirect)

``` php
Dan\Shopify\Util::validAppHmac(
    'hmac_from_request', 
    'your_app_client_secret', 
    ['shop' => '...', 'timestamp' => '...', ...]
);
```

#### Verify App Webhook Hmac

``` php
Dan\Shopify\Util::validWebhookHmac(
    'hmac_from_request', 
    'your_app_client_secret', 
    file_get_contents('php://input')
);
```

## Contributors

- [Diogo Gomes](https://github.com/diogogomeswww)
- [Hiram Cruz](https://github.com/shinyhiram)

## Todo

* Artisan Command to create token

## License

MIT.
