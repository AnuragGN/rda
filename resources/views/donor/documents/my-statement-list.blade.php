
<hr>
@foreach($items as $item)
    <p style="margin-bottom: 6px;" class="parent-element">
        <a href="javascript:void(0);"
           data-href="{{$item['link']}}"
           onclick="downloadStatementFile(this)"
           title="Download file">
            {{$item['name']}} <i class="fas fa-file-download"></i>
        </a>
    </p>

    <script>
        function downloadStatementFile(item) {
            var fileUrl = $(item).data("href");
            var message = "<div style='text-align: center'>Download fund statement file?</div><hr class='mb-0'>";
            $.confirm({
                columnClass: 'small',
                title: '',
                content: message,
                buttons: {
                    yes: {
                        text: 'Download',
                        btnClass: 'btn-accent',
                        keys: ['enter', 'shift'],
                        action: function(){
                            window.open(fileUrl, '_blank');
                        }
                    },
                    no: {
                        text: 'Cancel',
                        btnClass: 'btn-grey',
                        keys: ['enter', 'shift'],
                        action: function(){}
                    }
                }
            });
            return false;
        }
    </script>

@endforeach
