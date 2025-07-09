<link rel="stylesheet" href="<?= base_url('style.css'); ?>">

<?php if ($pager->getFirst() && $pager->getLast() && count($pager->links())): ?>
    <div class="pagination">
        <?php if ($pager->getPrevious()): ?>
            <a href="<?= $pager->getPrevious()['uri']; ?>">&laquo;</a>
        <?php endif; ?>

        <?php foreach ($pager->links() as $link): ?>
            <a href="<?= $link['uri']; ?>" class="<?= $link['active'] ? 'active' : ''; ?>">
                <?= $link['title']; ?>
            </a>
        <?php endforeach; ?>

        <?php if ($pager->getNext()): ?>
            <a href="<?= $pager->getNext()['uri']; ?>">&raquo;</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
