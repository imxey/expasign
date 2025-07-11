<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File ke R2</title>
</head>
<body>
    <h2>Upload Bukti Pembayaran</h2>

    <form action="{{ route('registrant.upload') }}" method="POST" enctype="multipart/form-data">
        
        @csrf

        <input type="file" name="receipt" required>
        
        <button type="submit">Upload Sekarang</button>
    </form>
    </body>
</html>