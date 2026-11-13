# How to lock a customer account in magento 2
- When you work on a project, the client requires you to block a customer account from the backend, the client means preventing them from using their account. Then the customer can't be able to log in to their account. By default, Magento hasn't this functionality. You don't know how to complete this task, you are searching for a good solution. Today I show you the best codes to complete your task.

- We have the scenario:
We will create a new customer attribute called "Blocked", it will have two options (Yes/No). If this attribute is set to "Yes" when a customer tries to log in, they will get an inform similar to this "Your account is blocked for the security reason, please contact us for details."

# Screenshot
![ScreenShot](https://github.com/php-cuong/customer-login/blob/main/Screenshot/lock-a-customer-account.gif)
![ScreenShot](https://github.com/php-cuong/customer-login/blob/main/Screenshot/muiltiple-rows.gif)

# See the video How to lock a customer account in magento 2 here.
https://www.youtube.com/watch?v=tBGagDLXUAQ&list=PL98CDCbI3TNvPczWSOnpaMoyxVISLVzYQ

# How to add a new dynamic row system configuration in the Magento 2 admin.
https://www.youtube.com/watch?v=GZ-I9SdGLT0&list=PL98CDCbI3TNvPczWSOnpaMoyxVISLVzYQ

# After instalation execute the query to change the source model to your attribute
```UPDATE `eav_attribute` SET `source_model` = 'PHPCuong\\CustomerLogin\\Model\\Customer\\Attribute\\Source\\Reason' WHERE `eav_attribute`.`attribute_code` = 'reason';```
This is to not impact when you generate an image that is direct connect to your production BD, so after deploy you need to change the source_model for attribute reason

The admin customer grid filters for "Blocked" and "Reason" are enabled automatically by the data patch `Setup/Patch/Data/MakeAttributesFilterableInGrid.php` (no manual query needed, also on stores that already had the module installed).
