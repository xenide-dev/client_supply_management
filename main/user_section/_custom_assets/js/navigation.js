$(document).ready(function(){
    var CURRENT_URL = window.location.href.split('#')[0].split('?')[0];
    CURRENT_URL = CURRENT_URL.split('/');
    var $TOP_MENU = $('#navbarCollapse');
    $TOP_MENU.find('a[href="' + CURRENT_URL[CURRENT_URL.length - 1] + '"]').parent('li').addClass('active');
})