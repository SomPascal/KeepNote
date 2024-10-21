<!DOCTYPE html>
<html lang="<?= service('request')?->getLocale() ?>" translate="no">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <!-- Bulma's files: start -->
    <link rel="stylesheet" href="/assets/libs/bulma/css/bulma.min.css">
    
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.4/css/bulma.min.css" integrity="sha512-HqxHUkJM0SYcbvxUw5P60SzdOTy/QVwA1JJrvaXJv4q7lmbDZCmZaqz01UPOaQveoxfYRv1tHozWGPMcuTBuvQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

    <!-- Font awesome libraby -->
    <link rel="stylesheet" href="/assets/font-awesome/css/font-awesome.min.css">
    
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

    <?php foreach (($head->styles) as $style): ?>
        <link rel="stylesheet" href="/assets/css/<?= esc($style, 'url') ?>">
    <?php endforeach ?>
    <!-- favicon -->
    <link rel="shortcut icon" href="/assets/img/icons/keepnote-logo.png" type="image/x-icon">

    <!-- Tab bar color -->
    <meta name="theme-color" content="<?= $head->color ?? "#ffe08a" ?>">
    <meta name="description" content="<?= sprintf("%s | KeepNote", $head->description  ?? "Description") ?>">

    <?php if (config("Config\App")->PWA == true): ?>
        <link rel="manifest" href="/manifest.json">
    <?php endif ?>

    <title>KeepNote | <?= esc($head->title ?? "") ?></title>
</head>
<body>
<?= csrf_field('csrf_input') ?>
