(function ($) {
    "use strict";
    let Vemid = this;

    Vemid.namespace("crypto");

    Vemid.crypto = (function () {

        let options = {
            stringify: function (cipherParams) {
                let j = {ct: cipherParams.ciphertext.toString(CryptoJS.enc.Base64)};
                if (cipherParams.iv) j.iv = cipherParams.iv.toString();
                if (cipherParams.salt) j.s = cipherParams.salt.toString();

                return JSON.stringify(j);
            },
            parse: function (jsonStr) {
                let j = JSON.parse(jsonStr);
                let cipherParams = CryptoJS.lib.CipherParams.create({ciphertext: CryptoJS.enc.Base64.parse(j.ct)});
                if (j.iv) cipherParams.iv = CryptoJS.enc.Hex.parse(j.iv)
                if (j.s) cipherParams.salt = CryptoJS.enc.Hex.parse(j.s)

                return cipherParams;
            }
        };

        return {
            decrypt: function (cipher) {
                return JSON.parse(CryptoJS.AES.decrypt(
                    cipher, "Vemid", {
                        format: options
                    }).toString(CryptoJS.enc.Utf8))
            }
        };
    })();
}).call(Vemid, $);