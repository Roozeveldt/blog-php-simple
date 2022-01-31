"use strict";
(window.util = {
    isEscEvent: function (t, e) {
        27 === t.keyCode && e();
    },
    getScrollbarWidth: function () {
        return window.innerWidth - document.documentElement.clientWidth;
    },
}),
    (function () {
        var t = function (t) {
                var i = t.querySelector(".tabs__list").querySelectorAll(".tabs__item"),
                    e = t.querySelectorAll(".tabs__content"),
                    c = 0,
                    n = !1,
                    r = function () {
                        if (!n) {
                            var t = !1;
                            n = !0;
                            for (var e = 0; e < i.length; e++) {
                                var r = i[e];
                                t && r.classList.contains("tabs__item--active") && ((t = !0), (c = e)), s(r, e);
                            }
                        }
                    },
                    s = function (t, e) {
                        t.addEventListener("click", function (t) {
                            t.preventDefault(), a(e);
                        });
                    },
                    a = function (t) {
                        if (t !== c) {
                            if (
                                (i[c].classList.remove("tabs__item--active"),
                                i[t].classList.add("tabs__item--active"),
                                e[c].classList.remove("tabs__content--active"),
                                e[t].classList.add("tabs__content--active"),
                                i[t].classList.contains("filters__button"))
                            )
                                i[t].parentNode.parentNode.querySelector(".filters__button--active").classList.remove("filters__button--active"), i[t].classList.add("filters__button--active");
                            if (i[t].classList.contains("messages__contacts-tab"))
                                i[t].parentNode.parentNode.querySelector(".messages__contacts-tab--active").classList.remove("messages__contacts-tab--active"), i[t].classList.add("messages__contacts-tab--active");
                            c = t;
                        }
                    };
                return r(), { init: r, goToTab: a };
            },
            e = document.querySelector(".adding-post__tabs-wrapper"),
            r = document.querySelector(".profile__tabs-wrapper"),
            i = document.querySelector(".messages");
        if (e) t(e);
        if (r) t(r);
        if (i) t(i);
    })(),
    document.querySelector(".modal--active"),
    document.querySelector(".modal"),
    document.querySelector(".modal--adding"),
    document.querySelector(".adding-post__submit"),
    window.util.getScrollbarWidth(),
    document.querySelector(".page__main-section"),
    document.querySelector(".footer__wrapper"),
    (function () {
        var t = document.querySelector(".sorting");
        if (t)
            for (
                var e = t.querySelectorAll(".sorting__link"),
                    r = t.querySelector(".sorting__link--active"),
                    i = function (t) {
                        t.preventDefault(), t.currentTarget === r ? r.classList.toggle("sorting__link--reverse") : (r.classList.remove("sorting__link--active"), t.currentTarget.classList.add("sorting__link--active"), (r = t.currentTarget));
                    },
                    c = 0;
                c < e.length;
                c++
            )
                e[c].addEventListener("click", i);
    })(),
    (function () {
        var t = document.querySelector(".filters");
        if (t) var e = t.querySelectorAll(".filters__button:not(.tabs__item)");
        if (e)
            for (
                var r = t.querySelector(".filters__button--active"),
                    i = function (t) {
                        /* t.preventDefault(),  */t.currentTarget !== r && (r.classList.remove("filters__button--active"), t.currentTarget.classList.add("filters__button--active"), (r = t.currentTarget));
                    },
                    c = 0;
                c < e.length;
                c++
            )
                e[c].addEventListener("click", i);
    })();
