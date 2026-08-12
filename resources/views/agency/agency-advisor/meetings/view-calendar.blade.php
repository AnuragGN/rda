<?php
//print_r($prettyCalendarEvents);
$array1 = $prettyCalendarEvents['items'];
// Create the $meetings array
$meetings = [];
foreach ($array1 as $event) {
    // Check if both 'summary' and 'attendees' are set for the event
    if (isset($event['summary'])) {
        $description = '';
        if (isset($event['description'])) {
            $description = $event['description'];
        }
        $location = '';
        if (isset($event['location'])) {
            $location = $event['location'];
        }
        // if (isset($event['timeZone'])) {
        //     $timezone = $event['timeZone'];
	    // }
        $timezone = '';
        if (isset($event['start']['timeZone'])) {
            $timezone = $event['start']['timeZone'];
        }
        $meetLink = '';
        if (isset($event['hangoutLink'])) {
            $meetLink = $event['hangoutLink'];
        } 
        // if (isset($createdEvent['conferenceData']['entryPoints'])) {
        //     foreach ($createdEvent['conferenceData']['entryPoints'] as $entryPoint) {
        //         if ($entryPoint['entryPointType'] === 'video') {
        //             $meetLink = $entryPoint['uri'];
        //         }
        //     }
        // }

        if(isset($event['attendees'])){ 
        $meeting = [
            'title' => $event['summary'] . '//' . $event['id'],
            'start' => date('Y-m-d H:i:s', strtotime(@$event['start']['dateTime'])),
            'end' => date('Y-m-d H:i:s', strtotime(@$event['end']['dateTime'])),
            'attendees' => array_map(function ($attendee) {
                return ['email' => $attendee['email']];
            }, $event['attendees']),
            'link' => $event['htmlLink'],
            'description' => $description,
            'location' => $location,
            'timezone' => $timezone,
            'meetLink' => $meetLink,
            'type' => 'event',
    ];
	}else{
        $meeting = [
            'title' => $event['summary'] . '//' . $event['id'],
            'start' => date('Y-m-d H:i:s', strtotime(@$event['start']['dateTime'])),
            'end' => date('Y-m-d H:i:s', strtotime(@$event['end']['dateTime'])),
            'link' => $event['htmlLink'],
            'description' => $description,
            'location' => $location,
            'timezone' => $timezone,
            'meetLink' => $meetLink,
            'type' => 'event',
    ];


        }		
        $meetings[] = $meeting;
    }
}

$taskData = $taskOnGoogleCalendar
    ->map(function ($task) {
        return [
            'id' => $task->id,
            'title' => $task->title,
            'start' => $task->due,
            'end' => $task->due,
            'description' => $task->description,
            'type' => 'task',
        ];
    })
    ->all();

$mergedData = array_merge($meetings, $taskData);
// print_r($taskData);die;
$pageTitle = 'View-Calendar';
?>


