<?php ob_start(); ?>
<div class="container">
    <div class="card">
        <div class="logo">⚡</div>
        <h1 class="card-title">Yeni Şifre</h1>
        <p class="card-subtitle">Yeni şifrenizi belirleyin.</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/reset-password">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= e($token ?? '') ?>">
            <input type="hidden" name="email" value="<?= e($email ?? '') ?>">

            <div class="form-group">
                <label for="password">Yeni Şifre</label>
                <input type="password" id="password" name="password" placeholder="En az 8 karakter" required>
                <?php if (!empty($errors['password'])): ?>
                    <div class="field-error"><?= e($errors['password'][0]) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Yeni Şifre Tekrar</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Şifreyi tekrar gir" required>
            </div>

            <button type="submit" class="btn">Şifremi Güncelle</button>
        </form>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/auth.php'; ?>
