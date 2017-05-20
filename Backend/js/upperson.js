$(function(){
    //更新政界板块
    $.ajax({
        url: '../back/polScript.php',
        type: 'POST',
        data: {
            last:0,
            amount: 4,
        },
        dataType:"json",
        success: function(data) {
            var html ="";
            $.each(data,function(i,v){
                if(i<4){
                    html+='<div class="each"><text class="name">'+v.name+'</text><span class="eng_name" style="display:none;">'+v.eng_name+'</span><button class="uppol">更新</button></div>';
                }
            });
            $("#pol").append(html);
            if(data.count <= last){
                $("#pol_more").hide();
            }
            updatepol();
        },
    });
    var last = 4;
    $("#pol_more").click(function(){
        $.ajax({
            url: '../back/polScript.php',
            type: 'POST',
            data: {
                last:last,
                amount: 4,
            },
            dataType:"json",
            success: function(data) {
                var html ="";
                $.each(data,function(i,v){
                    if(i<4){
                        html+='<div class="each"><text class="name">'+v.name+'</text><span class="eng_name" style="display:none;">'+v.eng_name+'</span><button class="uppol">更新</button></div>';
                    }
                });
                $("#pol").append(html);
                if(data.count <= last){
                     $("#pol_more").hide();
                }
                updatepol();

            },
        });
        last+=4;
    });

    //更新商界板块
    $.ajax({
        url: '../back/ecoScript.php',
        type: 'POST',
        data: {
            last:0,
            amount: 4,
        },
        dataType:"json",
        success: function(data) {
            var html ="";
            $.each(data,function(i,v){
                if(i<4){
                    html+='<div class="each"><text class="name">'+v.name+'<span class="eng_name" style="display:none;">'+v.eng_name+'</span><button class="upeco">更新</button></div>';
                }
            });
            $("#eco").append(html);
            if(data.count <= last){
                $("#eco_more").hide();
            }
            updateeco();
        },
    });
    var last = 4;
    $("#eco_more").click(function(){
        $.ajax({
            url: '../back/ecoScript.php',
            type: 'POST',
            data: {
                last:last,
                amount: 4,
            },
            dataType:"json",
            success: function(data) {
                var html ="";
                $.each(data,function(i,v){
                    if(i<4){
                        html+='<div class="each"><text class="name">'+v.name+'<span class="eng_name" style="display:none;">'+v.eng_name+'</span><button class="upeco">更新</button></div>';
                    }
                });
                $("#eco").append(html);
                if(data.count <= last){
                     $("#eco_more").hide();
                }
                updateeco();

            },
        });
        last+=4;
    });

    //更新学界板块
    $.ajax({
        url: '../back/leaScript.php',
        type: 'POST',
        data: {
            last:0,
            amount: 4,
        },
        dataType:"json",
        success: function(data) {
            var html ="";
            $.each(data,function(i,v){
                if(i<4){
                    html+='<div class="each"><text class="name">'+v.name+'<span class="eng_name" style="display:none;">'+v.eng_name+'</span><button class="uplea">更新</button></div>';
                }
            });
            $("#lea").append(html);
            if(data.count <= last){
                $("#lea_more").hide();
            }
            updatelea();
        },
    });
    var last = 4;
    $("#lea_more").click(function(){
        $.ajax({
            url: '../back/leaScript.php',
            type: 'POST',
            data: {
                last:last,
                amount: 4,
            },
            dataType:"json",
            success: function(data) {
                var html ="";
                $.each(data,function(i,v){
                    if(i<4){
                        html+='<div class="each"><text class="name">'+v.name+'<span class="eng_name" style="display:none;">'+v.eng_name+'</span><button class="uplea">更新</button></div>';
                    }
                });
                $("#lea").append(html);
                if(data.count <= last){
                     $("#lea_more").hide();
                }
                updatelea();
            },
        });
        last+=4;
    });
});
//异步更新政界
function updatepol(){
     $.each($(".uppol"),function(i,v){
        $(v).click(function(){
            $(v).text("正在更新...");
            $(v).attr('disabled',true);
            $.ajax({
                url: '../back/updatepol.php',
                type: 'POST',
                data: {
                    eng_name:$(v).prev().text(),
                },
                dataType:'json',
                success: function(json) {
                    if (json) {
                        $(v).text("已更新");
                    }else{
                        $(v).attr('disabled',false);
                        $(v).text("更新");
                    }
                },
                error:function(){
                    $(v).attr('disabled',false);
                    $(v).text("更新");
                }
            });
        });
    });
}
//异步更新商界
function updateeco(){
     $.each($(".upeco"),function(i,v){
        $(v).click(function(){
            $(v).text("正在更新...");
            $(v).attr('disabled',true);
            $.ajax({
                url: '../back/updateeco.php',
                type: 'POST',
                data: {
                    eng_name:$(v).prev().text(),
                },
                dataType:'json',
                success: function(json) {
                    if (json) {
                        $(v).text("已更新");
                    }else{
                        $(v).attr('disabled',false);
                        $(v).text("更新");
                    }
                },
                error:function(){
                    $(v).attr('disabled',false);
                    $(v).text("更新");
                }
            });
        });
    });
}
//异步更新学界
function updatelea(){
     $.each($(".uplea"),function(i,v){
        $(v).click(function(){
            $(v).text("正在更新...");
            $(v).attr('disabled',true);
            $.ajax({
                url: '../back/updatelea.php',
                type: 'POST',
                data: {
                    eng_name:$(v).prev().text(),
                },
                dataType:'json',
                success: function(json) {
                    if (json) {
                        $(v).text("已更新");
                    }else{
                        $(v).attr('disabled',false);
                        $(v).text("更新");
                    }
                },
                error:function(){
                    $(v).attr('disabled',false);
                    $(v).text("更新");
                }
            });
        });
    });
}
