$(document).ready(function () {
  //全屏滚动插件的调用
  $('#dowebok').fullpage({
    // sectionsColor: ['#1bbc9b', '#4BBFC3', '#7BAABE', '#f90','#7BAABE', '#f90'],
    anchors: ['page1', 'page2', 'page3', 'page4', 'page5'],
    menu: '#menu',
    resize: false,
    normalScrollElements: '.ul2'
  });
  //scrollReveal特效调用
  var config = {
    after: "0s",
    enter: "left",
    move: "100px",
    over: '0.7s',
    easing: "ease-in-out",
    viewportFactor: 0.33,
    reset: true,
    init: true
  };
  window.scrollReveal = new scrollReveal(config);
  //精准搜索
  $.each($('#page-menu .ul1 a'), function (index, value) {
    $(this).click(function () {
      $('#nation').text($(this).text());
    });
  });
  $.each($('#page-menu .ul2 a'), function (index, value) {
    $(this).click(function () {
      $('#school').text($(this).text());
    });
  });
  $(".ul1").hide();
  $(".ul2").hide();
  $(".button1").click(function (e) {
    var _this = this;
    e.stopPropagation();
    $(_this).animate({ 
      width: 0,
    }, 400 );
    $(".ul1").slideDown("slow", function () {
      $.each($(".ul1 li a"), function (i, v) {
        $(this).on("click", function (e) {
          e.stopPropagation();
          $(".ul1").slideUp();
          setTimeout(function () {
            $(".ul2").slideDown();
          }, 400)
        });
      });
      $('#page-search').on('click', function (e) {
        e.stopPropagation();
        $(".ul1").slideUp();
        $(".ul2").slideUp();
        $(_this).animate({ 
          width: "35px",
        }, 400 );
      });
    });
  });

  //首页搜索框返回提示(模糊搜索)
  $('#exampleInputEmail1').keyup(function () {
    var name = $.trim(this.value);
    if (name !== "") { //检测键盘输入的内容是否为空，为空就不发出请求
      $.ajax({
        type: "POST",
        url: "../back/searchSomeSch.php",
        data: "name=" + name,
        cache: false, //不从浏览器缓存中加载请求信息
        dataType: 'json', //服务器返回数据的类型为json
        success: function (json) {
          if (json) {
            var lists = "<ul>";
            $.each(json, function () {
              lists += "<li>" + this.name + "</li>"; //遍历出每一条返回的数据
            });
            lists += "</ul>";
            $("#searchBox").html(lists).slideDown(); //将搜索到的结果展示出来
          } else {
            $("#searchBox").slideUp();
          }
          //点击body搜索提示框消失
          $("body").on('click', function () {
            $("#searchBox").slideUp();
          });
        },
      });
    } else {
      $("#searchBox").slideUp(); //没有查询结果就隐藏搜索框
    }
  });
  //点击提示框搜索框获取其值
  $('#exampleInputEmail1').focusout(function () {
    $.each($("#searchBox li"), function (i, v) {
      $(v).on("click", function () {
        $('#exampleInputEmail1').val($(v).text());
        $("#searchBox").slideUp();
      });
    });
  });
  //首页搜索框提交搜索(精准搜索)
  $('#search_sub').click(function () {
    var name = $("#exampleInputEmail1").val();
    if (name !== '') {
      $.ajax({
        type: "POST",
        url: "../back/searchSch.php",
        data: "name=" + name,
        cache: false,
        dataType: 'json',
        success: function (json) {
          if (json) {
            window.location.href = encodeURI("school.html?name=" + json);
          }
        }
      });
    }
  });
  //Enter触发click
  $(document).keyup(function (event) {
    if (event.keyCode == 13) {
      $("#search_sub").trigger("click");
    }
  });
  //选择地区
  $.each($('.ul1 li a'), function (i, v) {
    $(this).click(function (e) {
      $('.ul2').empty();
      e.stopPropagation();
      var pro_id = $(this).attr('data-value');
      $.ajax({
        type: "POST",
        url: "../back/searchAllSch.php",
        data: "pro_id=" + pro_id,
        cache: false,
        dataType: 'json',
        success: function (json) {
          var html = "";
          $.each(json, function (index, value) {
            html += '<li><a href="javascript:void(0)">' + value + '</a></li>';
          });
          $('.ul2').append(html);
          // alert(html);
          //mouseover
          $.each($('.ul2 li'), function (s, b) {
            $(b).click(function () {
              $("#exampleInputEmail1").val($(b).text());
            });
          });
        }
      });
    });
  });
  //忘记密码
  if ($.cookie("userId")) {
    //加载关注高校情况
    $.ajax({
      type: "POST",
      url: "../back/isCareSch.php",
      data: {
        user_id: $.cookie("userId"),
        sch_id: $.cookie("sch_id"),
      },
      cache: false,
      dataType: 'json',
      success: function (json) {
        if (json == 'cared') {
          $.cookie("cared", 1);
          $("#schCare").css("background", "#00c2ff");
          $("#schCare").text("已关注");
        } else {
          $.cookie("cared", 0);
          $("#schCare").css("background", "transparent");
          $("#schCare").text("关注");
        }
      },
    });
  }

  //发邮件反馈意见
  $("#send-advise").click(function () {
    var advise = $.trim($('#message').val());
    if ($.cookie("userId")) {
      if (advise !== '') {
        $.ajax({
          type: "POST",
          url: "../back/sendSuggest.php",
          data: {
            user_name: $.cookie('name'),
            user_mail: $.cookie('mail'),
            user_advise: advise,
          },
          cache: false,
          dataType: 'json',
          success: function (json) {
            if (json == 'success') {
              $("#send-advise").text("已发送");
            }
          },
        });
      }
    } else {
      $("#send-advise").hide();
      $("#send-false").show();
    }
  });
});