//选：../../Fronted/back/searchSomeSch.php
//更：../back/updateold.php传递name now_id
//取消:../back/cancelold.php传递name
$(function() {
    $.ajax({
        url: '../back/oldschool.php',
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
                    html += '<div class="each"><text class="roe name">' + v.name + '</text>' + '<input type="text" class="roe key_world">' + '<div class="roe btn"><button class="update">更新</button><button class="cancel">取消</button></div><div class="searchBox" display: none"></div></div>';
                }
            });
            $("#old").append(html);
            if (data.count <= last) {
                $("#more").hide();
            }
            search();
            cancelold();
            $.each($('.key_world'), function(index, value) {
                $(value).focusout(function() {
                    $.each($(value).parent().find('.searchBox li'), function(i, v) {
                        $(v).on("click", function() {
                            $(value).val($(v).text());
                            $(value).attr("data-value", $(v).attr('data-value'));
                        });
                    });
                });
            });
            updateold();
        },
    });
    var last = 6;
    $("#more").click(function() {
        $.ajax({
            url: '../back/oldschool.php',
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
                        html += '<div class="each"><text class="roe name">' + v.name + '</text>' + '<input type="text" class="roe key_world">' + '<div class="roe btn"><button class="update">更新</button><button class="cancel">取消</button></div><div class="searchBox" display: none"></div></div>';
                    }
                });
                $("#old").append(html);
                if (data.count <= last) {
                    $("#more").hide();
                }
                search();
                cancelold();
                $.each($('.key_world'), function(index, value) {
                    $(value).focusout(function() {
                        $.each($(value).parent().find('.searchBox li'), function(i, v) {
                            $(v).on("click", function() {
                                $(value).val($(v).text());
                                $(value).attr("data-value", $(v).attr('data-value'));
                            });
                        });
                    });
                });
                updateold();
            },
        });
        last += 6;
    });
});

function search() {
    $.each($(".key_world"), function(i, v) {
        $(v).keyup(function() {
            var name = $.trim($(v).val());
            if (name !== '') {
                $.ajax({
                    url: '../../Frontend/back/searchSomeSch.php',
                    type: 'POST',
                    data: {
                        name: name,
                    },
                    cache: false,
                    dataType: 'json',
                    success: function(json) {
                        if (json) {
                            var lists = "<ul>";
                            $.each(json, function() {
                                lists += "<li data-value='" + this.id + "'>" + this.name + "</li>";
                            });
                            lists += "</ul>";
                            $(v).parent().find('.searchBox').html(lists);
                            $(v).parent().find('.searchBox').show();
                        } else {
                            $(v).parent().find('.searchBox').hide();
                        }
                        //点击body搜索提示框消失
                        $("body").on('click', function() {
                            $(v).parent().find('.searchBox').hide();
                        });
                    }
                });
            } else {
                $(v).parent().find('.searchBox').hide();
            }
        });
    });
}
//取消更新old
function cancelold() {
    $.each($(".cancel"), function(i, v) {
        $(v).click(function() {
            $(v).text("正在取消...");
            $(v).attr('disabled', true);
            $.ajax({
                url: '../back/cancelold.php',
                type: 'POST',
                data: {
                    name: $(v).parent().parent().find('.name').text(),
                },
                dataType: 'json',
                success: function(json) {
                    if (json) {
                        $(v).text("已取消");
                    } else {
                        $(v).attr('disabled', false);
                        $(v).text("取消");
                    }
                },
                error: function() {
                    $(v).attr('disabled', false);
                    $(v).text("取消");
                }
            });
        });
    });
}
//更新old
function updateold() {
    $.each($(".update"), function(i, v) {
        $(v).click(function() {
            $(v).text("正在更新...");
            $(v).attr('disabled', true);
            $.ajax({
                url: '../back/updateold.php',
                type: 'POST',
                data: {
                    name: $(v).parent().parent().find('.name').text(),
                    now_id: $(v).parent().parent().find('.key_world').attr("data-value"),
                },
                dataType: 'json',
                success: function(json) {
                    if (json) {
                        $(v).text("已更新");
                    } else {
                        $(v).attr('disabled', false);
                        $(v).text("更新");
                    }
                },
                error: function() {
                    $(v).attr('disabled', false);
                    $(v).text("更新");
                }
            });
        });
    });
}