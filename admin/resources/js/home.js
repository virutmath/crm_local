$(function(){
    var iframe_height = $(window).height() - 56;
    $('.menu-function').click(function(){
        $(this).toggleClass('active');
    });
    $('#main-content').height(iframe_height);
})
// ham dong mwindow
function communicateParentWindow($action, $data) {
    switch ($action) {
        case 'closeSetting' :
            $('.mwindow-close').trigger('click');
            break;
        case 'activeTab' :
            var moduleID = $data;
            var activeTab = $('#menu').find('[data-module-id='+moduleID+']');
            if(activeTab.length) {
                $('#menu').find('a.active').removeClass('active');
                activeTab.find('a').addClass('active');
            }
    }
}