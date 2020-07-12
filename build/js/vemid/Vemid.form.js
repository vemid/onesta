(function ($) {
    "use strict";
    let Vemid = this;

    Vemid.namespace("form");

    Vemid.form = (function () {
        let _filterName = function (name) {
            return name.replace(/\W+/gi, "-");
        };

        let _getFormElements = async function (url) {
            try {
                return await $.get(url);
            } catch (error) {
                return null;
            }
        };

        let _parseAttributes = function (object) {
            let attributes = "";
            $.each(object, function (index, value) {
                attributes += index + '="' + value + '"';
            });

            return attributes;
        };

        let _renderInput = function (elementObject) {
            let html = "";
            let value = elementObject.default ? elementObject.default : elementObject.attributes.value;
            if (typeof value === "undefined") {
                value = '';
            }

            if (elementObject.attributes.type !== 'hidden' && (elementObject.attributes.class.includes('datepicker') || elementObject.attributes.class.includes('dateTimePicker'))) {
                html += '<div class="input-group date"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>'
            }

            html += '<input name="' + elementObject.name + '" id="' + _filterName(elementObject.attributes.id ? elementObject.attributes.id : elementObject.name) + '" type="' + elementObject.attributes.type + '" value="' + value + '"' + _parseAttributes(elementObject.attributes) + ' />';

            if (elementObject.attributes.type !== 'hidden' && (elementObject.attributes.class.includes('datepicker') || elementObject.attributes.class.includes('dateTimePicker'))) {
                html += '</div>';
            }

            return html;
        };

        let _renderChooseOption = function (elementObject) {
            let html = '<select data-placeholder="' + Vemid.language.get('choose') + '."  name="' + elementObject.name + '" id="' + _filterName(elementObject.attributes.id ? elementObject.attributes.id : elementObject.name) + '"' + _parseAttributes(elementObject.attributes) + '>';

            let optionsArray = [];
            $.each(elementObject.options, function (k, v) {
                optionsArray.push({key: k, value: v});
            });

            $.each(optionsArray, function (k, v) {
                let selected = '';
                let disabled = '';

                if ($.isPlainObject(v.value)) {
                    let optionGroup = v.key.substring(0, 1).toUpperCase() + v.key.substring(1);
                    html += '<optgroup label="' + optionGroup + '">';
                    $.each(v.value, function (optKey, optValue) {
                        if ($.trim(optKey) === elementObject.default) {
                            selected = ' selected="selected"';
                        }

                        var value = optValue.substring(0, 1).toUpperCase() + optValue.substring(1);
                        html += '<option value="' + optKey + '"' + selected + '>' + value + '</option>';
                    });
                    html += '</optgroup>';
                } else {
                    if ($.isArray(elementObject.default)) {
                        if (-1 !== $.inArray(v.key, elementObject.default)) {
                            selected = ' selected="selected"';
                        }
                    } else if ($.trim(v.key) === elementObject.default) {
                        selected = ' selected="selected"';
                    }

                    if (-1 !== $.inArray(v.key, elementObject.disabledOptions)) {
                        disabled = ' disabled="disabled"';
                    }

                    let value = v.value.substring(0, 1).toUpperCase() + v.value.substring(1);
                    html += '<option value="' + v.key + '"' + selected + disabled + '>' + value + '</option>';
                }
            });

            html += '</select>';

            return html;
        };

        let _renderUpload = function (elementObject) {
            let html = '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text" id="inputGroupFileAddon01">Upload</span>';
            html += '</div><div class="custom-file">';
            html += '<input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">';
            html += '<label class="custom-file-label" for="inputGroupFile01">' + Vemid.language.get('chooseFile') + '</label></div></div>';

            return html;
        };

        let _renderCheckbox = function (elementObject) {
            return '<input name="' + elementObject.name + '" id="' + _filterName(elementObject.attributes.id ? elementObject.attributes.id : elementObject.name) + '" type="checkbox" value="' + elementObject.default + '" class="switcher" ' + (elementObject.default ? ' checked="checked"' : '') + ' />';
        };

        let _renderForm = function (postUrl, object) {
            return '<form id="fmr-' + object.default + '"  class="form-horizontal" action="' + postUrl + '" method="post" enctype="multipart/form-data">';
        };

        let _processForm = function (form, button) {
            let formData = new FormData(form.get(0)),
                files = form.find("input[type='file']");
            let currentUrl = window.location.href;

            $(files).each(function (key, element) {
                if (!$(element).val()) {
                    return;
                }

                let name = $(element).attr("name");
                formData.append(name, element.files[0]);
            });

            Vemid.validation.validationMessages();
            let isValid = Vemid.validation.validateForm(form);

            if (!isValid) {
                console.log("adsa");
                return;
            }

            $(".loader-box").show();
            Vemid.misc.makeAjaxCall(form.attr("action"), "POST", formData)
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
        };

        return {

            renderElement: function (elementObject) {
                let required = "",
                    html = "";
                let type = elementObject.attributes.type;

                if (typeof type === "undefined") {
                    type = elementObject.type;
                }

                html += '<div class="form-group' + (elementObject.attributes.type === 'hidden' ? ' hidden' : '') + '">';

                if (elementObject.attributes.type !== 'hidden') {
                    if (elementObject.required) {
                        required = '<span class="">*</span>';
                    }

                    if (elementObject.label !== "") {
                        html += '<label class="col-sm-4 control-label no-padding-right" for="' + _filterName(elementObject.name) + '">' + elementObject.label + ': ' + required + '</label>';
                    }
                }

                html += '<div class="col-sm-' + (elementObject.label !== "" ? '8' : '12') + '">';

                switch (type) {
                    case 'text':
                    case 'date':
                    case 'dateTime':
                    case 'email':
                    case 'password':
                    case 'hiddenField':
                    case 'hidden':
                        html += _renderInput(elementObject);
                        break;
                    case 'select':
                    case 'multiSelect':
                        html += _renderChooseOption(elementObject);
                        break;

                    case 'checkbox':
                        html += _renderCheckbox(elementObject);
                        break;

                    case 'upload':
                    case 'file':
                    case 'uploadControl':
                        html += _renderUpload(elementObject);
                        break;
                    default:
                }

                html += '<div class="help-block m-b-none"></div></div></div>';

                return html;
            },

            renderElements: function (url, postUrl) {
                return (async function () {
                    let elements = JSON.parse(await _getFormElements(url));
                    let form = _renderForm(postUrl, elements._form_);

                    $.each(elements, function (name, elementObject) {
                        form += Vemid.form.renderElement(elementObject);
                    });

                    form += "</form>";

                    return form;
                })();
            },

            initForm: function () {
                $(document).on("click", "a[data-form]", function (e) {
                    e.preventDefault();

                    let title = $(this).attr("data-title");
                    let formUrl = $(this).attr("data-form-url");
                    let postUrl = $(this).attr("data-post-url");
                    let event = e.srcElement || e.target,
                        ico = "plus",
                        modal = $("#modal");

                    formUrl = Vemid.crypto.decrypt(formUrl);
                    formUrl = "/form" + (formUrl.startsWith("/") ? "" : "/") + formUrl;

                    if (typeof postUrl !== "undefined") {
                        postUrl = Vemid.crypto.decrypt(postUrl);
                        postUrl = "/form" + (postUrl.startsWith("/") ? "" : "/") + postUrl;
                    } else {
                        postUrl = formUrl;
                    }

                    Vemid.form.renderElements(formUrl, postUrl).then(function (result) {
                        $(event).attr("data-backdrop", "static")
                            .attr("data-toggle", "modal")
                            .attr("data-target", "#modal");

                        if (typeof title === "undefined" || title === "")
                            title = $.trim($(event).attr("title")) || $.trim($(event).parent().attr("title"));

                        if (typeof title === "undefined" || title === "")
                            title = $.trim($(event).parents(".ibox").children(".ibox-title").find("h5").html());

                        modal
                            .find(".modal-title")
                            .html("<i class='fa fa-" + ico + "'></i> " + title);
                        modal
                            .find(".modal-body")
                            .html(result);

                        modal.show();
                        Vemid.misc.initChosen();
                        Vemid.misc.initSwitcherCheckbox();
                        Vemid.misc.readFileFromInput();
                        Vemid.datetime.init($(".datetime"));
                    }, function (reason) {
                        toastr.error('Error processing request', reason.responseText)
                    });

                });
            },

            initDeleteForm: function () {
                $(document).on("click", "a[data-delete]", function (e) {
                    let formUrl = $(this).attr("data-form-url");
                    let currentUrl = window.location.href;

                    formUrl = Vemid.crypto.decrypt(formUrl);
                    formUrl = "/form" + (formUrl.startsWith("/") ? "" : "/") + formUrl;

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
                                        });
                                    }
                                }
                            }, function (reason) {
                                toastr.error('Error processing request', reason.statusText)
                            });
                    });
                });
            },

            processModalForm: function () {
                $(document).on("click", "#save-modal", function (e) {
                    e.preventDefault();
                    let form = $(this).parents("form"),
                        button = $(this);

                    if (form.length === 0) {
                        form = $(this).parents("#modal").find("form");
                    }

                    _processForm(form, button);
                });
            },

            processDomForm: function () {
                $(document).on("submit", "form", function (e) {
                    e.preventDefault();

                    let form = $(this),
                        button = $(this).find(':submit');

                    _processForm(form, button);
                });
            },

            destroyForm: function () {
                $(document).on("click", ".destroy-modal", function (e) {
                    e.preventDefault();
                    let modal = $(this).parents("#modal");
                    modal.find(".modal-title").html(null);
                    modal.find(".modal-body").html(null);
                    modal.find(".flash-notification").html(null);
                    modal.find("#save-modal").removeClass("disabled");
                    modal.hide();

                    $("body").removeClass("modal-open");
                    $(".modal-backdrop").remove();
                });
            },

            init: function () {
                this.initForm();
                this.initDeleteForm();
                this.processModalForm();
                this.processDomForm();
                this.destroyForm();
            }
        };
    })();
}).call(Vemid, $);