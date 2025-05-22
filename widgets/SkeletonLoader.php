<?php

namespace app\widgets;

use yii\base\Widget;

class SkeletonLoader extends Widget
{
    public $blocks = 2;
    public $lines = 3;
    public $variant = 'default'; // 'default', 'avatar-card', 'icon', 'table'
    public $compact = false;
    public $fadeAfter = null; // em milissegundos, ex: 2000 para 2 segundos

    public function run()
    {
        $id = 'skeleton-' . uniqid();
        $output = "<div id=\"$id\" class=\"skeleton-loader\">";

        for ($i = 0; $i < $this->blocks; $i++) {
            $output .= '<div class="skeleton-card rounded shadow-sm p-3 mb-3 d-flex align-items-start gap-3">';

            switch ($this->variant) {
                case 'avatar-card':
                    $output .= '<div class="skeleton-avatar flex-shrink-0"></div><div class="flex-grow-1">';
                    $output .= '<div class="skeleton-title w-50 mb-2"></div><div class="skeleton-line w-75 mb-3"></div>';
                    for ($j = 0; $j < $this->lines; $j++) {
                        $w = [100, 90, 80, 60][array_rand([0, 1, 2, 3])];
                        $output .= "<div class=\"skeleton-line w-{$w} mb-2\"></div>";
                    }
                    $output .= '</div>';
                    break;

                case 'icon':
                    $output .= '<div class="skeleton-icon-square flex-shrink-0"></div><div class="flex-grow-1">';
                    $output .= '<div class="skeleton-line w-100 mb-2"></div>';
                    $output .= '<div class="skeleton-line w-75 mb-2"></div>';
                    $output .= '</div>';
                    break;

                case 'table':
                    $output .= '<div class="w-100">';
                    for ($j = 0; $j < $this->lines; $j++) {
                        $cols = rand(3, 5);
                        $output .= '<div class="d-flex gap-3 mb-2">';
                        for ($c = 0; $c < $cols; $c++) {
                            $w = rand(10, 30) * 3; // entre 30% e 90%
                            $output .= "<div class=\"skeleton-line w-{$w}\"></div>";
                        }
                        $output .= '</div>';
                    }
                    $output .= '</div>';
                    break;

                default: // default
                    $output .= '<div class="w-100">';
                    for ($j = 0; $j < $this->lines; $j++) {
                        $w = $this->compact ? 30 : [100, 90, 75, 50][array_rand([0, 1, 2, 3])];
                        $output .= "<div class=\"skeleton-line w-{$w} mb-2\"></div>";
                    }
                    $output .= '</div>';
            }

            $output .= '</div>';
        }

        $output .= '</div>';

        if ($this->fadeAfter) {
            $output .= <<<JS
<script>
setTimeout(() => {
    document.getElementById('$id')?.classList.add('fade-out');
}, {$this->fadeAfter});
</script>
JS;
        }

        return $output;
    }
}
