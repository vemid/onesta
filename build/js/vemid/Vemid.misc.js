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
                $('.footable').footable();
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

            nextWindow: function (next, container) {
                $(container).load(next + " #next-step > *", function (responseText, textStatus, XMLHttpRequest) {
                    if (XMLHttpRequest.status === 403) {
                        toastr.error(Vemid.language.get("permissionDenied"));
                    }
                }).animate( { "opacity": "show", right:"300"} , 500 );
            },

            init: function () {
                this.initChosen();
                this.initFootTable();
                this.setupToastr();
                this.initSwitcherCheckbox();
                this.readFileFromInput();
            }
        };
    })();
}).call(Vemid, $);