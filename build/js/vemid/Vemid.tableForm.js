(function ($) {
    "use strict";

    let Vemid = this;

    Vemid.namespace("tableForm");

    Vemid.tableForm = (function () {

        let _validateRow = function (row) {
            if (!_checkIfRowIsForm(row)) {
                return true;
            }

            let nonValidRows = [];
            $.each($("td", row), function (tdIndex, td) {
                let formElement = $(td).removeClass("has-error").find("input, select, textarea");
                if (formElement.length > 0) {
                    $.each(formElement, function (elementIndex, formElement) {

                        let $formElement = $(formElement);

                        if ($formElement.hasClass("required") && !$formElement.val()) {
                            $(td).addClass("has-error");
                            nonValidRows[tdIndex] = 0;
                        }
                    });
                }
            });

            return nonValidRows.length === 0;
        };

        let _cloneLastRow = function (table) {
            $(document).on("click", "#clone-row", function (e) {
                let row = $("tr:last", table);

                if (!_checkIfRowIsForm(row) || !_validateRow(row)) {
                    return false;
                } else {
                    let clonedRow = row.clone();
                    row.parents("table").append(clonedRow);
                    $.each($("td", clonedRow), function (tdIndex, td) {
                        let formElement = $(td).find("input, select, textarea");
                        if (formElement.length > 0) {
                            $.each(formElement, function (index, el) {
                                let $el = $(el);
                                let value = $el.val();
                                $el.val("");

                                if ($el.is("select")) {
                                    if (clonedRow.find(".chosen-container").length > 0) {
                                        let prevElementVal = $("td", row).eq(index).find("select").val();
                                        if (!!prevElementVal) {
                                            $("option[value='"+ prevElementVal + "']", $el).attr('disabled', true);
                                        }

                                        $el.next().remove();
                                        $el.chosen()
                                            .trigger("liszt:updated")
                                            .trigger("chosen:updated")
                                            .trigger("liszt:activate");
                                    }
                                }
                            });
                        }
                    });
                }
            });
        };

        let _deleteClonedRow = function() {
            $(document).on("click", "#delete-cloned-row", function() {
                let tr = $(this).parents("tr");
                let table = tr.parents("table");

                if ($(".fa-minus", table).length > 1) {
                    tr.remove();
                } else {
                    toastr.error(Vemid.language.get("notPermittedOperation"));
                }

                if ($("tr", table).find("#delete-cloned-row").length === 0 ){
                    $(".action-foot").hide();
                }
            });
        };

        let _initActionFoot = function () {
            return "<div class='row action-foot'><div class='col-xs-12'>" +
                "<a href='#' onclick='return false;' class='btn btn-info' id='clone-row'><i class='fa fa-plus'></i> "+ Vemid.language.get("addNewRow") +"</a>" +
                "<a href='#' onclick='return false;' class='btn btn-default' id='process-table-form'><i class='fa fa-save'></i> <span class='nav-label'> "+ Vemid.language.get("save") +"</span></a>" +
                "</div></div>";
        };

        let _composeFormData = function (table) {
            let formData = new FormData();

            $.each($("tr", table), function(index, row){
                if (_checkIfRowIsForm($(row))) {
                    $.each($("td", row), function (tdIndex, td) {
                        let $td = $(td);
                        let formElement = $td.find("input, select, textarea");
                        if (formElement.length > 0 && !formElement.hasClass(".chosen-search-input")) {
                            formData.append(formElement.attr("name") + "["+ index +"]", formElement.val());
                        }
                    });
                }
            });

            return formData;
        };

        let _addActionColumn = function (row) {
            let rowChild = row.firstChild.nodeName,
                column,
                $row = $(row);

            if (rowChild === "TH") {
                column = "<th class='col-xs-1 text-center'>"+ Vemid.language.get("action") +"</th>";
            } else {
                column = "<td class='text-center'>";


                if (_checkIfRowIsForm($row)) {
                    column += "<a href='#' onclick='return false;' id='delete-cloned-row' class='text-danger bigger-140 text-center'><i class='fa fa-minus'></i></a>";
                }else if (_ifActionMode($row)) {
                    column += "<a href='#' class='text-info bigger-120 text-center'><i class='fa fa-edit'></i></a>";
                    column += "&nbsp;<a href='#' class='text-danger bigger-120 text-center'><i class='fa fa-remove'></i></a>";
                }

                column += "<td>";
            }

            $row.append(column);
        };

        let _checkIfRowIsForm = function (row) {
            let isForm = false;

            $.each($("td", row), function (tdIndex, td) {
                let formElement = $(td).find("input, select, textarea");
                if (formElement.length > 0) {
                    isForm = true;
                    return;
                }
            });

            return isForm;
        };

        let _ifActionMode = function (row) {
            if (row.attr("data-primary")) {
                return true;
            }

            return false;
        };

        let _processTableForm = function (table) {
            $(document).on("click", "#process-table-form", function(){
                let nonValidRows = [];
                $.each($("tr", table), function(index, row){
                    if (!_validateRow($(row))) {
                        nonValidRows[index] = 0;
                    }
                });

                if (nonValidRows.length === 0) {
                    let formData = _composeFormData(table);

                    Vemid.misc.makeAjaxCall("", "POST", formData)
                        .then(function (respJson) {
                            if (typeof respJson.url !== "undefined") {
                                window.location = respJson.url;
                                return;
                            }

                            if (typeof respJson.next !== "undefined") {
                                Vemid.misc.nextWindow(respJson.next, form.parent());
                                return;
                            }

                            Vemid.notification.fromResponse(respJson);

                            if (!respJson.error) {
                                $("#content").load(currentUrl + " #content > *", function () {
                                    $("#modal").hide();
                                    $(".modal-body").empty();

                                    $("body").removeClass("modal-open");
                                    Vemid.misc.init();
                                    Vemid.datetime.init();
                                    $(".loader-box").hide();
                                });
                            }
                        }, function (reason) {
                            $(".loader-box").hide();
                            toastr.error('Error processing request', reason.statusText)
                        });
                }
            });
        };

        return {
            initTable: function(table) {
                if (table.length === 0) {
                    return;
                }

                if ($(this).is("table")) {
                    throw new Error('Selector must be a table element');
                }

                let lastRow = $("tr:last", table);

                if (_checkIfRowIsForm(lastRow)) {
                    let footerHtml = _initActionFoot();
                    $(footerHtml).insertAfter(table);
                }

                $.each($("tr", table), function (rowIndex, row) {
                    _addActionColumn(row);
                });

                _cloneLastRow(table);
                _deleteClonedRow();
                _processTableForm(table);
            },
            init: function (element) {
                this.initTable(element);
            }
        }
    })();
}).call(Vemid, $);