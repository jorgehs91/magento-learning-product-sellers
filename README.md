## Module Learning_ProductSellers

### Installation
No one action needed except moving this module to `app/code/Learning` and run `setup:upgrade` and `setup:di:compile`.

### Objectives
This module intends to create a relationship between a new entity called ProductSeller and the Magento Products.

### Features
This module creates an admin page to manage the Product Sellers in: **Product Sellers** > **Product Sellers List**.  

Also, the following GraphQl Query and Rest Endpoint were created:

##### GraphQl
```
query {
    productSellers (seller_id: 11) {
        seller_id
        seller_name
        seller_telephone
        seller_products {
            item_id
            product_id
        }
    }
}
```
The argument `seller_id` is optional.
If it is not sent, all the Products Sellers will be returned.

This GraphQl endpoint don't need any kind of authorization.

##### Rest Endpoints
- `POST /V1/productSellers`
- `PUT /V1/productSellers/:sellerId`
- `DELETE /V1/productSellers/:sellerId`
- `GET /V1/productSellers/:sellerId`
- `GET /V1/productSellers`

These endpoints have access control through module ACL and can only be accessed if the consumer is previously authorized.
