    document.observe('dom:loaded',function(){
        $$('.raffle-index-index .product-info .actions button span span').each(function(el){
            el.update('Purchase Tickets');
        });
    });