(function ($) {
    "use strict";
    let Vemid = this;

    Vemid.namespace("dataTable");

    let table = $('#dataTable');

    let _defaultOptions = {
        pageLength: 25,
        // "draw": 1,
        // "recordsTotal": 57,
        // "recordsFiltered": 57,
        // processing: true,
        deferRender: true,
        fixedHeader: true,
        orderCellsTop: true,
        pagingType: "full",
        language: {
            lengthMenu: "Display _MENU_ records per pageaaa",
            zeroRecords: Vemid.language.get("zeroRecords"),
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
        stateSave:      true,
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
    };

    Vemid.dataTable = (function () {

        return {
            initGrid: function () {

                let _ajaxOptions,
                    _options;

                if (!!table.attr("data-serverside")) {
                    if (!table.get(0).hasAttribute("data-ajax-url")) {
                        alert("Missing settings for api url!");
                        throw "Missing settings for api url!";
                    }

                    _ajaxOptions = {
                        serverSide: true,
                        ajax: {
                            url: Vemid.crypto.decrypt(table.attr("data-ajax-url")),
                            type: "POST",
                            data:  function ( d ) {
                                return $.extend({}, d, {

                                });
                            }
                        }
                    };
                }

                _options = {..._ajaxOptions, ..._defaultOptions};

                let dataTable = $('#dataTable').DataTable(_options);
                $("div.toolbar").addClass("text-center").html("<b class='bigger-140'>" + table.attr("data-title") + "</b>");

                $("thead tr:eq(1) th", table).each( function (i) {
                    let parentTh = $("thead tr:eq(0) th:eq("+ i +")", table);
                    let title = parentTh.text();
                    let filter = $(this).children().eq(0);
                    filter
                        .text(title)
                        .attr("placeholder", title);

                    $( 'input', this ).on( 'keyup change', function () {
                        if ( dataTable.column(i).search() !== this.value ) {
                            dataTable
                                .column(i)
                                .search( this.value )
                                .draw();
                        }
                    } );
                });
            },
            init: function () {
                this.initGrid();
            }
        };
    })();
}).call(Vemid, $);




