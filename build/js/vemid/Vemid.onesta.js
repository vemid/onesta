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

            init: function () {
                this.nameAutocomplete();
            }
        }
    })();
}).call(Vemid, $);