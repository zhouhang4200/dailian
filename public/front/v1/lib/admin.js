/** layuiAdmin.pro-v1.0.0-beta9 LPPL License By http://www.layui.com/admin/ */ ;
layui.define("view", function (e) {
    var a = layui.jquery,
        t = layui.laytpl,
        i = layui.element,
        n = layui.setter,
        s = layui.view,
        l = layui.device(),
        o = a(window),
        r = a("body"),
        u = a("#" + n.container),
        d = "layui-show",
        c = "layui-this",
        m = "layui-disabled",
        y = "#LAY_app_body",
        h = "LAY_app_flexible",
        f = "layadmin-side-spread-sm",
        p = "layadmin-tabsbody-item",
        v = "layui-icon-shrink-right",
        b = "layui-icon-spread-left",
        g = "layadmin-side-shrink",
        x = "LAY-system-side-menu",
        C = {
            v: "1.0.0-beta9 pro",
            req: s.req,
            sendAuthCode: function (e) {
                e = a.extend({
                    seconds: 60,
                    elemPhone: "#LAY_phone",
                    elemVercode: "#LAY_vercode"
                }, e);
                var t, i = e.seconds,
                    n = function (s) {
                        var l = a(e.elem);
                        i--, i < 0 ? (l.removeClass(m).html("获取验证码"), i = e.seconds, clearInterval(t)) : l.addClass(m).html(i + "秒后重获"), s || (t = setInterval(function () {
                            n(!0)
                        }, 1e3))
                    };
                r.on("click", e.elem, function () {
                    e.elemPhone = a(e.elemPhone), e.elemVercode = a(e.elemVercode);
                    var t = e.elemPhone,
                        s = t.val();
                    if (i === e.seconds && !a(this).hasClass(m)) {
                        if (!/^1\d{10}$/.test(s)) return t.focus(), layer.msg("请输入正确的手机号");
                        if ("object" == typeof e.ajax) {
                            var l = e.ajax.success;
                            delete e.ajax.success
                        }
                        C.req(a.extend(!0, {
                            url: "/auth/code",
                            type: "get",
                            data: {
                                phone: s
                            },
                            success: function (a) {
                                layer.msg("验证码已发送至你的手机，请注意查收", {
                                    icon: 1,
                                    shade: 0
                                }), e.elemVercode.focus(), n(), l && l(a)
                            }
                        }, e.ajax))
                    }
                })
            },
            screen: function () {
                var e = o.width();
                return e >= 1200 ? 3 : e >= 992 ? 2 : e >= 768 ? 1 : 0
            },
            exit: s.exit,
            
            sideFlexible: function (e) {
                var t = u,
                    i = a("#" + h),
                    s = C.screen();
                    "spread" === e ? (i.removeClass(b).addClass(v), s < 2 ? t.addClass(f) : t.removeClass(f), t.removeClass(g)) : (i.removeClass(v).addClass(b), s < 2 ? t.removeClass(g) : t.addClass(g), t.removeClass(f)), layui.event.call(this, n.MOD_NAME, "side({*})", {
                    status: e
                })
            },
            on: function (e, a) {
                return layui.onevent.call(this, n.MOD_NAME, e, a)
            },
            popup: s.popup,
            popupRight: function (e) {
                return C.popup.index = layer.open(a.extend({
                    type: 1,
                    id: "LAY_adminPopupR",
                    anim: -1,
                    title: !1,
                    closeBtn: !1,
                    offset: "r",
                    shade: .1,
                    shadeClose: !0,
                    skin: "layui-anim layui-anim-rl layui-layer-adminRight",
                    area: "300px"
                }, e))
            },
            theme: function (e) {
                var i = (n.theme, layui.data(n.tableName)),
                    s = "LAY_layadmin_theme",
                    l = document.createElement("style"),
                    o = t([".layui-side-menu,", ".layadmin-pagetabs .layui-tab-title li:after,", ".layadmin-pagetabs .layui-tab-title li.layui-this:after,", ".layui-layer-admin .layui-layer-title,", ".layadmin-side-shrink .layui-side-menu .layui-nav>.layui-nav-item>.layui-nav-child", "{background-color:{{d.color.main}} !important;}", ".layui-nav-tree .layui-this,", ".layui-nav-tree .layui-this>a,", ".layui-nav-tree .layui-nav-child dd.layui-this,", ".layui-nav-tree .layui-nav-child dd.layui-this a", "{background-color:{{d.color.selected}} !important;}", ".layui-layout-admin .layui-logo{background-color:{{d.color.logo || d.color.main}} !important;}}"].join("")).render(e = a.extend({}, i.theme, e)),
                    u = document.getElementById(s);
                "styleSheet" in l ? (l.setAttribute("type", "text/css"), l.styleSheet.cssText = o) : l.innerHTML = o, l.id = s, u && r[0].removeChild(u), r[0].appendChild(l), r.attr("layadmin-themealias", e.color.alias), i.theme = i.theme || {}, layui.each(e, function (e, a) {
                    i.theme[e] = a
                }), layui.data(n.tableName, {
                    key: "theme",
                    value: i.theme
                })
            },
            tabsPage: {},
            tabsBody: function (e) {
                return a(y).find("." + p).eq(e || 0)
            },
            tabsBodyChange: function (e) {
                C.tabsBody(e).addClass(d).siblings().removeClass(d), A.rollPage("auto", e)
            },
            resize: function (e) {
                var a = layui.router(),
                    t = a.path.join("-");
                o.off("resize", C.resizeFn[t]), e(), C.resizeFn[t] = e, o.on("resize", C.resizeFn[t])
            },
            resizeFn: {},
            runResize: function () {
                var e = layui.router(),
                    a = e.path.join("-");
                C.resizeFn[a] && C.resizeFn[a]()
            },
            delResize: function () {
                var e = layui.router(),
                    a = e.path.join("-");
                o.off("resize", C.resizeFn[a]), delete C.resizeFn[a]
            },
            correctRouter: function (e) {
                return /^\//.test(e) || (e = "/" + e), e.replace(/^(\/+)/, "/").replace(new RegExp("/" + n.entry + "$"), "/")
            },
            closeThisTabs: function () {
                C.tabsPage.index && a(k).eq(C.tabsPage.index).find(".layui-tab-close").trigger("click")
            }
        },
        A = C.events = {
            flexible: function (e) {
                var a = e.find("#" + h),
                    t = a.hasClass(b);
                C.sideFlexible(t ? "spread" : null)
            },
            refresh: function () {
                layui.index.render()
            },
            message: function (e) {
                e.find(".layui-badge-dot").remove()
            },
            theme: function () {
                C.popupRight({
                    id: "LAY_adminPopupTheme",
                    success: function () {
                        s(this.id).render("system/theme")
                    }
                })
            },
            note: function (e) {
                var a = C.screen() < 2,
                    t = layui.data(n.tableName).note;
                A.note.index = C.popup({
                    title: "便签",
                    shade: 0,
                    offset: ["41px", a ? null : e.offset().left - 250 + "px"],
                    anim: -1,
                    id: "LAY_adminNote",
                    skin: "layadmin-note layui-anim layui-anim-upbit",
                    content: '<textarea placeholder="内容"></textarea>',
                    resize: !1,
                    success: function (e, a) {
                        var i = e.find("textarea"),
                            s = void 0 === t ? "便签中的内容会存储在本地，这样即便你关掉了浏览器，在下次打开时，依然会读取到上一次的记录。是个非常小巧实用的本地备忘录" : t;
                        i.val(s).focus().on("keyup", function () {
                            layui.data(n.tableName, {
                                key: "note",
                                value: this.value
                            })
                        })
                    }
                })
            },
            about: function () {
                C.popupRight({
                    id: "LAY_adminPopupAbout",
                    success: function () {
                        s(this.id).render("system/about")
                    }
                })
            },
            more: function () {
                C.popupRight({
                    id: "LAY_adminPopupMore",
                    success: function () {
                        s(this.id).render("system/more")
                    }
                })
            },
            back: function () {
                history.back()
            },
            setTheme: function (e) {
                var a = n.theme,
                    t = e.data("index");
                e.siblings(".layui-this").data("index");
                e.hasClass(c) || (e.addClass(c).siblings(".layui-this").removeClass(c), a.color[t] && (a.color[t].index = t, C.theme({
                    color: a.color[t]
                })))
            },
            rollPage: function (e, t) {
                var i = a("#LAY_app_tabsheader"),
                    n = i.children("li"),
                    s = (i.prop("scrollWidth"), i.outerWidth()),
                    l = parseFloat(i.css("left"));
                if ("left" === e) {
                    if (!l && l <= 0) return;
                    var o = -l - s;
                    n.each(function (e, t) {
                        var n = a(t),
                            s = n.position().left;
                        if (s >= o) return i.css("left", -s), !1
                    })
                } else "auto" === e ? ! function () {
                    var e, o = n.eq(t);
                    if (o[0]) {
                        if (e = o.position().left, e < -l) return i.css("left", -e);
                        if (e + o.outerWidth() >= s - l) {
                            var r = e + o.outerWidth() - (s - l);
                            n.each(function (e, t) {
                                var n = a(t),
                                    s = n.position().left;
                                if (s + l > 0 && s - l > r) return i.css("left", -s), !1
                            })
                        }
                    }
                }() : n.each(function (e, t) {
                    var n = a(t),
                        o = n.position().left;
                    if (o + n.outerWidth() >= s - l) return i.css("left", -o), !1
                })
            },
            leftPage: function () {
                A.rollPage("left")
            },
            rightPage: function () {
                A.rollPage()
            },
            closeThisTabs: function () {
                C.closeThisTabs()
            },
            closeOtherTabs: function (e) {
                var t = "LAY-system-pagetabs-remove";
                "all" === e ? (a(k + ":gt(0)").remove(), a(y).find("." + p + ":gt(0)").remove()) : (a(k).each(function (e, i) {
                    e && e != C.tabsPage.index && (a(i).addClass(t), C.tabsBody(e).addClass(t))
                }), a("." + t).remove())
            },
            closeAllTabs: function () {
                A.closeOtherTabs("all"), location.hash = ""
            },
            shade: function () {
                C.sideFlexible()
            }
        };
    ! function () {
        var e = layui.data(n.tableName);
        e.theme && C.theme(e.theme), r.addClass("layui-layout-body"), C.screen() < 1 && delete n.pageTabs, n.pageTabs || u.addClass("layadmin-tabspage-none"), l.ie && l.ie < 10 && s.error("IE" + l.ie + "下访问可能不佳，推荐使用：Chrome / Firefox / Edge 等高级浏览器", {
            offset: "auto",
            id: "LAY_errorIE"
        })
    }(), C.on("hash(side)", function (e) {
        var t = e.path,
            i = function (e) {
                return {
                    list: e.children(".layui-nav-child"),
                    name: e.data("name"),
                    jump: e.data("jump")
                }
            },
            n = a("#" + x),
            s = "layui-nav-itemed",
            l = function (e) {
                var n = C.correctRouter(t.join("/"));
                e.each(function (e, l) {
                    var o = a(l),
                        r = i(o),
                        u = r.list.children("dd"),
                        d = t[0] == r.name || 0 === e && !t[0] || r.jump && n == C.correctRouter(r.jump);
                    if (u.each(function (e, l) {
                            var o = a(l),
                                u = i(o),
                                d = u.list.children("dd"),
                                m = t[0] == r.name && t[1] == u.name || u.jump && n == C.correctRouter(u.jump);
                            if (d.each(function (e, l) {
                                    var o = a(l),
                                        d = i(o),
                                        m = t[0] == r.name && t[1] == u.name && t[2] == d.name || d.jump && n == C.correctRouter(d.jump);
                                    if (m) {
                                        var y = d.list[0] ? s : c;
                                        return o.addClass(y).siblings().removeClass(y), !1
                                    }
                                }), m) {
                                var y = u.list[0] ? s : c;
                                return o.addClass(y).siblings().removeClass(y), !1
                            }
                        }), d) {
                        var m = r.list[0] ? s : c;
                        return o.addClass(m).siblings().removeClass(m), !1
                    }
                })
            };
        n.find("." + c).removeClass(c), C.screen() < 2 && C.sideFlexible(), l(n.children("li"))
    }), i.on("nav(layadmin-system-side-menu)", function (e) {
        e.siblings(".layui-nav-child")[0] && u.hasClass(g) && (C.sideFlexible("spread"), layer.close(e.data("index"))), C.tabsPage.type = "nav"
    }), i.on("nav(layadmin-pagetabs-nav)", function (e) {
        var a = e.parent();
        a.removeClass(c), a.parent().removeClass(d)
    });
    var P = function (e) {
            var a = e.attr("lay-id"),
                t = e.attr("lay-attr"),
                i = e.index();
            C.tabsBodyChange(i), location.hash = a === n.entry ? "/" : t
        },
        k = "#LAY_app_tabsheader>li";
    r.on("click", k, function () {
        var e = a(this),
            t = e.index();
        return C.tabsPage.type = "tab", C.tabsPage.index = t, "iframe" === e.attr("lay-attr") ? C.tabsBodyChange(t) : (P(e), void C.runResize())
    }), i.on("tabDelete(layadmin-layout-tabs)", function (e) {
        var t = a(k + ".layui-this");
        e.index && C.tabsBody(e.index).remove(), P(t), C.delResize()
    }), r.on("click", "*[lay-href]", function () {
        var e = a(this),
            t = e.attr("lay-href");
        layui.router();
        C.tabsPage.elem = e, location.hash = C.correctRouter(t)
    }), r.on("click", "*[layadmin-event]", function () {
        var e = a(this),
            t = e.attr("layadmin-event");
        A[t] && A[t].call(this, e)
    }), r.on("mouseenter", "*[lay-tips]", function () {
        var e = a(this);
        if (!e.parent().hasClass("layui-nav-item") || u.hasClass(g)) {
            var t = e.attr("lay-tips"),
                i = e.attr("lay-offset"),
                n = e.attr("lay-direction"),
                s = layer.tips(t, this, {
                    tips: n || 1,
                    time: -1,
                    success: function (e, a) {
                        i && e.css("margin-left", i + "px")
                    }
                });
            e.data("index", s)
        }
    }).on("mouseleave", "*[lay-tips]", function () {
        layer.close(a(this).data("index"))
    });
    var z = layui.data.resizeSystem = function () {
        layer.closeAll("tips"), z.lock || setTimeout(function () {
            C.sideFlexible(C.screen() < 2 ? "" : "spread"), delete z.lock
        }, 100), z.lock = !0
    };
    o.on("resize", layui.data.resizeSystem), e("admin", C)
});