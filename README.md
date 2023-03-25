# Shopify API

A fluent and object-oriented approach for using the Shopify API.

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
* [Shop](https://shopify.dev/docs/admin-api/rest/reference/store-properties/shop)
* [Theme](https://help.shopify.com/en/api/reference/online-store/theme)
* [Variant](https://help.shopify.com/en/api/reference/products/product-variant)
* [Webhook](https://help.shopify.com/en/api/reference/events/webhook)

## Composer

```shell
composer require dan/shopify
```

## Basic Usage

The APIs all function alike, here is an example of usage of the products API.

```php
$api = Dan\Shopify\Shopify::make($shop = 'shop-name.myshopify.com', $token = 'shpua_abc123');

// Shop information
$api->shop(); // array dictionary

// List of products
$api->products->get(); // array of array dictionaries

// Attach query parameters to a get request
$api->products->get(['created_at_min' => '2023-03-25']); // array of array dictionaries

// A specific product
$api->products('123456789')->get(); // array dictionary

// Get all variants for a product
$api->products('123456789')->variants->get(); // array of array dictionaries

// Get a specific variant for a specific product
$s->api2()->products('123456789')->variants('567891234')->get(); // array dictionary

// Append URI string to a get request
$api->orders('123456789')->get([], 'risks'); // array dictionary

// Create a product.
// See https://shopify.dev/docs/api/admin-rest/2023-01/resources/product#post-products
$api->products->post(['title' => 'Simple Test']); // array dictionary

// Update something specific on a product
$api->products('123456789')->put(['title' => 'My title changed.']); // array dictionary
```

## Using cursors

> Shopify doesn't jam with regular old pagination, sigh ...

As of the `2019-10` API version, Shopify has removed per page pagination on their busiest endpoints.  
With the deprecation of the per page pagination comes a new cursor based pagination.  
You can use the `next` method to get paged responses.  
Example usage:

```php
// First call to next can have all the usual query params you might want.
$api->orders->next(['limit' => 100, 'status' => 'closed');

// Further calls will have all query params preset except for limit.
$api->orders->next(['limit' => 100]);
```

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

## Usage with Laravel

### Single Store App

In your `config/app.php`

### Add the following to your `providers` array:

Requires for private app (env token) for single store usage of oauth (multiple stores)

```php
Dan\Shopify\Integrations\Laravel\ShopifyServiceProvider::class,
```
    
### Add the following to your `aliases` array:

If your app only interacts with a single store, there is a Facade that may come in handy.

```php
'Shopify' => Dan\Shopify\Integrations\Laravel\ShopifyFacade::class,
```
    
### For facade usage, replace the following variables in your `.env`
    
```dotenv
SHOPIFY_DOMAIN=your-shop-name.myshopify.com
SHOPIFY_TOKEN=your-token-here
```

### Optionally replace following variables in your `.env`

Empty or `admin` defaults to oldest supported API, [learn more](https://help.shopify.com/en/api/versioning)

```dotenv
SHOPIFY_API_BASE="admin/api/2022-07"
```

### Using the Facade gives you `Dan\Shopify\Shopify`

> It will be instantiated with your shop and token you set up in `config/shopify.php`

Review the `Basic Usage` above, using the Facade is more or less the same, except you're only interacting with the one store in your config.

```php
// Facade same as $api->shop(), but for just the one store.
Shopify::shop();

// Facade same as $api->products->get(), but for just the one store.
Shopify::products()->get();

// Facade same as $api->products('123456789')->get(), but for just the one store.
Shopify::products('123456789')->get();
```

## Oauth Apps

Making a public app using oauth, follow the Shopify docs to make your auth url, and use the following helper to retrieve your access token using the code from your callback.

### Get a token for a redirect response.

```php
Shopify::getAppInstallResponse(
    'your_app_client_id', 
    'your_app_client_secret',
    'shop_from_request',
    'code_from_request'
);

// returns (object) ['access_token' => '...', 'scopes' => '...']
```

### Verify App Hmac (works for callback or redirect)

```php
Dan\Shopify\Util::validAppHmac(
    'hmac_from_request', 
    'your_app_client_secret', 
    ['shop' => '...', 'timestamp' => '...', ...]
);
```

### Verify App Webhook Hmac

```php
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
