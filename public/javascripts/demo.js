

var primaryAccountHolderMailingAddressHeight = 0;
$(function() {
    var item = $(".form-primary-account-holder .mailing-address");
    primaryAccountHolderMailingAddressHeight = item.height() + 'px';
    item.css({height: primaryAccountHolderMailingAddressHeight});
});

$("#same-as").click(function(){
    var check = $(this).prop('checked');
    var item = $(".form-primary-account-holder .mailing-address");
    if(check == true) {
        item.css({height: '0'}); // .fadeOut();
    } else {
        item.css({height: primaryAccountHolderMailingAddressHeight});// .fadeIn();
    }
});

$(".field-password i").hover(function() {
    $('.password-help').toggle(500, 'swing');
}, function() {
    $('.password-help').toggle(500, 'swing');
});

$('body').on('click', '.js_toggle_pool_values', function (e) {
    var target = '#' + $(this).attr('data-target-id');
    $(target).toggle('slow', 'swing');

    var isClosed = false;
    $("#id_pool_open").filter(function() {
        isClosed = $(this).css("display") == "none";
        console.log("isClosed in: " + isClosed);
    });
    if (isClosed) {
        $('#id_pool_open').show();
        $('#id_pool_closed').hide();
    } else {
        $('#id_pool_open').hide();
        $('#id_pool_closed').show();
    }
    return false;
});

function onDocusign() {
    $('.btn-docusign').addClass('active');
    $('.docusign').removeClass('hide');

    $('.btn-signhere').removeClass('active');
    $('.signhere').addClass('hide');
}
function onSignHere() {
    $('.btn-docusign').removeClass('active');
    $('.docusign').addClass('hide');

    $('.btn-signhere').addClass('active');
    $('.signhere').removeClass('hide');
}

// one
$('#collapseOne').on('show.bs.collapse', function () {
    $("#arrow-right-one").addClass('hide');
    $("#arrow-down-one").removeClass('hide');
}).on('hide.bs.collapse', function () {
    $("#arrow-right-one").removeClass('hide');
    $("#arrow-down-one").addClass('hide');
});
// two
$('#collapseTwo').on('show.bs.collapse', function () {
    $("#arrow-right-two").addClass('hide');
    $("#arrow-down-two").removeClass('hide');
}).on('hide.bs.collapse', function () {
    $("#arrow-right-two").removeClass('hide');
    $("#arrow-down-two").addClass('hide');
});
// three
$('#collapseThree').on('show.bs.collapse', function () {
    $("#arrow-right-three").addClass('hide');
    $("#arrow-down-three").removeClass('hide');
}).on('hide.bs.collapse', function () {
    $("#arrow-right-three").removeClass('hide');
    $("#arrow-down-three").addClass('hide');
});
// four
$('#collapseFour').on('show.bs.collapse', function () {
    $("#arrow-right-four").addClass('hide');
    $("#arrow-down-four").removeClass('hide');
}).on('hide.bs.collapse', function () {
    $("#arrow-right-four").removeClass('hide');
    $("#arrow-down-four").addClass('hide');
});
