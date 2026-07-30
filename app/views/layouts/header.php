<!DOCTYPE html>

<html
    lang="<?= htmlspecialchars(Language::get()); ?>"
    dir="<?= Language::isRtl() ? 'rtl' : 'ltr'; ?>">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars(t('app.title')); ?>
    </title>

    <?php if (Language::isRtl()): ?>

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.rtl.min.css"
            rel="stylesheet">

    <?php else: ?>

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
            rel="stylesheet">

    <?php endif; ?>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Tajawal:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>assets/css/style.css?v=71">

</head>

<body class="<?= Language::isRtl()
    ? 'rtl-mode'
    : 'ltr-mode'; ?>">