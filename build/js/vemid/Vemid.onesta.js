(function ($) {
    "use strict";

    let Vemid = this;

    Vemid.namespace("onesta");

    Vemid.onesta = (function () {

        return {
            nameAutocomplete: function () {
                $("input[name ='firstName']").each(function (index, element) {
                    let $element = $(element);

                    $element
                        .autocomplete({
                            minLength: 3,
                            source: Vemid.config.formUrl + "form/clients/fetch-by-term",
                            select: function (event, ui) {
                                event.preventDefault();
                                let $id = ui.item.id;
                                Vemid.misc.makeAjaxCall("/form/clients/fetch-by-id/" + $id, "GET")
                                    .then(function (respJson) {
                                        console.log(respJson);
                                        if (Object.keys(respJson).length) {
                                            $.each(respJson, function (property, value) {
                                                let $el = $("[name ='" + property + "']");
                                                console.log($el);
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

                    $element
                        .autocomplete({
                            minLength: 3,
                            source: function (request, response) {
                                request['clientId'] = $("input[name='client']").val();
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

                    if (val === 'DELOVI') {
                        $([chassis, plates, model, insuranceLevel]).each(function (index, element) {
                            element.addClass("hidden")
                                .parents(".row:first")
                                .addClass("hidden");
                        });
                    } else if (val === 'REGISTRACIJA') {
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
                    formData.append('id', $(this).val());

                    Vemid.misc.makeAjaxCall('/form/supplier-products/get-qty', "POST", formData)
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
                            }

                            qtyElement.trigger("touchspin.updatesettings", {max: maxQty});

                        }, function (reason) {
                            toastr.error('Error processing request', reason.statusText)
                        });
                });
            },

            removeGuarantor: function () {
                $(document).on("keyup change", "input[name='guarantorId']", function(){
                    console.log($(this).val());
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

                    if (val === 'NATURAL') {
                        pib.addClass("hidden")
                            .parents(".row:first")
                            .addClass("hidden");

                        jmbg.removeClass("hidden")
                            .parents(".row:first")
                            .removeClass("hidden");
                    } else if (val === 'LEGAL') {
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

            init: function () {
                this.nameAutocomplete();
                this.guarantorAutocomplete();
                this.purchaseTypeSelect();
                this.clientTypeSelect();
                this.supplierProductChange();
                this.removeGuarantor();
                Vemid.tableForm.init($(".tableForm"));
            }
        }
    })();
}).call(Vemid, $);