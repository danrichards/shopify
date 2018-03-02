<?php

namespace Dan\Shopify\Models;

/**
 * Class Theme
 */
class Theme extends AbstractModel
{
    /** @var string $resource_name */
    public static $resource_name = 'theme';

    /** @var string $resource_name_many */
    public static $resource_name_many = 'themes';

    /** @var array $dates */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /** @var array $casts */
    protected $casts = [
        'name' => 'string',
        'role' => 'string',
        'previewable' => 'bool',
        'processing' => 'bool'
    ];

    const THEME_ROLE_MAIN = 'main';
    const THEME_ROLE_UNPUBLISHED = 'unpublished';

    /** @var array $theme_roles */
    public static $theme_roles = [
        self::THEME_ROLE_MAIN,
        self::THEME_ROLE_UNPUBLISHED
    ];

    const FILE_LAYOUT_GIFT_CARD = 'layout/gift_card.liquid';
    const FILE_LAYOUT_PASSWORD = 'layout/password.liquid';
    const FILE_LAYOUT_THEME = 'layout/theme.liquid';
    const FILE_SETTINGS_SCHEMA_JSON = 'config/settings_schema.json';
    const FILE_SETTINGS_DATA_JSON = 'config/settings_data.json';
    const FILE_TEMPLATES_404 = 'templates/404.liquid';
    const FILE_TEMPLATES_ARTICLE = 'templates/article.liquid';
    const FILE_TEMPLATES_BLOG = 'templates/blog.liquid';
    const FILE_TEMPLATES_CART = 'templates/cart.liquid';
    const FILE_TEMPLATES_COLLECTION = 'templates/collection.liquid';
    const FILE_TEMPLATES_CUSTOMERS_ACCOUNT = 'templates/customers/account.liquid';
    const FILE_TEMPLATES_CUSTOMERS_ACTIVATE = 'templates/customers/activate.liquid';
    const FILE_TEMPLATES_CUSTOMERS_ADDRESSES = 'templates/customers/addresses.liquid';
    const FILE_TEMPLATES_CUSTOMERS_LOGIN = 'templates/customers/login.liquid';
    const FILE_TEMPLATES_CUSTOMERS_ORDER = 'templates/customers/order.liquid';
    const FILE_TEMPLATES_CUSTOMERS_REGISTER = 'templates/customers/register.liquid';
    const FILE_TEMPLATES_CUSTOMERS_RESET_PASSWORD = 'templates/customers/reset_password.liquid';
    const FILE_TEMPLATES_GIFT_CARD = 'templates/gift_card.liquid';
    const FILE_TEMPLATES_INDEX = 'templates/index.liquid';
    const FILE_TEMPLATES_LIST_COLLECTIONS = 'templates/list-collections.liquid';
    const FILE_TEMPLATES_PAGE = 'templates/page.liquid';
    const FILE_TEMPLATES_PASSWORD = 'templates/password.liquid';
    const FILE_TEMPLATES_PRODUCT = 'templates/product.liquid';
    const FILE_TEMPLATES_SEARCH = 'templates/search.liquid';
    const FILE_LOCALES_EN_DEFAULT = 'locales/en.default.json';

    /** @var array $core_files */
    public static $core_files = [
        self::FILE_LAYOUT_GIFT_CARD,
        self::FILE_LAYOUT_PASSWORD,
        self::FILE_LAYOUT_THEME,
        self::FILE_SETTINGS_DATA_JSON,
        self::FILE_SETTINGS_SCHEMA_JSON,
        self::FILE_TEMPLATES_404,
        self::FILE_TEMPLATES_ARTICLE,
        self::FILE_TEMPLATES_BLOG,
        self::FILE_TEMPLATES_CART,
        self::FILE_TEMPLATES_COLLECTION,
        self::FILE_TEMPLATES_CUSTOMERS_ACCOUNT,
        self::FILE_TEMPLATES_CUSTOMERS_ACTIVATE,
        self::FILE_TEMPLATES_CUSTOMERS_ADDRESSES,
        self::FILE_TEMPLATES_CUSTOMERS_LOGIN,
        self::FILE_TEMPLATES_CUSTOMERS_ORDER,
        self::FILE_TEMPLATES_CUSTOMERS_REGISTER,
        self::FILE_TEMPLATES_CUSTOMERS_RESET_PASSWORD,
        self::FILE_TEMPLATES_GIFT_CARD,
        self::FILE_TEMPLATES_INDEX,
        self::FILE_TEMPLATES_LIST_COLLECTIONS,
        self::FILE_TEMPLATES_PAGE,
        self::FILE_TEMPLATES_PASSWORD,
        self::FILE_TEMPLATES_PRODUCT,
        self::FILE_TEMPLATES_SEARCH,
    ];
}