@extends(\App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')
@section('content')
    @include('common.page-header', ['pageTitle' => 'Calendar', 'hcXlWidth' => '12'])
    
    <div class="container">
            <div class="form-wrapper form-last">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">

                                {{-- @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif --}}
                                
                                <style>
                                    .fc-daygrid-day-hover {
                                        background-color: #eee3e3 !important;
                                        cursor: pointer;
                                    }
                                </style>

                                <div class="calendar-container">
                                    <div id="calendar"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


<!-- Selection Modal -->
<div class="modal fade" id="selectionModal" tabindex="-1" aria-labelledby="selectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectionModalLabel">Click to Create</h5> 
               <!-- <h5 class="modal-title" id="selectionModalLabel">
                    <span><strong>Click to create:</strong></span>
                    <button id="eventButton" class="btn btn-link p-0 m-0 align-baseline">Event</button>
                    <span>or</span>
                    <button id="taskButton" class="btn btn-link p-0 m-0 align-baseline">Task</button>
                </h5>  -->
                <a href="#" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            <div class="modal-body">
                <button id="eventButton" class="btn btn-primary">Event</button>
                <button id="taskButton" class="btn btn-primary">Task</button>
            </div>
        </div>
    </div>
</div>

<!--Event Creation Modal -->
<div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEventModalLabel">Create New Event</h5>
                <a href="#" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            <div class="modal-body">
                <!-- Your event creation form goes here -->
                <form id="createEventForm" action="{{ route('create.event') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label for="title" class="col-sm-3 col-form-label">Title:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="location" class="col-sm-3 col-form-label">Location:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="location" name="location" required>
                        </div>
                    </div>

                    <!-- Start Date and Time -->
                    <div class="form-group row">
                        <label for="startDateTime" class="col-sm-3 col-form-label">Start Date & Time:</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="startDateTime" name="startDateTime" required>
                        </div>
                    </div>
                    <!-- End Date and Time -->
                    <div class="form-group row">
                        <label for="endDateTime" class="col-sm-3 col-form-label">End Date & Time:</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="endDateTime" name="endDateTime" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="timezone" class="col-sm-3 col-form-label">Timezone:</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="timezone" name="timezone" required>
                                <option value="Asia/Kolkata">(GMT+05:30) India</option>
                                <option value="America/Los_Angeles">(GMT-08:00) Pacific Time (US & Canada)</option>
                                <option value="America/Toronto">(GMT-05:00) Eastern Time (US & Canada)</option>
                                <option value="Europe/Moscow">(GMT+03:00) Moscow</option>
                                <option value="America/Toronto">(GMT-04:00) Canada</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-sm-3 col-form-label">Description:</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="createMeetLink"
                                    name="createMeetLink">
                                <label class="form-check-label" for="createMeetLink">Create Google Meet Link</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" id="assigneesField" style="display: none;">
                        <label for="assignees" class="col-sm-3 col-form-label">Assignees:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="assignees" name="assignees">
                            <!-- <small id="assigneeHelp" class="form-text text-muted">Separate multiple assignees with
                                commas.</small> -->
                        </div>
                    </div>
                    {{-- <div class="form-group">
                            <button type="button" class="btn btn-info" id="addMeetLinkBtn">Add Google Meet Video Conferencing</button>
                        </div> --}}
                    <button type="submit" class="btn btn-primary">Create Event</button>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailsModalLabel">Event Details</h5>
                <a href="#" class="btn-close" data-dismiss="modal" aria-label="Close" id="closeEventModel">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            <div class="modal-body">
                <form action="{{ route('update.event') }}" method="POST">
                    @csrf
                    <input type="hidden" id="event_id" name="event_id_input" />

                    <p><strong>Title:</strong> <span id="eventDetailsModalTitle"></span></p>
                    <p><strong>Start Time:</strong> <span id="eventDetailsModalStart"></span></p>
                    <p><strong>End Time:</strong> <span id="eventDetailsModalEnd"></span></p>
                    <p><strong>Description:</strong> <span id="eventDetailsModalDescription"></span></p>
                    <p><strong>Location:</strong> <span id="eventDetailsModalLocation"></span></p>
                    <p><strong>Timezone:</strong> <span id="eventDetailsModalTimezone"></span></p>
                    <p><strong>Google meet link:</strong> <span id="eventDetailsModalMeetLink"></span></p>
                    <p><strong id="participants">Participants:</strong><span id="eventDetailsModalAttendees"></span></p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-accent btn-sm" id="updateEventBtn"
                    style="display: none;">Update Event</button>
                {{-- <a href="{{route('update.event')}}" class="btn btn-accent btn-sm" id="updateEventBtn" style="display: none;">Update Event</a> --}}
                </form>
                <a href="#" class="btn btn-accent btn-sm" id="editEventBtn">Edit Event</a>

                <form id="deleteEventForm" action="{{ route('delete.event') }}" method="POST">
                    @csrf
                    {{-- @method('POST') --}}
                    <input type="hidden" name="event_id" id="delete_event_id">
                    <button type="button" class="btn btn-danger btn-sm" id="deleteEventBtn"
                        onclick="deleteEvent();">Delete Event</button>
                </form>

                <button type="button" class="btn btn-danger btn-sm" id="cancelEventUpdateBtn"
                    style="display: none;">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Task Creation Modal -->
<div class="modal fade" id="CreateTaskModal" tabindex="-1" aria-labelledby="taskDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskDetailsModalLabel">Create Task</h5>
                <a href="#" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            <div class="modal-body">
                <!-- Add task details here -->
                <form method="POST" action="{{ route('create.task') }}">
                    @csrf

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input id="title" type="text" class="form-control" name="title" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" class="form-control" name="description" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="taskDueDate">Due Date:</label>
                        <input type="datetime-local" class="form-control" id="taskDueDate" name="due" required>
                    </div>

                    {{-- <div class="mb-3">
                        <label for="taskColor" class="form-label">Color</label>
                        <input type="color" class="form-control" id="taskColor" name="color" required>
                    </div> --}}

                    <button type="submit" class="btn btn-primary">Create Task</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Task Details Modal -->
<div class="modal fade" id="taskDetailsModal" tabindex="-1" aria-labelledby="taskDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskDetailsModalLabel">Task Details</h5>
                <a href="#" class="btn-close" data-dismiss="modal" aria-label="Close" id="closeTaskModel">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            <div class="modal-body">
                <form action="{{ route('update.task') }}" method="POST">
                    @csrf
                    <input type="hidden" id="task_id" name="task_id_input" />
                    <p><strong>Title:</strong> <span id="taskDetailsModalTitle"></span></p>
                    <p><strong>Description:</strong> <span id="taskDetailsModalDescription"></span></p>
                    <p><strong>Due:</strong> <span id="taskDetailsModalEnd"></span></p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-accent btn-sm" id="updateTaskBtn"
                    style="display: none;">Update Task</button>
                </form>
                <a href="#" class="btn btn-accent btn-sm" id="editTaskBtn">Edit Task</a>

                <form id="deleteTaskForm" action="{{ route('delete.task') }}" method="POST">
                    @csrf
                    {{-- @method('POST') --}}
                    <input type="hidden" name="task_id" id="delete_task_id">
                    <button type="button" class="btn btn-danger btn-sm" id="deleteTaskBtn"
                        onclick="deleteTask();">Delete Task</button>
                </form>
                <button type="button" class="btn btn-danger btn-sm" id="cancelTaskUpdateBtn"
                    style="display: none;">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        console.log(<?php echo json_encode($mergedData); ?>);

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth', // Default view is month
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,dayGridYear' // Add view buttons here
            },
            views: {
                dayGridMonth: { // Monthly view
                    type: 'dayGridMonth',
                    buttonText: 'Month'
                },
                timeGridWeek: { // Weekly view
                    type: 'timeGridWeek',
                    buttonText: 'Week'
                },
                dayGridYear: { // Yearly view
                    type: 'dayGrid',
                    duration: {
                        years: 1
                    },
                    buttonText: 'Year'
                }
            },
            events: <?php echo json_encode($mergedData); ?>,
            eventClick: function(info) {

                var type = info.event.extendedProps.type;
                // console.log(type);
                if (type === 'task') {
                    console.log(info.event);
                    // Open the task details modal
                    $('#taskDetailsModal').modal('show');

                    // Populate the modal with task details
                    var title = info.event.title;
                    // console.log(title);
                    var description = info.event.extendedProps.description;
                    // var end = info.event.end;
                    var end = info.event._instance.range.end; // Access end date from range object
                    console.log("End Date:", end);
                    var publicId = info.event._def.publicId;
                    console.log(publicId);

                    // Set text content for modal elements
                    $('#task_id').val(publicId);
                    $('#taskDetailsModalTitle').text(title);
                    $('#taskDetailsModalDescription').text(description);
                    // $('#taskDetailsModalEnd').text(end);
                    $('#taskDetailsModalEnd').text(end.toLocaleString());

                } else if (type === 'event') {
			console.log(info.event);
		        var end = info.event._instance.range.end;
		        console.log("the end value :"+end);	
                    $('#eventDetailsModal').modal('show');

                    // Populate modal with event details
                    var infotitle = info.event.title;
                    var etitle = infotitle.split("//")[0];
                    var extractedString = infotitle.split("//")[1];
                    var attendees1 = info.event.extendedProps.attendees || [];
                    const attendees = attendees1;
                    let emailString = '';

                    for (let i = 0; i < attendees.length; i++) {
                        if (i > 0) {
                            emailString += ', ';
                        }
                        emailString += attendees[i].email;
                    }
                    console.log(emailString);
                    var attendeesText = emailString;
                    var meetLink = info.event.extendedProps.meetLink;

                    $('#event_id').val(extractedString);
                    $('#eventDetailsModalTitle').text(etitle);
                    $('#eventDetailsModalStart').text(info.event.start.toLocaleString());
                    $('#eventDetailsModalEnd').text(end.toLocaleString());
                    $('#eventDetailsModalDescription').text(info.event.extendedProps.description);
                    $('#eventDetailsModalLocation').text(info.event.extendedProps.location);
                    $('#eventDetailsModalTimezone').text(info.event.extendedProps.timezone);
                    $('#eventDetailsModalAttendees').text(attendeesText);
                    // $("#eventDetailsModalMeetLink").text(meetLink);
                    $("#eventDetailsModalMeetLink").html('<a href="' + meetLink + '" style="color: blue;" target="_blank">' + meetLink + '</a>');

                    // Check if checkbox already exists, if not, create and append it
                    if ($("#eventDetailsCheckbox").length === 0) {
                        var checkbox = $(
                            '<div class="form-check mt-2"><input class="form-check-input" type="checkbox" id="eventDetailsCheckbox" name="eventDetailsCheckbox"><label class="form-check-label" for="eventDetailsCheckbox">Create Google meet link</label></div>'
                            );
                        $("#participants").before(checkbox);
                    }

                    // Check the checkbox if participants' emails are available
                    if (attendeesText !== '') {
                        $("#eventDetailsCheckbox").prop('checked', true);
                        $("#eventDetailsCheckbox").prop('disabled', true);
                    } else {
                        $("#eventDetailsCheckbox").prop('checked', false);
                        $("#eventDetailsCheckbox").prop('disabled', false);
                    }

                    // Show or hide participants field based on the checkbox state
                    toggleParticipantsField();
                    // Function to show or hide the participants field based on the checkbox state
                    function toggleParticipantsField() {
                        if ($('#eventDetailsCheckbox').is(':checked')) {
                            $('#eventDetailsModalAttendees').parent().show();
                        } else {
                            $('#eventDetailsModalAttendees').parent().hide();
                        }
                    }
                }
            },
            editable: true, // Allow event creation
            selectable: true, // Allow date range selection
            select: function(info) {
                // Show the selection modal
                $('#selectionModal').modal('show');

                // Handle the event button click
                $('#eventButton').off('click').on('click', function() {
                    // Close the selection modal
                    $('#selectionModal').modal('hide');
                    // Show the  create modal
                    $('#createEventModal').modal('show');
                });

                // Handle the task button click
                $('#taskButton').off('click').on('click', function() {
                    // Close the selection modal
                    $('#selectionModal').modal('hide');
                    // Open the task details modal
                    $('#CreateTaskModal').modal('show');

                });
            },
            // Other callbacks and options...
        });
        calendar.render();
        
        $(document).on('mouseenter', '.fc-daygrid-day', function() {
            $(this).addClass('fc-daygrid-day-hover');
        }).on('mouseleave', '.fc-daygrid-day', function() {
            $(this).removeClass('fc-daygrid-day-hover');
        });
    });
