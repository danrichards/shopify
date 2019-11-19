# Shopify API

An object-oriented approach towards using the Shopify API. This repos is a work-in-progress and not production ready.

> Please note: the old version (v0.9) using Guzzle 3.9 is [maintained here](https://github.com/danrichards/shopify-api)

## Supported Objects / Endpoints:

* [Order](https://help.shopify.com/api/reference/order)

## Composer

    $ composer require dan/shopify-api v1.*
    
## Usage without Laravel

```
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
    
```
SHOPIFY_DOMAIN=your-shop-name.myshopify.com
SHOPIFY_TOKEN=your-token-here
```

### Optionally replace following variables in your `.env`

Empty or `admin` defaults to oldest legacy, [learn more](https://help.shopify.com/en/api/versioning)

```
SHOPIFY_API_BASE="admin/api/2019-07"
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

```
Shopify::getAppInstallResponse(
    'your_app_client_id', 
    'your_app_client_secret',
    'shop_from_request',
    'code_from_request'
);

// returns (object) ['access_token' => '...', 'scopes' => '...']
```

#### Verify App Hmac (works for callback or redirect)

```
Dan\Shopify\Util::validAppHmac(
    'hmac_from_request', 
    'your_app_client_secret', 
    ['shop' => '...', 'timestamp' => '...', ...]
);
```

#### Verify App Webhook Hmac

```
Dan\Shopify\Util::validWebhookHmac(
    'hmac_from_request', 
    'your_app_client_secret', 
    file_get_contents('php://input')
);
```

## Contributors

- [Diogo Gomes](https://github.com/diogogomeswww)

## Todo

* Artisan Command to create token

## License

MIT.