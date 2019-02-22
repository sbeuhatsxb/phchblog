//<![CDATA[
jQuery.easing["jswing"] = jQuery.easing["swing"];
jQuery.extend(jQuery.easing, {
    def: "easeOutQuad",
    swing: function (x, t, b, c, d) {
        return jQuery.easing[jQuery.easing.def](x, t, b, c, d)
    },
    easeInQuad: function (x, t, b, c, d) {
        return c * (t /= d) * t + b
    },
    easeOutQuad: function (x, t, b, c, d) {
        return -c * (t /= d) * (t - 2) + b
    },
    easeInOutQuad: function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t + b;
        return -c / 2 * (--t * (t - 2) - 1) + b
    },
    easeInCubic: function (x, t, b, c, d) {
        return c * (t /= d) * t * t + b
    },
    easeOutCubic: function (x, t, b, c, d) {
        return c * ((t = t / d - 1) * t * t + 1) + b
    },
    easeInOutCubic: function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
        return c / 2 * ((t -= 2) * t * t + 2) + b
    },
    easeInQuart: function (x, t, b, c, d) {
        return c * (t /= d) * t * t * t + b
    },
    easeOutQuart: function (x, t, b, c, d) {
        return -c * ((t = t / d - 1) * t * t * t - 1) + b
    },
    easeInOutQuart: function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t * t * t + b;
        return -c / 2 * ((t -= 2) * t * t * t - 2) + b
    },
    easeInQuint: function (x, t, b, c, d) {
        return c * (t /= d) * t * t * t * t + b
    },
    easeOutQuint: function (x, t, b, c, d) {
        return c * ((t = t / d - 1) * t * t * t * t + 1) + b
    },
    easeInOutQuint: function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t * t * t * t + b;
        return c / 2 * ((t -= 2) * t * t * t * t + 2) + b
    },
    easeInSine: function (x, t, b, c, d) {
        return -c * Math.cos(t / d * (Math.PI / 2)) + c + b
    },
    easeOutSine: function (x, t, b, c, d) {
        return c * Math.sin(t / d * (Math.PI / 2)) + b
    },
    easeInOutSine: function (x, t, b, c, d) {
        return -c / 2 * (Math.cos(Math.PI * t / d) - 1) + b
    },
    easeInExpo: function (x, t, b, c, d) {
        return t == 0 ? b : c * Math.pow(2, 10 * (t / d - 1)) + b
    },
    easeOutExpo: function (x, t, b, c, d) {
        return t == d ? b + c : c * (-Math.pow(2, -10 * t / d) + 1) + b
    },
    easeInOutExpo: function (x, t, b, c, d) {
        if (t == 0) return b;
        if (t == d) return b + c;
        if ((t /= d / 2) < 1) return c / 2 * Math.pow(2, 10 * (t - 1)) + b;
        return c / 2 * (-Math.pow(2, -10 * --t) + 2) + b
    },
    easeInCirc: function (x, t, b, c, d) {
        return -c * (Math.sqrt(1 - (t /= d) * t) - 1) + b
    },
    easeOutCirc: function (x, t, b, c, d) {
        return c * Math.sqrt(1 - (t = t / d - 1) * t) + b
    },
    easeInOutCirc: function (x, t, b, c, d) {
        if ((t /= d / 2) < 1) return -c / 2 * (Math.sqrt(1 - t * t) - 1) + b;
        return c / 2 * (Math.sqrt(1 - (t -= 2) * t) + 1) + b
    },
    easeInElastic: function (x, t, b, c, d) {
        var s = 1.70158;
        var p = 0;
        var a = c;
        if (t == 0) return b;
        if ((t /= d) == 1) return b + c;
        if (!p) p = d * 0.3;
        if (a < Math.abs(c)) {
            a = c;
            var s = p / 4
        } else var s = p / (2 * Math.PI) * Math.asin(c / a);
        return -(a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * 2 * Math.PI / p)) + b
    },
    easeOutElastic: function (x, t, b, c, d) {
        var s = 1.70158;
        var p = 0;
        var a = c;
        if (t == 0) return b;
        if ((t /= d) == 1) return b + c;
        if (!p) p = d * 0.3;
        if (a < Math.abs(c)) {
            a = c;
            var s = p / 4
        } else var s = p / (2 * Math.PI) * Math.asin(c / a);
        return a * Math.pow(2, -10 * t) * Math.sin((t * d - s) * 2 * Math.PI / p) + c + b
    },
    easeInOutElastic: function (x, t, b, c, d) {
        var s = 1.70158;
        var p = 0;
        var a = c;
        if (t == 0) return b;
        if ((t /= d / 2) == 2) return b + c;
        if (!p) p = d * 0.3 * 1.5;
        if (a < Math.abs(c)) {
            a = c;
            var s = p / 4
        } else var s = p / (2 * Math.PI) * Math.asin(c / a); if (t < 1) return -0.5 * a * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * d - s) * 2 * Math.PI / p) + b;
        return a * Math.pow(2, -10 * (t -= 1)) * Math.sin((t * d - s) * 2 * Math.PI / p) * 0.5 + c + b
    },
    easeInBack: function (x, t, b, c, d, s) {
        if (s == undefined) s = 1.70158;
        return c * (t /= d) * t * ((s + 1) * t - s) + b
    },
    easeOutBack: function (x, t, b, c, d, s) {
        if (s == undefined) s = 1.70158;
        return c * ((t = t / d - 1) * t * ((s + 1) * t + s) + 1) + b
    },
    easeInOutBack: function (x, t, b, c, d, s) {
        if (s == undefined) s = 1.70158;
        if ((t /= d / 2) < 1) return c / 2 * t * t * (((s *= 1.525) + 1) * t - s) + b;
        return c / 2 * ((t -= 2) * t * (((s *= 1.525) + 1) * t + s) + 2) + b
    },
    easeInBounce: function (x, t, b, c, d) {
        return c - jQuery.easing.easeOutBounce(x, d - t, 0, c, d) + b
    },
    easeOutBounce: function (x, t, b, c, d) {
        if ((t /= d) < 1 / 2.75) return c * 7.5625 * t * t + b;
        else if (t < 2 / 2.75) return c * (7.5625 * (t -= 1.5 / 2.75) * t + 0.75) + b;
        else if (t < 2.5 / 2.75) return c * (7.5625 * (t -= 2.25 / 2.75) * t + 0.9375) + b;
        else return c * (7.5625 * (t -= 2.625 / 2.75) * t + 0.984375) + b
    },
    easeInOutBounce: function (x, t, b, c, d) {
        if (t < d / 2) return jQuery.easing.easeInBounce(x, t * 2, 0, c, d) * 0.5 + b;
        return jQuery.easing.easeOutBounce(x, t * 2 - d, 0, c, d) * 0.5 + c * 0.5 + b
    }
});
(function (e, t, n) {
    var r = t.event,
        i;
    r.special.smartresize = {
        setup: function () {
            t(this).bind("resize", r.special.smartresize.handler)
        },
        teardown: function () {
            t(this).unbind("resize", r.special.smartresize.handler)
        },
        handler: function (e, t) {
            var n = this,
                s = arguments;
            e.type = "smartresize", i && clearTimeout(i), i = setTimeout(function () {
                r.dispatch.apply(n, s)
            }, t === "execAsap" ? 0 : 100)
        }
    }, t.fn.smartresize = function (e) {
        return e ? this.bind("smartresize", e) : this.trigger("smartresize", ["execAsap"])
    }, t.Mason = function (e, n) {
        this.element = t(n), this._create(e), this._init()
    }, t.Mason.settings = {
        isResizable: !0,
        isAnimated: !1,
        animationOptions: {
            queue: !1,
            duration: 500
        },
        gutterWidth: 0,
        isRTL: !1,
        isFitWidth: !1,
        containerStyle: {
            position: "relative"
        }
    }, t.Mason.prototype = {
        _filterFindBricks: function (e) {
            var t = this.options.itemSelector;
            return t ? e.filter(t).add(e.find(t)) : e
        },
        _getBricks: function (e) {
            var t = this._filterFindBricks(e).css({
                position: "absolute"
            }).addClass("masonry-brick");
            return t
        },
        _create: function (n) {
            this.options = t.extend(!0, {}, t.Mason.settings, n), this.styleQueue = [];
            var r = this.element[0].style;
            this.originalStyle = {
                height: r.height || ""
            };
            var i = this.options.containerStyle;
            for (var s in i) this.originalStyle[s] = r[s] || "";
            this.element.css(i), this.horizontalDirection = this.options.isRTL ? "right" : "left";
            var o = this.element.css("padding-" + this.horizontalDirection),
                u = this.element.css("padding-top");
            this.offset = {
                x: o ? parseInt(o, 10) : 0,
                y: u ? parseInt(u, 10) : 0
            }, this.isFluid = this.options.columnWidth && typeof this.options.columnWidth == "function";
            var a = this;
            setTimeout(function () {
                a.element.addClass("masonry")
            }, 0), this.options.isResizable && t(e).bind("smartresize.masonry", function () {
                a.resize()
            }), this.reloadItems()
        },
        _init: function (e) {
            this._getColumns(), this._reLayout(e)
        },
        option: function (e, n) {
            t.isPlainObject(e) && (this.options = t.extend(!0, this.options, e))
        },
        layout: function (e, t) {
            for (var n = 0, r = e.length; n < r; n++) this._placeBrick(e[n]);
            var i = {};
            i.height = Math.max.apply(Math, this.colYs);
            if (this.options.isFitWidth) {
                var s = 0;
                n = this.cols;
                while (--n) {
                    if (this.colYs[n] !== 0) break;
                    s++
                }
                i.width = (this.cols - s) * this.columnWidth - this.options.gutterWidth
            }
            this.styleQueue.push({
                $el: this.element,
                style: i
            });
            var o = this.isLaidOut ? this.options.isAnimated ? "animate" : "css" : "css",
                u = this.options.animationOptions,
                a;
            for (n = 0, r = this.styleQueue.length; n < r; n++) a = this.styleQueue[n], a.$el[o](a.style, u);
            this.styleQueue = [], t && t.call(e), this.isLaidOut = !0
        },
        _getColumns: function () {
            var e = this.options.isFitWidth ? this.element.parent() : this.element,
                t = e.width();
            this.columnWidth = this.isFluid ? this.options.columnWidth(t) : this.options.columnWidth || (this.$bricks.outerWidth(!0) || t), this.columnWidth += this.options.gutterWidth, this.cols = Math.floor((t + this.options.gutterWidth) / this.columnWidth), this.cols = Math.max(this.cols, 1)
        },
        _placeBrick: function (e) {
            var n = t(e),
                r, i, s, o, u;
            r = Math.ceil(n.outerWidth(!0) / this.columnWidth), r = Math.min(r, this.cols);
            if (r === 1) s = this.colYs;
            else {
                i = this.cols + 1 - r, s = [];
                for (u = 0; u < i; u++) o = this.colYs.slice(u, u + r), s[u] = Math.max.apply(Math, o)
            }
            var a = Math.min.apply(Math, s),
                f = 0;
            for (var l = 0, c = s.length; l < c; l++)
                if (s[l] === a) {
                    f = l;
                    break
                }
            var h = {
                top: a + this.offset.y
            };
            h[this.horizontalDirection] = this.columnWidth * f + this.offset.x, this.styleQueue.push({
                $el: n,
                style: h
            });
            var p = a + n.outerHeight(!0),
                d = this.cols + 1 - c;
            for (l = 0; l < d; l++) this.colYs[f + l] = p
        },
        resize: function () {
            var e = this.cols;
            this._getColumns(), (this.isFluid || this.cols !== e) && this._reLayout()
        },
        _reLayout: function (e) {
            var t = this.cols;
            this.colYs = [];
            while (t--) this.colYs.push(0);
            this.layout(this.$bricks, e)
        },
        reloadItems: function () {
            this.$bricks = this._getBricks(this.element.children())
        },
        reload: function (e) {
            this.reloadItems(), this._init(e)
        },
        appended: function (e, t, n) {
            if (t) {
                this._filterFindBricks(e).css({
                    top: this.element.height()
                });
                var r = this;
                setTimeout(function () {
                    r._appended(e, n)
                }, 1)
            } else this._appended(e, n)
        },
        _appended: function (e, t) {
            var n = this._getBricks(e);
            this.$bricks = this.$bricks.add(n), this.layout(n, t)
        },
        remove: function (e) {
            this.$bricks = this.$bricks.not(e), e.remove()
        },
        destroy: function () {
            this.$bricks.removeClass("masonry-brick").each(function () {
                this.style.position = "", this.style.top = "", this.style.left = ""
            });
            var n = this.element[0].style;
            for (var r in this.originalStyle) n[r] = this.originalStyle[r];
            this.element.unbind(".masonry").removeClass("masonry").removeData("masonry"), t(e).unbind(".masonry")
        }
    }, t.fn.imagesLoaded = function (e) {
        function u() {
            e.call(n, r)
        }

        function a(e) {
            var n = e.target;
            n.src !== s && (t.inArray(n, o) === -1 && (o.push(n), --i <= 0 && (setTimeout(u), r.unbind(".imagesLoaded", a))))
        }
        var n = this,
            r = n.find("img").add(n.filter("img")),
            i = r.length,
            s = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==",
            o = [];
        return i || u(), r.bind("load.imagesLoaded error.imagesLoaded", a).each(function () {
            var e = this.src;
            this.src = s, this.src = e
        }), n
    };
    var s = function (t) {
        e.console && e.console.error(t)
    };
    t.fn.masonry = function (e) {
        if (typeof e == "string") {
            var n = Array.prototype.slice.call(arguments, 1);
            this.each(function () {
                var r = t.data(this, "masonry");
                if (!r) {
                    s("cannot call methods on masonry prior to initialization; attempted to call method '" + e + "'");
                    return
                }
                if (!t.isFunction(r[e]) || e.charAt(0) === "_") {
                    s("no such method '" + e + "' for masonry instance");
                    return
                }
                r[e].apply(r, n)
            })
        } else this.each(function () {
            var n = t.data(this, "masonry");
            n ? (n.option(e || {}), n._init()) : t.data(this, "masonry", new t.Mason(e, this))
        });
        return this
    }
})(window, jQuery);
(function ($) {
    $.fn.superfish = function (d) {
        var e = $.fn.superfish,
            c = e.c,
            $arrow = $(['<span class="', c.arrowClass, '"> &#187;</span>'].join("")),
            over = function () {
                var a = $(this),
                    menu = getMenu(a);
                clearTimeout(menu.sfTimer);
                a.showSuperfishUl().siblings().hideSuperfishUl()
            },
            out = function () {
                var a = $(this),
                    menu = getMenu(a),
                    o = e.op;
                clearTimeout(menu.sfTimer);
                menu.sfTimer = setTimeout(function () {
                    o.retainPath = $.inArray(a[0], o.$path) > -1;
                    a.hideSuperfishUl();
                    if (o.$path.length && a.parents(["li.", o.hoverClass].join("")).length < 1) over.call(o.$path)
                }, o.delay)
            },
            getMenu = function (a) {
                var b = a.parents(["ul.", c.menuClass, ":first"].join(""))[0];
                e.op = e.o[b.serial];
                return b
            },
            addArrow = function (a) {
                a.addClass(c.anchorClass).append($arrow.clone())
            };
        return this.each(function () {
            var s = this.serial = e.o.length;
            var o = $.extend({}, e.defaults, d);
            o.$path = $("li." + o.pathClass, this).slice(0, o.pathLevels).each(function () {
                $(this).addClass([o.hoverClass, c.bcClass].join(" ")).filter("li:has(ul)").removeClass(o.pathClass)
            });
            e.o[s] = e.op = o;
            $("li:has(ul)", this)[$.fn.hoverIntent && !o.disableHI ? "hoverIntent" : "hover"](over, out).each(function () {
                if (o.autoArrows) addArrow($(">a:first-child", this))
            }).not("." + c.bcClass).hideSuperfishUl();
            var b = $("a", this);
            b.each(function (i) {
                var a = b.eq(i).parents("li");
                b.eq(i).focus(function () {
                    over.call(a)
                }).blur(function () {
                    out.call(a)
                })
            });
            o.onInit.call(this)
        }).each(function () {
            var a = [c.menuClass];
            if (e.op.dropShadows && !($.browser.msie && $.browser.version < 7)) a.push(c.shadowClass);
            $(this).addClass(a.join(" "))
        })
    };
    var f = $.fn.superfish;
    f.o = [];
    f.op = {};
    f.IE7fix = function () {
        var o = f.op;
        if ($.browser.msie && ($.browser.version > 6 && (o.dropShadows && o.animation.opacity != undefined))) this.toggleClass(f.c.shadowClass + "-off")
    };
    f.c = {
        bcClass: "sf-breadcrumb",
        menuClass: "sf-js-enabled",
        anchorClass: "sf-with-ul",
        arrowClass: "sf-sub-indicator",
        shadowClass: "sf-shadow"
    };
    f.defaults = {
        hoverClass: "sfHover",
        pathClass: "overideThisToUse",
        pathLevels: 1,
        delay: 800,
        animation: {
            opacity: "show"
        },
        speed: "normal",
        autoArrows: true,
        dropShadows: true,
        disableHI: false,
        onInit: function () {},
        onBeforeShow: function () {},
        onShow: function () {},
        onHide: function () {}
    };
    $.fn.extend({
        hideSuperfishUl: function () {
            var o = f.op,
                not = o.retainPath === true ? o.$path : "";
            o.retainPath = false;
            var a = $(["li.", o.hoverClass].join(""), this).add(this).not(not).removeClass(o.hoverClass).find(">ul").hide().css("visibility", "hidden");
            o.onHide.call(a);
            return this
        },
        showSuperfishUl: function () {
            var o = f.op,
                sh = f.c.shadowClass + "-off",
                $ul = this.addClass(o.hoverClass).find(">ul:hidden").css("visibility", "visible");
            f.IE7fix.call($ul);
            o.onBeforeShow.call($ul);
            $ul.animate(o.animation, o.speed, function () {
                f.IE7fix.call($ul);
                o.onShow.call($ul)
            });
            return this
        }
    })
})(jQuery);
(function ($) {
    $.fn.mobileMenu = function (b) {
        var c = {
                defaultText: "Navigate to...",
                className: "select-menu",
                subMenuClass: "sub-menu",
                subMenuDash: "&ndash;"
            },
            settings = $.extend(c, b),
            el = $(this);
        this.each(function () {
            el.find("ul").addClass(settings.subMenuClass);
            $("<select />", {
                "class": settings.className,
                "title": settings.defaultText
            }).insertAfter(el);
            $("<option />", {
                "value": "#",
                "text": settings.defaultText
            }).appendTo("." + settings.className);
            el.find("a").each(function () {
                var a = $(this),
                    optText = "&nbsp;" + a.text(),
                    optSub = a.parents("." + settings.subMenuClass),
                    len = optSub.length,
                    dash;
                if (a.parents("ul").hasClass(settings.subMenuClass)) {
                    dash = Array(len + 1).join(settings.subMenuDash);
                    optText = dash + optText
                }
                $("<option />", {
                    "value": this.href,
                    "html": optText,
                    "selected": this.href == window.location.href
                }).appendTo("." + settings.className)
            });
            $("." + settings.className).change(function () {
                var a = $(this).val();
                if (a !== "#") window.location.href = $(this).val()
            })
        });
        return this
    }
})(jQuery);
(function (d) {
    d.flexslider = function (j, l) {
        var a = d(j),
            c = d.extend({}, d.flexslider.defaults, l),
            e = c.namespace,
            q = "ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch,
            u = q ? "touchend" : "click",
            m = "vertical" === c.direction,
            n = c.reverse,
            h = 0 < c.itemWidth,
            s = "fade" === c.animation,
            t = "" !== c.asNavFor,
            f = {};
        d.data(j, "flexslider", a);
        f = {
            init: function () {
                a.animating = !1;
                a.currentSlide = c.startAt;
                a.animatingTo = a.currentSlide;
                a.atEnd = 0 === a.currentSlide || a.currentSlide === a.last;
                a.containerSelector = c.selector.substr(0, c.selector.search(" "));
                a.slides = d(c.selector, a);
                a.container = d(a.containerSelector, a);
                a.count = a.slides.length;
                a.syncExists = 0 < d(c.sync).length;
                "slide" === c.animation && (c.animation = "swing");
                a.prop = m ? "top" : "marginLeft";
                a.args = {};
                a.manualPause = !1;
                var b = a,
                    g;
                if (g = !c.video)
                    if (g = !s)
                        if (g = c.useCSS) a: {
                            g = document.createElement("div");
                            var p = ["perspectiveProperty", "WebkitPerspective", "MozPerspective", "OPerspective", "msPerspective"],
                                e;
                            for (e in p)
                                if (void 0 !== g.style[p[e]]) {
                                    a.pfx = p[e].replace("Perspective", "").toLowerCase();
                                    a.prop = "-" + a.pfx + "-transform";
                                    g = !0;
                                    break a
                                }
                            g = !1
                        }
                        b.transitions = g;
                "" !== c.controlsContainer && (a.controlsContainer = 0 < d(c.controlsContainer).length && d(c.controlsContainer));
                "" !== c.manualControls && (a.manualControls = 0 < d(c.manualControls).length && d(c.manualControls));
                c.randomize && (a.slides.sort(function () {
                    return Math.round(Math.random()) - 0.5
                }), a.container.empty().append(a.slides));
                a.doMath();
                t && f.asNav.setup();
                a.setup("init");
                c.controlNav && f.controlNav.setup();
                c.directionNav && f.directionNav.setup();
                c.keyboard && ((1 === d(a.containerSelector).length || c.multipleKeyboard) && d(document).bind("keyup", function (b) {
                    b = b.keyCode;
                    if (!a.animating && (39 === b || 37 === b)) b = 39 === b ? a.getTarget("next") : 37 === b ? a.getTarget("prev") : !1, a.flexAnimate(b, c.pauseOnAction)
                }));
                c.mousewheel && a.bind("mousewheel", function (b, g) {
                    b.preventDefault();
                    var d = 0 > g ? a.getTarget("next") : a.getTarget("prev");
                    a.flexAnimate(d, c.pauseOnAction)
                });
                c.pausePlay && f.pausePlay.setup();
                c.slideshow && (c.pauseOnHover && a.hover(function () {
                    !a.manualPlay && (!a.manualPause && a.pause())
                }, function () {
                    !a.manualPause && (!a.manualPlay && a.play())
                }), 0 < c.initDelay ? setTimeout(a.play, c.initDelay) : a.play());
                q && (c.touch && f.touch());
                (!s || s && c.smoothHeight) && d(window).bind("resize focus", f.resize);
                setTimeout(function () {
                    c.start(a)
                }, 200)
            },
            asNav: {
                setup: function () {
                    a.asNav = !0;
                    a.animatingTo = Math.floor(a.currentSlide / a.move);
                    a.currentItem = a.currentSlide;
                    a.slides.removeClass(e + "active-slide").eq(a.currentItem).addClass(e + "active-slide");
                    a.slides.click(function (b) {
                        b.preventDefault();
                        b = d(this);
                        var g = b.index();
                        !d(c.asNavFor).data("flexslider").animating && (!b.hasClass("active") && (a.direction = a.currentItem < g ? "next" : "prev", a.flexAnimate(g, c.pauseOnAction, !1, !0, !0)))
                    })
                }
            },
            controlNav: {
                setup: function () {
                    a.manualControls ? f.controlNav.setupManual() : f.controlNav.setupPaging()
                },
                setupPaging: function () {
                    var b = 1,
                        g;
                    a.controlNavScaffold = d('<ol class="' + e + "control-nav " + e + ("thumbnails" === c.controlNav ? "control-thumbs" : "control-paging") + '"></ol>');
                    if (1 < a.pagingCount)
                        for (var p = 0; p < a.pagingCount; p++) g = "thumbnails" === c.controlNav ? '<img src="' + a.slides.eq(p).attr("data-thumb") + '"/>' : "<a>" + b + "</a>", a.controlNavScaffold.append("<li>" + g + "</li>"), b++;
                    a.controlsContainer ? d(a.controlsContainer).append(a.controlNavScaffold) : a.append(a.controlNavScaffold);
                    f.controlNav.set();
                    f.controlNav.active();
                    a.controlNavScaffold.delegate("a, img", u, function (b) {
                        b.preventDefault();
                        b = d(this);
                        var g = a.controlNav.index(b);
                        b.hasClass(e + "active") || (a.direction = g > a.currentSlide ? "next" : "prev", a.flexAnimate(g, c.pauseOnAction))
                    });
                    q && a.controlNavScaffold.delegate("a", "click touchstart", function (a) {
                        a.preventDefault()
                    })
                },
                setupManual: function () {
                    a.controlNav = a.manualControls;
                    f.controlNav.active();
                    a.controlNav.live(u, function (b) {
                        b.preventDefault();
                        b = d(this);
                        var g = a.controlNav.index(b);
                        b.hasClass(e + "active") || (g > a.currentSlide ? a.direction = "next" : a.direction = "prev", a.flexAnimate(g, c.pauseOnAction))
                    });
                    q && a.controlNav.live("click touchstart", function (a) {
                        a.preventDefault()
                    })
                },
                set: function () {
                    a.controlNav = d("." + e + "control-nav li " + ("thumbnails" === c.controlNav ? "img" : "a"), a.controlsContainer ? a.controlsContainer : a)
                },
                active: function () {
                    a.controlNav.removeClass(e + "active").eq(a.animatingTo).addClass(e + "active")
                },
                update: function (b, c) {
                    1 < a.pagingCount && "add" === b ? a.controlNavScaffold.append(d("<li><a>" + a.count + "</a></li>")) : 1 === a.pagingCount ? a.controlNavScaffold.find("li").remove() : a.controlNav.eq(c).closest("li").remove();
                    f.controlNav.set();
                    1 < a.pagingCount && a.pagingCount !== a.controlNav.length ? a.update(c, b) : f.controlNav.active()
                }
            },
            directionNav: {
                setup: function () {
                    var b = d('<ul class="' + e + 'direction-nav"><li><a class="' + e + 'prev" href="#">' + c.prevText + '</a></li><li><a class="' + e + 'next" href="#">' + c.nextText + "</a></li></ul>");
                    a.controlsContainer ? (d(a.controlsContainer).append(b), a.directionNav = d("." + e + "direction-nav li a", a.controlsContainer)) : (a.append(b), a.directionNav = d("." + e + "direction-nav li a", a));
                    f.directionNav.update();
                    a.directionNav.bind(u, function (b) {
                        b.preventDefault();
                        b = d(this).hasClass(e + "next") ? a.getTarget("next") : a.getTarget("prev");
                        a.flexAnimate(b, c.pauseOnAction)
                    });
                    q && a.directionNav.bind("click touchstart", function (a) {
                        a.preventDefault()
                    })
                },
                update: function () {
                    var b = e + "disabled";
                    1 === a.pagingCount ? a.directionNav.addClass(b) : c.animationLoop ? a.directionNav.removeClass(b) : 0 === a.animatingTo ? a.directionNav.removeClass(b).filter("." + e + "prev").addClass(b) : a.animatingTo === a.last ? a.directionNav.removeClass(b).filter("." + e + "next").addClass(b) : a.directionNav.removeClass(b)
                }
            },
            pausePlay: {
                setup: function () {
                    var b = d('<div class="' + e + 'pauseplay"><a></a></div>');
                    a.controlsContainer ? (a.controlsContainer.append(b), a.pausePlay = d("." + e + "pauseplay a", a.controlsContainer)) : (a.append(b), a.pausePlay = d("." + e + "pauseplay a", a));
                    f.pausePlay.update(c.slideshow ? e + "pause" : e + "play");
                    a.pausePlay.bind(u, function (b) {
                        b.preventDefault();
                        d(this).hasClass(e + "pause") ? (a.manualPause = !0, a.manualPlay = !1, a.pause()) : (a.manualPause = !1, a.manualPlay = !0, a.play())
                    });
                    q && a.pausePlay.bind("click touchstart", function (a) {
                        a.preventDefault()
                    })
                },
                update: function (b) {
                    "play" === b ? a.pausePlay.removeClass(e + "pause").addClass(e + "play").text(c.playText) : a.pausePlay.removeClass(e + "play").addClass(e + "pause").text(c.pauseText)
                }
            },
            touch: function () {
                function b(b) {
                    k = m ? d - b.touches[0].pageY : d - b.touches[0].pageX;
                    q = m ? Math.abs(k) < Math.abs(b.touches[0].pageX - e) : Math.abs(k) < Math.abs(b.touches[0].pageY - e);
                    if (!q || 500 < Number(new Date) - l) b.preventDefault(), !s && (a.transitions && (c.animationLoop || (k /= 0 === a.currentSlide && 0 > k || a.currentSlide === a.last && 0 < k ? Math.abs(k) / r + 2 : 1), a.setProps(f + k, "setTouch")))
                }

                function g() {
                    j.removeEventListener("touchmove", b, !1);
                    if (a.animatingTo === a.currentSlide && (!q && null !== k)) {
                        var h = n ? -k : k,
                            m = 0 < h ? a.getTarget("next") : a.getTarget("prev");
                        a.canAdvance(m) && (550 > Number(new Date) - l && 50 < Math.abs(h) || Math.abs(h) > r / 2) ? a.flexAnimate(m, c.pauseOnAction) : s || a.flexAnimate(a.currentSlide, c.pauseOnAction, !0)
                    }
                    j.removeEventListener("touchend", g, !1);
                    f = k = e = d = null
                }
                var d, e, f, r, k, l, q = !1;
                j.addEventListener("touchstart", function (k) {
                    a.animating ? k.preventDefault() : 1 === k.touches.length && (a.pause(), r = m ? a.h : a.w, l = Number(new Date), f = h && (n && a.animatingTo === a.last) ? 0 : h && n ? a.limit - (a.itemW + c.itemMargin) * a.move * a.animatingTo : h && a.currentSlide === a.last ? a.limit : h ? (a.itemW + c.itemMargin) * a.move * a.currentSlide : n ? (a.last - a.currentSlide + a.cloneOffset) * r : (a.currentSlide + a.cloneOffset) * r, d = m ? k.touches[0].pageY : k.touches[0].pageX, e = m ? k.touches[0].pageX : k.touches[0].pageY, j.addEventListener("touchmove", b, !1), j.addEventListener("touchend", g, !1))
                }, !1)
            },
            resize: function () {
                !a.animating && (a.is(":visible") && (h || a.doMath(), s ? f.smoothHeight() : h ? (a.slides.width(a.computedW), a.update(a.pagingCount), a.setProps()) : m ? (a.viewport.height(a.h), a.setProps(a.h, "setTotal")) : (c.smoothHeight && f.smoothHeight(), a.newSlides.width(a.computedW), a.setProps(a.computedW, "setTotal"))))
            },
            smoothHeight: function (b) {
                if (!m || s) {
                    var c = s ? a : a.viewport;
                    b ? c.animate({
                        height: a.slides.eq(a.animatingTo).height()
                    }, b) : c.height(a.slides.eq(a.animatingTo).height())
                }
            },
            sync: function (b) {
                var g = d(c.sync).data("flexslider"),
                    e = a.animatingTo;
                switch (b) {
                case "animate":
                    g.flexAnimate(e, c.pauseOnAction, !1, !0);
                    break;
                case "play":
                    !g.playing && (!g.asNav && g.play());
                    break;
                case "pause":
                    g.pause()
                }
            }
        };
        a.flexAnimate = function (b, g, p, j, l) {
            t && (1 === a.pagingCount && (a.direction = a.currentItem < b ? "next" : "prev"));
            if (!a.animating && ((a.canAdvance(b, l) || p) && a.is(":visible"))) {
                if (t && j)
                    if (p = d(c.asNavFor).data("flexslider"), a.atEnd = 0 === b || b === a.count - 1, p.flexAnimate(b, !0, !1, !0, l), a.direction = a.currentItem < b ? "next" : "prev", p.direction = a.direction, Math.ceil((b + 1) / a.visible) - 1 !== a.currentSlide && 0 !== b) a.currentItem = b, a.slides.removeClass(e + "active-slide").eq(b).addClass(e + "active-slide"), b = Math.floor(b / a.visible);
                    else return a.currentItem = b, a.slides.removeClass(e + "active-slide").eq(b).addClass(e + "active-slide"), !1;
                a.animating = !0;
                a.animatingTo = b;
                c.before(a);
                g && a.pause();
                a.syncExists && (!l && f.sync("animate"));
                c.controlNav && f.controlNav.active();
                h || a.slides.removeClass(e + "active-slide").eq(b).addClass(e + "active-slide");
                a.atEnd = 0 === b || b === a.last;
                c.directionNav && f.directionNav.update();
                b === a.last && (c.end(a), c.animationLoop || a.pause());
                if (s) q ? (a.slides.eq(a.currentSlide).css({
                    opacity: 0,
                    zIndex: 1
                }), a.slides.eq(b).css({
                    opacity: 1,
                    zIndex: 2
                }), a.slides.unbind("webkitTransitionEnd transitionend"), a.slides.eq(a.currentSlide).bind("webkitTransitionEnd transitionend", function () {
                    c.after(a)
                }), a.animating = !1, a.currentSlide = a.animatingTo) : (a.slides.eq(a.currentSlide).fadeOut(c.animationSpeed, c.easing), a.slides.eq(b).fadeIn(c.animationSpeed, c.easing, a.wrapup));
                else {
                    var r = m ? a.slides.filter(":first").height() : a.computedW;
                    h ? (b = c.itemWidth > a.w ? 2 * c.itemMargin : c.itemMargin, b = (a.itemW + b) * a.move * a.animatingTo, b = b > a.limit && 1 !== a.visible ? a.limit : b) : b = 0 === a.currentSlide && (b === a.count - 1 && (c.animationLoop && "next" !== a.direction)) ? n ? (a.count + a.cloneOffset) * r : 0 : a.currentSlide === a.last && (0 === b && (c.animationLoop && "prev" !== a.direction)) ? n ? 0 : (a.count + 1) * r : n ? (a.count - 1 - b + a.cloneOffset) * r : (b + a.cloneOffset) * r;
                    a.setProps(b, "", c.animationSpeed);
                    if (a.transitions) {
                        if (!c.animationLoop || !a.atEnd) a.animating = !1, a.currentSlide = a.animatingTo;
                        a.container.unbind("webkitTransitionEnd transitionend");
                        a.container.bind("webkitTransitionEnd transitionend", function () {
                            a.wrapup(r)
                        })
                    } else a.container.animate(a.args, c.animationSpeed, c.easing, function () {
                        a.wrapup(r)
                    })
                }
                c.smoothHeight && f.smoothHeight(c.animationSpeed)
            }
        };
        a.wrapup = function (b) {
            !s && (!h && (0 === a.currentSlide && (a.animatingTo === a.last && c.animationLoop) ? a.setProps(b, "jumpEnd") : a.currentSlide === a.last && (0 === a.animatingTo && (c.animationLoop && a.setProps(b, "jumpStart")))));
            a.animating = !1;
            a.currentSlide = a.animatingTo;
            c.after(a)
        };
        a.animateSlides = function () {
            a.animating || a.flexAnimate(a.getTarget("next"))
        };
        a.pause = function () {
            clearInterval(a.animatedSlides);
            a.playing = !1;
            c.pausePlay && f.pausePlay.update("play");
            a.syncExists && f.sync("pause")
        };
        a.play = function () {
            a.animatedSlides = setInterval(a.animateSlides, c.slideshowSpeed);
            a.playing = !0;
            c.pausePlay && f.pausePlay.update("pause");
            a.syncExists && f.sync("play")
        };
        a.canAdvance = function (b, g) {
            var d = t ? a.pagingCount - 1 : a.last;
            return g ? !0 : t && (a.currentItem === a.count - 1 && (0 === b && "prev" === a.direction)) ? !0 : t && (0 === a.currentItem && (b === a.pagingCount - 1 && "next" !== a.direction)) ? !1 : b === a.currentSlide && !t ? !1 : c.animationLoop ? !0 : a.atEnd && (0 === a.currentSlide && (b === d && "next" !== a.direction)) ? !1 : a.atEnd && (a.currentSlide === d && (0 === b && "next" === a.direction)) ? !1 : !0
        };
        a.getTarget = function (b) {
            a.direction = b;
            return "next" === b ? a.currentSlide === a.last ? 0 : a.currentSlide + 1 : 0 === a.currentSlide ? a.last : a.currentSlide - 1
        };
        a.setProps = function (b, g, d) {
            var e, f = b ? b : (a.itemW + c.itemMargin) * a.move * a.animatingTo;
            e = -1 * function () {
                if (h) return "setTouch" === g ? b : n && a.animatingTo === a.last ? 0 : n ? a.limit - (a.itemW + c.itemMargin) * a.move * a.animatingTo : a.animatingTo === a.last ? a.limit : f;
                switch (g) {
                case "setTotal":
                    return n ? (a.count - 1 - a.currentSlide + a.cloneOffset) * b : (a.currentSlide + a.cloneOffset) * b;
                case "setTouch":
                    return b;
                case "jumpEnd":
                    return n ? b : a.count * b;
                case "jumpStart":
                    return n ? a.count * b : b;
                default:
                    return b
                }
            }() + "px";
            a.transitions && (e = m ? "translate3d(0," + e + ",0)" : "translate3d(" + e + ",0,0)", d = void 0 !== d ? d / 1E3 + "s" : "0s", a.container.css("-" + a.pfx + "-transition-duration", d));
            a.args[a.prop] = e;
            (a.transitions || void 0 === d) && a.container.css(a.args)
        };
        a.setup = function (b) {
            if (s) a.slides.css({
                width: "100%",
                "float": "left",
                marginRight: "-100%",
                position: "relative"
            }), "init" === b && (q ? a.slides.css({
                opacity: 0,
                display: "block",
                webkitTransition: "opacity " + c.animationSpeed / 1E3 + "s ease",
                zIndex: 1
            }).eq(a.currentSlide).css({
                opacity: 1,
                zIndex: 2
            }) : a.slides.eq(a.currentSlide).fadeIn(c.animationSpeed, c.easing)), c.smoothHeight && f.smoothHeight();
            else {
                var g, p;
                "init" === b && (a.viewport = d('<div class="' + e + 'viewport"></div>').css({
                    overflow: "hidden",
                    position: "relative"
                }).appendTo(a).append(a.container), a.cloneCount = 0, a.cloneOffset = 0, n && (p = d.makeArray(a.slides).reverse(), a.slides = d(p), a.container.empty().append(a.slides)));
                c.animationLoop && (!h && (a.cloneCount = 2, a.cloneOffset = 1, "init" !== b && a.container.find(".clone").remove(), a.container.append(a.slides.first().clone().addClass("clone")).prepend(a.slides.last().clone().addClass("clone"))));
                a.newSlides = d(c.selector, a);
                g = n ? a.count - 1 - a.currentSlide + a.cloneOffset : a.currentSlide + a.cloneOffset;
                m && !h ? (a.container.height(200 * (a.count + a.cloneCount) + "%").css("position", "absolute").width("100%"), setTimeout(function () {
                    a.newSlides.css({
                        display: "block"
                    });
                    a.doMath();
                    a.viewport.height(a.h);
                    a.setProps(g * a.h, "init")
                }, "init" === b ? 100 : 0)) : (a.container.width(200 * (a.count + a.cloneCount) + "%"), a.setProps(g * a.computedW, "init"), setTimeout(function () {
                    a.doMath();
                    a.newSlides.css({
                        width: a.computedW,
                        "float": "left",
                        display: "block"
                    });
                    c.smoothHeight && f.smoothHeight()
                }, "init" === b ? 100 : 0))
            }
            h || a.slides.removeClass(e + "active-slide").eq(a.currentSlide).addClass(e + "active-slide")
        };
        a.doMath = function () {
            var b = a.slides.first(),
                d = c.itemMargin,
                e = c.minItems,
                f = c.maxItems;
            a.w = a.width();
            a.h = b.height();
            a.boxPadding = b.outerWidth() - b.width();
            h ? (a.itemT = c.itemWidth + d, a.minW = e ? e * a.itemT : a.w, a.maxW = f ? f * a.itemT : a.w, a.itemW = a.minW > a.w ? (a.w - d * e) / e : a.maxW < a.w ? (a.w - d * f) / f : c.itemWidth > a.w ? a.w : c.itemWidth, a.visible = Math.floor(a.w / (a.itemW + d)), a.move = 0 < c.move && c.move < a.visible ? c.move : a.visible, a.pagingCount = Math.ceil((a.count - a.visible) / a.move + 1), a.last = a.pagingCount - 1, a.limit = 1 === a.pagingCount ? 0 : c.itemWidth > a.w ? (a.itemW + 2 * d) * a.count - a.w - d : (a.itemW + d) * a.count - a.w - d) : (a.itemW = a.w, a.pagingCount = a.count, a.last = a.count - 1);
            a.computedW = a.itemW - a.boxPadding
        };
        a.update = function (b, d) {
            a.doMath();
            h || (b < a.currentSlide ? a.currentSlide += 1 : b <= a.currentSlide && (0 !== b && (a.currentSlide -= 1)), a.animatingTo = a.currentSlide);
            if (c.controlNav && !a.manualControls)
                if ("add" === d && !h || a.pagingCount > a.controlNav.length) f.controlNav.update("add");
                else if ("remove" === d && !h || a.pagingCount < a.controlNav.length) h && (a.currentSlide > a.last && (a.currentSlide -= 1, a.animatingTo -= 1)), f.controlNav.update("remove", a.last);
            c.directionNav && f.directionNav.update()
        };
        a.addSlide = function (b, e) {
            var f = d(b);
            a.count += 1;
            a.last = a.count - 1;
            m && n ? void 0 !== e ? a.slides.eq(a.count - e).after(f) : a.container.prepend(f) : void 0 !== e ? a.slides.eq(e).before(f) : a.container.append(f);
            a.update(e, "add");
            a.slides = d(c.selector + ":not(.clone)", a);
            a.setup();
            c.added(a)
        };
        a.removeSlide = function (b) {
            var e = isNaN(b) ? a.slides.index(d(b)) : b;
            a.count -= 1;
            a.last = a.count - 1;
            isNaN(b) ? d(b, a.slides).remove() : m && n ? a.slides.eq(a.last).remove() : a.slides.eq(b).remove();
            a.doMath();
            a.update(e, "remove");
            a.slides = d(c.selector + ":not(.clone)", a);
            a.setup();
            c.removed(a)
        };
        f.init()
    };
    d.flexslider.defaults = {
        namespace: "flex-",
        selector: ".slides > li",
        animation: "fade",
        easing: "swing",
        direction: "horizontal",
        reverse: !1,
        animationLoop: !0,
        smoothHeight: !1,
        startAt: 0,
        slideshow: !0,
        slideshowSpeed: 7E3,
        animationSpeed: 600,
        initDelay: 0,
        randomize: !1,
        pauseOnAction: !0,
        pauseOnHover: !1,
        useCSS: !0,
        touch: !0,
        video: !1,
        controlNav: !0,
        directionNav: !0,
        prevText: "Previous",
        nextText: "Next",
        keyboard: !0,
        multipleKeyboard: !1,
        mousewheel: !1,
        pausePlay: !1,
        pauseText: "Pause",
        playText: "Play",
        controlsContainer: "",
        manualControls: "",
        sync: "",
        asNavFor: "",
        itemWidth: 0,
        itemMargin: 0,
        minItems: 0,
        maxItems: 0,
        move: 0,
        start: function () {},
        before: function () {},
        after: function () {},
        end: function () {},
        added: function () {},
        removed: function () {}
    };
    d.fn.flexslider = function (j) {
        void 0 === j && (j = {});
        if ("object" === typeof j) return this.each(function () {
            var a = d(this),
                c = a.find(j.selector ? j.selector : ".slides > li");
            1 === c.length ? (c.fadeIn(400), j.start && j.start(a)) : void 0 == a.data("flexslider") && new d.flexslider(this, j)
        });
        var l = d(this).data("flexslider");
        switch (j) {
        case "play":
            l.play();
            break;
        case "pause":
            l.pause();
            break;
        case "next":
            l.flexAnimate(l.getTarget("next"), !0);
            break;
        case "prev":
        case "previous":
            l.flexAnimate(l.getTarget("prev"), !0);
            break;
        default:
            "number" === typeof j && l.flexAnimate(j, !0)
        }
    }
})(jQuery);
(function (f, h, $) {
    var a = "placeholder" in h.createElement("input"),
        d = "placeholder" in h.createElement("textarea"),
        i = $.fn,
        c = $.valHooks,
        k, j;
    if (a && d) {
        j = i.placeholder = function () {
            return this
        };
        j.input = j.textarea = true
    } else {
        j = i.placeholder = function () {
            var l = this;
            l.filter((a ? "textarea" : ":input") + "[placeholder]").not(".placeholder").bind({
                "focus.placeholder": b,
                "blur.placeholder": e
            }).data("placeholder-enabled", true).trigger("blur.placeholder");
            return l
        };
        j.input = a;
        j.textarea = d;
        k = {
            get: function (m) {
                var l = $(m);
                return l.data("placeholder-enabled") && l.hasClass("placeholder") ? "" : m.value
            },
            set: function (m, n) {
                var l = $(m);
                if (!l.data("placeholder-enabled")) return m.value = n;
                if (n == "") {
                    m.value = n;
                    if (m != h.activeElement) e.call(m)
                } else if (l.hasClass("placeholder")) b.call(m, true, n) || (m.value = n);
                else m.value = n;
                return l
            }
        };
        a || (c.input = k);
        d || (c.textarea = k);
        $(function () {
            $(h).delegate("form", "submit.placeholder", function () {
                var l = $(".placeholder", this).each(b);
                setTimeout(function () {
                    l.each(e)
                }, 10)
            })
        });
        $(f).bind("beforeunload.placeholder", function () {
            $(".placeholder").each(function () {
                this.value = ""
            })
        })
    }

    function g(m) {
        var l = {},
            n = /^jQuery\d+$/;
        $.each(m.attributes, function (p, o) {
            if (o.specified && !n.test(o.name)) l[o.name] = o.value
        });
        return l
    }

    function b(m, n) {
        var l = this,
            o = $(l);
        if (l.value == o.attr("placeholder") && o.hasClass("placeholder"))
            if (o.data("placeholder-password")) {
                o = o.hide().next().show().attr("id", o.removeAttr("id").data("placeholder-id"));
                if (m === true) return o[0].value = n;
                o.focus()
            } else {
                l.value = "";
                o.removeClass("placeholder");
                l == h.activeElement && l.select()
            }
    }

    function e() {
        var q, l = this,
            p = $(l),
            m = p,
            o = this.id;
        if (l.value == "") {
            if (l.type == "password") {
                if (!p.data("placeholder-textinput")) {
                    try {
                        q = p.clone().attr({
                            type: "text"
                        })
                    } catch (n) {
                        q = $("<input>").attr($.extend(g(this), {
                            type: "text"
                        }))
                    }
                    q.removeAttr("name").data({
                        "placeholder-password": true,
                        "placeholder-id": o
                    }).bind("focus.placeholder", b);
                    p.data({
                        "placeholder-textinput": q,
                        "placeholder-id": o
                    }).before(q)
                }
                p = p.removeAttr("id").hide().prev().attr("id", o).show()
            }
            p.addClass("placeholder");
            p[0].value = p.attr("placeholder")
        } else p.removeClass("placeholder")
    }
})(this, document, jQuery);
var $j = jQuery.noConflict();
$j(document).ready(function () {
    $j(".flexslider").flexslider({
        autoPlay: true,
        pauseOnAction: false,
        animation: "fade",
        start: function (a) {
            $j(".caption_wrapper").animate({
                "bottom": "50px",
                "opacity": 1
            }, 500)
        },
        before: function (a) {
            $j(".caption_wrapper").animate({
                "bottom": "0px",
                "opacity": 0
            }, 500)
        },
        after: function (a) {
            $j(".caption_wrapper").animate({
                "bottom": "50px",
                "opacity": 1
            }, 500)
        }
    });
    set_slider_height();
    $j(window).resize(function () {
        set_slider_height()
    });

    function set_slider_height() {
        var a = parseInt($j("#homeslider ul.slides").find("li").attr("data-height"));
        var b;
        if ($j(window).width() < 1030) b = a * $j(window).width() / 1030;
        else b = a;
        $j("#homeslider ul.slides").find("li").height(b)
    }
    var c = $j("#post_grids");
    c.imagesLoaded(function () {
        c.masonry({
            itemSelector: ".post_col"
        })
    });
    $j("#post_grids").masonry({
        itemSelector: ".post_col",
        gutterWidth: 0,
        columnWidth: function (a) {
            return a / 3
        }
    });
    $j("#Header1_headerimg").load(function () {
        var a = $j(this).height();
        var b = a / 2 - 13;
        $j(".socials").css("padding-top", b)
    });
    $j("input, textarea").placeholder();
    $j("ul.sf-menu").superfish({
        animation: {
            opacity: "show"
        },
        speed: 200,
        delay: 10,
        animation: {
            opacity: "show",
            height: "show"
        }
    });
    $j("#top_menu ul.sf-menu").mobileMenu({
        defaultText: "Navigation ...",
        className: "select_menu",
        subMenuDash: "&ndash;"
    });
    $j("select.select_menu").each(function () {
        var a = $j(this).attr("title");
        if ($j("option:selected", this).val() != "") a = jQuery("option:selected", this).text();
        $j(this).css({
            "z-index": 10,
            "opacity": 0,
            "-khtml-appearance": "none"
        }).after('<span class="nav_select">' + a + '<span class="menu_icon_wrapper"><span class="menu_icon"></span></span></span>').change(function () {
            val = $jj("option:selected", this).text();
            $j(this).next().text(val)
        })
    });
    $j(".search_btn").click(function () {
        $j(this).toggleClass("close");
        $j(".search_box").toggleClass("show");
        $j(".sf-menu").toggleClass("hide")
    })
});

document.addEventListener('DOMContentLoaded', function(event) {
  window.cookieChoices && cookieChoices.showCookieConsentBar && cookieChoices.showCookieConsentBar(
      (window.cookieOptions && cookieOptions.msg) || 'Ce site utilise des cookies provenant de Google pour fournir ses services et analyser le trafic. Votre adresse\xa0IP et votre user-agent, ainsi que des statistiques relatives aux performances et \xe0 la s\xe9curit\xe9, sont transmis \xe0 Google afin d\x27assurer un service de qualit\xe9, de g\xe9n\xe9rer des statistiques d\x27utilisation, et de d\xe9tecter et de r\xe9soudre les probl\xe8mes d\x27abus.',
      (window.cookieOptions && cookieOptions.close) || 'OK',
      (window.cookieOptions && cookieOptions.learn) || 'En savoir plus',
      (window.cookieOptions && cookieOptions.link) || 'https://www.blogger.com/go/blogspot-cookies');
});
