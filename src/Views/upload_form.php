<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Iconic Upload — Secure</title>
<link rel="icon" type="image/x-icon" href="/public/images/favicon.ico">
<link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
  <div class="container" role="main" aria-labelledby="title">
    <div class="header">
      <div class="logo">IU</div>
      <div>
        <h1 id="title">Iconic File Upload</h1>
        <div class="lead">Upload files. They are scanned with ClamAV in real time before saving.</div>
      </div>
    </div>

    <div class="card">
      <form id="uploadForm" enctype="multipart/form-data">
        <input id="file" name="file" type="file" aria-label="Choose file to upload" />
        <div class="row"><button id="uploadBtn" type="submit">Upload & Scan</button></div>
      </form>

      <div id="result" class="result">No result yet</div>
      <small class="muted">Files saved to <code>public/uploads</code> when clean. Quarantined files remain in <code>storage/temp</code>.</small>
    </div>

    <footer>© <?php echo date("Y"); ?> Iconic University — Secure Upload Demo</footer>
  </div>

  <script src="/public/js/app.js"></script>
</body>
</html>
