$(function() {
    var polSelect = '<select class="roe polSelect"><option value="1">正国级   （一级干部）</option><option value="2">副国级   （二级干部）</option><option value="3">省部级       （三级干部）</option><option value="4">副省部级  （四级干部）</option><option value="5">厅局级   （五级干部）</option><option value="6">副厅局级  （六级干部）</option><option value="7">县处级   （七级干部）</option><option value="8">副县处级  （八级干部）</option><option value="9">乡科级       （九级干部）</option><option value="10">副乡科级 （十级干部）</option><option value="11">正股级  （十一级干部）</option></select>';
    var ecoSelect = '<select class="roe ecoSelect"><option value="4">董事长级别</option><option value="3">副董级别</option><option value="2">总经理级别</option><option value="1">副总级别</option></select>';
    var leaSelect = '<select class="roe leaSelect"><option value="1">诺奖级</option><option value="2">院士级</option><option value="3"></option></select>';
    //更新政界职位等级板块
    $.ajax({
        url: '../back/polJobs.php',
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
                    html += '<div class="each"><div class="roe name">' + v.name + '</div>' + polSelect + '<button class="roe uppol">更新</button></div>';
                }
            });
            $("#pol").append(html);
            if (data.count <= last) {
                $("#pol_more").hide();
            }
            updatePolGrade();
        },
    });
    var last = 6;
    $("#pol_more").click(function() {
        $.ajax({
            url: '../back/polJobs.php',
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
                        html += '<div class="each"><div class="roe name">' + v.name + '</div>' + polSelect + '<button class="roe uppol">更新</button></div>';
                    }
                });
                $("#pol").append(html);
                if (data.count <= last) {
                    $("#pol_more").hide();
                }
                updatePolGrade();
            },
        });
        last += 6;
    });
    //更新商界职位等级板块
    $.ajax({
        url: '../back/ecoJobs.php',
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
                    html += '<div class="each"><div class="roe name">' + v.name + '</div>' + ecoSelect + '<button class="roe upeco">更新</button></div>';
                }
            });
            $("#eco").append(html);
            if (data.count <= last) {
                $("#eco_more").hide();
            }
            updateEcoGrade();
        },
    });
    var last = 6;
    $("#eco_more").click(function() {
        $.ajax({
            url: '../back/ecoJobs.php',
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
                        html += '<div class="each"><div class="roe name">' + v.name + '</div>' + ecoSelect + '<button class="roe upeco">更新</button></div>';
                    }
                });
                $("#eco").append(html);
                if (data.count <= last) {
                    $("#eco_more").hide();
                }
                updateEcoGrade();
            },
        });
        last += 6;
    });
    //更新学界职位等级板块
    $.ajax({
        url: '../back/leaJobs.php',
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
                    html += '<div class="each"><div class="roe name">' + v.name + '</div>' + leaSelect + '<button class="roe uplea">更新</button></div>';
                }
            });
            $("#lea").append(html);
            if (data.count <= last) {
                $("#lea_more").hide();
            }
            updateLeaGrade();
        },
    });
    var last = 6;
    $("#lea_more").click(function() {
        $.ajax({
            url: '../back/leaJobs.php',
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
                        html += '<div class="each"><div class="roe name">' + v.name + '</div>' + leaSelect + '<button class="roe uplea">更新</button></div>';
                    }
                });
                $("#lea").append(html);
                if (data.count <= last) {
                    $("#lea_more").hide();
                }
                updateLeaGrade();
            },
        });
        last += 6;
    });
    //商界等级自动更新
    $("#auto").click(function() {
        $('#auto').text("正在更新...");
        $('#auto').attr('disabled', true);
        $.ajax({
            url: '../back/autoEcoGrade.php',
            success: function() {
                $('#auto').text("已更新");
            },
            error: function() {
                $('#auto').attr('disabled', false);
                $('#auto').text("更新");
            }
        });
    });
    //政界界等级自动更新
    $("#autoPol").click(function() {
        $('#autoPol').text("正在更新...");
        $('#autoPol').attr('disabled', true);
        $.ajax({
            url: '../back/autoPolGrade.php',
            success: function() {
                $('#autoPol').text("已更新");
            },
            error: function() {
                $('#autoPol').attr('disabled', false);
                $('#autoPol').text("更新");
            }
        });
    });
});
//异步更新政界职位等级
function updatePolGrade() {
    $.each($(".uppol"), function(i, v) {
        $(v).click(function() {
            $(v).text("正在更新...");
            $(v).attr('disabled', true);
            $.ajax({
                url: '../back/updatePolGrade.php',
                type: 'POST',
                data: {
                    name: $(v).parent().find('.name').text(),
                    grade: $(v).prev().val(),
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
//异步更新商界职位等级
function updateEcoGrade() {
    $.each($(".upeco"), function(i, v) {
        $(v).click(function() {
            $(v).text("正在更新...");
            $(v).attr('disabled', true);
            $.ajax({
                url: '../back/updateEcoGrade.php',
                type: 'POST',
                data: {
                    name: $(v).parent().find('.name').text(),
                    grade: $(v).prev().val(),
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
//异步更新学界职位等级
function updateLeaGrade() {
    $.each($(".uplea"), function(i, v) {
        $(v).click(function() {
            $(v).text("正在更新...");
            $(v).attr('disabled', true);
            $.ajax({
                url: '../back/updateLeaGrade.php',
                type: 'POST',
                data: {
                    name: $(v).parent().find('.name').text(),
                    grade: $(v).prev().val(),
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