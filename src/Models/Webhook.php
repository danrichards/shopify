<?php

namespace Dan\Shopify\Models;

/**
 * Class Webhook
 */
class Webhook extends AbstractModel
{

    /** @var string $resource_name */
    public static $resource_name = 'webhook';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'webhooks';

    /** @var array $dates */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /** @var array $casts */
    protected $casts = [
        'id' => 'integer',
        'address' => 'string',
        'topic' => 'string',
        'fields' => 'array',
        'format' => 'string',
        'metafield_namespaces' => 'array',
    ];

    const CARTS_CREATE = 'carts/create';
    const CARTS_UPDATE = 'carts/update';
    const CHECKOUTS_CREATE = 'checkouts/create';
    const CHECKOUTS_DELETE = 'checkouts/delete';
    const CHECKOUTS_UPDATE = 'checkouts/update';
    const COLLECTION_LISTINGS_ADD = 'collection_listings/add';
    const COLLECTION_LISTINGS_REMOVE = 'collection_listings/remove';
    const COLLECTION_LISTINGS_UPDATE = 'collection_listings/update';
    const COLLECTIONS_CREATE = 'collections/create';
    const COLLECTIONS_DELETE = 'collections/delete';
    const COLLECTIONS_UPDATE = 'collections/update';
    const CUSTOMER_GROUPS_CREATE = 'customer_groups/create';
    const CUSTOMER_GROUPS_DELETE = 'customer_groups/delete';
    const CUSTOMER_GROUPS_UPDATE = 'customer_groups/update';
    const CUSTOMERS_CREATE = 'customers/create';
    const CUSTOMERS_DELETE = 'customers/delete';
    const CUSTOMERS_DISABLE = 'customers/disable';
    const CUSTOMERS_ENABLE = 'customers/enable';
    const CUSTOMERS_UPDATE = 'customers/update';
    const DISPUTES_CREATE = 'disputes/create';
    const DISPUTES_UPDATE = 'disputes/update';
    const DRAFT_ORDERS_CREATE = 'draft_orders/create';
    const DRAFT_ORDERS_DELETE = 'draft_orders/delete';
    const DRAFT_ORDERS_UPDATE = 'draft_orders/update';
    const FULFILLMENT_EVENTS_CREATE = 'fulfillment_events/create';
    const FULFILLMENT_EVENTS_DELETE = 'fulfillment_events/delete';
    const FULFILLMENTS_CREATE = 'fulfillments/create';
    const FULFILLMENTS_UPDATE = 'fulfillments/update';
    const ORDER_TRANSACTIONS_CREATE = 'order_transactions/create';
    const ORDERS_CANCELLED = 'orders/cancelled';
    const ORDERS_CREATE = 'orders/create';
    const ORDERS_DELETE = 'orders/delete';
    const ORDERS_FULFILLED = 'orders/fulfilled';
    const ORDERS_PAID = 'orders/paid';
    const ORDERS_PARTIALLY_FULFILLED = 'orders/partially_fulfilled';
    const ORDERS_UPDATED = 'orders/updated';
    const PRODUCT_LISTINGS_ADD = 'product_listings/add';
    const PRODUCT_LISTINGS_REMOVE = 'product_listings/remove';
    const PRODUCT_LISTINGS_UPDATE = 'product_listings/update';
    const PRODUCTS_CREATE = 'products/create';
    const PRODUCTS_DELETE = 'products/delete';
    const PRODUCTS_UPDATE = 'products/update';
    const REFUNDS_CREATE = 'refunds/create';
    const SHOP_UPDATE = 'shop/update';
    const APP_UNINSTALLED = 'app/uninstalled';
    const THEMES_CREATE = 'themes/create';
    const THEMES_DELETE = 'themes/delete';
    const THEMES_PUBLISH = 'themes/publish';
    const THEMES_UPDATE = 'themes/update';

    /** @var array $topics*/
    public static $topics = [
        self::CARTS_CREATE,
        self::CARTS_UPDATE,
        self::CHECKOUTS_CREATE,
        self::CHECKOUTS_DELETE,
        self::CHECKOUTS_UPDATE,
        self::COLLECTION_LISTINGS_ADD,
        self::COLLECTION_LISTINGS_REMOVE,
        self::COLLECTION_LISTINGS_UPDATE,
        self::COLLECTIONS_CREATE,
        self::COLLECTIONS_DELETE,
        self::COLLECTIONS_UPDATE,
        self::CUSTOMER_GROUPS_CREATE,
        self::CUSTOMER_GROUPS_DELETE,
        self::CUSTOMER_GROUPS_UPDATE,
        self::CUSTOMERS_CREATE,
        self::CUSTOMERS_DELETE,
        self::CUSTOMERS_DISABLE,
        self::CUSTOMERS_ENABLE,
        self::CUSTOMERS_UPDATE,
        self::DISPUTES_CREATE,
        self::DISPUTES_UPDATE,
        self::DRAFT_ORDERS_CREATE,
        self::DRAFT_ORDERS_DELETE,
        self::DRAFT_ORDERS_UPDATE,
        self::FULFILLMENT_EVENTS_CREATE,
        self::FULFILLMENT_EVENTS_DELETE,
        self::FULFILLMENTS_CREATE,
        self::FULFILLMENTS_UPDATE,
        self::ORDER_TRANSACTIONS_CREATE,
        self::ORDERS_CANCELLED,
        self::ORDERS_CREATE,
        self::ORDERS_DELETE,
        self::ORDERS_FULFILLED,
        self::ORDERS_PAID,
        self::ORDERS_PARTIALLY_FULFILLED,
        self::ORDERS_UPDATED,
        self::PRODUCT_LISTINGS_ADD,
        self::PRODUCT_LISTINGS_REMOVE,
        self::PRODUCT_LISTINGS_UPDATE,
        self::PRODUCTS_CREATE,
        self::PRODUCTS_DELETE,
        self::PRODUCTS_UPDATE,
        self::REFUNDS_CREATE,
        self::SHOP_UPDATE,
        self::THEMES_CREATE,
        self::THEMES_DELETE,
        self::THEMES_PUBLISH,
        self::THEMES_UPDATE
    ];

}
