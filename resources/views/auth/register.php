<?php ob_start(); ?>
<div class="container">
    <div class="card">
        <div class="logo">⚡</div>
        <h1 class="card-title">Kayıt Ol</h1>
        <p class="card-subtitle">Yeni hesap oluştur</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/register">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="name">Ad Soyad</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?= e($old['name'] ?? '') ?>"
                    placeholder="Ad Soyad"
                    required
                    autocomplete="name"
                >
                <?php if (!empty($errors['name'])): ?>
                    <div class="field-error"><?= e($errors['name'][0]) ?></div>
                <?php endif; ?>
            </div>

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
                    placeholder="En az 8 karakter"
                    required
                    autocomplete="new-password"
                >
                <?php if (!empty($errors['password'])): ?>
                    <div class="field-error"><?= e($errors['password'][0]) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Şifre Tekrar</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Şifreyi tekrar gir"
                    required
                    autocomplete="new-password"
                >
            </div>

            <button type="submit" class="btn">Kayıt Ol</button>
        </form>

        <div class="links">
            Zaten hesabın var mı? <a href="/login">Giriş Yap</a>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/auth.php'; ?>
