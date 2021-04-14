/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Wed, 07 Apr 2021 01:23:49 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

window.createShopifyProduct = function (id) {

    return {
        id:id,
        isLoading:false,
        submitAction($dispatch,id=this.id) {
            this.isLoading = true;
            fetch('/shopify_products/create?user_portfolio_item_id='+id)
                .then((res) => res.json())
                .then((res) => {

                    if(res.success){

                        document.getElementById("portfolio_item_"+id).getElementsByClassName("formatted_status")[0].innerHTML ='test';
                        document.getElementById("portfolio_item_"+id).getElementsByClassName("action")[0].innerHTML ='';
                        this.$store.product_stats.portfolio_items='xx';

                    }


                });
        },
    };


}

