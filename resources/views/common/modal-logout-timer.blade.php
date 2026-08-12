<?php
$lifetime = config("session.lifetime");
?>

@if(!\App\Models\ClientInfo::isHGA() && !\App\Models\ClientInfo::isNIF() && !\App\Models\ClientInfo::isJCF())

    {{-- modal overlay for 'user inactivity timeout' view --}}
    <div class="modal fade" id="id_modal_logout_timer" tabindex="-1"
         data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body text-centerX">
                    <h3>Are you still there?</h3>
                    <p>If not, we will close this session in <span id="id_timer_count"></span> seconds.</p>
                    <hr>
                    <div class="text-center">
                        <input name="save" class="btn btn-accent" type="submit" value="I'm here" onclick="stopLogoutTimer();">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/ma/javascripts/afk.js"></script>

    <script>
        let jsAFK = null;
        $(function(){
            jsAFK = new JsAfkTimer('{{$lifetime}}');
        });
        function stopLogoutTimer() {
            jsAFK.stopLogoutTimer();
        }
    </script>

@endif
