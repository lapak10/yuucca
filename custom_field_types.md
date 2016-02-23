[<< Back to Table of Contents](readme.md)

####YuccaSay.com
> Custom fields we going to need for each CPT (i.e yuccasay_product and yuccasay_order)

Custom fields
-------------

Defining a CPT (i.e Custom Post Type ) is like defining a new room which tells Wordpress that we need a room for our data, so WP will create a new room for us. Next to make it useful for us we need to define further custom fields which is like defining that for that room we will be need a double bed, one table, one chair and one TV etc etc.

So that WP can remember that in addition to creating a new ROOM for every new posts, WP has to put all those set of furnitures into it for make it usable for the user.

yuccasay_product
=======
*POST STATUS* -> array('pending_approval','approved','unavailable','available')

- **TITLE (default in wordpress):** Name of the product
- **SHORT TAG LINE** Short Description of your product
- **DESCRIPTION** Description of product
- **PRICE** Price of the product in INR
- **FEATURED IMAGE** Thumbnail for the product
- **TAG** for tagging product if it doesnt belongs to any category
- **PRODUCT CATEGORY** for classifying product to make it easier for customer to search and look for
- **GALLERY(May be in future)** for a basic product slider
 
------
yuccasay_order
=======
*POST STATUS* ->[‘processing’,’ready’,’completed’,’cancelled_by_seller_single_person’,’awaiting_payment_from_delivery_boy’]
[‘in_transit’,’delivered’,’returned_by_customer’,’customer_not_found’,’flat_locked’]

- **ORDER ID (self generating):** ID of the order
- **PRODUCT [ARRAY]** Array of Product containg in array with thier respective prices
- **Date of Delivery** Date of delivery according to the CUSTOMER
- **ADDRESS** Shipping Address
- **PRODUCT CATEGORY** for classifying product to make it easier for customer to 
- **NOTE** CUSTOMER optional note for the order
- **ORDER CATEGORY (may be in future)** Time boung Urgent or Normal order

[<< Back to Table of Contents](readme.md)