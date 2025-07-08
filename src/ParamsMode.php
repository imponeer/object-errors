<?php

declare(strict_types=1);

namespace Imponeer\ObjectErrors;

enum ParamsMode: int
{
    /**
     * Mode that says that only one param for adding is used
     */
    case Mode1 = 0;

    /**
     * Mode that says two params are used
     */
    case Mode2 = 1;

    /**
     * Mode that says that 2nd param is a used as prefix
     */
    case Mode2AsPrefix = 2;
}
