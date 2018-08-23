$(function() {
    var urls = getInjectedUrls();
    // 保存
    $('#submit').click(function() {
        var data = formData($("#rest-password"));
        // 验证数据
        if (data['email'] == '') {
            layer.msg('请输入邮箱');
            $('#email').focus();
            return;
        }
        if (data['password'] == '' || data['password'].length < 6){
            layer.msg('请输入6位以上密码');
            $('#password').focus();
            return;
        }
        if (data['repassword'] == ''){
            layer.msg('请确认密码');
            $('#repassword').focus();
            return;
        }
        if (data['repassword'] != data['password']){
            layer.msg('两次密码不一致，请重新输...');
            $('#password').focus();
            return;
        }
        if (data['captcha'] == ''){
            layer.msg('请输入验证码');
            $('#captcha').focus();
            return;
        }
        $.post(urls.save, data, function (res) {
            if (res.code == 200){
                layer.alert(res.msg, function () {
                    window.location.reload();
                });
            }else{
                layer.msg(res.msg);
            }
        });
    });

    //调用监听
    monitor($("#getcode"));
    // 点击发送验证码
    $('#getcode').on('click', function () {
        // 判断手机号码
        var email = $.trim($('#email').val());
        if (email.length == 0) {
            layer.msg('邮箱没有输入');
            $('#email').focus();
            return;
        } else {
            if(isEmail(email) === false) {
                layer.msg('邮箱码不正确');
                $('#email').focus();
                return;
            }
            countDown($(this), function () {
                $.post(urls.sendCaptcha, {email: email}, function (data) {
                    layer.msg(data.msg);
                });
            });
        }

    });

    function formData(obj) {
        var data = {};
        var form = obj.serializeArray();
        $.each(form, function() {
            data[this.name] = this.value;
        });
        return data;
    }
    // 验证邮箱
    function isEmail(email) {
        var pattern = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
        return pattern.test(email);
    }
});