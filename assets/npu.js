$(document).ready( function () {
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }


    var actionOk = $('.action-ok');
    var actionCancel = $('.action-cancel');
    var popup = $('#npu-pop-up');

    actionOk.on('click', function () {
        setCookie('gdpr', 'Agree', 365);
        popup.fadeOut();
    })

    actionCancel.on('click', function () {
        setCookie('gdpr', 'No Agree', 1);
        popup.fadeOut();
    })

    if(!getCookie('gdpr')){
        popup.addClass('display-flex')
    }

});