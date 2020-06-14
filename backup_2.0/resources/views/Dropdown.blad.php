<html>
<head>
    <title>province</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <style>
        .box {
            width: 500px;
            margin: 0 auto;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <div class="container box">
        <h3>ข้อมูลจังหวัดในประเทศไทย</h3>
        <div class="form-group">
            <select name="province" id="province" class="form-control province" >
                <option value="">เลือกข้อมูลจังหวัด</option>
                @foreach ($list as $row)
                    <option value="{{ $row->id }}">{{$row->name_th}}</option>
                @endforeach
            </select>
        </div>
    
        <div class="form-group">
            <select name="province" class="form-control amphures">
                <option value="">เลือกข้อมูลอำเภอ</option>
            </select>
        </div>
    </div>
    {{ csrf_field()}}
</body>
</html>
<script type="text/javascript">
    $('.province').change(function(){
        if($(this).val()!=''){
            var select=$(this).val();
            var _token=$('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('dropdown.fetch')}}",
                method:"POST",
                data:{select:select,_token:_token},
                success:function(result){
                    $('.amphures').html(result);  
                }
            })
        }
    });
</script>