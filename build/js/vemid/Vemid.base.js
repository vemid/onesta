/**
 * The global Vemid object under which our complete library
 * functionality will be namespaced.
 */
var Vemid = {};

(function () {

    "use strict";

    var Vemid = this;

    /**
     * Basic configuration parameters for our library.
     *
     * @type {object}
     */
    Vemid.config = {};

    Vemid.config.root_url = window.location.protocol + "//" + window.location.host + "/";
    Vemid.config.formUrl = Vemid.config.root_url;

    /**
     * Method taken from the awesome YAHOO.namespace().
     *
     * @return {object} The original object, to allow chaining
     */
    Vemid.namespace = function () {
        var args = arguments;
        var object = null;
        var i;
        var j;
        var j_start;
        var parts;

        for (i = 0; i < args.length; i++) {
            parts = args[i].split(".");
            object = Vemid;

            // Vemid is always present, so skip it if it
            // was included
            j_start = (parts[0] === "Vemid") ? 1 : 0;

            for (j = j_start; j < parts.length; j++) {
                object[parts[j]] = object[parts[j]] || {};
                object = object[parts[j]];
            }
        }

        return object;
    };

}).apply(Vemid, []);

