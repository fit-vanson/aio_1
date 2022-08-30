
        <div>
            <table class="table table-bordered mb-0 hubTable">
                <thead>
                <tr>
                    <th>Hub ID</th>
                    <th>Cọc Sim</th>
                    <th>Phone</th>
                    <th>Code</th>
                    <th>Code Space</th>
                    <th>Time Space</th>
                </tr>
                </thead>
                <tbody>
            @foreach($items as $item)
                <tr>
                    <th>{{$item->hubid}}</th>
                    <td>{{$item->cocsim}}</td>
                    <td id="phone">{{$item->phone}} <button type="button" data-text="{{$item->phone}}" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button></td>
                    <td id="code">{{$item->code}} <button type="button" data-text="{{$item->code}}" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button></td>
                    <td>{{$item->code}}</td>
                    <td>{{$item->code}}</td>
                </tr>
            @endforeach

                </tbody>
            </table>
        </div>

        <script>
            $('.copyboard').on('click', function(e) {
                e.preventDefault();
                var copyText = $(this).attr('data-text');
                var textarea = document.createElement("textarea");
                textarea.textContent = copyText;
                textarea.style.position = "fixed"; // Prevent scrolling to bottom of page in MS Edge.
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand("copy");
                document.body.removeChild(textarea);
            })
        </script>

