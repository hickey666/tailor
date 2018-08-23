$(function () {
    $("#delete").click(function () {
        var id = $(this).attr('data-id');
        var data = {id: id, _csrf: $("#csrf").val()};
        console.log(data);
        layer.confirm('确认删除吗？', function () {
            $.post('/customer/ajax-delete', data, function (res) {
                if (res.code == 100){
                    layer.alert(res.msg, function () {
                        window.location.href="/customer";
                    });
                }else{
                    layer.alert("删除失败，请联系管理员...");
                }
            });
        });
    });
});