</script>
<script>
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        // Get the success message element
        var successMessage = document.getElementById('successMessage');

        // Hide the success message after 10 seconds
        setTimeout(function() {
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 10000); // 10 seconds in milliseconds
    });
</script>

<script>
    // $(document).ready(function() {
    //     $('#deleteEventBtn').on('click', function() {
    //         var eventId = $('#event_id')
    //     .val(); // Get the event ID from a data attribute or any other source
    //         deleteEvent(eventId);
    //         // $('#eventDetailsModal').modal('hide');
    //     });
    // });

    function deleteEvent() {
        var eventId = $('#event_id').val();
        $('#delete_event_id').val(eventId);
        var message = "Are you sure you want to delete this event?";
        $.confirm({
            columnClass: 'medium',
            title: '',
            content: message,
            buttons: {
                no: {
                    text: 'No',
                    btnClass: 'btn-light',
                    keys: ['enter', 'shift'],
                    action: function() {
                        // no action
                    }
                },
                yes: {
                    text: 'Yes',
                    btnClass: 'btn-accent',
                    keys: ['enter', 'shift'],
                    action: function() {
                        $('#deleteEventForm').submit();
                    }
                }
            }
        });
    }
</script>

{{-- JS for: Create Event PopUP --}}
<script>
    $('#createEventForm').submit(function(event) {
        // Check if the checkbox for creating Google Meet link is checked
        if ($('#createMeetLink').is(':checked')) {
            // Check if the assignee field is empty
            if ($('#assignees').val().trim() === '') {
                // Prevent form submission
                event.preventDefault();
                // Show an error message
                var message = "Assignee feild is required";
                $.alert({
                    columnClass: 'medium',
                    title: '',
                    content: message,
                    buttons: {
                        no: {
                            text: 'OK',
                            btnClass: 'btn-accent',
                            keys: ['enter', 'shift'],
                            action: function() {
                                // no action
                            }
                        },
                    }
                });
                // alert('Assignee field is required.');
            }
        }
    });
    $(document).ready(function() {
        $('#createMeetLink').change(function() {
            if ($(this).is(':checked')) {
                $('#assigneesField').show();
            } else {
                $('#assigneesField').hide();
                $('#assignees').val('');
            }
        });
    });
