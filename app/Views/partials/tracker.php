<?php
/** @var string $status */
$steps = [
    'awaiting' => ['label' => 'Awaiting Confirmation', 'icon' => icon_bag()],
    'processing' => ['label' => 'Processing', 'icon' => icon_box()],
    'out_for_delivery' => ['label' => 'Out for Delivery', 'icon' => icon_truck()],
    'delivered' => ['label' => 'Delivered', 'icon' => icon_check()],
];
$currentStep = status_step($status);
$fillPercent = $status === 'cancelled' ? 0 : max(0, ($currentStep - 1)) / 3 * 100;
?>
<?php if ($status === 'cancelled'): ?>
    <div class="tracker-cancelled">This order was cancelled.</div>
<?php else: ?>
    <div class="tracker">
        <div class="fill-line" style="width: calc(<?= $fillPercent ?>% - <?= $fillPercent > 0 ? '0px' : '0px' ?>);"></div>
        <?php $i = 1; foreach ($steps as $key => $step): ?>
            <div class="tracker-step <?= $i < $currentStep ? 'done' : ($i === $currentStep ? 'current' : '') ?>">
                <div class="tracker-dot"><?= $i < $currentStep ? icon_check() : $step['icon'] ?></div>
                <div class="tracker-label"><?= $step['label'] ?></div>
            </div>
        <?php $i++; endforeach; ?>
    </div>
<?php endif; ?>
