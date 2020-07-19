(function ($) {
    "use strict";

    let Vemid = this;

    Vemid.namespace("validation");

    Vemid.validation = (function () {

        let _getFormElements = function (form) {
            let $elements = $(":input", form);

            return $elements.filter(function () {
                return $(this).attr('required') !== undefined || $(this).hasClass('required');
            });
        };

        let _validateElement = function (element) {
            let valid = true,
                $element = $(element);

            $element
                .parents(".form-group")
                .removeClass("has-error")
                .find(".help-block").html("");

            if ($element.val() === "" || $element.val() === "0") {
                $element.parents(".form-group").addClass("has-error");
                $element.parents(".form-group").find(".help-block").html(Vemid.language.get('requiredField'));
                valid = false;
            }

            return valid;
        };

        return {

            validateFormOnSubmit: function (e) {
                $(document).on("submit", "form", function (e) {
                    if (!Vemid.validation.validateForm(this)) {
                        e.preventDefault();
                    }
                });
            },

            validateForm: function (form) {
                let $this = $(form),
                    $elements = _getFormElements($this),
                    isValidForm = true;

                $.each($elements, function (index, element) {
                    let isValidElement = _validateElement(element);
                    if (!isValidElement) {
                        isValidForm = false;
                        return;
                    }
                });

                return isValidForm;
            },

            validationMessages: function () {
                $(document).on("change invalid", ":input", function () {
                    if (!$(this).prop('required')) {
                        return;
                    }

                    let field = $(this).get(0);
                    field.setCustomValidity('');

                    if (!field.validity.valid) {
                        let validationAttribute = JSON.parse($(this).attr("data-nette-rules"));
                        field.setCustomValidity(validationAttribute[0].msg);
                        _validateElement($(this));
                    }
                });
            },

            init: function () {
                this.validateFormOnSubmit();
                this.validationMessages();
            }
        };
    })();
}).call(Vemid, $);

