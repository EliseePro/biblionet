<?php if (($pagination['total_items'] ?? 0) > 0): ?>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3">
        <p class="text-muted small mb-0">
            Affichage de <?= (int) $pagination['from'] ?> à <?= (int) $pagination['to'] ?>
            sur <?= (int) $pagination['total_items'] ?> résultat(s)
        </p>

        <?php if ($pagination['total_pages'] > 1): ?>
            <?php
            $visiblePages = array_unique(array_merge(
                [1],
                range(max(1, $pagination['page'] - 2), min($pagination['total_pages'], $pagination['page'] + 2)),
                [$pagination['total_pages']]
            ));
            sort($visiblePages);
            $previousPage = null;
            ?>
            <nav aria-label="Pagination">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item<?= $pagination['page'] <= 1 ? ' disabled' : '' ?>">
                        <?php if ($pagination['page'] > 1): ?>
                            <a class="page-link" href="<?= $e(app_url($paginationPath . '?' . http_build_query(array_merge($paginationQuery, ['page' => $pagination['page'] - 1])))) ?>" aria-label="Page précédente">Précédent</a>
                        <?php else: ?>
                            <span class="page-link">Précédent</span>
                        <?php endif; ?>
                    </li>
                    <?php foreach ($visiblePages as $pageNumber): ?>
                        <?php if ($previousPage !== null && $pageNumber > $previousPage + 1): ?>
                            <li class="page-item disabled" aria-hidden="true"><span class="page-link">…</span></li>
                        <?php endif; ?>
                        <li class="page-item<?= $pageNumber === $pagination['page'] ? ' active' : '' ?>">
                            <?php if ($pageNumber === $pagination['page']): ?>
                                <span class="page-link" aria-current="page"><?= (int) $pageNumber ?></span>
                            <?php else: ?>
                                <a class="page-link" href="<?= $e(app_url($paginationPath . '?' . http_build_query(array_merge($paginationQuery, ['page' => $pageNumber])))) ?>"><?= (int) $pageNumber ?></a>
                            <?php endif; ?>
                        </li>
                        <?php $previousPage = $pageNumber; ?>
                    <?php endforeach; ?>
                    <li class="page-item<?= $pagination['page'] >= $pagination['total_pages'] ? ' disabled' : '' ?>">
                        <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                            <a class="page-link" href="<?= $e(app_url($paginationPath . '?' . http_build_query(array_merge($paginationQuery, ['page' => $pagination['page'] + 1])))) ?>" aria-label="Page suivante">Suivant</a>
                        <?php else: ?>
                            <span class="page-link">Suivant</span>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
<?php endif; ?>
