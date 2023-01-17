<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Models;

    use Illuminate\Database\Eloquent\Model;
    use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasPageAttributes;
    use Statikbe\FilamentFlexibleContentBlocks\Models\Traits\HasSEOAttributes;

    class Page extends Model
    {
        use HasPageAttributes;
        use HasSEOAttributes;
    }
