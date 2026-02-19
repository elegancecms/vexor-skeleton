<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= e($title ?? config('app.name', 'Vexor')) ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; color: #333; }
        .container { max-width: 420px; margin: 80px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 12px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
        .card-title { font-size: 24px; font-weight: 700; margin-bottom: 8px; }
        .card-subtitle { color: #666; margin-bottom: 28px; font-size: 14px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #444; }
        input { width: 100%; padding: 10px 14px; border: 1.5px solid #ddd; border-radius: 8px; font-size: 15px; transition: border-color .2s; }
        input:focus { outline: none; border-color: #6c63ff; }
        .btn { width: 100%; padding: 12px; background: #6c63ff; color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: background .2s; }
        .btn:hover { background: #574fd6; }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .alert-error { background: #fff0f0; color: #c0392b; border: 1px solid #f5c6cb; }
        .alert-success { background: #f0fff4; color: #27ae60; border: 1px solid #c3e6cb; }
        .field-error { color: #c0392b; font-size: 12px; margin-top: 4px; }
        .links { text-align: center; margin-top: 20px; font-size: 14px; color: #666; }
        .links a { color: #6c63ff; text-decoration: none; font-weight: 600; }
        .links a:hover { text-decoration: underline; }
        .checkbox-group { display: flex; align-items: center; gap: 8px; }
        .checkbox-group input { width: auto; }
        .divider { text-align: center; color: #aaa; margin: 16px 0; font-size: 13px; }
        .logo { text-align: center; margin-bottom: 28px; font-size: 28px; }
    </style>
</head>
<body>
<?= $content ?? '' ?>
</body>
</html>
