//打开 模态框
function openModal(oShow) {
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

//6位随机数
function randomNum() {
    var t = '';
    for (var i = 0; i < 6; i++) {
        t += Math.floor(Math.random() * 10);
    }
    return t;
}

//倒计时60秒
var wait = 60;
function time(o) {
    if (wait == 0) {
        o.attr("disabled", false);
        o.text("获取验证码");
        wait = 60;
    } else {
        o.attr("disabled", true);
        o.text("重发 (" + wait + ")");
        wait--;
        setTimeout(function() {
            time(o)
        }, 1000)
    }
}

//对json进行降序排序函数  
function oSort(json, key) {
    var colId = key;
    var desc = function(x, y) {
        return (x[colId] < y[colId]) ? 1 : -1
    }
    //对json进行升序排序函数  
    //var asc = function(x,y){  
    //    return (x[colId] > y[colId]) ? 1 : -1  
    //}      
    //json.sort(asc); //升序排序
    return json.sort(desc); //降序排序
}

function appendHtml(json) {
    var html = '';
    $.each(json, function(index, value) {
        html += '<div class="col-md-4 prencts">' + 
                    '<div class="thumbnail transform">' + 
                        '<div class="row">' + 
                            '<div class="col-md-5">' + 
                                '<img src="' + value.img + '" alt="" class="img-circle">' + 
                                '<h4 data-perid="' + value.per_id + '">' + value.name + '</h4>' + 
                                '<div class="percent">' + 
                                    '<img src="../img/huo.jpg" alt="" class="img-icon">' + 
                                    '<div class="number">' + value.grade + '</div>' +
                                '</div>' + 
                            '</div>' + 
                            '<div class="col-md-7">' + 
                                '<p class="job">' + value.job + '</p>' + 
                                '<p class="contact">' + value.intr + '</p>' + 
                                '<input data-type="'+value.type+'" type="hidden" value="' + value.time + '">' + 
                            '</div>' + 
                        '</div>' +
                        '<div class="box"></div>' + 
                        '<div class="box-btn">' + 
                            '<a class="btn btn-default active btn-two btn-md-click">更多详情</a>' + 
                        '</div>' + 
                    '</div>' + 
                '</div>';
    });
    return html;
};