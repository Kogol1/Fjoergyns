<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>
<body style="font-family: 'Source Code Pro', monospace;">
<div class="container" style="margin-top: 30px">
    <div class="container">
        <form id="form">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="killer" type="text" class="form-control" id="killer" placeholder="Nick vraha" value="{{$killer ?? null}}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="victim" type="text" class="form-control" id="victim" placeholder="Nick oběti" value="{{$victim ?? null}}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" id="server">
                            <option @if($server === 'Survival') selected @endif>Survival</option>
                            <option @if($server === 'Economy') selected @endif>Economy</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div id="submit" class="btn btn-primary">Vyhledat</div>
                </div>
            </div>
        </form>
    </div>
    <script>
        $("#submit").click(function () {
            var http = window.location.origin;
            var killer = $("#killer").val();
            var victim = $("#victim").val();
            var server = $("#server").val();
            if (killer !== '' && victim !== '') {
                window.location.replace(http + '/tpa-kills/' + killer + '/' + victim + '/' + server);
            }
        });
    </script>

    @if(isset($killer) && isset($victim) && isset($log))
        <h3>
            Log vraha <span style="color:darkred">{{ $killer }}</span> a oběti <span
                style="color:blue">{{ $victim }}</span> ze serveru {{ $server }}
            <hr>
        </h3>
        @foreach($log as $command)
            @if(isset($command->user) && ($command->teleportationCoammand()))

                <div class="row">
                    <div class="col-md-2">
                        <span style="font-size: small">{{ date('d.m.Y H:i:s', $command->time)}}</span>
                    </div>
                    <div class="col-md-10">
                        <span style="color: @if($command->user()->user == $killer) darkred @else blue @endif">
                            {{ $command->message }}
                        </span>
                    </div>
                </div>
            @endif
            @if(!is_null($command->date))
                <div class="row">
                    <div class="col-md-2">
                        <span style="font-size: small">{{ date('d.m.Y H:i:s', $command->time)}}</span>
                    </div>
                    <div class="col-md-10">
                        <span style="color: mediumvioletred">
                            {{ $killer }} zabil {{ $victim }} pomocí {{ $command->weapon }}
                            </span>
                    </div>
                </div>
            @endif
        @endforeach
    @else
        <h3>
            Hráč nenalezen
        </h3>
    @endif
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
</body>
</html>

