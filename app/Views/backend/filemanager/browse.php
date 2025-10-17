<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Trình duyệt file</title>
<style>
body { font-family: Arial; background: #f9f9f9; }
img { width: 120px; margin: 10px; border: 1px solid #ccc; cursor: pointer; }
</style>
<script>
function selectFile(url) {
    window.opener.CKEDITOR.tools.callFunction(<?= $funcNum ?? 1 ?>, url);
    window.close();
}
</script>
</head>
<body>
<h3>Chọn hình ảnh</h3>
<?php foreach ($files as $file): 
    $filename = basename($file);
    $url = $baseUrl . $filename;
?>
    <img src="<?= $url ?>" onclick="selectFile('<?= $url ?>')" title="<?= $filename ?>">
<?php endforeach; ?>
</body>
</html>
