(function ($) {
    "use strict";
    let Vemid = this;

    Vemid.namespace("menu");

    Vemid.menu = (function () {

        return {
            initHamburger : function ()  {
                $('.navbar-minimalize').on('click', function (event) {
                    event.preventDefault();
                    $("body").toggleClass("mini-navbar");
                    SmoothlyMenu();
                });

            },
            initMenu : function() {

                // Add body-small class if window less than 768px
                if (window.innerWidth < 769) {
                    $('body').addClass('body-small')
                } else {
                    $('body').removeClass('body-small')
                }

                $('#side-menu').metisMenu();

                $('.collapse-link').on('click', function (e) {
                    e.preventDefault();
                    let ibox = $(this).closest('div.ibox');
                    let button = $(this).find('i');
                    let content = ibox.children('.ibox-content');
                    content.slideToggle(200);
                    button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
                    setTimeout(function () {
                        ibox.resize();
                        ibox.find('[id^=map-]').resize();
                    }, 50);
                });
            },
            init: function () {
                // this.initHamburger();
                this.initMenu();
            }
        };
    })();
}).call(Vemid, $);