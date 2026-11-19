<?php $pager->setSurroundCount(2); ?>
<nav aria-label="Navigasi Halaman" class="flex flex-wrap items-center justify-between gap-3">
    <div class="text-xs text-[#7A6655]">
        Halaman <span class="font-bold text-[#2C1810]"><?= $pager->getCurrentPageNumber() ?></span>
        dari <span class="font-bold text-[#2C1810]"><?= $pager->getPageCount() ?></span>
    </div>

    <ul class="flex flex-wrap items-center gap-1.5 text-xs font-semibold">
        <?php if ($pager->hasPreviousPage()): ?>
            <li>
                <a href="<?= $pager->getPreviousPage() ?>"
                   class="btn-smooth px-3.5 py-2 rounded-full bg-white border border-[#E8DFD5] text-[#4A2C20] hover:border-[#C86D2D] hover:text-[#C86D2D]">
                    &larr; Prev
                </a>
            </li>
        <?php endif; ?>

        <?php foreach ($pager->links() as $link): ?>
            <li>
                <a href="<?= $link['uri'] ?>"
                   class="btn-smooth min-w-9 h-9 px-3 rounded-full border <?= $link['active'] ? 'bg-[#C86D2D] border-[#C86D2D] text-white shadow-sm' : 'bg-white border-[#E8DFD5] text-[#4A2C20] hover:border-[#C86D2D] hover:text-[#C86D2D]' ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach; ?>

        <?php if ($pager->hasNextPage()): ?>
            <li>
                <a href="<?= $pager->getNextPage() ?>"
                   class="btn-smooth px-3.5 py-2 rounded-full bg-white border border-[#E8DFD5] text-[#4A2C20] hover:border-[#C86D2D] hover:text-[#C86D2D]">
                    Next &rarr;
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
