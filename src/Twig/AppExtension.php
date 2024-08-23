<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Utils\DateUtils;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('date_en_lettres', [$this, 'convertirDateEnLettres']),
        ];
    }

    public function convertirDateEnLettres($date)
    {
        return DateUtils::convertirDateEnLettres($date);
    }
}
?>
