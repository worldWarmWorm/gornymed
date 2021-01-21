/**
 * Script for AddToButtonWidget widget of DCart module
 * 
 * @use DCart.js
 * @use jquery-impromptu.3.2.min.js
 */
$(document).on("click", ".dcart-add-to-cart-btn", function(e) {
    e.preventDefault();
	DCart.add($(this).attr("href"), $(this).attr("data-dcart-attributes"), e);
});
