[<< Back to Table of Contents](readme.md)

####YuccaSay.com
> Details of CPT needed in the app.

Custom Post Types 
-------------

As this app is basically nothing but a WP plugin so we to save our data we need to create some CPT's.
Template for the CPT description document.

		YSY_PRODUCT

- **Definition: Holds all the product details.**

- **Access in dashboard** SELLER (only own products) and ADMIN (all products)

- **Custom Fields** ['product_name',product price,'gallery']
 


---------- 


		YSY_ORDER

- **Definition: Holds all the ORDER details.**

- **Access in dashboard** SELLER (only own products in the order) and DELIVERY BOY (only his associated sellers product)

- **Custom Fields** ['ARRAY_product_name',ARRAY_product price,'ARRAY_product_details','TIME of delivery','ORDER STATUS']
 


[<< Back to Table of Contents](readme.md)