(function ($) {
    "use strict";
    let Vemid = this;

    Vemid.namespace("dataTable");

    Vemid.dataTable = (function () {

        return {
            initGrid: function () {
                let table = $('#dataTable').dataTable({
                    pagingType: "full",
                    language: {
                        lengthMenu: "Display _MENU_ records per pageaaa",
                        zeroRecords: "Nothing found - sorry",
                        info: Vemid.language.get("infoPagination"),
                        infoEmpty: Vemid.language.get("emptyDatatablePagination"),
                        emptyTable: Vemid.language.get("emptyDatatable"),
                        paginate: {
                            first:    '«',
                            previous: '‹',
                            next:     '›',
                            last:     '»'
                        },
                        aria: {
                            paginate: {
                                previous: Vemid.language.get("previous"),
                                next: Vemid.language.get("next"),
                                first: Vemid.language.get("first"),
                                last: Vemid.language.get("last"),
                            }
                        }
                    },
                    scrollCollapse: true,
                    dom: '<"toolbar">Bfrtip',
                    buttons: {
                        dom: {
                            container: {
                                className: 'table-button-container pull-left'
                            },
                            button: {
                                tag: 'a',
                                className: 'mx-auto mt-75'
                            }
                        },
                        buttons: [
                            {
                                text: '<i class="fa fa-plus-circle bigger-160 text-default" aria-hidden="true"></i>',
                                action: function (e, dt, node, config) {
                                    window.location.href = Vemid.config.formUrl + table.attr("data-entity") + "s/create";
                                },
                                titleAttr: Vemid.language.get("createNew"),
                            },
                            {
                                extend: 'print',
                                text: '<i class="fa fa-print bigger-160 text-default" aria-hidden="true"></i>',
                                titleAttr: Vemid.language.get("print"),
                            }
                        ]
                    }
                });

                $("div.toolbar").addClass("text-center").html("<b class='bigger-140'>" + $(table).attr("data-title") + "</b>");
            },
            init: function () {
                this.initGrid();
            }
        };
    })();
}).call(Vemid, $);




