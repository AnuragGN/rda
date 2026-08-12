


function navExtras(){
    var widthX = $(window).width();
    var width = window.innerWidth;
    var margin = 0;
    if ( width >= 1200) { // 1140px
        // console.log("A: 20");
        margin = 20 + (width - 1140) / 2;
    } else if (width >= 992) { // 960
        // console.log("B: 20");
        margin = 20 + (width - 960) / 2;
    } else if (width >= 768) { // 720
        // console.log("C: 20");
        margin = 20 + (width - 720) / 2;
    } else if (width >= 576) { // 540
        // console.log("D: 0");
        margin = (width - 540) / 2;
    } else { // 540
        // console.log("E: 24");
        margin = 24;
    }
    $('#nav-extras').css('right', margin);
    // console.log("widthX:"  + widthX );
    // console.log("width:"  + width );
    // console.log("margin:"  + margin );
}

function poweredBy(){
    var widthX = $(window).width();
    var width = window.innerWidth;
    var margin = 0;
    if ( width >= 1200) { // 1140px
        // console.log("A: 20");
        margin = 20 + (width - 1140) / 2;
    } else if (width >= 992) { // 960
        // console.log("B: 20");
        margin = 20 + (width - 960) / 2;
    } else if (width >= 768) { // 720
        // console.log("C: 20");
        margin = 20 + (width - 720) / 2;
    } else if (width >= 576) { // 540
        // console.log("D: 0");
        margin = (width - 540) / 2;
    } else { // 540
        // console.log("E: 24");
        margin = 24;
    }
    $('#nav-powered').css('right', margin);
    // console.log("widthX:"  + widthX );
    // console.log("width:"  + width );
    // console.log("margin:"  + margin );
}

function sageCollapsible(e) {
    var tag = e.getAttribute('data-child-id');
    // same => var tag = $(e).data('child-id');
    if (!tag) return;
    var element = document.getElementById(tag);
    if (!element) return;

    var extraCls = 'collapsible-child-visible';
    if (element.style.display === "none") {
        $(e).addClass(extraCls);
        // element.style.display = "block";
        $('#' + tag).show(400);
    } else {
        $(e).removeClass(extraCls);
        $('#' + tag).hide(400);
        // element.hide(400);
        // element.style.display = "none";
    }
}

