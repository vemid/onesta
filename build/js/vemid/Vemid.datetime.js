(function ($) {
    "use strict";
    let Vemid = this;

    Vemid.namespace("datetime");

    Vemid.datetime = (function () {

        let _getShortNameDays = function () {
            return [
                Vemid.language.get('mondayShort'),
                Vemid.language.get('tuesdayShort'),
                Vemid.language.get('wednesdayShort'),
                Vemid.language.get('thursdayShort'),
                Vemid.language.get('fridayShort'),
                Vemid.language.get('saturdayShort'),
                Vemid.language.get('sundayShort')
            ];
        };

        let _getLongNameDays = function () {
            return [
                Vemid.language.get('mondayLong'),
                Vemid.language.get('tuesdayLong'),
                Vemid.language.get('wednesdayLong'),
                Vemid.language.get('thursdayLong'),
                Vemid.language.get('fridayLong'),
                Vemid.language.get('saturdayLong'),
                Vemid.language.get('sundayLong')
            ];
        };

        let _getShortMonthNames = function () {
            return [
                Vemid.language.get('janShort'),
                Vemid.language.get('febShort'),
                Vemid.language.get('marShort'),
                Vemid.language.get('aprShort'),
                Vemid.language.get('mayShort'),
                Vemid.language.get('junShort'),
                Vemid.language.get('julShort'),
                Vemid.language.get('augShort'),
                Vemid.language.get('sepShort'),
                Vemid.language.get('octShort'),
                Vemid.language.get('novShort'),
                Vemid.language.get('decShort'),
            ];
        };

        let _getLongMonthNames = function () {
            return [
                Vemid.language.get('janLong'),
                Vemid.language.get('febLong'),
                Vemid.language.get('marLong'),
                Vemid.language.get('aprLong'),
                Vemid.language.get('mayLong'),
                Vemid.language.get('junLong'),
                Vemid.language.get('julLong'),
                Vemid.language.get('augLong'),
                Vemid.language.get('sepLong'),
                Vemid.language.get('octLong'),
                Vemid.language.get('novLong'),
                Vemid.language.get('decLong'),
            ];
        };

        return {
            initDate: function ($element, date) {
                let fp = $element.flatpickr({
                    altInput: true,
                    static: !$(".modal").is(":hidden"),
                    altFormat: "F j, Y",
                    allowInput: true,
                    locale: {
                        firstDayOfWeek: 1,
                        weekdays: {
                            shorthand: _getShortNameDays(),
                            longhand: _getLongNameDays(),
                        },
                        months: {
                            shorthand: _getShortMonthNames(),
                            longhand: _getLongMonthNames(),
                        },
                    },
                    onChange: function (selectedDates, dateStr, instance) {
                    },
                });

                if (!!date) {
                    fp.setDate(date);
                }
            },

            init: function () {
                this.initDate($(".datepicker"));
            }
        }
    })();
}).call(Vemid, $);