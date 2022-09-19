-- Short Description --
Changes the order reference AERTDF to 000001.
Without editing core files, or overiding any classes.
It simply overwrites prestashops reference with it's own.

-- Features --

* Number refrence as 000001
* Customizable length of reference
* Posibilty to increase the next order reference
* Support multiple order eg. 000001#1 and 000001#2
* Module can be switched of, not must to disable / uninstall
* Does not alter prestashop tabels or core files
* v1.1 Option to set increase step
* v1.1 Option to set random increase step
* v1.3 Option to set a two letter prefix
* v1.6.0 Same reference can be used in all shop, or diffrent in each shop

-- Update notice from diffrent versions --

** UPDATE NOTICE TO 1.1**
If you change the increase step / use random increase there will be one order delay untill it takes effect.
That is beacuse the next order reference is allready set at last order, so next order reference is set after the last order is made,
so that is why the step increase won't show untill the 2nd order.
However, AFTER the 1st new order has been placed, you can see the next number in the confiuration of the module,
and there you can see it has been increased using the new step you configured.

** UPDATE NOTICE  FOR MULTISTORES TO 1.2 **
Changing the config to use global values, will force unique reference on all orders.
It does not matter what store the order is from, the reference will be increased at all shops.
Due to this, store owners need to save the reference number again in module configuration.
The module should automaticlly get the highest value of all shops, so it should just be to press the save button.
But it might be a good idea to double check the latest ordernumber anyway.

** UPDATE NOTICE FOR MULTISHOPS FROM 1.2 TO 1.5 **
Config is now back as it was in 1.1, allowing for diffrent reference in diffrent shops.
There is a automatic upgrade file, that will copy your current settings to ALL shops.


-- Install --

1. Download the module from the site
2. Upload to your PrestaShop
3. Install the module
4. Configuration
  4a. Set reference number (default 1)
  4b. Set length of refrence, module will automaticly pad with 0's if needed in reference (default 6)
  4c. Enable prefix (Optional)
  4d. Enable Use numeric order reference switch
  4e. Save configuration
5. Hook position
  It's recomended to move this module to the first position in Modules > Positions > actionValidateOrder.
  This should stop any module / PrestaShop from sending the old reference.
