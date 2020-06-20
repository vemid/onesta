(function ($) {
    "use strict";

    let Vemid = this;

    Vemid.namespace("misc");

    Vemid.misc = (function () {

        return {
            makeAjaxCall: function (url, methodType, data) {
                return $.ajax({
                    url: url,
                    method: methodType,
                    data: data,
                    dataType: "json",
                    enctype: "multipart/form-data",
                    processData: false,
                    contentType: false,
                })
            },

            initChosen: function () {
                $(".chosen-search").chosen({
                    placeholder_text_multiple: Vemid.language.get('choose')
                });
            },

            initSwitcherCheckbox: function () {
                $(".switcher").each(function (key, elem) {
                    if (!elem.hasAttribute("data-switchery")) {
                        let check = elem;
                        new Switchery(elem, {color: "#1AB394"});
                        elem.onchange = function () {
                            $(check).attr('checked', check.checked);
                            $(check).val(check.checked ? 1 : 0);
                        }
                    }
                });
            },

            readFileFromInput: function () {
                $('input[type="file"]').change(function (e) {
                    let fileName = e.target.files[0].name;
                    $(this).next("label").text(fileName);
                });
            },

            initFootTable: function () {
                $(".footable").footable({
                    pageSize: 25,
                });
            },

            setupToastr: function () {
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "onclick": null,
                    "showDuration": "400",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut",
                    "preventDuplicates": true
                };
            },
            maintainCursorPositionAfterReloadPage: function () {
                function refreshPage() {
                    let page_y = document.getElementsByTagName("body")[0].scrollTop;
                    window.location.href = window.location.href.split('?')[0] + '?page_y=' + page_y;
                }

                window.onload = function () {
                    setTimeout(refreshPage, 35000);
                    if (window.location.href.indexOf('page_y') != -1) {
                        let match = window.location.href.split('?')[1].split("&")[0].split("=");
                        document.getElementsByTagName("body")[0].scrollTop = match[1];
                    }
                }
            },
            nextWindow: function (next, container) {
                $(container).load(next + " #next-step > *", function (responseText, textStatus, XMLHttpRequest) {
                    if (XMLHttpRequest.status === 403) {
                        toastr.error(Vemid.language.get("permissionDenied"));
                    }
                }).animate({"opacity": "show", right: "300"}, 500);
            },

            redirectLogout: function () {
                $(document).ajaxComplete(function (e, xhr) {
                    if (xhr.getResponseHeader('require-auth') === '1') {
                        toastr.error(Vemid.language.get("sessionEnd"));
                        window.location = Vemid.config.root_url + 'auth/login';
                    }
                });
            },
            checkExpiredStorageItems: function () {
                let keys = Object.keys(localStorage),
                    i = keys.length;

                while (i--) {
                    let storage = JSON.parse(localStorage.getItem(keys[i]));

                    if (typeof storage['expire'] !== 'undefined') {
                        let expiredDate = new Date(storage['expire']);
                        if (expiredDate < new Date()) {
                            localStorage.removeItem(keys[i]);
                            $(dataTable).DataTable().ajax.reload();
                        }
                    }
                }
            },
            removeExpiredStorageItems: function () {
                let myInterval = 15 * 60 * 1000;
                setInterval(function () {
                    Vemid.misc.checkExpiredStorageItems();
                }, myInterval);
            },

            init: function () {
                this.initChosen();
                this.initFootTable();
                this.setupToastr();
                this.initSwitcherCheckbox();
                this.readFileFromInput();
                this.redirectLogout();
                this.removeExpiredStorageItems();
            }
        };
    })();
}).call(Vemid, $);