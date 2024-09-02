
//===============================
document.addEventListener("DOMContentLoaded", function (e) {
    // default
    var els = document.querySelectorAll(".selectize");
    els.forEach(function (select) {
        NiceSelect.bind(select);
    });
});

//
document.addEventListener("DOMContentLoaded", function (e) {
    // seachable 
    var options = {
        searchable: true
    };
    var els = document.querySelectorAll(".search-select")
    els.forEach(function(select) {
        NiceSelect.bind(select, options);
    })
})
