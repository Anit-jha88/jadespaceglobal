var {createElement} = wp.element;
var {registerPlugin} = wp.plugins;
var {ExperimentalOrderMeta} = wc.blocksCheckout;
var {registerExpressPaymentMethod, registerPaymentMethod} = wc.wcBlocksRegistry;
var {addAction} = wp.hooks;

(function (e) {
    var t = {};
    function n(o) {
        if (t[o])
            return t[o].exports;
        var r = (t[o] = {i: o, l: !1, exports: {}});
        return e[o].call(r.exports, r, r.exports, n), (r.l = !0), r.exports;
    }
    n.m = e;
    n.c = t;
    n.d = function (e, t, o) {
        if (!n.o(e, t)) {
            Object.defineProperty(e, t, {enumerable: !0, get: o});
        }
    };
    n.r = function (e) {
        if (typeof Symbol !== "undefined" && Symbol.toStringTag) {
            Object.defineProperty(e, Symbol.toStringTag, {value: "Module"});
        }
        Object.defineProperty(e, "__esModule", {value: !0});
    };
    n.t = function (e, t) {
        if (1 & t && (e = n(e)), 8 & t)
            return e;
        if (4 & t && typeof e === "object" && e && e.__esModule)
            return e;
        var o = Object.create(null);
        if (
                (n.r(o),
                        Object.defineProperty(o, "default", {enumerable: !0, value: e}),
                        2 & t && typeof e !== "string")
                ) {
            for (var r in e)
                n.d(
                        o,
                        r,
                        function (t) {
                            return e[t];
                        }.bind(null, r)
                        );
        }
        return o;
    };
    n.n = function (e) {
        var t =
                e && e.__esModule
                ? function () {
                    return e.default;
                }
        : function () {
            return e;
        };
        return n.d(t, "a", t), t;
    };
    n.o = function (e, t) {
        return Object.prototype.hasOwnProperty.call(e, t);
    };
    n.p = "";
    n(n.s = 6);
})([
    function (e, t) {
        e.exports = window.wp.element;
    },
    function (e, t) {
        e.exports = window.wp.htmlEntities;
    },
    function (e, t) {
        e.exports = window.wp.i18n;
    },
    function (e, t) {
        e.exports = window.wc.wcSettings;
    },
    function (e, t) {
        e.exports = window.wc.wcBlocksRegistry;
    },
    ,
            function (e, t, n) {
                "use strict";
                n.r(t);
                var o = n(0),
                        r = n(4),
                        c = n(2),
                        i = n(3),
                        u = n(1);


            
                const l = Object(i.getSetting)("pgppw_phonepe_data", {});
                const {useEffect, createElement} = wp.element;
                const ContentPhonePeCheckout = (props) => {
                    if (l.currency !== 'INR') {
                        return createElement(
                                "div",
                                {className: "phonepe_checkout_notice"},
                                createElement("p", {className: "phonepe_notice_message"}, l.inr_notice),
                                l.manage_woocommerce === 'yes' &&
                                createElement("small", {className: "phonepe_notice_admin_guide"}, l.admin_guide)
                                );
                    }
                    const showRedirectIcon = !!l.redirect_icon && String(l.hide_redirect_icon || '').toLowerCase() !== 'yes';
                    return createElement(
                            "div",
                            {className: "phonepe_checkout_parent"},
                            createElement(
                                    "div",
                                    {className: "wc_pgppw_container"},
                                    showRedirectIcon &&
                                    createElement("img", {
                                        src: l.redirect_icon,
                                        alt: "PhonePe"
                                    }),
                                    createElement("p", null, l.description || '')
                                    )
                            );
                };
                const phonepePaymentMethod = {
                    name: "pgppw_phonepe",
                    label: createElement(
                            "span",
                            {style: {width: "100%"}},
                            l.title,
                            createElement("img", {
                                src: l.icons,
                                style: {
                                    float: "right",
                                    marginLeft: "20px",
                                    display: "flex",
                                    justifyContent: "flex-end",
                                    paddingRight: "10px"
                                }
                            })
                            ),
                    placeOrderButtonLabel: Object(c.__)(l.placeOrderButtonLabel),
                    content: createElement(ContentPhonePeCheckout, null),
                    edit: createElement(ContentPhonePeCheckout, null),
                    canMakePayment: () => Promise.resolve(true),
                    ariaLabel: Object(u.decodeEntities)(l.title || Object(c.__)("Payment via PhonePe", "woo-gutenberg-products-block")),
                    supports: {
                        features: l.supports || [],
                        showSavedCards: false,
                        showSaveOption: false
                    }
                };
                Object(r.registerPaymentMethod)(phonepePaymentMethod);
            }
]);