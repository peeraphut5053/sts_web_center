
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ขอเข้า Syteline</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <!-- <link href="css/bootstrap.min.css" rel="stylesheet">
  <script src="js/bootstrap.js"></script> -->
</head>
<body style="background: rgb(215, 231, 247);">
  <div class="container-sm" style="margin-top: 10%;">

    <div class="row justify-content-center">
      <div class="col-4">
        <p class="form-label" style="text-align: center; color: hsl(0, 0%, 99%); font-size: 32px; background-color: rgba(21, 81, 209, 0.979);">ขอเข้า Syteline</p>
      </div>
    </div>
<br>
<form method="post" action="data.php" id="form1" name="form1">
    <div class="row justify-content-center">
        <div class="col-md-4">
          <label for="department" class="form-label">แผนก</label>
          <select id="department" name="department" id="department" class="form-select">
            <option selected>--กรุณาเลือกแผนก--</option>
            <option>ฝ่ายบัญชี</option>
            <option>ฝ่ายจัดซื้อ</option>
            <option>ฝ่ายขาย</option>
            <option>ฝ่ายผลิต</option>
            <option>ฝ่ายตรวจสอบคุณภาพ</option>
          </select>
        </div>
      </div>
<br>
    
    <div class="row justify-content-center">
        <div class="col-md-4">
          <label for="username" class="form-label">ชื่อผู้ขอ</label>
          <input type="hidden" name="load" value="minkick">
          <input type="text" name="username" id="username" value="" class="form-control">
        </div>
      </div>
<br>
    <div class="row justify-content-center">
      <div class="d-grid gap-2 col-4 mx-auto">
        <!-- <a href="http://61.90.156.165/sts_web_center/module/APP_Infor_syteline_user_session/data.php?load=minkick&minkick=9&textline=%E0%B8%84%E0%B8%A3%E0%B8%B1%E0%B8%9A%E0%B8%9C%E0%B8%A1%20%E0%B9%80%E0%B8%82%E0%B9%89%E0%B8%B2%E0%B9%84%E0%B8%94%E0%B9%89%E0%B9%80%E0%B8%A5%E0%B8%A2%E0%B8%84%E0%B8%A3%E0%B8%B1%E0%B8%9A%20!!&con_sendtoline=1&con_clickuser=1">  -->
          <button type="submit" onclick="submitForm('data.php')" class="btn btn-primary active">ขอเข้าระบบ</button></a>
      </div>
</from>
</div>
<script type="text/javascript">
  function submitForm(action) {
    var form = document.getElementById('form1');
    form.action = action;
    form.submit();
  }
</script>
</body>
</html>