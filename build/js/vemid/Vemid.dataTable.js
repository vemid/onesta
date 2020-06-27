(function ($) {
    "use strict";
    let Vemid = this;

    Vemid.namespace("dataTable");

    let table = $('#dataTable');

    let _defaultOptions = {
        pageLength: 25,
        searching: true,
        fixedHeader: true,
        orderCellsTop: true,
        pagingType: "full",
        language: {
            lengthMenu: "Display _MENU_ records per pageaaa",
            zeroRecords: Vemid.language.get("zeroRecords"),
            info: Vemid.language.get("infoPagination"),
            infoEmpty: Vemid.language.get("emptyDatatablePagination"),
            emptyTable: Vemid.language.get("emptyDatatable"),
            infoFiltered: Vemid.language.get("filteredDatatable"),
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
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print bigger-160 text-default" aria-hidden="true"></i>',
                    titleAttr: Vemid.language.get("print"),
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    text: '<i class="fa fa-file-text-o bigger-160 text-default" aria-hidden="true"></i>',
                    titleAttr: Vemid.language.get("print"),
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                // {
                //     extend: 'excelHtml5',
                //     createEmptyCells: true,
                //     extension: 'xls',
                //     text: '<i class="fa fa-file-excel-o bigger-160 text-default" aria-hidden="true"></i>',
                //     titleAttr: 'Excel',
                //     exportOptions: {
                //         columns: ':visible',
                //         decodeEntities: true,
                //         stripHtml: true
                //     }
                // },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o bigger-160 text-default" aria-hidden="true"></i>',
                    titleAttr: 'PDF',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    text: '<i class="fa fa-filter bigger-160 text-default" aria-hidden="true"></i>',
                },
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

                let filterIndex = table.attr("data-store");
                let filterValues = JSON.parse(localStorage.getItem(filterIndex));
                let columnsFilters = [];

                $("thead tr:eq(0) th", table).each( function (i) {
                    if (filterValues && typeof filterValues[i] !== 'undefined' && filterValues[i]) {
                        columnsFilters[i] = {search: filterValues[i]};
                    } else {
                        columnsFilters[i] = null;
                    }
                });

                _options = {..._ajaxOptions, ..._defaultOptions, ...{searchCols: columnsFilters}};

                $.fn.dataTable.ext.errMode = 'none';
                var dataTable = $('#dataTable').DataTable(_options);

                $("div.toolbar").addClass("text-center").html("<b class='bigger-140'>" + table.attr("data-title") + "</b>");

                $("thead tr:eq(1) th", table).each( function (i) {
                    let parentTh = $("thead tr:eq(0) th:eq("+ i +")", table);
                    let title = parentTh.text();
                    let filter = $(this).find("input:visible, select");
                    let filterValues = JSON.parse(localStorage.getItem(filterIndex));

                    let dateRangeFilters = $("input:hidden", this);

                    if (dateRangeFilters.length === 2 && !!filterValues && filterValues[i] !== 'undefined') {
                        let filterRangeValues = filterValues[i].split(" - ");
                        Vemid.datetime.initDate(dateRangeFilters.eq(0), filterRangeValues[0]);
                        Vemid.datetime.initDate(dateRangeFilters.eq(1), filterRangeValues[1]);
                    } else {
                        filter
                            .attr("placeholder", title)
                            .val(filterValues ? filterValues[i] : '');
                    }

                    if (filter.is("select")) {
                        filter
                            .trigger("liszt:updated")
                            .trigger("chosen:updated");
                    }

                    $("input, select", this ).on("keyup change", function () {
                        if ($(this).hasClass("chosen-search-input")) {
                            return;
                        }

                        filterValues = JSON.parse(localStorage.getItem(filterIndex));

                        if (!filterValues) {
                            filterValues = {};
                        }

                        let filterValue = $(this).val();
                        if (dateRangeFilters.length === 2) {
                            filterValue = '';
                            $.each(dateRangeFilters, function (index, $element) {
                                if (index === 1) {
                                    filterValue += " - ";
                                }

                                filterValue += $($element).val();
                            });
                        }

                        filterValues[i] = filterValue;

                        let date = new Date();
                        date.setHours(date.getHours() + 1);
                        filterValues['expire'] = date;

                        localStorage.setItem(filterIndex, JSON.stringify(filterValues));

                        dataTable
                                .column(i)
                                .search(filterValue)
                                .draw();

                    });
                });
            },
            init: function () {
                this.initGrid();
            }
        };
    })();
}).call(Vemid, $);




