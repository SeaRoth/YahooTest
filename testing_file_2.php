<!DOCTYPE html>
<html>
    <title>jQuery Summing</title>
    <head>
        <script type="text/javascript" src="http://a...content-available-to-author-only...s.com/ajax/libs/jquery/1.8.3/jquery.min.js"> </script>
        $(document).ready(function() {
            $('.calc').on('input', function() {
                var t1 = document.getElementById('txt1');
                var t2 = document.getElementById('txt2');
                var tot=0;
                if (parseInt(t1.value))
                    tot += parseInt(t1.value);
                if (parseInt(t2.value))
                    tot += parseInt(t2.value);
                document.getElementById('txt3').value = tot;
            });
        });
        </script>
    </head>
    <body>
        <input type='text' class='calc' id='txt1'>
        <input type='text' class='calc' id='txt2'>
        <input type='text' id='txt3' readonly>
    </body>
</html>
