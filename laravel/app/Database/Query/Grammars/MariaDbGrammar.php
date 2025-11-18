<?php

namespace App\Database\Query\Grammars;

use Illuminate\Database\Query\Grammars\MariaDbGrammar as BaseMariaDbGrammar;

class MariaDbGrammar extends BaseMariaDbGrammar
{
    /**
     * Get the format for database stored dates.
     *
     * @return string
     */
    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }
}
