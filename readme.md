YuccaSay.com (App Features)
===================
This file is feature list of for **YuccaSay.com** 
![UML diagram of YuccaSay](http://i.imgur.com/L2aEVm4.png)
----------


User Types (i.e user_roles)
-------------

StackEdit stores your documents in your browser, which means all your documents are automatically saved locally and are accessible **offline!**

> **Definition:**

> - ***seller_single_person*** is the individual/housewife who will put online their products for selling.They DON’T represent any SHOP. Will be called in this file as **SELLER**

> - ***delivery_boy*** who will take the order from the seller_single_person ’s location and delivery it to the customer user.Will be called in this file as **DELIVERY BOY**

> - ***customer_registered***  End user who browses the products or seller and order the products added  in his cart.Will be called in this file as **REGISTERED CUSTOMER**
> 
> - ***customer_guest***  End user who browses the products or seller and order the products added  in his cart.Will be called in this file as **GUEST CUSTOMER**

---------- 
 
#### SELLER
> - Able to list his/her product.
> - Choose the delivery_boy from the dropdown from available delivery boys.
> - Receives instant order detail.
> - can change the order status to [‘processing’,’ready_for_delivery’,’completed’,’cancelled_by_seller_single_person’,’awaiting_payment_from_delivery_boy’]

----------


#### DELIVERY BOY
> - Able to list his/her rate for delivery (currently is crossings) per order or per month (weight wise).
> - Choose the delivery_boy from the dropdown from available delivery boys.
> - Receives instant order detail and also gets notified when the order status is set to ‘ready_for_delivery’ or ‘cancelled_by_seller_single_person’
> - Can change the order status [‘in_transit’,’delivered’,’returned_by_customer’,’customer_not_found’,’flat_locked’]

----------

#### CUSTOMER (REGISTERED)
> - Able to save his/her address,date and time for delivery.

> - Able to LIKE seller.

> - Able to see his order history.
> - track his current not_completed orders.
> - report abuse the seller on his/ order with 24 hour of delivery.


----------

#### CUSTOMER (GUEST)
> - Able to tell date and time for delivery.
> - track his current not_completed orders with ORDER ID.
> - report abuse the seller on his/ order with 24 hour of delivery with ORDER ID.

----------
