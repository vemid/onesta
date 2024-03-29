(function ($) {
    "use strict";

    let Vemid = this;

    Vemid.namespace("onesta");

    Vemid.onesta = (function () {

        let _letCalculateTableRowsAmount = function (table, deleted) {
            let trs = $("tbody tr", table),
                total = table.attr("data-price").replace(/,/g,'');

            let rowValue = parseFloat(total) / parseFloat(trs.length);
            $.each(trs, function(){
                let formField = $(this).find("td").eq(1).find("input").eq(0);
                formField.val(rowValue.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            });
        };

        return {
            nameAutocomplete: function () {
                $("input[name ='firstName']").each(function (index, element) {
                    let $element = $(element);
                    let $client = $("[name ='clientId']");
                    let clientId = '';
                    if ($client.length) {
                        clientId = "?clientId=" + $client.val();
                    }

                    $element
                        .autocomplete({
                            minLength: 3,
                            source: Vemid.config.formUrl + "form/clients/fetch-by-term" + clientId,
                            select: function (event, ui) {
                                event.preventDefault();
                                let $id = ui.item.id;
                                Vemid.misc.makeAjaxCall("/form/clients/fetch-by-id/" + $id, "GET")
                                    .then(function (respJson) {
                                        if (Object.keys(respJson).length) {
                                            $.each(respJson, function (property, value) {
                                                let $el = $("[name ='" + property + "']");
                                                if ($el.length) {
                                                    $el.val(value);

                                                    if ($el.is("select")) {
                                                        $el
                                                            .trigger("liszt:updated")
                                                            .trigger("chosen:updated");
                                                    }
                                                }
                                            })
                                        } else {
                                            $("[name ='client']").val("");
                                        }
                                    }, function (reason) {
                                        toastr.error("Error in processing your request", reason);
                                    });
                            }
                        });
                });
            },

            guarantorAutocomplete: function () {
                $("input[name ='guarantorId']").each(function (index, element) {
                    let $element = $(element);
                    let $client = $("[name ='clientId']");
                    let clientId = '';
                    if ($client.length) {
                        clientId = $client.val();
                    }

                    $element
                        .autocomplete({
                            minLength: 3,
                            source: function (request, response) {
                                request["clientId"] = $("input[name='client']").val();
                                if (clientId) {
                                    request["clientId"] = clientId;
                                }

                                $.post(Vemid.config.formUrl + "form/clients/fetch-by-term", request, response);
                            },
                            select: function (event, ui) {
                                $("#guarantor").val(ui.item.id);
                            }
                        });
                });
            },

            purchaseTypeSelect: function () {
                $(document).on("change", "select[name='code']", function (e) {
                    let val = $(this).val(),
                        model = $("input[name='model']"),
                        insuranceLevel = $("input[name='insuranceLevel']"),
                        plates = $("input[name='plates']"),
                        chassis = $("input[name='chassis']");

                    if (val === "DELOVI") {
                        $([chassis, plates, model, insuranceLevel]).each(function (index, element) {
                            element.addClass("hidden")
                                .parents(".row:first")
                                .addClass("hidden");
                        });
                    } else if (val === "REGISTRACIJA") {
                        $([chassis, plates, model, insuranceLevel]).each(function (index, element) {
                            element
                                .removeClass("hidden")
                                .parents(".row:first")
                                .removeClass("hidden");
                        });
                    }
                });
            },

            supplierProductChange: function () {
                $(document).on("change", "select[name='supplierProduct']", function (e) {
                    let formData = new FormData();
                    let $this = $(this);
                    formData.append("id", $(this).val());

                    Vemid.misc.makeAjaxCall("/form/supplier-products/get-qty", "POST", formData)
                        .then(function (respJson) {
                            if (typeof respJson.qty === "undefined") {
                                toastr.error('Server error');
                                return;
                            }

                            let qtyElement = $this.parents("table").find("input[name='qty']");
                            let priceElement = $this.parents("table").find("input[name='price']");
                            let maxQty = respJson.qty;
                            priceElement.val(respJson.price);
                            qtyElement.parents("td").removeClass("has-error");

                            if (!respJson.disableQty) {
                                maxQty = 100000;
                                qtyElement.val(1);
                            }

                            qtyElement.trigger("touchspin.updatesettings", {max: maxQty});

                        }, function (reason) {
                            toastr.error(Vemid.language.get("errorRequest"), reason.statusText)
                        });
                });
            },

            removeGuarantor: function () {
                $(document).on("keyup change", "input[name='guarantorId']", function(){
                    if (!$(this).val()) {
                        $("input[name='guarantor']").val("");
                    }
                });
            },

            clientTypeSelect: function () {
                $(document).on("change", "select[name='type']", function (e) {
                    let val = $(this).val(),
                        pib = $("input[name='pib']"),
                        jmbg = $("input[name='jmbg']");

                    if (val === "NATURAL") {
                        pib.addClass("hidden")
                            .parents(".row:first")
                            .addClass("hidden");

                        jmbg.removeClass("hidden")
                            .parents(".row:first")
                            .removeClass("hidden");
                    } else if (val === "LEGAL") {
                        pib
                            .removeClass("hidden")
                            .parents(".row:first")
                            .removeClass("hidden");

                        jmbg.addClass("hidden")
                            .parents(".row:first")
                            .addClass("hidden");
                    }
                });
            },

            finishPurchase: function() {
                $("#finishPurchase").click(function() {
                    let id = $(this).attr("data-id");
                    Vemid.misc.makeAjaxCall("/purchases/finish/" + id, "POST", {})
                        .then(function (respJson) {
                            if (typeof respJson.success !== "undefined" && respJson.success) {
                                location.reload();
                                return;
                            }
                        }, function (reason) {
                            toastr.error(Vemid.language.get("errorRequest"), reason.statusText)
                        });
                });
            },

            paymentInstallmentsOnAddRow: function () {
                let tableFormInstallments = $(".tableFormInstallments");
                if (tableFormInstallments.length === 0) {
                    return;
                }

                $("#clone-row").click(function () {
                    let row = $("tr:last", tableFormInstallments);
                    let inputDateTime = row.prev().find("td").eq(0).find("input").eq(0);
                    let prevDate = inputDateTime.val();
                    let eq = row.find("td").eq(0).find("input").eq(0);
                    let date = new Date(prevDate);

                    date.setMonth(date.getMonth() + 1);
                    Vemid.datetime.initDate(eq, date);
                    _letCalculateTableRowsAmount(tableFormInstallments, false);
                });
            },

            paymentInstallmentsOnDeleteRow: function () {
                let tableFormInstallments = $(".tableFormInstallments");
                if (tableFormInstallments.length === 0) {
                    return;
                }

                $(document).on("click", ".delete-cloned-row", function(e) {
                    _letCalculateTableRowsAmount(tableFormInstallments, true);
                });
            },

            removeInstallment: function () {
                $(document).on("click", "a[data-custom-remove-inst]", function (e) {
                    e.preventDefault();

                    let formUrl = $(this).attr("href");
                    let currentUrl = window.location.href;
                    formUrl = Vemid.crypto.decrypt(formUrl);

                    swal({
                        title: Vemid.language.get("areYouSure"),
                        text: "",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonText: Vemid.language.get("giveUp"),
                        confirmButtonColor: "#da180f",
                        confirmButtonText: Vemid.language.get("delete"),
                        closeOnConfirm: true
                    }, function () {
                        Vemid.misc.makeAjaxCall(formUrl, "POST", {})
                            .then(function (result) {
                                Vemid.notification.fromResponse(result);

                                if (!result.error) {
                                    if (typeof dataTable !== 'undefined') {
                                        $(dataTable).DataTable().ajax.reload();
                                    } else {
                                        $("#content").load(currentUrl + " #content > *", function () {
                                            Vemid.misc.init();
                                            Vemid.datetime.init();
                                            Vemid.tableForm.init($(".tableForm"));
                                            Vemid.tableForm.init($(".tableFormInstallments"));
                                        });
                                    }
                                }
                            }, function (reason) {
                                toastr.error(Vemid.language.get('errorRequest'), reason.statusText)
                            });
                    });
                });
            },

            init: function () {
                this.nameAutocomplete();
                this.guarantorAutocomplete();
                this.purchaseTypeSelect();
                this.clientTypeSelect();
                this.supplierProductChange();
                this.removeGuarantor();
                this.finishPurchase();
                Vemid.tableForm.init($(".tableForm"));
                Vemid.tableForm.init($(".tableFormInstallments"));
                this.paymentInstallmentsOnAddRow();
                this.paymentInstallmentsOnDeleteRow();
                this.removeInstallment();
            }
        }
    })();
}).call(Vemid, $);