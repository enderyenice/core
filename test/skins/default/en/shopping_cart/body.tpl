{* SVN $Id$ *}

<widget class="XLite_View_Form_Cart_Main" name="cart_form" />

  <p align=justify>The items in your shopping cart are listed below. To remove any item click "Delete Item". To place your order, please click "CHECKOUT".</p>

  <div FOREACH="cart.items,cart_id,item">
    <p>
      <widget template="shopping_cart/item.tpl" IF="item.isUseStandardTemplate()" />
      <widget module="GiftCertificates" template="modules/GiftCertificates/item.tpl" IF="item.gcid"/>
  </div>
  <img src="images/spacer.gif" class="DialogBorder" width="100%" height="1" alt="" />

  <widget template="shopping_cart/delivery.tpl">

  <table width="100%">

	  <tr>
      <td>
    		<widget template="shopping_cart/totals.tpl">
	    </td>
    </tr>

  </table>

  <img src="images/spacer.gif" class="DialogBorder" width="100%" height="1" alt="" />

  <widget template="shopping_cart/buttons.tpl">
  <widget module="GoogleCheckout" template="modules/GoogleCheckout/shopping_cart/gcheckout_notes.tpl">

<widget name="cart_form" end />

<widget module="ProductAdviser" template="modules/ProductAdviser/OutOfStock/notify_form.tpl" visible="{xlite.PA_InventorySupport}">
