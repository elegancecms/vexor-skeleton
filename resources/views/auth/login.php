<?php ob_start(); ?>
<div class="container">
    <div class="card">
        <div class="logo">⚡</div>
        <h1 class="card-title">Giriş Yap</h1>
        <p class="card-subtitle"><?= e(config('app.name')) ?>'e hoş geldiniz</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="/login">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="email">E-posta</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= e($old['email'] ?? '') ?>"
                    placeholder="ornek@mail.com"
                    required
                    autocomplete="email"
                >
                <?php if (!empty($errors['email'])): ?>
                    <div class="field-error"><?= e($errors['email'][0]) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Şifre</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                >
                <?php if (!empty($errors['password'])): ?>
                    <div class="field-error"><?= e($errors['password'][0]) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember" value="1">
                    <label for="remember" style="margin-bottom:0">Beni hatırla</label>
                </div>
            </div>

            <button type="submit" class="btn">Giriş Yap</button>
        </form>

        <div class="links">
            <a href="/forgot-password">Şifremi Unuttum</a>
        </div>
        <div class="divider">— veya —</div>
        <div class="links">
            Hesabın yok mu? <a href="/register">Kayıt Ol</a>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/auth.php'; ?>
