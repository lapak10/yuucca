jQuery(function(){

//============================================================================
	jQuery('#farji_modal_table .plus_one').on("click",function () {
    
    var el = $(this).closest('tr').find('span.quantity');
    var el_price = $(this).closest('tr').find('span.price');
    var el_total_amount = $(this).closest('tr').find('span.total_amount');

    var el_total_amount_price = parseInt(el_total_amount.text());
	var quantity = parseInt(el.text());
    var el_original_price = parseInt(el_price.text());


    quantity = quantity + 1;
    
    el_total_amount.text(quantity*el_original_price);
	el.text(quantity);
	$(this).closest('tr').find('input.product_quantity_hidden').val(quantity);
    farji_modal_table_total_calculate();

});
//============================================================================
jQuery('#farji_modal_table .minus_one').on("click",function () {
    
    var el = $(this).closest('tr').find('span.quantity');
    var el_price = $(this).closest('tr').find('span.price');
    var el_total_amount = $(this).closest('tr').find('span.total_amount');

    var el_total_amount_price = parseInt(el_total_amount.text());
	var quantity = parseInt(el.text());
    var el_original_price = parseInt(el_price.text());

    	if(quantity>1){
    		quantity = quantity - 1;
    	}else{
    		return;
    	}
   el_total_amount.text(quantity*el_original_price);
   el.text(quantity);

   	$(this).closest('tr').find('input.product_quantity_hidden').val(quantity);


   farji_modal_table_total_calculate();

});

//============================================================================

function farji_modal_table_total_calculate (){
	var sum=0

jQuery('#farji_modal_table span.total_amount').each(function(i,e){
sum = sum + parseInt(jQuery(e).text());

})

jQuery('#edit_cart span.modal_total_amount').text('(total Rs '+sum+')');
}
//============================================================================










//============================================================================
});