$(document).ready(function () {

    initCalendar();

});

/**
 *
 */
function initCalendar() {
    $("#calendar").fullCalendar({
        header: {
            left: "prev, today, next",
            center: "title",
            right: "month, agendaWeek, agendaDay"
        },
        editable: false,
        droppable: false,
        eventSources: {
            url: "/trainings/calendar",
            type: "post"
        },
        eventRender: function (event, element) {
            element
                .attr({
                    "title": event.title
                });
        },
        //eventClick: function (calEvent, jsEvent, view) {
        //    getUpdateForm("trainings", calEvent.id, jsEvent);
        //},
        defaultView: "month"
    });
}