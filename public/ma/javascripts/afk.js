/**
 * IMPORTANT
 * - this same script will run on multiple tabs!
 * - after set duration (default 900seconds), session also expires on server
 */
class JsAfkTimer {

    // timeout in minutes
    constructor(timeout) {
        this.log("AFK: Construct..");

        this.delayCorrection = 15 + 5; //15 seconds display + 5 seconds network
        this.timeout = 15*60 - this.delayCorrection; // default
        if (timeout && timeout >= 1 && timeout < 1440) {
            this.timeout = timeout*60 - this.delayCorrection; // in seconds
        }
        this.keyLogout = "gn_logout";
        this.keyExpireTime = "gn_expire_time";

        // to monitor user activity
        this.userActivity = true; // start with user-activity!
        this.userActivityTimer = null;
        this.eventHandler = this.onUserActivity.bind(this);

        // runs every second to detect user inactivity
        this.watchDogTimer = null;

        // runs on Timeout
        this.logoutTimer = null;
        this.logoutInProgress = false;

        this.init();
        this.tracker();

        this.elapsedTicks = 0;
    }

    init(){
        this.log("AFK: INIT with timeout=" + this.timeout);
        localStorage.setItem(this.keyLogout, null);

        // check if expired on start
        const expiredTime = parseInt(localStorage.getItem(this.keyExpireTime) || 0, 10);
        if (expiredTime > 0 && expiredTime < Date.now()) {
            this.log("AFK: expired on start.. logout");
            // session will expire on server side
        }

        this.startUserActivityTimer();
        this.startWatchDogTimer();
    }


    /************************ USer Activity ************************/
    // runs every 0.8 to update on user activity
    startUserActivityTimer(){
        const _this = this;
        this.userActivityTimer = setInterval(function(){
            _this.log("AFK: check user activity..");
            // check user activity
            if (_this.logoutInProgress == false && _this.userActivity == true) {
                _this.userActivity = false;
                _this.updateExpireTime();
                _this.stopLogoutTimer();
            }
        }, 800);
    }
    stopUserActivityTimer(){
        if (this.userActivityTimer != null) {
            clearInterval(this.userActivityTimer);
            this.userActivityTimer = null;
        }
    }
    onUserActivity(){
        this.log("User activity detected..");
        this.userActivity = true;
    }

    /************************ Watch Dog ************************/
    // runs every second to check if session should expire
    startWatchDogTimer(){
        const _this = this;
        this.watchDogTimer = setInterval(function(){
            // check if expired
            const expiredTime = parseInt(
                localStorage.getItem(_this.keyExpireTime) || 0,
                10
            );

            const expiresAt = expiredTime + this.delayCorrection*1000;
            const expired = expiresAt < Date.now();

            _this.log("AFK: watchdog - expires at: " + expiredTime + ", ElapsedTicks= " + ++_this.elapsedTicks + ", expired:" + expired);
            if (expiredTime == 0){
                _this.updateExpireTime();
            } else if (expired) {
                setTimeout(function(){
                    _this.doLogout();
                }, 10);
            } else if (expiredTime < Date.now()){
                _this.startLogoutTimer();
            } else {
                // _this.log("AFK: expire time: " + expiredTime);
            }

        }, 1000);
    }
    stopWatchDogTimer(){
        if (this.watchDogTimer != null) {
            clearInterval(this.watchDogTimer);
            this.watchDogTimer = null;
        }
    }
    updateExpireTime(){
        this.log("AFK: update time");
        this.elapsedTicks = 0;
        localStorage.setItem(this.keyExpireTime, Date.now() + this.timeout * 1000);
    }

    /************************ Logout Time ************************/
    startLogoutTimer(){
        this.log("AFK: start logout timer..");

        // stop watchDogTimer
        this.stopWatchDogTimer();

        if (this.logoutTimer == null){
            var counter = 15;
            this.logoutInProgress = true;
            localStorage.setItem(this.keyLogout, 'Y');

            const _this = this;
            _this.logoutTimer = setInterval(function() {
                _this.log("AFK: in logout timer..");
                const logout = localStorage.getItem(_this.keyLogout);
                if (logout == 'N') {
                    setTimeout(function(){
                        _this.stopLogoutTimer()
                    }, 50);
                    return;
                }
                _this.log("AFK: continue..");

                // check if expired
                const expiredTime = parseInt(
                    localStorage.getItem(_this.keyExpireTime) || 0,
                    10
                );

                const delay = parseInt(_this.delayCorrection*1000,10);
                _this.log("------ expiredTime: " + expiredTime + ", delay:" + delay);
                const expiresAt = expiredTime + delay;
                const expired = expiresAt < Date.now();
                _this.log("------ expiresAt: " + expiresAt + ", Date.now:" + Date.now() + ", expired: " + expired);
                if (expired) {
                    _this.log("Expired while inactive...");
                }

                // Update counter info & show dialog
                $("#count").html(--counter);
                $('#id_modal_logout_timer').modal('show');
                $("#id_timer_count").html(counter);

                if (expired || counter < 1) {
                    setTimeout(function(){
                        _this.doLogout();
                    }, 10);
                }
            }, 1000);
        }
    }

    stopLogoutTimer(){
        // keep alive on server
        $.get("/m/ajax-keep-alive");

        this.log("AFK: stop logout timer..");
        $('#id_modal_logout_timer').modal('hide');

        this.logoutInProgress = false;
        localStorage.setItem(this.keyLogout, 'N');

        this.updateExpireTime();

        if (this.logoutTimer != null) {
            clearTimeout(this.logoutTimer);
            this.logoutTimer = null;

            // restart watchDog
            this.startWatchDogTimer();
        }
    }

    /************************ Logout ************************/
    doLogout() {
        this.log("AFK: do logout..");

        // if (localStorage.getItem(this.keyLogout) == 'N') {
        //    return;
        // }

        if (this.logoutTimer) {
            clearTimeout(this.logoutTimer);
            this.logoutTimer = null;
        }
        if (this.watchDogTimer) {
            clearInterval(this.watchDogTimer);
            this.watchDogTimer = null;
        }
        if (this.userActivityTimer) {
            clearInterval(this.userActivityTimer);
            this.userActivityTimer = null;
        }

        this.logout();

        // stop logoutTimer
        // this.stopLogoutTimer();
    }

    logout(){
        document.theLogoutForm.submit();
    }

    /************************ Helper ************************/
    tracker() {
        window.addEventListener("mousemove", this.eventHandler);
        window.addEventListener("scroll", this.eventHandler);
        window.addEventListener("keydown", this.eventHandler);
        window.addEventListener("wheel", this.eventHandler);
    }

    log(text){
        // console.log("AFK --- " + text);
    }
}


