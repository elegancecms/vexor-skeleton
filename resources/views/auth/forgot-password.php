<?php ob_start(); ?>
<div class="container">
    <div class="card">
        <div class="logo">⚡</div>
        <h1 class="card-title">Şifremi Unuttum</h1>
        <p class="card-subtitle">E-posta adresinizi girin, sıfırlama bağlantısı gönderelim.</p>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error"><?= e(array_values($errors)[0][0] ?? '') ?></div>
        <?php endif; ?>

        <form method="POST" action="/forgot-password">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="email">E-posta</label>
                <input type="email" id="email" name="email" placeholder="ornek@mail.com" required>
            </div>

            <button type="submit" class="btn">Sıfırlama Bağlantısı Gönder</button>
        </form>

        <div class="links">
            <a href="/login">← Giriş sayfasına dön</a>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/auth.php'; ?>
