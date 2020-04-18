/**
 * This is a base for adding different language definitions for the system, where each module
 * is able to define its own language strings, and the method get() enables easy fetching of a
 * language string by key, where the current site language is automatically detected and used.
 */

(function ($) {
    "use strict";

    var Vemid = this;

    Vemid.namespace("language");

    Vemid.language = (function () {
        var _current_language = "cyr",
            _dictionary = {},

            /**
             * Adds a new translation string into the internal dictionary.
             *
             * @param {string} translation_name A name/key for this translation string
             * @param {object} definitions      Translations for this string.
             */
            _add_translation = function (translation_name, definitions) {
                var language_code;

                for (language_code in definitions) {
                    if (definitions.hasOwnProperty(language_code)) {

                        if (!_dictionary.hasOwnProperty(language_code)) {
                            _dictionary[language_code] = {};
                        }

                        // We should stop if we encounter a duplicate definition
                        if (_dictionary[language_code].hasOwnProperty(translation_name)) {
                            throw {
                                name: "Duplicate language definition",
                                message: "Key \"" + translation_name + "\" already exists in Vemid.language._dictionary." + language_code
                            };
                        }

                        _dictionary[language_code][translation_name] = definitions[language_code];
                    }
                }
            };

        return {

            /**
             * Adds an object of translation strings into the internal dictionary.
             *
             * @param {object} translations The list of translation objects to import into the
             *                              internal dictionary. A complete translation object is
             *                              expected to be structured with property names as
             *                              specific names for the translation string in question,
             *                              and the associated values are the translations for
             *                              that key, or, to be more specific, an object with
             *                              properties corresponding to ISO 639-1 language codes,
             *                              and their values being the actual translations.
             */
            add_translation: function (translations) {
                var translation_name;

                for (translation_name in translations) {
                    if (translations.hasOwnProperty(translation_name)) {
                        _add_translation(translation_name, translations[translation_name]);
                    }
                }
            },

            /**
             * Returns a string by its translation name (key), in the specified language, or the
             * language currently in use, if one wasn't specified.
             *
             * @param  {string} translation_name The name of the requested translation
             * @param  {string} language_code    An ISO 639-1 language code (optional)
             * @return {string}                  The translated string. Returns
             */
            get: function (translation_name, language_code) {
                language_code = language_code || _current_language;

                if (!_dictionary.hasOwnProperty(language_code)) {
                    throw {
                        name: "Unknown language requested",
                        message: "The requested language (" + language_code + ") wasn't found in the currently active dictionary."
                    };
                }

                if (!_dictionary[language_code].hasOwnProperty(translation_name)) {
                    throw {
                        name: "Unknown translation item",
                        message: "The requested item (" + translation_name + ") wasn't found in the currently active dictionary for the language \"" + language_code + "\"."
                    };
                }

                return _dictionary[language_code][translation_name] || "";
            },

            /**
             * Updates the current language in use to the specified language. If no language was
             * specified, the "lang" attribute of the <html> element is used. If that isn't defined
             * either, English is set as the default.
             *
             * @param {string} language_code An ISO 639-1 language code (optional)
             */
            set_language: function (language_code) {
                var html_lang = $("html").attr("lang") || "cyr";

                _current_language = language_code || html_lang;
            },

            /**
             * Returns the current language in use.
             *
             * @return {string} An ISO 639-1 language code
             */
            get_language: function () {
                return _current_language;
            },

            init: function () {
                this.set_language();
            }
        };

    }());

    Vemid.language.init();

}).call(Vemid, jQuery);

