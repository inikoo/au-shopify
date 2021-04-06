


window.get_title=function (value){
    return window["translations"].title[value];
}

window.set_breadcrumb=function (title){
    myTitleBar.set({
        title: title,
    });

}
