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
                            select: function( event, ui ) {
                                event.preventDefault();
                                let $id =  ui.item.id;
                                Vemid.misc.makeAjaxCall("/form/clients/fetch-by-id/" + $id, "GET")
                                    .then(function (respJson) {
                                        $.each(respJson, function (property, value) {
                                            let $el = $("[name ='"+ property +"']");
                                            if ($el.length) {
                                                $el.val(value);

                                                if ($el.is("select")) {
                                                    $el
                                                        .trigger("liszt:updated")
                                                        .trigger("chosen:updated");
                                                }
                                            }
                                        })

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
                            source: Vemid.config.formUrl + "form/clients/fetch-by-term",
                            select: function( event, ui ) {
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
                        $([chassis, plates, model, insuranceLevel]).each(function(index, element) {
                            element.addClass("hidden")
                                .parents(".row:first")
                                .addClass("hidden");
                        });
                    } else if (val === 'REGISTRACIJA') {
                        $([chassis, plates, model, insuranceLevel]).each(function(index, element) {
                            element
                                .removeClass("hidden")
                                .parents(".row:first")
                                .removeClass("hidden");
                        });
                    }
                });
            },

            clientTypeSelect: function () {
                $(document).on("change", "select[name='type']", function (e) {
                    let val = $(this).val(),
                        pib = $("input[name='pib']");

                    if (val === 'NATURAL') {
                        pib.addClass("hidden")
                            .parents(".row:first")
                            .addClass("hidden");
                    } else if (val === 'LEGAL') {
                        pib
                            .removeClass("hidden")
                            .parents(".row:first")
                            .removeClass("hidden");
                    }
                });
            },

            init: function () {
                this.nameAutocomplete();
                this.guarantorAutocomplete();
                this.purchaseTypeSelect();
                this.clientTypeSelect();
                Vemid.tableForm.init($(".tableForm"));
            }
        }
    })();
}).call(Vemid, $);