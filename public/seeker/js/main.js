$(function(){

    $(".js_phone_format").on("input", function() {
        var val = this.value.replace(/\D/g, '');
        val = val.replace(/^(\d{3})(\d{1,2})/, '$1-$2');
        val = val.replace(/^(\d{3})-(\d{3})(.+)/, '$1-$2-$3');
        this.value = val.substring(0, 12);
    });
    $("#id_zip").on("input", function() {
        var val = this.value.replace(/\D/g, '');
        this.value = val.substring(0, 5);
    });

});