<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Trình duyệt file</title>
<style>
body { font-family: Arial; background: #f9f9f9; display: flex; }
.sidebar { width: 200px; background: #fff; border-right: 1px solid #ddd; padding: 10px; }
.sidebar ul { list-style: none; padding: 0; margin: 0; }
.sidebar li { margin: 5px 0; }
.sidebar a { text-decoration: none; color: #333; }
.sidebar a.active { font-weight: bold; color: #007bff; }

.main { flex: 1; padding: 10px; }
img { width: 120px; margin: 8px; border: 1px solid #ccc; cursor: pointer; border-radius: 4px; }
</style>
<script>
function selectFile(url) {
    window.opener.CKEDITOR.tools.callFunction(<?= $funcNum ?? 1 ?>, url);
    window.close();
}
</script>
</head>
<body>

<div class="sidebar">
  <h4>📁 Thư mục</h4>
  <ul>
    <li><a href="?CKEditorFuncNum=<?= $funcNum ?>" class="<?= $currentDir == '' ? 'active' : '' ?>">uploads</a></li>
    <?php foreach ($dirs as $dir): 
        $dirname = basename($dir);
        $link = '?CKEditorFuncNum=' . $funcNum . '&dir=' . urlencode($dirname);
    ?>
      <li><a href="<?= $link ?>" class="<?= $currentDir == $dirname ? 'active' : '' ?>">📂 <?= $dirname ?></a></li>
    <?php endforeach; ?>
  </ul>
</div>

<div class="main">
  <h4>🖼 Ảnh trong thư mục: <?= $currentDir ?: 'uploads' ?></h4>
  <?php if (empty($images)): ?>
    <p><i>Không có ảnh nào.</i></p>
  <?php else: ?>
    <?php foreach ($images as $img): 
        $filename = basename($img);
        $url = $baseUrl . ($currentDir ? $currentDir . '/' : '') . $filename;
    ?>
        <img src="<?= $url ?>" onclick="selectFile('<?= $url ?>')" title="<?= $filename ?>">
    <?php endforeach; ?>
  <?php endif; ?>
</div>

</body>
</html>
