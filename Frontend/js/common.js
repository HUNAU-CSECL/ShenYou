$(function() {
    //打开登录框
    $('#login_a').click(function() {
        openModal($('#login'));
    });
    //打开注册框
    $('#enroll_a').click(function() {
        openModal($('#enroll'));
    });
    $('#unkownPassword').click(function() {
        //alert('af');
        $('#login').modal('hide');
        $('#unkownPasswordMd').modal('show');
    });
});
//导航的登录注册显示验证
$(function() {
    if ($.cookie("userId")) {
        // 隐藏登录注册
        $('.incook').hide();
        // 显示用户名、 退出或管理系统
        $('#login_user a').text($.cookie('name'));
        $('#login_user a').attr('href', 'member.html');
        if ($.cookie('type') == 1) {
            $('#admins a').attr('href', '../../Backend/fore/update.html');
            $('#admins').show();
        }
    } else {
        $('.oncook').hide();
    }
    //退出
    $("#logout a").click(function() {
        $.removeCookie('userId');
        window.location.reload();
    });
});
//登录注册数据校验和高校筛选
$(function() {
    var url = window.location.href;
    $("#login-btn").click(function() {
        var mail = $.trim($('#input1').val());
        var password = $('#input2').val();
        if (mail.indexOf("@") == -1) {
            $('#input1-d').show();
        } else if (password.length < 8) {
            $('#input1-d').hide();
            $('#input2-d').show();
        } else {
            $('#input1-d').hide();
            $('#input2-d').hide();
            $.ajax({
                type: "POST",
                url: "../back/login.php",
                data: {
                    mail: mail,
                    password: password,
                },
                cache: false,
                dataType: 'json',
                success: function(json) {
                    if (json == 'false') {
                        $('#input2-d').show();
                    } else {
                        $.cookie('userId', json[0].id);
                        $.cookie('name', json[0].name);
                        $.cookie('mail', json[0].mail);
                        $.cookie('type', json[0].type);
                        window.location.reload();
                    }
                }
            });
        }
    });
    //注册密码是否长于八位数
    $("#inputPassword1").focusout(function() {
        var password = $('#inputPassword1').val();
        if (password.length < 8) {
            $('#inputPassword1-c').show();
        } else {
            $('#inputPassword1-c').hide();
        }
    });
    //密码与确认密码验证
    $("#inputPassword2").focusout(function() {
        var password = $('#inputPassword1').val();
        var sur_password = $('#inputPassword2').val();
        if (password !== sur_password) {
            $('#inputPassword2-d').show();
        } else {
            $('#inputPassword2-d').hide();
        }
    });
    //所在高校提示选择
    $('#inputEmail7').keyup(function() {
        var name = $.trim(this.value);
        if (name !== "") {
            $.ajax({
                type: "POST",
                url: "../back/searchSomeSch.php",
                data: "name=" + name,
                cache: false,
                dataType: 'json',
                success: function(json) {
                    if (json) {
                        var lists = "<ul>";
                        $.each(json, function() {
                            lists += "<li data-value=" + this.id + ">" + this.name + "</li>";
                        });
                        lists += "</ul>";
                        $("#regist-searchBox").html(lists).show();
                    } else {
                        $("#regist-searchBox").hide();
                    }
                    //点击body搜索提示框消失
                    $("body").on('click', function() {
                        $("#regist-searchBox").hide();
                    });
                },
            });
        } else {
            $("#regist-searchBox").hide();
        }
    });
    //点击注册高校选择提示框搜索框获取其值
    $('#inputEmail7').focusout(function() {
        $.each($("#regist-searchBox li"), function(i, v) {
            $(v).on("click", function() {
                $('#inputEmail7').val($(v).text());
                $('#inputEmail7').attr('data-value', $(v).attr('data-value'));
                $("#regist-searchBox").hide();
            });
        });
    });
    //验证邮箱是否被注册
    $("#input4").focusout(function() {
        var mail = $.trim($('#input4').val());
        if (mail.indexOf("@") !== -1) {
            $.ajax({
                type: "POST",
                url: "../back/ver_mail.php",
                data: {
                    mail: mail,
                },
                cache: false,
                dataType: 'json',
                success: function(json) {
                    if (json == 'true') {
                        //邮箱已经被注册样式处理
                        $('#input4-d').show();
                        $('#send-code').attr("disabled", true);
                    } else if (json == 'false') {
                        $('#input4-d').hide();
                        $('#send-code').attr("disabled", false);
                    }
                }
            });
        }
    });
    // //填写邮箱后发送验证码120s
    $("#send-code").click(function() {
        var ver_code = randomNum();
        localStorage.code = ver_code;
        var user_mail = $.trim($('#input4').val());
        localStorage.mail = user_mail;
        if (user_mail.indexOf("@") !== -1) {
            $.ajax({
                type: "POST",
                url: "../back/sendMail.php",
                data: {
                    user_mail: user_mail,
                    ver_code: ver_code,
                },
                cache: false,
                dataType: 'json',
                success: function(json) {
                    time($("#send-code"));
                }
            });
        }
    });
    //提交注册
    $("#register-btn").click(function() {
        var name = $.trim($('#input3').val());
        var password = $('#inputPassword1').val();
        var sur_password = $('#inputPassword2').val();
        var sch_id = $("#inputEmail7").attr("data-value");
        var mail = $.trim($('#input4').val());
        var in_ver_code = $.trim($('#inputEmail8').val());
        if (name !== '' && password !== '' && sch_id !== '' && mail !== '' && in_ver_code !== '' && sur_password !== '' && password.length > 7) {
            //验证验证码是否正确
            if (in_ver_code == localStorage.code && mail == localStorage.mail) {
                $.ajax({
                    type: "POST",
                    url: "../back/register.php",
                    data: {
                        name: name,
                        mail: mail,
                        password: password,
                        sch_id: sch_id,
                    },
                    cache: false,
                    dataType: 'json',
                    success: function(json) {
                        if (json == 'occupied') {
                            //邮箱已经被注册样式处理
                            $('#input4-d').show();
                            $('#send-code').attr("disabled", true);
                        } else {
                            $.cookie('userId', json);
                            $.cookie('name', name);
                            $.cookie('mail', mail);
                            $.cookie('type', 0);
                            alert("注册成功");
                            window.location.reload();
                        }
                    }
                });
            } else {
                //验证失败样式变化
                $('#inputEmail8-d').show();
            }
        }
    });
    $('.close').click(function() {
        $('.reset').trigger("click");
    });
});
//忘记密码和修改密码弹框
$(function() {
    //验证邮箱是否被注册
    $("#input5").focusout(function() {
        var mail = $.trim($('#input5').val());
        if (mail.indexOf("@") !== -1) {
            $.ajax({
                type: "POST",
                url: "../back/ver_mail.php",
                data: {
                    mail: mail,
                },
                cache: false,
                dataType: 'json',
                success: function(json) {
                    if (json == 'false') {
                        //邮箱未被注册样式处理
                        $('#input5-d').show();
                        $('#send-code-agin').attr("disabled", true);
                    } else if (json == 'false') {
                        $('#input5-d').hide();
                        $('#send-code-agin').attr("disabled", false);
                    }
                }
            });
        }
    });
    // //填写邮箱后发送验证码120s
    $("#send-code-agin").click(function() {
        var ver_code = randomNum();
        localStorage.code = ver_code;
        var user_mail = $.trim($('#input5').val());
        localStorage.mail = user_mail;
        if (user_mail.indexOf("@") !== -1) {
            $.ajax({
                type: "POST",
                url: "../back/sendMail.php",
                data: {
                    user_mail: user_mail,
                    ver_code: ver_code,
                },
                cache: false,
                dataType: 'json',
                success: function(json) {
                    time($("#send-code-agin"));
                }
            });
        }
    });
    //注册密码是否长于八位数
    $("#inputPassword3").focusout(function() {
        var password = $('#inputPassword3').val();
        if (password.length < 8) {
            $('#inputPassword3-d').show();
        } else {
            $('#inputPassword3-d').hide();
        }
    });
    //密码与确认密码验证
    $("#inputPassword4").focusout(function() {
        var password = $('#inputPassword3').val();
        var sur_password = $('#inputPassword4').val();
        if (password !== sur_password) {
            $('#inputPassword4-d').show();
        } else {
            $('#inputPassword4-d').hide();
        }
    });
    //提交修改密码
    $("#modify").click(function() {
        var password = $('#inputPassword3').val();
        var sur_password = $('#inputPassword4').val();
        var mail = $.trim($('#input5').val());
        var in_ver_code = $.trim($('#inputEmail9').val());
        if (password !== '' && mail !== '' && in_ver_code !== '' && sur_password !== '' && password.length > 7) {
            //验证验证码是否正确
            if (in_ver_code == localStorage.code && mail == localStorage.mail) {
                $.ajax({
                    type: "POST",
                    url: "../back/modify.php",
                    data: {
                        mail: mail,
                        password: password,
                    },
                    cache: false,
                    dataType: 'json',
                    success: function(json) {
                        if (json == 'occupied') {
                            //邮箱已经未被注册样式处理
                            $('#input5-d').show();
                            $('#send-code-agin').attr("disabled", true);
                        } else {
                            alert('密码修改成功！');
                            $('#myModal').modal('hide');
                        }
                    }
                });
            } else {
                //验证失败样式变化
                $('#inputEmail9-d').show();
            }
        }
    });
    $('.close').click(function() {
        $('.reset').trigger("click");
    });
});