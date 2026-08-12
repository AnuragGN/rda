<style>
    #theLogoutForm {margin: 0;}
</style>
<form name="theLogoutForm" id="theLogoutForm" method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Confirm Log Out');">
    {{ csrf_field() }}
</form>

<script>
    function onLogout() {
        var message = "Are you sure you want to log out?";
        $.confirm({
            columnClass: 'medium',
            title: '',
            content: message,
            buttons: {
                no: {
                    text: 'Cancel',
                    btnClass: 'btn-light',
                    keys: ['enter', 'shift'],
                    action: function(){
                        // no action
                    }
                },
                yes: {
                    text: 'Confirm',
                    btnClass: 'btn-accent',
                    keys: ['enter', 'shift'],
                    action: function(){
                        document.theLogoutForm.submit();
                    }
                }
            }
        });
    }
</script>
