<?php
$e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$title = 'Connexion';
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-4 text-center">Connexion bibliothécaire</h1>
                <form method="post" action="<?= $e(app_url('/login')) ?>" autocomplete="on">
                    <input type="hidden" name="csrf_token" value="<?= $e($csrf_token ?? '') ?>">

                    <?php if (isset($errors['csrf'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $e($errors['csrf']) ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                               value="<?= $e($old['email'] ?? '') ?>" required autofocus>
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback"><?= $e($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" required>
                        <?php if (isset($errors['password'])): ?>
                            <div class="invalid-feedback"><?= $e($errors['password']) ?></div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>
                <p class="text-muted small mt-3 mb-0 text-center">
                    Démo : admin@biblionet.local / admin123
                </p>
            </div>
        </div>
    </div>
</div>