</script>


{{-- JS for: Update Event PopUP --}}
<script>
    $(document).ready(function() {

        $("#editEventBtn").click(function() {
            // Hide edit button and show update button
            $(this).hide();
            $("#deleteEventBtn").hide();
            $("#updateEventBtn").show();
            $("#cancelEventUpdateBtn").show();

            // Convert title, location, description, start date, and end date, attendees paragraphs to input fields
            $("#eventDetailsModalTitle, #eventDetailsModalLocation, #eventDetailsModalDescription, #eventDetailsModalStart, #eventDetailsModalEnd, #eventDetailsModalAttendees, #eventDetailsModalTimezone")
                .each(function() {
                    var text = $(this).text().trim();
                    var input;
                    if ($(this).attr('id') === 'eventDetailsModalDescription') {
                        // Create a textarea for description
                        input = $('<textarea name="description" class="form-control"></textarea>');
                        // Set the value of textarea to the existing content
                        input.val(text);
                    }
                    else if ($(this).attr('id') === 'eventDetailsModalStart') {
                        // Create text input fields for start date
                        var dateTime = new Date(text); // Convert text to date object
                        var formattedDateTime = dateTime.toLocaleString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true }); // Format date and time as mm-dd-yyyy hh:mm AM/PM
                        input = $('<input type="text" name="start_date" class="form-control">');
                        input.val(formattedDateTime); // Set the formatted date and time as the value of the input field
                    } else if ($(this).attr('id') === 'eventDetailsModalEnd') {
                        // Create text input fields for end date
                        var dateTime = new Date(text); // Convert text to date object
                        var formattedDateTime = dateTime.toLocaleString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true }); // Format date and time as mm-dd-yyyy hh:mm AM/PM
                        input = $('<input type="text" name="end_date" class="form-control">');
                        input.val(formattedDateTime); // Set the formatted date and time as the value of the input field
                    }
                    else if ($(this).attr('id') === 'eventDetailsModalLocation') {
                        // Create text input fields for location
                        input = $('<input type="text" name="location" class="form-control">');
                        input.val(text);
                    } else if ($(this).attr('id') === 'eventDetailsModalTimezone') {
                        var timezoneOptions = `
                            <option value="Asia/Kolkata">(GMT+05:30) India</option>
                            <option value="America/Los_Angeles">(GMT-08:00) Pacific Time (US & Canada)</option>
                            <option value="America/Toronto">(GMT-05:00) Eastern Time (US & Canada)</option>
                            <option value="Europe/Moscow">(GMT+03:00) Moscow</option>
                            <option value="America/Toronto">(GMT-04:00) Canada</option>
                        `;
                        input = $('<select name="timezone" class="form-control">' + timezoneOptions + '</select>');
                        // input = $('<input type="text" name="timezone" class="form-control">');
                        input.val(text);
                    }
                    else if ($(this).attr('id') === 'eventDetailsModalAttendees') {
                        // Create text input fields for assignees
                        // var assignees = text.replace('Assignees:', '').trim();
                        input = $('<input type="text" name="assignees" class="form-control">');
                        input.val(text);
                        // Check if help text has been appended before appending it
                        var helpText = $(this).parent().find("#assigneeHelp");
                        if (helpText.length === 0) {
                            helpText = $(
                                '<small id="assigneeHelp" class="form-text text-muted">Separate multiple assignees with commas.</small>'
                            );
                            $(this).parent().append(helpText);
                        }
                    } else {
                        // Create text input fields for title
                        input = $('<input type="text" name="title" class="form-control">');
                        input.val(text);
                    }
                    $(this).html(input);
                });
        });

        $("#cancelEventUpdateBtn").click(function() {

            $(this).hide();
            $("#updateEventBtn").hide();
            $("#cancelEventUpdateBtn").hide();
            $("#deleteEventBtn").show();
            $("#editEventBtn").show();
            // Convert input fields back to paragraphs
            $("#eventDetailsModal .modal-body input, #eventDetailsModal .modal-body textarea, #eventDetailsModal .modal-body select").each(
                function() {
                    // Check if the input field or textarea is hidden
                    if (!$(this).is(':hidden')) {
                        var text;
                        if ($(this).is('textarea')) {
                            // For textareas, retrieve the text from the textarea
                            text = $(this).val().trim();
                        } else if ($(this).is('select')) {
                             text = $(this).find('option:selected').text();
                        }
                        else if ($(this).is(':checkbox')) {
                            // For checkboxes, retrieve the checked status
                            text = $(this).is(':checked') ? 'Checked' : 'Unchecked';
                        }
                        else {
                            // For other input fields, retrieve the text from the value attribute
                            text = $(this).val().trim();
                        }
                        var span = $('<span>');
                        span.text(text);
                        // Replace the input/textarea with the span, except for the checkbox
                        if (!$(this).is(':checkbox')) {
                            $(this).parent().html(span);
                        }
                    }
                });

        });

        $("#closeEventModel").click(function() {
            // alert("Anurag");
            $("#updateEventBtn").hide();
            $("#cancelEventUpdateBtn").hide();
            $("#deleteEventBtn").show();
            $("#editEventBtn").show();
        });

    });
