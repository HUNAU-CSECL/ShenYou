$(document).ready(function() {
    var url = window.location.href;
    var regg1 = /.*?name=/;
    var regg2 = /#/;
    var name = url.replace(regg2, '');
    var name = name.replace(regg1, '');
    //加载高校信息
    var scloolId = '';
    $.ajax({
        type: "POST",
        url: "../back/school.php",
        data: "name=" + name,
        cache: false,
        dataType: 'json',
        success: function(json) {
            if (json) {
                $.each(json, function() {
                    $("#school_name").text(this.name);
                    if (this.eng_name !== '' || this.eng_name !== null) {
                        $("#school_eng_name").text(this.eng_name);
                    }
                    $("#web").attr('href', this.web);
                    $("#introduct").html(this.introduct);
                });
                scloolId = json[0].id;
                //$.cookie('sch_id',json[0].id);
                if ($.cookie("userId")) {
                    //加载关注高校情况
                    //alert(scloolId);
                    $.ajax({
                        type: "POST",
                        url: "../back/isCareSch.php",
                        data: {
                            user_id: $.cookie("userId"),
                            sch_id: scloolId,
                        },
                        cache: true,
                        dataType: 'json',
                        success: function(json) {
                            $("#schCare").attr('data-card', json);
                            if (json == '1') {
                                $("#schCare").removeClass('btn-primary');
                                $("#schCare").addClass('btn-success');
                                $("#schCare").text("已关注");
                            } else {
                                $("#schCare").text("关注");
                            }
                            $("#schCare").on("click", function() {
                                if ($("#schCare").attr('data-card') == 0) {
                                    $.ajax({
                                        type: "POST",
                                        url: "../back/careSch.php",
                                        data: {
                                            user_id: $.cookie("userId"),
                                            sch_id: scloolId,
                                        },
                                        cache: true,
                                        dataType: 'json',
                                        success: function(json) {
                                            if (json == 'success') {
                                                $("#schCare").removeClass('btn-primary');
                                                $("#schCare").addClass('btn-success');
                                                $("#schCare").text("已关注");
                                                $("#schCare").attr('data-card', 1);
                                            }
                                        },
                                    });
                                } else {
                                    $.ajax({
                                        type: "POST",
                                        url: "../back/cancelCareSch.php",
                                        data: {
                                            user_id: $.cookie("userId"),
                                            sch_id: scloolId,
                                        },
                                        cache: true,
                                        dataType: 'json',
                                        success: function(json) {
                                            if (json == 'success') {
                                                $("#schCare").removeClass('btn-success');
                                                $("#schCare").addClass('btn-primary');
                                                $("#schCare").text("关注");
                                                $("#schCare").attr('data-card', 0);
                                            }
                                        },
                                    });
                                }
                            });
                        },
                    });
                } else {
                    $("#schCare").click(function() {
                        //$("#schCare").addClass('btn-danger');
                        $("#schCare").text("抱歉！请您先登录");
                        setTimeout(function() {
                            openModal("#login");
                        }, 1000);
                    });
                }
            }
        },
    });

    //处理点击关注事件
    //var nCard = 0;

    //加载高校校友
    $.ajax({
        type: "POST",
        url: "../back/schper.php",
        data: {
            name: name,
            user_id: $.cookie("userId"),
        },
        cache: true,
        dataType: 'json',
        success: function(json) {
            $('#sj .row').html('');
            $('#xj .row').html('');
            $('#zj .row').html('');
            var pol = oSort(json['pol'], "grade");
            var eco = oSort(json['eco'], "grade");
            var lea = oSort(json['lea'], "grade");
            $('#sj .row').append(appendHtml(eco));
            $('#xj .row').append(appendHtml(lea));
            $('#zj .row').append(appendHtml(pol));
            //开始制作弹框
            //alert($('.btn-md-click').length);
            $.each($('.btn-md-click'), function(i, v) {
                $(this).on('click', function() {
                    openModals($("#myModal"));
                    var xper_id = $('.prencts').eq(i).find('h4').attr('data-perid');
                    var xtype = $('.prencts').eq(i).find('input').attr('data-type');
                    var xtime = $('.prencts').eq(i).find('input').val();
                    //加载多个职位
                    $("#myModal .modal-body .m-name").text($('.prencts').eq(i).find('h4').text());
                    //$("#myModal .modal-body .m-job").text($('.prencts').eq(i).find('.job').text());
                    $("#myModal .modal-body .m-contact").html($('.prencts').eq(i).find('.contact').html());
                    $("#myModal .modal-body .m-time").text(xtime);
                    $.ajax({
                        type: "POST",
                        url: "../back/jobs.php",
                        data: {
                            per_id: xper_id,
                            type: xtype,
                            user_id: $.cookie('userId') ? $.cookie('userId') : '',
                        },
                        cache: false,
                        dataType: 'json',
                        success: function(json) {
                            if (json) {
                                //alert(json.care);
                                //显示多个职位
                                var span = '';
                                $.each(json.jobs, function(i, v) {
                                    span += '<span>' + v + '</span><br>';
                                });
                                $("#myModal .modal-body .m-job").append(span);
                                //处理关注
                                if ($.cookie("userId")) {
                                    if (json.care == 0) {
                                        $.cookie("perCare", 0);
                                        $("#perCare").text("关注").addClass('btn-primary');
                                    } else {
                                        $.cookie("perCare", 1);
                                        $("#perCare").text("已关注").addClass('btn-success');
                                    }
                                    $("#perCare").on('click', function() {
                                        if ($.cookie("perCare") == 0) {
                                            $.ajax({
                                                type: "POST",
                                                url: "../back/carePer.php",
                                                data: {
                                                    user_id: $.cookie("userId"),
                                                    per_id: xper_id,
                                                },
                                                cache: false,
                                                dataType: 'json',
                                                success: function(json) {
                                                    if (json == 'success') {
                                                        $("#perCare").removeClass('btn-primary');
                                                        $("#perCare").text("已关注");
                                                        $("#perCare").addClass("btn-success");
                                                        $.cookie("perCare", 1);
                                                    }
                                                },
                                            });
                                        } else {
                                            $.ajax({
                                                type: "POST",
                                                url: "../back/cancelCarePer.php",
                                                data: {
                                                    user_id: $.cookie("userId"),
                                                    per_id: xper_id,
                                                },
                                                cache: false,
                                                dataType: 'json',
                                                success: function(json) {
                                                    if (json == 'success') {
                                                        $("#perCare").removeClass('btn-success');
                                                        $("#perCare").text("关注");
                                                        $("#perCare").addClass("btn-primary");
                                                        $.cookie("perCare", 0);
                                                    }
                                                },
                                            });
                                        }
                                    });
                                } else {
                                    $("#myModal .modal-body .m-care").text("关注");
                                    $("#perCare").click(function() {
                                        //alert("请先登录");
                                        $('#myModal').modal('hide');
                                        openModal($('#login'));
                                    });
                                }
                            }
                        },
                    });
                });
            });
            // console.log('政界:', pol);
            // console.log('商界:', eco);
            // console.log('學界:', lea);
        },
    });
});

function openModals(oShow) {
    $(oShow).modal({
        keyboard: false,
        backdrop: 'static'
    });
    $(oShow).on('shown.bs.modal', function(e) {
        $(this).find(".close,.closed").click(function() {
            $(oShow).modal("hide");
            $(oShow).find('.modal-body .m-name').text('');
            $(oShow).find('.modal-body .m-job').text('');
            $(oShow).find('.modal-body .m-contact').html('');
            $('#perCare').removeClass('btn-primary');
            $('#perCare').removeClass('btn-success');
        });
    });
}