function floatWithCommas(amount) {
    // parseFloat(Math.round(num3 * 100) / 100).toFixed(2);
    amount = parseFloat(amount).toFixed(2);
    var parts = amount.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

$('body').on('click', '.js_toggle_pool_values', function (e) {
  
    var target = '#' + $(this).attr('data-target-id');
    $(target).toggle('slow', 'swing');

    var isClosed = false;
    $(this).find("#id_pool_open").filter(function() {
        isClosed = $(this).css("display") == "none";
        console.log("isClosed in: " + isClosed);
    });
    if (isClosed) {
        $(this).find('#id_pool_open').show();
        $(this).find('#id_pool_closed').hide();
    } else {
        $(this).find('#id_pool_open').hide();
        $(this).find('#id_pool_closed').show();
    }
    return false;
});

$('body').on('click', '.js_toggle_pool_values_fa_fund', function (e) {
    
    var isClosed = false;
    $(this).find("#id_pool_open").filter(function() {
        isClosed = $(this).css("display") == "none";
        
        if(isClosed == true) {

            $('.fund_fa').hide('slow', 'swing');
            $(target).toggle('slow', 'swing');
            $('.id_pool_open_fa').hide();
            $('.id_pool_closed_fa').show();
        }
    });

    var target = '#' + $(this).attr('data-target-id');
    $(target).toggle('slow', 'swing');

    if (isClosed) {

        $(this).find('#id_pool_open').show();
        $(this).find('#id_pool_closed').hide();

    } else {

        $(this).find('#id_pool_open').hide();
        $(this).find('#id_pool_closed').show();
    }
    return false;
});


$('body').on('click', '.js_toggle_pool_values_fa_fund_org', function (e) {
    
    var isClosed = false;
    $(this).find("#id_pool_open").filter(function() {
        isClosed = $(this).css("display") == "none";
        
        if(isClosed == true) {

            $('.fund_org_fa').hide('slow', 'swing');
            $(target).toggle('slow', 'swing');
            $('.id_pool_open_fa_org').hide();
            $('.id_pool_closed_fa_org').show();
        }
    });

    var target = '#' + $(this).attr('data-target-id');
    $(target).toggle('slow', 'swing');

    if (isClosed) {

        $(this).find('#id_pool_open').show();
        $(this).find('#id_pool_closed').hide();

    } else {

        $(this).find('#id_pool_open').hide();
        $(this).find('#id_pool_closed').show();
    }
    return false;
});

// fund statement fund-links
$('.js_external_fund_url').on('click', function () {
    var fund = $(this).data('fund');
    openInNewTab('https://finance.yahoo.com/quote/' + fund);
    // openInNewTab('https://finance.yahoo.com/lookup?s=' + fund);
});

function openInNewTab(url) {
    var win = window.open(url, '_blank');
    win.focus();
}

function showAlert(title, content) {
    $.alert({
        title: title,
        content: content,
        animation: 'scale',
        closeAnimation: 'scale',
        backgroundDismiss: true,
        buttons: {
            okay: {
                text: 'Close',
                btnClass: 'btn-blue',
                action: function(){
                    // do nothing
                }
            }
        }
    });
}

// https://stackoverflow.com/questions/7790561/how-can-i-make-the-html5-number-field-display-trailing-zeroes
function setTrailingZeros(item) {
    console.log('on setTrailingZeros');
    if (item.value === '') {
        return;
    }
    item.setAttribute('type', 'text');
    if (item.value.indexOf('.') === -1) {
        item.value = item.value + '.00';
    }
    while (item.value.indexOf('.') > item.value.length - 3) {
        item.value = item.value + '0';
    }
    item.value = Number.parseFloat(item.value).toFixed(2);
}

$(function() {
    var amountInput = document.getElementById('id_floating_amount');

    if (amountInput && amountInput != undefined) {

        amountInput.addEventListener('blur', function () {
            setTrailingZeros(this);
        });

        amountInput.addEventListener('focus', function () {
            // console.log('on focus');
            // $('#ide_floating_amount').hide();
            this.setAttribute('type', 'number');
        });

        setTrailingZeros(amountInput);
    }
});


// Floating amount with comma and decimal
$(function() {
    var textAmountInput = document.getElementById('id_text_float_amount');
    if (!textAmountInput || textAmountInput == undefined) return;

    // user moves away from this field
    textAmountInput.addEventListener('blur', function () {
        var item = this;
        if (item.value == '') return;
        var value = parseFloat(item.value.replace(/,/g, '')).toFixed(2);
        if (!value || value == 'NaN' || isNaN(value)) {
            item.value = '';
            return;
        }

        var parts = value.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        value = parts.join(".");
        item.value = value;
    });


    $('#id_text_float_amount').on("keyup", function(event) {

        // When user select text in the document, abort.
        var selection = window.getSelection().toString();
        if ( selection !== '' ) return;

        // When the arrow keys are pressed, abort.
        if ( $.inArray( event.keyCode, [38,40,37,39] ) !== -1 ) return;

        // Get the value.
        var item = $(this);
        var value = item.val();
        value = value.replace(/[^\d.]/g, "");
        item.val(value);
    });

});

function isValidEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function formatPhoneNumber(item=null){
    if (!item) item = $("#id_phone");
    if (!item) return;

    var val = item.val().replace(/\D/g, '');
    val = val.replace(/^(\d{3})(\d{1,2})/, '$1-$2');
    val = val.replace(/^(\d{3})-(\d{3})(.+)/, '$1-$2-$3');
    console.log("final value.." + val);
    item.val(val.substring(0, 12));
}

$(function(){
    // handing it in PHP
    // formatPhoneNumber(null);

    $("#id_phone").on("input", function() {
        var val = this.value.replace(/\D/g, '');
        val = val.replace(/^(\d{3})(\d{1,2})/, '$1-$2');
        val = val.replace(/^(\d{3})-(\d{3})(.+)/, '$1-$2-$3');
        this.value = val.substring(0, 12);
    });
    $("#id_zip").on("input", function() {
        var val = this.value.replace(/\D/g, '');
        this.value = val.substring(0, 5);
    });
    $(".phone_number").on("input", function() {
        var val = this.value.replace(/\D/g, '');
        val = val.replace(/^(\d{3})(\d{1,2})/, '$1-$2');
        val = val.replace(/^(\d{3})-(\d{3})(.+)/, '$1-$2-$3');
        this.value = val.substring(0, 12);
    });
    $(".org_ein").on("input", function() {
        var val = this.value.replace(/\D/g, '');
        val = val.replace(/^(\d{2})(\d{1,2})/, '$1-$2');
        val = val.replace(/^(\d{2})-(\d{7})(.+)/, '$1-$2');
        this.value = val.substring(0, 10);
    });
    $(".address_zip").on("input", function() {
        var val = this.value.replace(/\D/g, '');
        this.value = val.substring(0, 5);
    });
    $(".ssn").on("input", function() {
        var val = this.value.replace(/\D/g, '');
        val = val.replace(/^(\d{3})(\d{1,2})/, '$1-$2');
        val = val.replace(/^(\d{3})-(\d{2})(.+)/, '$1-$2-$3');
        this.value = val.substring(0, 11);
    });
    $(".js_only_aphabet").on("keypress, keydown", function(event) {
        if (event.shiftKey || event.ctrlKey || event.altKey) {
            // not allow copy paste
            event.preventDefault();
        } else {
            var regex = new RegExp("^[a-zA-Z]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        }
    });

});

function highlighttabs(tabs, tabDefault) {
    var found = false;
    var url = decodeURIComponent(window.location.href);
    console.log('url: '  + url);

    var index = tabs + " ul li a";
    console.log('index: ' + index);

    $(tabs + " ul li a" ).each(function() {
        var href = $(this).attr('href');

        if (url.indexOf(href) != -1) {
            if (found == true) $(tabs + " ul li a" ).removeClass("active");
            found = true;
            $( this ).addClass("active");
        } else {
            $(this).removeClass("active");
        }
    });
    if (!found) $(tabDefault).addClass("active");
}

function setActiveTab(tabs, tabDefault) {
    return;
    var found = false;
    var url = decodeURIComponent(window.location.href);
    $(tabs + " li a" ).each(function() {
        var href = $(this).attr('href');
        if (url.indexOf(href) != -1) {
            if (found == true) $(tabs + " li a" ).removeClass("active");
            found = true;
            $( this ).addClass("active");
        }
    });
    if (!found) $(tabDefault).addClass("active");
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
    });
});