</script>

{{-- JS for: Delete Task --}}
<script>
    function deleteTask() {
        var taskId = $('#task_id').val();
        $('#delete_task_id').val(taskId);
        var message = "Are you sure you want to delete this task?";
        $.confirm({
            columnClass: 'medium',
            title: '',
            content: message,
            buttons: {
                no: {
                    text: 'No',
                    btnClass: 'btn-light',
                    keys: ['enter', 'shift'],
                    action: function() {
                        // no action
                    }
                },
                yes: {
                    text: 'Yes',
                    btnClass: 'btn-accent',
                    keys: ['enter', 'shift'],
                    action: function() {
                        $('#deleteTaskForm').submit();
                    }
                }
            }
        });
    }
</script>

{{-- JS for: Update Task PopUP --}}
<script>
    $(document).ready(function() {
        var originalValues = {};
        $("#editTaskBtn").click(function() {
            $(this).hide();
            $("#deleteTaskBtn").hide();
            $("#updateTaskBtn").show();
            $("#cancelTaskUpdateBtn").show();

            $("#taskDetailsModalTitle, #taskDetailsModalDescription, #taskDetailsModalEnd").each(
                function() {

                    var text = $(this).text().trim();
                    originalValues[$(this).attr('id')] = text; // Store original value
                    // console.log(text);
                    var input;
                    if ($(this).attr('id') === 'taskDetailsModalDescription') {
                        // Create a textarea for description
                        input = $(
                            '<textarea name="taskDescription" id="taskDetailsModalDescription" class="form-control"></textarea>'
                        );
                        // Set the value of textarea to the existing content
                        input.val(text);
                        $(this).replaceWith(input);
                    } else if ($(this).attr('id') === 'taskDetailsModalEnd') {
                        // Create text input fields for due date
                        var dateTime = new Date(text);
                        var formattedDateTime = dateTime.toLocaleString('en-US', {
                            month: '2-digit',
                            day: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        });
                        input = $(
                            '<input type="text" name="dueDate" id="taskDetailsModalEnd" class="form-control">'
                        );
                        input.val(formattedDateTime);
                        // Replace the current element with the new input field
                        $(this).replaceWith(input);
                    } else {
                        // Create text input fields for title
                        input = $(
                            '<input type="text" name="taskTitle" id="taskDetailsModalTitle" class="form-control">'
                        );
                        input.val(text);
                        // Replace the current element with the new input field
                        $(this).replaceWith(input);
                    }


                });
        });

        $("#cancelTaskUpdateBtn").click(function() {
            $(this).hide();
            $("#deleteTaskBtn").show();
            $("#updateTaskBtn").hide();
            $("#editTaskBtn").show();

            // Revert all the changes to original values
            $("#taskDetailsModal .modal-body input, #taskDetailsModal .modal-body textarea").each(function() {
                var id = $(this).attr('id');
                var originalText = originalValues[id];
                var span = $('<span id="' + id + '"></span>');
                span.text(originalText);

                $(this).replaceWith(span);
            });
        });
    });
</script>
{{-- JS for: Email Id suggestion in create event POPUP --}}
<script>  
    $(document).ready(function() {
        $("#assignees").autocomplete({
            source: function(request, response) {
                var terms = request.term.split(/,\s*/); // Split input by commas
                var term = terms.pop(); // Get the last term after splitting by commas
                $.ajax({
                    url: "/m/email-suggestions",
                    dataType: "json",
                    data: {
                        term: term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            appendTo: "#createEventModal", // Append suggestions to modal
            focus: function() {
                // Prevent value inserted on focus
                return false;
            },
            select: function(event, ui) {
                var terms = this.value.split(/,\s*/); // Split current value by commas
                terms.pop(); // Remove the last term
                terms.push(ui.item.value); // Add selected value
                terms.push(""); // Add an empty string for the next term
                this.value = terms.join(", "); // Update input value
                return false;
            }
        });
    });
</script>
@endsection
