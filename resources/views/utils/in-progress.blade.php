{{-- modal overlay for 'transaction in progress' view --}}
<div class="modal fade" id="id_in_progress_overlay" tabindex="-1"
     data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="id_in_progress_overlay" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <span class="fw600">
                    <img src="/ma/images/spinner.gif" width="16px" class="mb-1">&nbsp;&nbsp;
                    <span id="id_in_progress_overlay_message"></span>
                </span>
            </div>
        </div>
    </div>
</div>
<script>
    function showInProgressOverlay(message) {
        if (!message || message == undefined) message = "Processing your request...";
        $('#id_in_progress_overlay_message').html(message);
        $('#id_in_progress_overlay').modal('show');
    }
    function hideInProgressOverlay(message) {
        $('#id_in_progress_overlay').modal('hide');
    }
</script>