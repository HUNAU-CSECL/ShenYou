$(function() {
    $.ajax({
        url: '../back/user.php',
        type: 'POST',
        dataType: "json",
        success: function(data) {
            var html = "";
            $.each(data, function(i, v) {
                html += '<div class="each">';
                html += '<span class="id" style="display:none">'+v.id+'</span>';
                html += '<p class="name">' + v.name + '</p>';
                if (v.type==0) {
                	html+='<div><button class="toAdmin">升级为管理员</button></div><p class="type">普通用户</p>';
                }else{
                	html+='<p class="type">管理员</p>';
                }
                html+='<p class="mail">E-mail：'+v.mail+'</p>';
                html+= '<hr></div>'
            });
            $(".main").append(html);
            //升级普通用户为管理员
            toAdmin();
        },
    });
});
//升级普通用户为管理员
function toAdmin() {
    $.each($(".toAdmin"), function(i, v) {
        $(v).click(function() {
            $(v).text("正在升级...");
            $(v).attr('disabled', true);
            $.ajax({
                url: '../back/toAdmin.php',
                type: 'POST',
                data: {
                    id: $(v).parent().find('.id').text(),
                },
                dataType: 'json',
                success: function(json) {
                    if (json) {
                        $(v).text("已升级为管理员");
                    } else {
                        $(v).attr('disabled', false);
                        $(v).text("升级为管理员");
                    }
                },
                error: function() {
                    $(v).attr('disabled', false);
                    $(v).text("升级为管理员");
                }
            });
        });
    });
}