// download file
$('.js_confirm_file_download').on('click', function (e) {
    e.preventDefault();  //stop the browser from following

    var fileUrl = $(this).data("href");
    var msg = $(this).data("message");
    if (msg == undefined || msg == null) {
        msg = "Download the performance PDF file?";
    }
    var message = "<div style='text-align: center'>" + msg + "</div><hr class='mb-0'>";

    $.confirm({
        columnClass: 'small',
        title: '',
        content: message,
        buttons: {
            no: {
                text: 'Cancel',
                btnClass: 'btn-light',
                keys: ['enter', 'shift'],
                action: function(){}
            },
            yes: {
                text: 'Download',
                btnClass: 'btn-accent',
                keys: ['enter', 'shift'],
                action: function(){
                    // console.log("fileUrl: ", fileUrl);
                    window.open(fileUrl, '_blank');
                }
            }
        }
    });

    return false;
});

    //var fileUrl = $(this).data("href");
    //console.log("fileUrl: ", fileUrl);
    //window.open(fileUrl, '_blank');
    // window.location.href = fileUrl;
//});

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};

// confirmation, cancel grant
$('.js_cancel_recurring_grant').on('click', function(){
    var item = this;
    $(item).closest(".cart-grant").addClass('opacity40');
    $.confirm({
        // title: 'Cancel Recommendation?',
        title: '',
        content: 'Are you sure you want to cancel this grant recommendation?',
        // icon: 'fa fa-exclamation-circle',
        animation: 'scale',
        closeAnimation: 'scale',
        opacity: 0.5,
        buttons: {
            no: {
                text: 'No',
                btnClass: 'btn-light',
                keys: ['enter', 'shift'],
                action: function(){
                    $(item).closest(".cart-grant").removeClass('opacity40');
                }
            },
            yes: {
                text: 'YES',
                btnClass: 'btn-accent',
                keys: ['enter', 'shift'],
                action: function(){
                    window.location.href = $(item).data('href');
                }
            }
        }
    });
});
