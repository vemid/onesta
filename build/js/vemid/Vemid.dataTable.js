(function ($) {
    "use strict";
    let Vemid = this;

    Vemid.namespace("dataTable");

    let table = $('#dataTable');

    $(document).mouseup(function (e) {
        let filterDivAbs = $(".filter-absolute");
        if (!filterDivAbs.is(e.target) && filterDivAbs.has(e.target).length === 0 && $(e.target).parents(".flatpickr-calendar").length === 0) {
            filterDivAbs.fadeOut(500);
        }
    });

    let buttons = [
        {
            text: '<i class="fa fa-plus-circle bigger-160 text-default" aria-hidden="true"></i>',
            action: function (e, dt, node, config) {
                let type = table.attr("data-type");
                window.location.href = Vemid.config.formUrl + table.attr("data-entity") + "s/create" + (typeof type !== 'undefined' && !!type ? "/" + type : '');
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
            extend: 'pdfHtml5',
            text: '<i class="fa fa-file-pdf-o bigger-160 text-default" aria-hidden="true"></i>',
            titleAttr: 'PDF',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'colvis',
            text: '<i class="fa fa-cog bigger-160 text-default" aria-hidden="true"></i>',
        },
    ];


    let filterTr = $("tr", table).eq(1);

    if (filterTr.hasClass("hidden")) {
        buttons[(buttons.length + 1)] = {
            text: '<i class="fa fa-filter bigger-160 text-default" aria-hidden="true"></i>',
            action: function (e, dt, node, config) {
                $(".filter-absolute").fadeIn(600);
            },
            titleAttr: Vemid.language.get("filter"),
            exportOptions: {
                columns: ':visible'
            }
        };
    }

    let _initFilter = function () {
        let filterTr = $("tr", table).eq(1);

        if (!filterTr.hasClass("hidden")) {
            return;
        }
        let headerTr = $("tr", table).eq(0);
        let elements = "";
        let counter = 0;
        $.each($("th", filterTr), function (index, td) {
            if ($(td).html()) {
                counter++;
            }
        });

        let col = Math.round((12 / counter) * 10) / 10;
        col.toFixed();

        $.each($("th", filterTr), function (index, th) {
            let html = $(th).html();
            if (html) {
                elements += "<div class='filter-group no-padding-left col-sm-" + col + " col-lg-" + col + " col-xs-12' data-column-index='" + index + "'><label class='col-xs-12 no-padding'>" + headerTr.find("th").eq(index).html() + ":</label>" + html + "</div>";
            }
        });

        if (elements) {
            elements += "<div class='block text-right col-xs-12'><button type='button' id='apply-table-filter' class='btn btn-success no-margin-right'>" + Vemid.language.get("applyFilters") + "</button></div>"
        }

        let filterDiv = $("<div class='filter-absolute col-xs-6 col-sm-12 col-lg-12'><div class='row'><div class='col-xs-12'>" + elements + "</div></div></div>");
        let formElements = $("input, select", filterDiv);
        $.each(formElements, function (index, formElement) {
            let $formElement = $(formElement);
            let name = $($formElement).attr("name");
            if ($formElement.hasClass("datepicker")) {
                if (typeof name !== 'undefined') {
                    Vemid.datetime.initDate($formElement, $formElement.val());
                } else {
                    $formElement.remove();
                }
            }

            if ($formElement.is("select") && $formElement.parent().find(".chosen-container").length > 0) {
                $formElement.next().remove();
                $formElement.chosen()
                    .trigger("liszt:updated")
                    .trigger("chosen:updated")
                    .trigger("liszt:activate");
            }
        });

        table.parent().append(filterDiv);
    };

    let _defaultOptions = {
        pageLength: 25,
        searching: true,
        fixedHeader: true,
        orderCellsTop: true,
        pagingType: "full",
        language: {
            lengthMenu: "Display _MENU_ records per page",
            zeroRecords: Vemid.language.get("zeroRecords"),
            info: Vemid.language.get("infoPagination"),
            infoEmpty: Vemid.language.get("emptyDatatablePagination"),
            emptyTable: Vemid.language.get("emptyDatatable"),
            infoFiltered: Vemid.language.get("filteredDatatable"),
            paginate: {
                first: '«',
                previous: '‹',
                next: '›',
                last: '»'
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
            buttons: buttons
        }
    };

    let _applyFilter = function (dataTable) {
        let body = document.body,
            filterDivAbs = $(".filter-absolute");

        body.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                if (filterDivAbs.is(":visible")) {
                    $("#apply-table-filter").click();
                }
            }
        });

        $(document).on("click", "#apply-table-filter", function () {
            let headerTr = $("tr", table).eq(1);
            let filterDiv = $(".filter-absolute");
            let block = $(".filter-group", filterDiv);

            $.each(block, function (index, element) {
                let $field = $("input, select", element);
                let dateRangeFilters = $("input", element).filter(".input");
                let filterValue = $field.val();

                if (dateRangeFilters.length === 2) {
                    filterValue = '';
                    $.each(dateRangeFilters, function (index, filedElement) {
                        let $filedElement = $(filedElement);
                        if (index === 1) {
                            filterValue += " - ";
                        }

                        filterValue += $filedElement.val();
                    });
                }

                let name = $field.attr("name");
                let columnIndex = $field.parents(".filter-group").attr("data-column-index");
                $("th", headerTr)
                    .eq(columnIndex)
                    .find("[name ='" + name + "']")
                    .val(filterValue ? filterValue : "")
                    .trigger("change");

                $field
                    .trigger("liszt:updated")
                    .trigger("chosen:updated");
            });

            dataTable.draw();
            filterDiv.fadeOut(500);
        });
    };

    let _processThFilters = function (dataTable, table, filterIndex) {
        $("thead tr:eq(1) th", table).each(function (i) {
            let parentTh = $("thead tr:eq(0) th:eq(" + i + ")", table);
            let title = parentTh.text();
            let filter = $(this).find("input, select");
            if (filter.length === 0) {
                return;
            }

            let filterValues = JSON.parse(localStorage.getItem(filterIndex));

            let dateRangeFilters = $("input:hidden", $(this)).filter(":not(.input)");
            if (dateRangeFilters.length === 2 && !!filterValues && typeof filterValues[i] !== 'undefined') {
                let filterRangeValues = filterValues[i].split(" - ");

                dateRangeFilters.eq(0).val(filterRangeValues[0]).attr("value", filterRangeValues[0]);
                dateRangeFilters.eq(1).val(filterRangeValues[1]).attr("value", filterRangeValues[1]);
            } else if (filter.is("select")) {
                if (!!filterValues && typeof filterValues[i] !== 'undefined') {
                    $("option[value="+ filterValues[i] +"]", filter).attr('selected','selected');
                }

                filter
                    .trigger("liszt:updated")
                    .trigger("chosen:updated");
            } else {
                filter
                    .attr("placeholder", title)
                    .attr("value", filterValues ? filterValues[i] : '')
                    .val(filterValues ? filterValues[i] : '');
            }

            $("input, select", this).on("keyup change", function () {
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
                    .search(filterValue);

            });
        });
    };

    Vemid.dataTable = (function () {

        return {
            initGrid: function () {

                let _ajaxOptions,
                    _options,
                    filterIndex = table.attr("data-store");
                ;

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
                            data: function (d) {
                                return $.extend({}, d, {});
                            }
                        }
                    };
                }

                let filterValues = JSON.parse(localStorage.getItem(filterIndex));
                let columnsFilters = [];

                $("thead tr:eq(0) th", table).each(function (i) {
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

                _processThFilters(dataTable, table, filterIndex);
                _initFilter();
                _applyFilter(dataTable);
            },
            init: function () {
                this.initGrid();
            }
        };
    })();
}).call(Vemid, $);




