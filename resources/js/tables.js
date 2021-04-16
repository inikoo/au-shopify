/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Wed, 07 Apr 2021 01:23:49 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */


window.table = function (tableId) {
    return {
        selectedOption: "",
        searchValue: '',
        page: 1, limit: 10, from: 0, to: 0, total: 0, items: null, isLoading: false, previousPage: 1, nextPage: null, lastPage: 0,

        fetchData(page = this.page) {
            this.page = page
            this.isLoading = true;
            const url=this.$store.tables[tableId].url+'?'+ new URLSearchParams({
                elements: JSON.stringify(this.$store.tables[tableId].open),
                page: page
            })
            fetch(url)
                .then((res) => res.json())
                .then((res) => {

                    console.log(res)

                    this.isLoading = false;

                    //this.previousPage = this.page === 1 ? this.previousPage : this.page - 1
                    //this.nextPage = this.page + 1
                    //this.lastPage = Math.floor(this.total / this.limit)

                    this.items = res.data;
                    this.from = res.from;
                    this.to = res.to;
                    this.total = res.total;


                });
        },
    };
}

