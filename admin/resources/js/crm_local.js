function synchronize_data() {
    setInterval(function(){
        $.ajax({
            type: 'get',
            cache: 'false',
            url: 'http://static.khang.vn:8080/pictures/dotted.gif?t=' + Date.now(),
            success: function (resp) {
                $.get('/admin/synchronize.php', function () {
                    console.log('synchronize done!');
                });
            },
            error: function () {
                console.log('synchronize fail!');
            }
        });
    },30000);
}