(function ($) {
    "use strict";

    let Vemid = this;

    Vemid.namespace("uploader");

    Vemid.uploader = (function () {

        return {
            resetPreviewImage: function () {
                $(document).on("click", "#reset-preview", function () {
                    let originalProfileImageSrc = $(".profile-element .img-circle").attr("src");

                    $("#user-profile-image").attr('src', originalProfileImageSrc);
                    $(this).siblings(".btn-default.hidden").removeClass("hidden");
                    $(this).prev(".btn-default:not(.hidden)").addClass("hidden");
                    $(this).addClass("hidden");
                });
            },

            previewImage: function () {
                $(document).on("change", "#account-upload", function () {
                    let fileData = $(this).prop('files')[0];

                    if (fileData.type.match(/image.*/)) {
                        $("#reset-preview").removeClass("hidden");
                        let reader = new FileReader();

                        reader.onload = function (e) {
                            $("#user-profile-image").attr('src', e.target.result);
                        };

                        reader.readAsDataURL($(this).prop('files')[0]);

                        $(this).addClass("hidden");
                        $(this).prev(".btn-default:not(.hidden)").addClass("hidden");
                        $(this).next(".btn-default.hidden").removeClass("hidden");
                    } else {
                        $(this).replaceWith($(this).val('').clone(true));
                        toastr.error("Please upload the image .jpg|.jpeg|.png!");
                    }
                });
            },

            uploadImage: function () {
                $(document).on("click", "#save-profile-img", function () {
                    let $this = $(this);
                    let fileData = $("#account-upload").prop('files')[0];
                    if (!fileData.type.match(/image.*/)) {
                        toastr.error("File type not supported!");

                        return;
                    }

                    let formData = new FormData();
                    formData.append('file', fileData);

                    Vemid.misc.makeAjaxCall('/form/user/upload-profile-image', "POST", formData)
                        .then(function (respJson) {
                            $("#user-profile-image").attr('src', respJson.avatar);
                            $(".profile-element .img-circle").attr('src', respJson.avatar);

                            $this.addClass("hidden");
                            $this.siblings("label").removeClass("hidden");
                            $("#reset-preview").addClass("hidden");
                        }, function (reason) {
                            toastr.error("Error in processing your request", reason);
                        });
                });
            },

            init: function () {
                this.resetPreviewImage();
                this.previewImage();
                this.uploadImage();
            }
        }
    })();
}).call(Vemid, $);