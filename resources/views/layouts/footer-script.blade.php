<!-- App's Basic Js  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/metisMenu.min.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/waves.min.js"></script>



<script src="assets/js/notify.js"></script>

 @yield('script')

<!-- App js-->
<script src="assets/js/app.js"></script>

@yield('script-bottom')

<script>
    function changeImg(input){
//Nếu như tồn thuộc tính file, đồng nghĩa người dùng đã chọn file mới
        if(input.files && input.files[0]){
            var reader = new FileReader();
//Sự kiện file đã được load vào website
            reader.onload = function(e){
//Thay đổi đường dẫn ảnh
                $('#avatar').attr('src',e.target.result);
                $('#avatar_fake').attr('src',e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(document).ready(function() {
        $('#avatar').click(function(){
            $('#logo').click();
        });
        $('#avatar_fake').click(function(){
            $('#logo_fake').click();
        });
    });
</script>

<script>
    function copy1() {
        let textarea1 = document.getElementById("policy1");
        textarea1.select();
        document.execCommand("copy");
    }
    function copy2() {
        let textarea2 = document.getElementById("policy2");
        textarea2.select();
        console.log(textarea2)
        document.execCommand("copy");
    }
</script>

