(function ($) {
    "use strict";

    let Vemid = this;

    Vemid.namespace("notification");

    Vemid.notification = (function () {

        return {

            fromSession: function () {
                let $sessionHolder = $("#flash-session");

                $sessionHolder.hide()
                    .find("div").each(function () {
                    $(this).find("button").remove()
                    let message = $(this).text();
                    if ("" !== message) {
                        if ($(this).hasClass("alert-warning")) {
                            toastr.warning(message);
                        } else if ($(this).hasClass("alert-success")) {
                            toastr.success(message);
                        } else {
                            toastr.error(message);
                        }
                    }
                });
            },

            fromResponse: function (response) {
                if (typeof response.messages !== 'undefined') {
                    $.each(response.messages, function (index, messageObject) {
                        if (messageObject.type === 'danger') {
                            toastr.error(messageObject.message);
                            $(".loader-box").hide();
                        } if (messageObject.type === 'warning') {
                            toastr.warning(messageObject.message);
                            $(".loader-box").hide();
                        } else {
                            toastr.success(messageObject.message);
                        }
                    });
                }
            },

            init: function () {
                this.fromSession();
            }
        };
    })();
}).call(Vemid, $);