
<?php
$page = isset($page) ? $page : 1;
$class = ['' ,'' ,'' ,'' ,''];
for ($index = 0; $index < $page; $index++) {
    $class[$index] = 'active';
}
?>
<div class="row">
    <div class="col-12">

        <div class="dr-progress">
            <div>
                <ul id="progressbar-text">
                    <li class="{{$class[0]}}">ACCOUNT FUND NAME &<br>ACCOUNT HOLDER INFORMATION</li>
                    <li class="{{$class[1]}}">SUCCESSOR <br>DESIGNATIONS</li>
                    <li class="{{$class[2]}}"><br>CONTRIBUTIONS</li>
                    <li class="{{$class[2]}}"><br>INVESTMENT POOLS</li>
                    <li class="{{$class[3]}}">AUTHORIZATION & <br>FINAL SUBMITTAL</li>
                </ul>

                <ul id="progressbar">
                    <li class="{{$class[0]}}"></li>
                    <li class="{{$class[1]}}"></li>
                    <li class="{{$class[2]}}"></li>
                    <li class="{{$class[3]}}"></li>
                    <li class="{{$class[4]}}"></li>
                </ul>

            </div>
        </div>

    </div>
</div>
