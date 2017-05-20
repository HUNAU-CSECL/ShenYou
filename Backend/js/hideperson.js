$(function() {
    $('#key_world').keyup(function() {
        var name = $.trim(this.value);
        if (name !== "") {
            //提示搜索
            $.ajax({
                type: "POST",
                url: "../back/searchSomePer.php",
                data: "name=" + name,
                cache: false,
                dataType: 'json',
                success: function(json) {
                    if (json) {
                        var lists = "<ul>";
                        $.each(json, function() {
                            lists += "<li>" + this.name + "</li>";
                        });
                        lists += "</ul>";
                        $("#searchBox").html(lists);
                        $("#searchBox").show();
                    } else {
                        $("#searchBox").hide();
                    }
                    //点击body搜索提示框消失
                    $("body").on('click', function() {
                        $("#searchBox").hide();
                    });
                    //输入人名弹出option
                    $.ajax({
                        type: "POST",
                        url: "../back/searchPer.php",
                        data: "name=" + name,
                        cache: false,
                        dataType: 'json',
                        success: function(json) {
                            if (json) {
                                var options = '';
                                $.each(json, function(i, v) {
                                    options += "<option value='" + i + "'>";
                                    $.each(v, function(a, b) {
                                        options += b + " ";
                                    });
                                    options += "</option>";
                                });
                                $("#select").append(options);
                            }
                        }
                    });
                }
            });
        } else {
            $("#searchBox").hide();
        }
    });
    //点击提示框搜索框获取其值
    $('#key_world').focusout(function() {
        $.each($("#searchBox li"), function(i, v) {
            $(v).on("click", function() {
                $('#key_world').val($(v).text());
                $("#searchBox").hide();
                var name = $(v).text();
                $.ajax({
                    type: "POST",
                    url: "../back/searchPer.php",
                    data: "name=" + name,
                    cache: false,
                    dataType: 'json',
                    success: function(json) {
                        if (json) {
                            var options = '';
                            $.each(json, function(i, v) {
                                options += "<option value='" + i + "'>";
                                $.each(v, function(a, b) {
                                    options += b + " ";
                                });
                                options += "</option>";
                            });
                            $("#select").append(options);
                        }
                    }
                });
            });
        });
    });
    $('#confirm').click(function() {
        var per_id = $('#select').val();
        var reason = $.trim($('#content').val());
        if (reason !== '') {
            $.ajax({
                type: "POST",
                url: "../back/insertReason.php",
                data: {
                    per_id: per_id,
                    reason: reason,
                },
                cache: false,
                dataType: 'json',
                success: function(json) {
                    if (json) {
                        //刷新页面
                        window.location.href = "hideperson.html";
                    }
                },
            });
        }
    });
    //加载丑闻列表
    $.ajax({
        url: '../back/reasons.php',
        type: 'POST',
        data: {
            last: 0,
            amount: 6,
        },
        dataType: "json",
        success: function(data) {
            var html = "";
            $.each(data, function(i, v) {
                if (i < 6) {
                    html += '<div class="each"><text class="per_id" style="display:none">' + v.per_id + '</text><text class="name">' + v.name + '</text><button class="cancel">撤销</button></br><text class="jobs">职务：';
                    $.each(v.job, function(i, v) {
                        html += v + ' ';
                    });
                    html += '</text></br><text class="reason">' + v.reason + '</text></div>';
                }
            });
            $("#detailed").append(html);
            if (data.count <= last) {
                $("#more").hide();
            }
            cancel();
        },
    });
    var last = 6;
    $("#more").click(function() {
        $.ajax({
            url: '../back/reasons.php',
            type: 'POST',
            data: {
                last: last,
                amount: 6,
            },
            dataType: "json",
            success: function(data) {
                var html = "";
                $.each(data, function(i, v) {
                    if (i < 6) {
                        html += '<div class="each"><text class="per_id" style="display:none">' + v.per_id + '</text><text class="name">' + v.name + '</text><button class="cancel">撤销</button></br><text class="jobs">职务：';
                        $.each(v.job, function(i, v) {
                            html += v + ' ';
                        });
                        html += '</text></br><text class="reason">' + v.reason + '</text></div>';
                    }
                });
                $("#detailed").append(html);
                if (data.count <= last) {
                    $("#more").hide();
                }
                cancel();
            },
        });
        last += 6;
    });
});

function cancel() {
    $.each($(".cancel"), function(i, v) {
        $(v).click(function() {
            $(v).text("正在撤销...");
            $(v).attr('disabled', true);
            $.ajax({
                url: '../back/cancelReason.php',
                type: 'POST',
                data: {
                    per_id: $(v).parent().find('.per_id').text(),
                },
                dataType: 'json',
                success: function(json) {
                    if (json) {
                        $(v).text("已撤销");
                    } else {
                        $(v).attr('disabled', false);
                        $(v).text("撤销");
                    }
                },
                error: function() {
                    $(v).attr('disabled', false);
                    $(v).text("撤销");
                }
            });
        });
    });
}