
<p align="center">
  <img src="https://raw.githubusercontent.com/corcel/woocommerce/master/.github/logo.jpg" alt="Corcel WooCommerce logo" />
</p>

**A collection of Laravel models for WooCommerce.**

This plugin extends base [Corcel](https://github.com/corcel/corcel) package and allows fetching WooCommerce data from WordPress database. Currently this plugin implements the following models:

* [Customer](#customer-model) - wrapper for User model
* [Order](#order-model) - wrapper for Post model
* [Product](#product-model) - wrapper for Post model
* [Item](#item-model)
* Product Category - wrapper for Taxonomy model
* Product Tag - wrapper for Taxonomy model
* Product Attribute - helper model for getting product attributes (should not be used directly)
* Product Type - helper model for getting product types (should not be used directly)

Some meta values are collected into helper classes:

* [BillingAddress](#billingaddress-helper) - helper for customer and order billing address
* [ShippingAddress](#shippingaddress-helper) - helper for customer and order shipping address
* [Payment](#payment-helper) - helper for order payment

## Compatibility list

| Corcel WooCommerce                                    | Laravel        | PHP version |
| ----------------------------------------------------- | -------------- | ----------- |
| [1.x](https://github.com/corcel/woocommerce/tree/1.x) | 6.x, 7.x       | >= 7.2      |
| 2.x (master)                                          | 6.x, 7.x, 8.x  | >= 7.3      |

Currently PHP 8.0 is not supported.

## Models list

### Customer model

#### Get customer by id

```php
$customer = Customer::find(1);
```

#### Customer relation

```php
$customer = Customer::find(1);

$customerOrders = $customer->orders;
```

#### Customer properties

```php
$customer = Customer::find(1);

$customer->order_count;      // e.g. 10
$customer->billing_address;  // \Corcel\WooCommerce\Support\BillingAddress class instance
$customer->shipping_address; // \Corcel\WooCommerce\Support\ShippingAddress class instance
```

### Order model

#### Get order by id

```php
$order = Order::find(1);
```

#### Get completed orders

```php
$completedOrders = Order::completed()->get();
```

*For other statuses methods please check [OrderBuilder.php](src/Model/Builder/OrderBuilder.php).*

#### Order relations

```php
$order = Order::find(1);

$orderItems    = $order->items;
$orderCustomer = $order->customer;
```

#### Order properties

```php
$order = Order::find(1);

$order->currency;         // e.g. EUR
$order->total;            // e.g. 10.20
$order->tax;              // e.g. 0.50
$order->shipping_tax;     // e.g. 0.20
$order->status;           // e.g. completed
$order->date_completed;   // e.g. 2020-06-01 10:00:00
$order->date_paid;        // e.g. 2020-06-01 10:00:00
$order->payment;          // \Corcel\WooCommerce\Support\Payment class instance
$order->billing_address;  // \Corcel\WooCommerce\Support\BillingAddress class instance
$order->shipping_address; // \Corcel\WooCommerce\Support\ShippingAddress class instance
```

### Item model

#### Get item by id

```php
$item = Item::find(1);
```

#### Item relations

```php
$item = Item::find(1);

$itemOrder   = $item->order;
$itemProduct = $item->product;
```

#### Item properties

```php
$item = Item::find(1);

$item->order_item_id;
$item->order_item_name;
$item->order_item_type;
$item->order_id;
$item->product_id;
$item->variation_id;
$item->quantity;          // e.g. 2
$item->tax_class;
$item->line_subtotal;     // e.g. 5.50
$item->line_subtotal_tax; // e.g. 0.50
$item->line_total;        // e.g. 10.50
$item->line_tax;          // e.g. 2.00
```

### Product model

#### Get product by id

```php
$product = Product::find(1);
```

#### Product relations

```php
$product = Product::find(1);

$product->categories;
$product->items;
$product->tags;
```

#### Product properties

```php
$product = Product::find(1);

$product->price;
$product->regular_price;
$product->sale_price;
$product->on_sale;
$product->sku;
$product->tax_status;
$product->is_taxable;
$product->weight;
$product->length;
$product->width;
$product->height;
$product->is_virtual;
$product->is_downloadable;
$product->stock;
$product->in_stock;
$product->type;
$product->attributes; // Collection of (variation) attributes
$product->crosssells; // Collection of cross-sell products
$product->upsells;    // Collection of up-sell products
$product->gallery;    // Collection of gallery attachments
```

## Helper classes list

### BillingAddress helper

This class collects customer and order meta related to billing address.

```php
$order   = Order::find(1);
$address = $order->billingAddress;

$address->first_name;
$address->last_name;
$address->company;
$address->address_1;
$address->address_2;
$address->city;
$address->state;
$address->postcode;
$address->country;
$address->email;
$address->phone;
```

### ShippingAddress helper

This class collects customer and order meta related to billing address.

```php
$order   = Order::find(1);
$address = $order->shippingAddress;

$address->first_name;
$address->last_name;
$address->company;
$address->address_1;
$address->address_2;
$address->city;
$address->state;
$address->postcode;
$address->country;
```

### Payment helper

This class collects order meta related to payment.

```php
$order   = Order::find(1);
$payment = $order->payment;

$payment->method;
$payment->method_title;
$payment->transaction_id;
```
