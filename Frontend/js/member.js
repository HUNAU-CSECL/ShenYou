$(document).ready(function() {
    //个人中心
    if ($.cookie("userId")) {
        //个人信息展示
        $("#userName").text($.cookie('name'));
        $("#userMail").text($.cookie('mail'));
        $.ajax({
            type: "POST",
            url: "../back/usersch.php",
            data: {
                id: $.cookie('userId'),
            },
            cache: false,
            dataType: 'json',
            success: function(json) {
                if (json) {
                    $("#userSchool").text(json);
                }
            },
        });
        //初始加载关注的高校
        var last = 2;
        var count = 0;
        $.ajax({
            type: "POST",
            url: "../back/schcared.php",
            data: {
                user_id: $.cookie('userId'),
                last: 1,
                amount: 10,
            },
            cache: false,
            dataType: 'json',
            success: function(json) {
                count = parseInt(json.count / 10) + 1;
                var scl_html = '';
                $.each(json, function(i, v) {
                    if (i < 10) {
                        scl_html += '<li class="col-md-6"><a href="school.html?name=' + v.name + '">' + v.name + '</a></li>';
                    }
                });
                $('#profile .row').append(scl_html);
                $('#scl-prev').attr('disabled', true);
                console.log(json);
            },
        });
        //加载关注的高校下一页
        $('#scl-next').on('click', function() {
            $('#scl-prev').attr('disabled', false);
            if (last <= count) {
                $.ajax({
                    type: "POST",
                    url: "../back/schcared.php",
                    data: {
                        user_id: $.cookie('userId'),
                        last: last,
                        amount: 10,
                    },
                    cache: false,
                    dataType: 'json',
                    success: function(json) {
                        $('#profile .row').html('');
                        var scl_html = '';
                        $.each(json, function(i, v) {
                            if (i < 10) {
                                scl_html += '<li class="col-md-6"><a href="school.html?name=' + v.name + '">' + v.name + '</a></li>';
                            }
                        });
                        $('#profile .row').append(scl_html);
                        //console.log(json);
                        if (last < count) {
                            last++;
                            //$('#scl-next').attr('disabled',true);
                        }
                    },
                });
            }
            //alert(last);
        });
        //加载关注的高校上一页
        $('#scl-prev').on('click', function() {
            last--;
            if (last >= 1) {
                $.ajax({
                    type: "POST",
                    url: "../back/schcared.php",
                    data: {
                        user_id: $.cookie('userId'),
                        last: last,
                        amount: 10,
                    },
                    cache: false,
                    dataType: 'json',
                    success: function(json) {
                        $('#profile .row').html('');
                        var scl_html = '';
                        $.each(json, function(i, v) {
                            if (i < 10) {
                                scl_html += '<li class="col-md-6"><a href="school.html?name=' + v.name + '">' + v.name + '</a></li>';
                            }
                        });
                        $('#profile .row').append(scl_html);
                        //console.log(json);
                        if (last == 1) {
                            last++;
                            //$('#scl-next').attr('disabled',true);
                        }
                    },
                });
            }
        });
        //初始加载关注的校友
        var slast = 2;
        var scount = 0;
        $.ajax({
            type: "POST",
            url: "../back/percared.php",
            data: {
                user_id: $.cookie('userId'),
                last: 1,
                amount: 10,
            },
            cache: false,
            dataType: 'json',
            success: function(json) {
                scount = parseInt(json.count / 10) + 1;
                var per_html = '';
                $.each(json, function(i, v) {
                    if (i < 10) {
                        per_html += '<li class="col-md-6 openMd" date-value="' + v.id + '"><a>' + v.name + '</a></li>';
                    }
                });
                $('#messages .row').append(per_html);
                $('#per-prev').attr('disabled', true);
                //console.log(json);
                $.each($('.openMd'), function(i, v) {
                    $(v).on('click', function() {
                        var id = $(v).attr('date-value');
                        $.ajax({
                            type: "POST",
                            url: "../back/caredper_info.php",
                            data: {
                                id: id,
                            },
                            cache: false,
                            dataType: 'json',
                            success: function(json) {
                                meOpenModal($('#openMd'));
                                //alert(json[0].name);
                                var spens = '';
                                $.each(json[0].jobs, function(g, h) {
                                    spens += '<span>' + h + '</span>';
                                });
                                $("#openMd .modal-body .m-name").text(json[0].name);
                                $("#openMd .modal-body .m-job").html(spens);
                                $("#openMd .modal-body .m-contact").html(json[0].intr);
                                $("#openMd .modal-body .m-time").text(json[0].time);
                                console.log(json);
                            },
                        });
                    });
                });
            },
        });
        //加载关注的校友下一页
        $('#per-next').on('click', function() {
            $('#per-prev').attr('disabled', false);
            if (slast <= scount) {
                $.ajax({
                    type: "POST",
                    url: "../back/percared.php",
                    data: {
                        user_id: $.cookie('userId'),
                        last: slast,
                        amount: 10,
                    },
                    cache: false,
                    dataType: 'json',
                    success: function(json) {
                        $('#messages .row').html('');
                        var per_html = '';
                        $.each(json, function(i, v) {
                            if (i < 10) {
                                per_html += '<li class="col-md-6 openMd" date-value="' + v.id + '"><a>' + v.name + '</a></li>';
                            }
                        });
                        $('#messages .row').append(per_html);
                        //console.log(json);
                        if (slast < scount) {
                            slast++;
                            //$('#scl-next').attr('disabled',true);
                        }
                        $.each($('.openMd'), function(i, v) {
                            $(v).on('click', function() {
                                var id = $(v).attr('date-value');
                                $.ajax({
                                    type: "POST",
                                    url: "../back/caredper_info.php",
                                    data: {
                                        id: id,
                                    },
                                    cache: false,
                                    dataType: 'json',
                                    success: function(json) {
                                        meOpenModal($('#openMd'));
                                        //alert(json[0].name);
                                        var spens = '';
                                        $.each(json[0].jobs, function(g, h) {
                                            spens += '<span>' + h + '</span>';
                                        });
                                        $("#openMd .modal-body .m-name").text(json[0].name);
                                        $("#openMd .modal-body .m-job").html(spens);
                                        $("#openMd .modal-body .m-contact").html(json[0].intr);
                                        $("#openMd .modal-body .m-time").text(json[0].time);
                                        console.log(json);
                                    },
                                });
                            });
                        });
                    },
                });
            }
            //alert(last);
        });
        //加载关注的高校上一页
        $('#per-prev').on('click', function() {
            slast--;
            if (slast >= 1) {
                $.ajax({
                    type: "POST",
                    url: "../back/percared.php",
                    data: {
                        user_id: $.cookie('userId'),
                        last: slast,
                        amount: 10,
                    },
                    cache: false,
                    dataType: 'json',
                    success: function(json) {
                        $('#messages .row').html('');
                        var per_html = '';
                        $.each(json, function(i, v) {
                            if (i < 10) {
                                per_html += '<li class="col-md-6 openMd" date-value="' + v.id + '"><a>' + v.name + '</a></li>';
                            }
                        });
                        $('#messages .row').append(per_html);
                        //console.log(json);
                        if (slast == 1) {
                            slast++;
                            //$('#scl-next').attr('disabled',true);
                        }
                        $.each($('.openMd'), function(i, v) {
                            $(v).on('click', function() {
                                var id = $(v).attr('date-value');
                                $.ajax({
                                    type: "POST",
                                    url: "../back/caredper_info.php",
                                    data: {
                                        id: id,
                                    },
                                    cache: false,
                                    dataType: 'json',
                                    success: function(json) {
                                        meOpenModal($('#openMd'));
                                        //alert(json[0].name);
                                        var spens = '';
                                        $.each(json[0].jobs, function(g, h) {
                                            spens += '<span>' + h + '</span>';
                                        });
                                        $("#openMd .modal-body .m-name").text(json[0].name);
                                        $("#openMd .modal-body .m-job").html(spens);
                                        $("#openMd .modal-body .m-contact").html(json[0].intr);
                                        $("#openMd .modal-body .m-time").text(json[0].time);
                                        console.log(json);
                                    },
                                });
                            });
                        });
                    },
                });
            }
        });
    }
});

function meOpenModal(oShow) {
    $(oShow).modal({
        keyboard: false,
        backdrop: 'static'
    });
    $(oShow).on('shown.bs.modal', function(e) {
        $(this).find(".close,.closed").click(function() {
            $(oShow).modal("hide");
        });
    });
}